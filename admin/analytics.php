<?php
include 'includes/header.php';

// Fetch Global Stats
$total_views = $pdo->query("SELECT SUM(views) FROM products")->fetchColumn() ?: 0;
$total_inquiries = $pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn() ?: 0;
$conversion_rate = ($total_views > 0) ? round(($total_inquiries / $total_views) * 100, 2) : 0;

// Fetch Top Products
// Fetch All Products with Views
$top_products = $pdo->query("SELECT p.*, (SELECT url FROM product_images WHERE product_id = p.id LIMIT 1) as main_image FROM products p WHERE p.views > 0 ORDER BY views DESC")->fetchAll();

// Fetch Category Distribution
$cat_stats = $pdo->query("SELECT c.name, SUM(p.views) as total_cat_views FROM products p JOIN categories c ON p.category_id = c.id GROUP BY c.id ORDER BY total_cat_views DESC")->fetchAll();

// Dynamic Strategic Comment
$top_one = $top_products[0]->name ?? 'Market';
$strategic_insight = "Global engagement patterns indicate significant interest in " . h($top_one) . " for the current export quarter.";
if (count($top_products) > 10 && $top_products[10]->views > 0) {
    $strategic_insight = "Rising architectural demands detected for " . h($top_products[10]->name) . " and other large-format units.";
}
?>

<main class="p-10 flex-grow animate-fadeIn bg-[#f8fafc]">
    <header class="mb-12 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Market Intelligence Center</h2>
            <p class="text-slate-500 font-medium italic">High-fidelity surveillance of global product engagements and architectural trends.</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-white px-6 py-3 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
                <span class="w-3 h-3 bg-green-500 rounded-full animate-ping"></span>
                <span class="text-[10px] font-black uppercase text-slate-900 tracking-widest">Surveillance Live</span>
            </div>
        </div>
    </header>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-slate-900 p-8 rounded-[2rem] text-white shadow-xl relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-gold opacity-10 rounded-full blur-3xl"></div>
            <p class="text-[10px] font-black uppercase text-gold tracking-[0.2em] mb-2">Total Engagements</p>
            <p class="text-5xl font-black italic"><?= number_format($total_views) ?></p>
            <p class="text-[10px] mt-4 font-bold text-slate-400 uppercase tracking-widest italic">+4.2% from last session</p>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-2">Conversion Core</p>
            <p class="text-5xl font-black text-slate-900 italic"><?= $conversion_rate ?>%</p>
            <p class="text-[10px] mt-4 font-bold text-slate-400 uppercase tracking-widest italic">Views to Inquiry Ratio</p>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-2">Total Leads</p>
            <p class="text-5xl font-black text-slate-900 italic"><?= $total_inquiries ?></p>
            <p class="text-[10px] mt-4 font-bold text-slate-400 uppercase tracking-widest italic">International Requests</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Top Products List -->
        <div class="lg:col-span-8 bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-xs font-black uppercase text-slate-900 tracking-widest">Slab Performance Ranking</h3>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Active Product Engagements</span>
            </div>
            <div class="p-8">
                <div class="space-y-8">
                    <?php foreach ($top_products as $index => $p): 
                        $width = ($total_views > 0) ? ($p->views / $total_views) * 100 * 5 : 0; 
                        $width = min($width, 100);
                    ?>
                    <div class="relative group">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-black text-slate-300 italic tracking-tighter">#<?= $index + 1 ?></span>
                                <h4 class="text-[11px] font-black uppercase text-slate-900 group-hover:text-gold transition-colors"><?= h($p->name) ?></h4>
                            </div>
                            <span class="text-[10px] font-black text-slate-950 italic"><?= $p->views ?> Views</span>
                        </div>
                        <div class="w-full bg-slate-50 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-gold h-full rounded-full transition-all duration-1000 ease-out" style="width: <?= $width ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Category Popularity -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
                <h3 class="text-xs font-black uppercase text-slate-900 tracking-widest mb-8 border-b border-slate-50 pb-4">Category Distribution</h3>
                <div class="space-y-6">
                    <?php foreach ($cat_stats as $cat): ?>
                    <div class="flex justify-between items-center group">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black uppercase text-slate-900 mb-0.5 tracking-tight group-hover:text-gold transition-all"><?= h($cat->name) ?></span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest italic"><?= round(($cat->total_cat_views / $total_views) * 100, 1) ?>% Share</span>
                        </div>
                        <span class="text-xs font-black italic text-slate-900"><?= $cat->total_cat_views ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-gold p-8 rounded-[2rem] text-black shadow-xl shadow-gold/20 flex flex-col justify-between aspect-square">
                <div>
                    <h4 class="text-xl font-black uppercase tracking-widest mb-2 leading-tight italic">Strategic Edge Reporting</h4>
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Architectural surveillance version 3.4.1</p>
                </div>
                <div class="pt-6 border-t border-black/10">
                    <p class="text-[11px] font-black leading-relaxed italic">"<?= $strategic_insight ?>"</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
