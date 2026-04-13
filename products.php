<?php include 'includes/header.php'; ?>

<!-- Products Catalog -->
<main class="bg-[#fafafa] min-h-screen pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Header -->
        <div class="mb-16 text-center">
            <h1 class="text-4xl md:text-5xl font-heading font-black text-slate-900 uppercase mb-4 tracking-tight">Full Collection</h1>
            <div class="w-16 h-1 bg-gold mx-auto mb-6"></div>
            <p class="max-w-2xl mx-auto text-slate-600">Explore premium Indian granite, Italian marble, and custom-cut architectural stones ready for international export.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="sticky top-32 space-y-8">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-6 border-b pb-2">Categories</h4>
                        <div class="space-y-2">
                            <?php
                            $catStmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
                            while ($cat = $catStmt->fetch()):
                                $isActive = (isset($_GET['cat']) && $_GET['cat'] == $cat->slug);
                            ?>
                            <a href="products.php?cat=<?php echo $cat->slug; ?>" class="block py-2 px-4 rounded-md text-sm font-bold uppercase tracking-widest transition-colors hover:text-gold <?php echo $isActive ? 'text-gold pl-6 border-l-4 border-gold bg-slate-50' : 'text-slate-600'; ?>">
                                <?php echo h($cat->name); ?>
                            </a>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <div class="p-6 bg-slate-900 rounded-2xl text-white">
                        <h4 class="font-heading font-black text-lg mb-2 uppercase italic text-gold">Fast Logistics</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">Direct export to major ports in the Middle East, Europe, and USA. 25+ countries served.</p>
                        <a href="contact.php" class="inline-block mt-4 text-[10px] uppercase font-black tracking-widest border-b border-gold text-gold">Learn More →</a>
                    </div>
                </div>
            </aside>

            <!-- Product Grid -->
            <div class="flex-grow">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                    <?php
                    $cat_slug = $_GET['cat'] ?? null;
                    $query = "SELECT p.*, c.name as category_name, c.slug as category_slug, 
                              (SELECT url FROM product_images WHERE product_id = p.id LIMIT 1) as main_image 
                              FROM products p 
                              LEFT JOIN categories c ON p.category_id = c.id";
                    $params = [];
                    
                    if ($cat_slug) {
                        $query .= " WHERE c.slug = ?";
                        $params[] = $cat_slug;
                    }
                    
                    $query .= " ORDER BY p.created_at DESC";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute($params);
                    $products = $stmt->fetchAll();

                    if (count($products) == 0):
                        echo '<div class="col-span-full py-20 text-center text-slate-400 font-bold uppercase">No products found in this category.</div>';
                    endif;

                    foreach ($products as $p):
                        $mainImg = $p->main_image ?: 'assets/images/placeholder.jpg';
                    ?>
                    <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-2xl transition-all hover:-translate-y-1">
                        <a href="product-view.php?id=<?php echo $p->id; ?>" class="block">
                            <div class="relative overflow-hidden aspect-[4/3] bg-slate-100">
                                <img src="<?php echo ltrim($mainImg, '/'); ?>" alt="<?php echo h($p->name); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute top-4 right-4 py-1 px-2.5 bg-black/80 backdrop-blur shadow text-[10px] font-black uppercase text-white tracking-widest rounded">
                                    <?php echo h($p->category_name); ?>
                                </div>
                            </div>
                        </a>

                        <div class="p-6">
                            <a href="product-view.php?id=<?php echo $p->id; ?>" class="block">
                                <h3 class="text-lg font-heading font-black text-slate-900 mb-3 group-hover:text-gold transition-colors line-clamp-1 italic uppercase">
                                    <?php echo h($p->name); ?>
                                </h3>
                            </a>
                            
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="space-y-1">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Origin</span>
                                    <span class="block text-xs font-bold text-slate-700"><?php echo h($p->origin ?: 'Premium Import'); ?></span>
                                </div>
                                <div class="space-y-1">
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Finish</span>
                                    <span class="block text-xs font-bold text-slate-700"><?php echo h($p->finish ?: 'Polished'); ?></span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                <a href="product-view.php?id=<?php echo $p->id; ?>" class="text-[11px] font-black uppercase tracking-widest text-slate-900 border-b-2 border-transparent hover:border-gold transition-all">Details</a>
                                <a href="contact.php?product=<?php echo urlencode($p->name); ?>" class="text-[11px] font-black uppercase tracking-widest bg-gold/10 text-gold px-3 py-1.5 rounded hover:bg-gold hover:text-white transition-all">Inquiry</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
