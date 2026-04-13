<?php include 'includes/header.php'; ?>

<!-- Content Wrap -->
<main class="bg-white overflow-x-hidden min-h-screen">
    
    <!-- Hero Section (Replica of Next.js) -->
    <section class="relative min-h-screen flex items-center bg-[#0b0d10] text-white overflow-hidden pt-16">
        <div class="absolute inset-0 z-0">
            <img src="assets/images/Bg-home.jpeg" alt="Novarock luxury stone interior" class="h-full w-full object-cover opacity-60">
        </div>

        <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent z-10"></div>
        <div class="pointer-events-none absolute inset-0 z-20 bg-[radial-gradient(circle_at_center,_rgba(255,255,255,0.05),_transparent_60%)] mix-blend-overlay opacity-50"></div>

        <div class="relative z-30 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-24">
            <div id="hero-content" class="max-w-4xl">
                <p class="mb-6 text-xs font-black tracking-[0.25em] uppercase text-white/90">
                    Novarock International • Marble & Granite Export
                </p>

                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-heading tracking-tight leading-tight md:leading-[1.05] mb-8 drop-shadow-2xl">
                    <span class="block font-black uppercase text-white">Elevating Spaces</span>
                    <span class="block mt-2">
                        <span class="italic text-gold font-medium">Worldwide</span> 
                        <span class="font-medium text-white">with Premium Stone</span>
                    </span>
                </h1>

                <div class="mt-6 mb-8">
                    <p class="inline-block py-2 px-4 text-[12px] font-black md:text-sm uppercase tracking-[0.25em] bg-gold text-white rounded">
                        Precision • Quality • Trust
                    </p>
                </div>

                <div class="rounded-xl bg-black/40 p-6 shadow-xl backdrop-blur-md max-w-xl border border-white/10 transition-all duration-300 hover:bg-black/60 cursor-default">
                    <p class="text-sm md:text-base lg:text-lg text-white font-medium leading-relaxed drop-shadow-md">
                        Premium Indian granite and Italian marble for global projects. Novarock International partners with architects, developers, and luxury contractors to deliver stone that meets international design and performance standards.
                    </p>
                </div>

                <div class="mt-10 flex flex-col sm:flex-row gap-4 sm:gap-6">
                    <a href="products.php" class="inline-flex items-center justify-center rounded-md bg-gold px-12 py-4 text-xs font-black uppercase tracking-[0.24em] text-black shadow-2xl transition-transform hover:-translate-y-1">
                        Get Quote
                    </a>
                    <a href="contact.php" class="inline-flex items-center justify-center rounded-md bg-white px-12 py-4 text-xs font-black uppercase tracking-[0.24em] text-black shadow-lg transition-transform hover:-translate-y-1">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Bar Section -->
    <section class="relative z-40 pb-12 -mt-16 md:-mt-24 px-4 sm:px-6">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <?php
                $stats = [
                    ['label' => 'Years of Experience', 'value' => '15+ Years'],
                    ['label' => 'Export Countries', 'value' => '25+ Countries'],
                    ['label' => 'Stone Varieties', 'value' => '100+ Types'],
                    ['label' => 'Quality Standards', 'value' => 'International QA'],
                ];
                foreach ($stats as $stat):
                ?>
                <div class="glass-card rounded-2xl px-5 py-6 transition-all hover:-translate-y-1 hover:border-gold">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500 mb-2">
                        <?php echo $stat['label']; ?>
                    </p>
                    <p class="text-xl md:text-2xl font-heading font-bold text-slate-900">
                        <?php echo $stat['value']; ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Global Export Info -->
    <section class="py-16 md:py-32 bg-[#faf6ef]">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid gap-12 lg:grid-cols-2 items-start">
                <div class="space-y-4">
                    <h2 class="text-2xl md:text-3xl lg:text-5xl font-heading font-black leading-tight text-slate-900 uppercase">
                        Global Stone Excellence.<br>
                        <span class="font-normal text-slate-600 lowecase">Delivered with Precision.</span>
                    </h2>
                </div>

                <div class="space-y-6">
                    <p class="text-base md:text-lg text-slate-700 leading-relaxed font-medium">
                        From quarry selection to final installation, Novarock International manages the full export journey. Each shipment is calibrated for project timelines, on-site realities, and international building norms, ensuring every stone arrives ready for precision fabrication and installation.
                    </p>

                    <p class="text-base text-slate-600 leading-relaxed">
                        Our logistics network spans major ports and trade routes, with documentation, packing, and compliance aligned to the expectations of global developers, contractors, and architectural practices.
                    </p>

                    <ul class="mt-8 grid gap-4 text-sm text-slate-800 md:grid-cols-2">
                        <li class="flex gap-3 items-start">
                            <span class="mt-2 h-2 w-2 rounded-full bg-gold shrink-0"></span>
                            <span>International packaging and crating standards for slabs and tiles.</span>
                        </li>
                        <li class="flex gap-3 items-start">
                            <span class="mt-2 h-2 w-2 rounded-full bg-gold shrink-0"></span>
                            <span>Bulk export logistics engineered for mega and multi-phase projects.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Collection (Live Data) -->
    <section class="py-16 md:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-heading font-black text-slate-900 uppercase mb-4">
                    Featured Collection
                </h2>
                <div class="w-20 h-1.5 bg-gold mx-auto mb-6"></div>
                <p class="mx-auto max-w-2xl text-base text-slate-600">
                    A curated selection of signature stones favoured by architects for luxury hospitality, residential, and commercial spaces across the globe.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <?php
                // Fetch 3 featured products to match main catalog's visual scale
                $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_featured = 1 LIMIT 3");
                $stmt->execute();
                $featured = $stmt->fetchAll();

                if (count($featured) == 0) {
                    // Fallback to any 3 products if none marked featured
                    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id LIMIT 3");
                    $stmt->execute();
                    $featured = $stmt->fetchAll();
                }

                foreach ($featured as $product):
                    $imgStmt = $pdo->prepare("SELECT url FROM product_images WHERE product_id = ? LIMIT 1");
                    $imgStmt->execute([$product->id]);
                    $mainImg = $imgStmt->fetchColumn() ?: 'assets/images/placeholder.jpg';
                ?>
                <article class="group bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-2xl transition-all hover:-translate-y-1">
                    <a href="product-view.php?id=<?php echo $product->id; ?>" class="block">
                        <div class="relative overflow-hidden aspect-[4/3] bg-slate-100">
                            <img src="<?php echo ltrim($mainImg, '/'); ?>" alt="<?php echo h($product->name); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute top-4 right-4 py-1 px-2.5 bg-black/80 backdrop-blur shadow text-[10px] font-black uppercase text-white tracking-widest rounded">
                                <?php echo h($product->category_name); ?>
                            </div>
                        </div>
                    </a>

                    <div class="p-8">
                        <a href="product-view.php?id=<?php echo $product->id; ?>" class="block">
                            <h3 class="text-2xl font-heading font-black text-slate-900 mb-4 group-hover:text-gold transition-colors italic uppercase tracking-tight">
                                <?php echo h($product->name); ?>
                            </h3>
                        </a>
                        
                        <div class="grid grid-cols-2 gap-6 mb-8">
                            <div class="space-y-1">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Origin</span>
                                <span class="block text-sm font-bold text-slate-700"><?php echo h($product->origin ?: 'Premium Import'); ?></span>
                            </div>
                            <div class="space-y-1">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Finish</span>
                                <span class="block text-sm font-bold text-slate-700"><?php echo h($product->finish ?: 'Polished'); ?></span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                            <a href="product-view.php?id=<?php echo $product->id; ?>" class="text-[11px] font-black uppercase tracking-widest text-slate-900 border-b-2 border-transparent hover:border-gold transition-all">View Details</a>
                            <a href="contact.php?product=<?php echo urlencode($product->name); ?>" class="text-[11px] font-black uppercase tracking-widest bg-gold/10 text-gold px-4 py-2 rounded-lg hover:bg-gold hover:text-white transition-all">Direct Inquiry</a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-20 text-center">
                <a href="products.php" class="btn-gold px-12">Browse Full Catalog</a>
            </div>
        </div>
    </section>

    <!-- Google Reviews -->
    <section class="py-16 md:py-32 bg-[#fafafa]">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="text-center mb-16">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <h2 class="text-3xl md:text-4xl font-heading font-black text-slate-900 uppercase">Google Reviews</h2>
                    <svg viewBox="0 0 24 24" width="32" height="32" class="mt-1">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                    </svg>
                </div>
                <div class="flex items-center justify-center gap-2 mb-2 text-amber-500 font-bold">
                    <span class="text-2xl text-slate-900 mr-1">5.0</span>
                    ★★★★★
                </div>
                <p class="text-slate-500 text-sm">Validated exported client feedback</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                $reviews = [
                    ['n' => 'Elena Rossi', 't' => 'Italy', 'r' => 'Novarock understood our architectural vision perfectly. Material consistency across 5000sqm was flawless.'],
                    ['n' => 'James Chen', 't' => 'Australia', 'r' => 'The absolute black granite slabs were laser-straight and mirror-polished. Top-tier export packaging.'],
                    ['n' => 'Sophia Martinez', 't' => 'USA', 'r' => 'Reliable logistics and exceptional customer service. The Statuario marble is the crown jewel of our lobby.'],
                ];
                foreach ($reviews as $rev):
                ?>
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xl">
                            <?php echo $rev['n'][0]; ?>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900"><?php echo $rev['n']; ?></h4>
                            <p class="text-xs text-gold font-bold uppercase tracking-widest"><?php echo $rev['t']; ?></p>
                        </div>
                    </div>
                    <p class="text-slate-600 italic leading-relaxed">"<?php echo $rev['r']; ?>"</p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>
