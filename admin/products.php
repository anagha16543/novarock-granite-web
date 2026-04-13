<?php
include 'includes/header.php';

// Handle Deletion
if (isset($_POST['delete_id'])) {
    // Delete images first (filesystem + DB)
    $stmt = $pdo->prepare("SELECT url FROM product_images WHERE product_id = ?");
    $stmt->execute([$_POST['delete_id']]);
    $imgs = $stmt->fetchAll();
    foreach ($imgs as $img) {
        $file = '../' . ltrim($img->url, '/');
        if (file_exists($file) && !is_dir($file)) unlink($file);
    }
    
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$_POST['delete_id']]);
    redirect('products.php?deleted=1');
}

$products = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC")->fetchAll();
?>

<main class="p-10 flex-grow animate-fadeIn bg-white">
    <header class="mb-12 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Stone Inventory</h2>
            <p class="text-slate-500 font-medium italic">Manage your premium granite and marble catalog across global markets.</p>
        </div>
        <a href="product-form.php" class="bg-gold text-black px-10 py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:-translate-y-1 hover:shadow-xl transition-all shadow-lg active:scale-95 shadow-gold/20 flex items-center gap-3">
            <span>💎</span> Add New Product
        </a>
    </header>

    <?php if (isset($_GET['deleted'])): ?>
    <div class="mb-8 p-6 bg-red-50 border border-red-100 rounded-2xl text-red-500 text-xs font-black uppercase tracking-widest flex items-center gap-3 animate-slideIn">
        <span class="w-2 h-2 bg-red-500 rounded-full animate-ping"></span> Record purged successfully
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <?php if (empty($products)): ?>
        <div class="col-span-full py-40 text-center text-slate-300 font-black uppercase tracking-[0.5em] italic border-2 border-dashed border-slate-100 rounded-3xl">No inventory items found.</div>
        <?php else: foreach ($products as $p): 
            $imgStmt = $pdo->prepare("SELECT url FROM product_images WHERE product_id = ? LIMIT 1");
            $imgStmt->execute([$p->id]);
            $mainImg = $imgStmt->fetchColumn() ?: 'assets/images/placeholder.jpg';
        ?>
        <article class="bg-[#fafafa] rounded-3xl border border-slate-100 p-4 shadow-sm transition-all hover:shadow-2xl hover:-translate-y-2 group">
            <div class="relative mb-6 overflow-hidden rounded-2xl aspect-[4/3] bg-slate-200 border border-white">
                <img src="../<?php echo ltrim($mainImg, '/'); ?>" alt="<?php echo h($p->name); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute top-4 left-4 flex gap-2">
                    <span class="bg-slate-900/80 backdrop-blur-md text-white text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest"><?php echo h($p->category_name ?: 'Stone'); ?></span>
                    <?php if ($p->is_featured): ?>
                    <span class="bg-gold text-black text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">Featured</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="px-2 space-y-4">
                <h3 class="text-xl font-black text-slate-900 uppercase italic tracking-tight truncate group-hover:text-gold transition-colors"><?php echo h($p->name); ?></h3>
                
                <div class="grid grid-cols-2 gap-4 pb-6 border-b border-slate-100">
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Origin</p>
                        <p class="text-[11px] font-bold text-slate-900 uppercase"><?php echo h($p->origin ?: 'Global'); ?></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Finish</p>
                        <p class="text-[11px] font-bold text-slate-900 uppercase"><?php echo h($p->finish ?: 'Polished'); ?></p>
                    </div>
                </div>

                <div class="flex gap-4 pt-2">
                    <a href="product-form.php?id=<?= $p->id ?>" class="flex-grow bg-white text-slate-900 hover:text-gold px-4 py-3 rounded-xl border-2 border-slate-100 hover:border-gold font-black uppercase text-[10px] text-center transition-all">Edit Details</a>
                    <form method="POST" onsubmit="return confirm('Confirm permanent deletion of product and its images?')" class="flex-shrink-0">
                        <input type="hidden" name="delete_id" value="<?= $p->id ?>">
                        <button type="submit" class="bg-red-50/50 text-red-300 hover:text-red-500 hover:bg-red-50 p-3 rounded-xl transition-all border border-transparent hover:border-red-100">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </article>
        <?php endforeach; endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
