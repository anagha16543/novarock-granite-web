<?php
include 'includes/header.php';

// Handle Management
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_gallery'])) {
        $title = $_POST['title'];
        $cat = $_POST['category'];
        
        if (!empty($_FILES['gallery_file']['name'])) {
            $uploadDir = '../uploads/gallery/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $ext = pathinfo($_FILES['gallery_file']['name'], PATHINFO_EXTENSION);
            $newName = 'gal_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $savePath = $uploadDir . $newName;
            $dbPath = 'uploads/gallery/' . $newName;

            if (move_uploaded_file($_FILES['gallery_file']['tmp_name'], $savePath)) {
                $stmt = $pdo->prepare("INSERT INTO gallery_images (title, url, category) VALUES (?, ?, ?)");
                $stmt->execute([$title, $dbPath, $cat]);
            }
        } elseif (!empty($_POST['youtube_url'])) {
            $dbPath = $_POST['youtube_url'];
            $stmt = $pdo->prepare("INSERT INTO gallery_images (title, url, category) VALUES (?, ?, ?)");
            $stmt->execute([$title, $dbPath, $cat]);
        }
        redirect('gallery.php?success=added');
    }
    
    if (isset($_POST['delete_id'])) {
        $id = (int)$_POST['delete_id'];
        $stmt = $pdo->prepare("SELECT url FROM gallery_images WHERE id = ?");
        $stmt->execute([$id]);
        $url = $stmt->fetchColumn();
        
        if ($url) {
            $file = '../' . ltrim($url, '/');
            if (file_exists($file) && !is_dir($file)) unlink($file);
            $pdo->prepare("DELETE FROM gallery_images WHERE id = ?")->execute([$id]);
        }
        redirect('gallery.php?deleted=1');
    }
}

$gallery = $pdo->query("SELECT * FROM gallery_images ORDER BY created_at DESC")->fetchAll();

// Helper function to ensure youtube links are embeddable
function getYoutubeEmbedUrl($url) {
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match)) {
        return 'https://www.youtube.com/embed/' . $match[1];
    }
    return $url;
}
?>

<main class="p-10 flex-grow animate-fadeIn bg-white">
    <header class="mb-12 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Project Portfolio Gallery</h2>
            <p class="text-slate-500 font-medium italic">Manage project installations, shipments, and factory visual ledger.</p>
        </div>
        <button onclick="document.getElementById('add-modal').classList.remove('hidden')" class="bg-slate-900 text-white px-10 py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gold hover:text-black transition-all shadow-xl shadow-slate-200 flex items-center gap-3 active:scale-95">
            <span>📷</span> Upload Visual Asset
        </button>
    </header>

    <div id="add-modal" class="<?= isset($_GET['action']) && $_GET['action'] == 'add' ? '' : 'hidden' ?> fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
        <div class="bg-white rounded-3xl max-w-xl w-full p-12 relative shadow-2xl animate-scaleIn border border-slate-100">
            <button onclick="document.getElementById('add-modal').classList.add('hidden')" class="absolute top-8 right-8 text-slate-400 hover:text-slate-900 transition-colors cursor-pointer">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tight mb-8">Asset Registration</h3>
            
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Designation / Project Title *</label>
                    <input type="text" name="title" required class="w-full bg-[#fafafa] border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm transition-all focus:bg-white text-sm">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Gallery Categorization</label>
                    <select name="category" class="w-full bg-[#fafafa] border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm appearance-none cursor-pointer text-sm font-black uppercase tracking-widest">
                        <option value="Residential">Residential</option>
                        <option value="Commercial">Commercial</option>
                        <option value="Export Shipments">Export Shipments</option>
                        <option value="Factory">Factory</option>
                        <option value="YouTube links">YouTube links</option>
                    </select>
                </div>

                <div class="space-y-4 border border-slate-200 p-6 rounded-xl bg-white shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 border-b pb-2">Asset Source (Choose One)</p>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">1. Image File Upload</label>
                        <input type="file" name="gallery_file" class="w-full bg-[#fafafa] border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-900 focus:border-gold outline-none shadow-sm transition-all cursor-pointer">
                    </div>
                    
                    <div class="text-center font-bold text-xs text-slate-400">OR</div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">2. YouTube / External Link</label>
                        <input type="url" name="youtube_url" placeholder="https://www.youtube.com/embed/..." class="w-full bg-[#fafafa] border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-900 focus:border-gold outline-none shadow-sm transition-all text-sm">
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" name="add_gallery" class="w-full bg-gold text-black px-12 py-5 rounded-2xl text-[10px] font-black uppercase tracking-[0.25em] shadow-xl shadow-gold/20 hover:-translate-y-1 transition-all active:scale-95">Commit Asset to Platform</button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        <?php if (empty($gallery)): ?>
        <div class="col-span-full py-40 text-center text-slate-300 font-bold uppercase tracking-widest italic border-2 border-dashed border-slate-100 rounded-3xl">Gallery currently empty.</div>
        <?php else: foreach ($gallery as $item): ?>
        <article class="bg-white rounded-2xl border border-slate-100 p-3 shadow-sm transition-all hover:shadow-2xl hover:-translate-y-1 group relative">
            <div class="relative overflow-hidden rounded-xl aspect-square bg-slate-100 mb-4 flex items-center justify-center">
                <?php if (strpos($item->url, 'youtube.com') !== false || strpos($item->url, 'youtu.be') !== false): ?>
                    <iframe src="<?= h(getYoutubeEmbedUrl($item->url)) ?>" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <img src="../<?php echo ltrim($item->url, '/'); ?>" alt="<?php echo h($item->title); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <?php endif; ?>
                <form method="POST" onsubmit="return confirm('Confirm permanent asset destruction?')" class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                    <input type="hidden" name="delete_id" value="<?= $item->id ?>">
                    <button type="submit" class="bg-red-500 text-white p-2 rounded-lg hover:scale-110 shadow-xl transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    </button>
                </form>
            </div>
            <div class="px-1 text-center">
                <div class="text-[9px] font-black text-gold uppercase tracking-[0.2em] mb-1"><?= h($item->category) ?></div>
                <h4 class="text-[10px] font-black text-slate-900 uppercase truncate px-2"><?= h($item->title) ?></h4>
            </div>
        </article>
        <?php endforeach; endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
