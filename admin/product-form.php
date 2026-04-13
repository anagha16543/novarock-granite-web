<?php
include 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

// Handle Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
    $name = $_POST['name'];
    $cat_id = $_POST['category_id'] ?: null;
    $desc = $_POST['description'];
    $origin = $_POST['origin'];
    $finish = $_POST['finish'];
    $thick = $_POST['thickness'];
    $price = $_POST['price_range'] ?: 'On Request';
    $is_feat = isset($_POST['is_featured']) ? 1 : 0;

    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE products SET name=?, category_id=?, description=?, origin=?, finish=?, thickness=?, price_range=?, is_featured=? WHERE id=?");
        $stmt->execute([$name, $cat_id, $desc, $origin, $finish, $thick, $price, $is_feat, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (name, category_id, description, origin, finish, thickness, price_range, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $cat_id, $desc, $origin, $finish, $thick, $price, $is_feat]);
        $id = $pdo->lastInsertId();
    }

    // Handle Image Upload
    if (!empty($_FILES['product_images']['name'][0])) {
        // Ensure directory exists
        $uploadDir = '../uploads/products/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        foreach ($_FILES['product_images']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['product_images']['error'][$key] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['product_images']['name'][$key], PATHINFO_EXTENSION);
                $newName = 'prod_' . $id . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $savePath = $uploadDir . $newName;
                $dbPath = 'uploads/products/' . $newName;

                if (move_uploaded_file($tmpName, $savePath)) {
                    $stmt = $pdo->prepare("INSERT INTO product_images (product_id, url) VALUES (?, ?)");
                    $stmt->execute([$id, $dbPath]);
                }
            }
        }
    }

    redirect('products.php?success=1');
}

// Delete specific image
if (isset($_POST['delete_image_id'])) {
    $img_id = (int)$_POST['delete_image_id'];
    $stmt = $pdo->prepare("SELECT url FROM product_images WHERE id = ?");
    $stmt->execute([$img_id]);
    $img_url = $stmt->fetchColumn();
    
    if ($img_url) {
        $file = '../' . ltrim($img_url, '/');
        if (file_exists($file) && !is_dir($file)) unlink($file);
        $pdo->prepare("DELETE FROM product_images WHERE id = ?")->execute([$img_id]);
    }
    redirect("product-form.php?id=$id");
}
?>

<main class="p-10 flex-grow animate-fadeIn bg-white">
    <header class="mb-12 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tight"><?= $id > 0 ? 'Edit Product' : 'Add New Product' ?></h2>
            <p class="text-slate-500 font-medium italic">Configure Stone characteristics and visual assets.</p>
        </div>
        <a href="products.php" class="text-xs font-black uppercase text-slate-400 hover:text-slate-900 transition-all tracking-widest border-b-2 border-slate-200 pb-1 hover:border-gold">&larr; Return to Inventory</a>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <div class="lg:col-span-8">
            <form method="POST" enctype="multipart/form-data" class="space-y-10">
                <div class="bg-[#fafafa] rounded-3xl p-10 border border-slate-100 shadow-sm space-y-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Product Name *</label>
                        <input type="text" name="name" required value="<?= h($product->name ?? '') ?>" class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Category</label>
                            <select name="category_id" class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm appearance-none cursor-pointer">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>" <?= ($product->category_id ?? '') == $cat->id ? 'selected' : '' ?>><?= h($cat->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Origin / Quarry</label>
                            <input type="text" name="origin" value="<?= h($product->origin ?? '') ?>" class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Technical Description</label>
                        <textarea name="description" rows="5" class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm transition-all"><?= h($product->description ?? '') ?></textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Standard Finish</label>
                            <input type="text" name="finish" value="<?= h($product->finish ?? '') ?>" placeholder="e.g. Polished / Leather" class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Thickness Options</label>
                            <input type="text" name="thickness" value="<?= h($product->thickness ?? '') ?>" placeholder="e.g. 18mm, 20mm" class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Price Descriptor</label>
                            <input type="text" name="price_range" value="<?= h($product->price_range ?? '') ?>" placeholder="On Request" class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm">
                        </div>
                    </div>

                    <div class="flex items-center gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                        <input type="checkbox" name="is_featured" id="is_feat" <?= ($product->is_featured ?? 0) ? 'checked' : '' ?> class="w-6 h-6 accent-gold cursor-pointer">
                        <label for="is_feat" class="text-xs font-black uppercase text-slate-900 cursor-pointer">Promote to Featured Selection on Home Page</label>
                    </div>
                </div>

                <div class="bg-slate-900 rounded-3xl p-10 text-white shadow-2xl relative overflow-hidden group">
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-gold/5 rounded-full blur-3xl"></div>
                    <h3 class="text-xl font-black uppercase tracking-widest mb-8 border-b border-white/10 pb-4">Digital Vision Assets</h3>
                    
                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Upload Premium Images (Multiple)</label>
                        <div class="relative group cursor-pointer">
                            <input type="file" name="product_images[]" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="bg-white/5 border-2 border-dashed border-white/20 rounded-2xl p-12 text-center transition-all group-hover:border-gold group-hover:bg-gold/5">
                                <p class="text-3xl mb-4">📸</p>
                                <p class="text-xs font-black uppercase tracking-widest text-white tracking-[0.2em] mb-2">Drop Premium Slabs Here</p>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Supports JPEG, PNG • Minimum 1200px Preferred</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 flex gap-4">
                        <button type="submit" name="save_product" class="flex-grow bg-gold text-black px-12 py-5 rounded-2xl text-xs font-black uppercase tracking-widest hover:-translate-y-1 transition-all shadow-xl shadow-gold/20">Commit Product to Registry</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="lg:col-span-4 space-y-10">
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Existing Visual Ledger</h3>
                <div class="grid grid-cols-2 gap-4">
                    <?php
                    if ($id > 0) {
                        $imgs = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ?");
                        $imgs->execute([$id]);
                        $product_images = $imgs->fetchAll();
                        
                        if (empty($product_images)) {
                            echo '<div class="col-span-full py-10 text-center text-[10px] font-black uppercase text-slate-300 italic tracking-[0.2em]">No images recorded.</div>';
                        } else {
                            foreach ($product_images as $img): ?>
                            <div class="relative aspect-square rounded-xl overflow-hidden group border border-slate-100 bg-slate-100">
                                <img src="../<?= h($img->url) ?>" class="w-full h-full object-cover">
                                <form method="POST" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none group-hover:pointer-events-auto">
                                    <input type="hidden" name="delete_image_id" value="<?= $img->id ?>">
                                    <button type="submit" class="bg-red-500 text-white p-3 rounded-full hover:scale-110 transition-transform shadow-xl">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </form>
                            </div>
                    <?php endforeach; 
                        }
                    } else {
                        echo '<div class="col-span-full py-10 text-center text-[10px] font-black uppercase text-slate-300 tracking-[0.2em]">Add product first to upload visual ledger.</div>';
                    }
                    ?>
                </div>
            </div>

            <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 italic">Registry Guidelines</h4>
                <ul class="space-y-4 text-xs font-bold text-slate-600 list-disc pl-4 italic">
                    <li>Ensure stone images are high-resolution for luxury perception.</li>
                    <li>Technical details should mirror architectural specifications.</li>
                    <li>Toggle "Featured" for items intended for cinematic hero placement.</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
