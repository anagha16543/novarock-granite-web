<?php
include 'includes/header.php';
?>

<main class="p-10 flex-grow animate-fadeIn">
    <header class="mb-12 flex justify-between items-end">
        <div class="space-y-1">
            <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Overview</h2>
            <p class="text-slate-500 font-medium">Welcome to the Novarock Enterprise CMS. Manage your products, gallery, and customer inquiries with precision.</p>
        </div>
        <div class="text-right">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Current Session</p>
            <p class="text-sm font-bold text-slate-900 uppercase tracking-widest"><?php echo date('l, d F Y'); ?></p>
        </div>
    </header>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 transition-all hover:shadow-xl group">
            <div class="flex justify-between items-start mb-4">
                <span class="text-3xl">💎</span>
                <span class="text-xs font-black text-green-500 uppercase">+LIVE</span>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Products</p>
            <p class="text-4xl font-black text-slate-900 tracking-tighter"><?php echo $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(); ?></p>
        </div>
        
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 transition-all hover:shadow-xl group">
            <div class="flex justify-between items-start mb-4">
                <span class="text-3xl">🖼️</span>
                <span class="text-xs font-black text-blue-500 uppercase">+EXP</span>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Gallery Items</p>
            <p class="text-4xl font-black text-slate-900 tracking-tighter"><?php echo $pdo->query("SELECT COUNT(*) FROM gallery_images")->fetchColumn(); ?></p>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 transition-all hover:shadow-xl group">
            <div class="flex justify-between items-start mb-4">
                <span class="text-3xl">📂</span>
                <span class="text-xs font-black text-amber-500 uppercase">+CAT</span>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Categories</p>
            <p class="text-4xl font-black text-slate-900 tracking-tighter"><?php echo $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(); ?></p>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 border-l-8 border-l-gold transition-all hover:shadow-xl group">
            <div class="flex justify-between items-start mb-4">
                <span class="text-3xl">📩</span>
                <?php if ($unread_count > 0): ?>
                <span class="text-xs font-black text-red-500 animate-pulse uppercase">PENDING</span>
                <?php endif; ?>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Active Inquiries</p>
            <p class="text-4xl font-black text-gold tracking-tighter"><?= $unread_count ?></p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Recent Inquiries -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs flex items-center gap-2">
                    <span class="w-2 h-2 bg-gold rounded-full"></span> Recent Inquiries
                </h3>
                <a href="inquiries.php" class="text-[10px] text-gold font-black uppercase tracking-widest hover:underline">View All Register &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-white text-[10px] font-black uppercase text-slate-400 tracking-widest border-b border-slate-100">
                        <tr>
                            <th class="py-5 px-8">Source</th>
                            <th class="py-5 px-8">Subject</th>
                            <th class="py-5 px-8">Date</th>
                            <th class="py-5 px-8 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-slate-50">
                        <?php
                        $inquiries = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 6")->fetchAll();
                        if (empty($inquiries)):
                        ?>
                        <tr><td colspan="4" class="py-20 text-center text-slate-400 font-bold uppercase tracking-widest italic animate-pulse">No current inquiries on record.</td></tr>
                        <?php else: foreach ($inquiries as $iq): ?>
                        <tr class="hover:bg-slate-50 transition-all duration-300">
                            <td class="py-5 px-8">
                                <div class="font-black text-slate-900 uppercase mb-0.5 tracking-tight"><?php echo h($iq->name); ?></div>
                                <div class="text-[10px] text-slate-400 font-bold tracking-wider"><?php echo h($iq->email); ?></div>
                            </td>
                            <td class="py-5 px-8 font-medium text-slate-600 italic">"<?php echo h($iq->subject ?? 'Inquiry'); ?>"</td>
                            <td class="py-5 px-8 text-slate-400 font-bold uppercase tracking-tighter"><?php echo date('M d, Y', strtotime($iq->created_at)); ?></td>
                            <td class="py-5 px-8 text-right">
                                <a href="inquiries.php?id=<?php echo $iq->id; ?>" class="inline-block text-[10px] font-black uppercase text-gold hover:bg-gold hover:text-white px-4 py-2 border-2 border-gold rounded-xl transition-all">Details</a>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- System Alerts/Shortcuts -->
        <div class="space-y-8">
            <div class="bg-slate-900 rounded-3xl p-8 text-white relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-gold opacity-10 rounded-full blur-3xl group-hover:opacity-20 transition-opacity"></div>
                <h4 class="text-xl font-black uppercase tracking-widest mb-6 border-b border-white/5 pb-4">CMS Controls</h4>
                <div class="grid grid-cols-2 gap-4">
                    <a href="products.php?action=add" class="bg-white/5 hover:bg-gold hover:text-black p-6 rounded-2xl border border-white/10 transition-all group/card">
                        <p class="text-lg mb-2">➕</p>
                        <p class="text-[10px] font-black uppercase tracking-widest">New Product</p>
                    </a>
                    <a href="gallery.php?action=add" class="bg-white/5 hover:bg-gold hover:text-black p-6 rounded-2xl border border-white/10 transition-all group/card">
                        <p class="text-lg mb-2">📷</p>
                        <p class="text-[10px] font-black uppercase tracking-widest">Upload Image</p>
                    </a>
                </div>
            </div>

            <!-- Popular Products Section -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase text-slate-900 tracking-widest italic tracking-[0.2em] flex items-center gap-2">
                        <span class="w-2 h-2 bg-gold rounded-full animate-pulse"></span> Market Surveillance
                    </h3>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest px-3 py-1 bg-white rounded-full border border-slate-200">Live Focus</span>
                </div>
                <div class="p-8 space-y-6">
                    <?php
                    $popular = $pdo->query("SELECT p.*, (SELECT url FROM product_images WHERE product_id = p.id LIMIT 1) as main_image FROM products p WHERE p.views > 0 ORDER BY p.updated_at DESC LIMIT 8")->fetchAll();
                    
                    if (empty($popular)) {
                        echo '<p class="text-center py-10 text-[10px] font-black uppercase text-slate-300 italic tracking-widest">No market data recorded.</p>';
                    } else {
                        foreach ($popular as $p):
                    ?>
                    <div class="flex items-center gap-5 group hover:translate-x-1 transition-transform duration-300 cursor-default">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200 shadow-sm">
                            <img src="../<?= h($p->main_image ?: 'assets/images/placeholder.svg') ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-[9px] font-black uppercase text-slate-900 tracking-tight group-hover:text-gold transition-colors"><?= h($p->name) ?></h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[8px] font-bold text-slate-300 uppercase tracking-widest"><?= h($p->origin ?: 'Unknown') ?></span>
                                <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                                <span class="text-[8px] font-black text-gold uppercase tracking-widest italic"><?= $p->views ?> Views</span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; } ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
