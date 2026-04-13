<?php include 'includes/header.php'; ?>

<main class="bg-white min-h-screen pb-20">
    <!-- Hero Section -->
    <section class="relative h-[40vh] md:h-[60vh] bg-black overflow-hidden group">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1600607686527-6fb886090705?auto=format&fit=crop&q=80&w=2000" class="w-full h-full object-cover opacity-60 transition-transform duration-1000 group-hover:scale-110">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent z-10"></div>
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 z-20 mt-16">
            <h1 class="text-4xl md:text-7xl font-heading font-black text-white mb-6 uppercase tracking-widest italic">
                Our Story
            </h1>
            <div class="w-24 h-1.5 bg-gold mx-auto"></div>
        </div>
    </section>

    <!-- Content -->
    <section class="py-20 md:py-32">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="space-y-8">
                    <h2 class="text-3xl md:text-5xl font-heading font-black text-slate-900 uppercase">Excellence in Every Stone</h2>
                    <div class="w-16 h-1 bg-gold"></div>
                    <p class="text-lg text-slate-600 leading-relaxed font-medium">
                        Novarock International is extremely proud to be a leading manufacturer and exporter of premium natural stones. Our journey began with a commitment to excellence and a passion for uncovering the finest stones earth has to offer.
                    </p>
                    <p class="text-base text-slate-500 leading-relaxed">
                        At Novarock, we combine state-of-the-art technology with traditional craftsmanship to deliver products that meet the highest global standards. Our dedication to quality, sustainability, and customer satisfaction sets us apart in the global premium stone market.
                    </p>
                    <div class="grid grid-cols-2 gap-8 pt-6">
                        <div>
                            <p class="text-3xl font-heading font-black text-gold mb-1">15+</p>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Years Industry Experience</p>
                        </div>
                        <div>
                            <p class="text-3xl font-heading font-black text-gold mb-1">25k+</p>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Projects Completed</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1600607688969-a5bfcd646154?auto=format&fit=crop&q=80&w=1000" class="w-full h-full object-cover">
                    </div>
                    <!-- Decorative element -->
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-gold/10 rounded-full blur-3xl -z-10"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-20 md:py-32 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="text-center mb-20 space-y-4">
                <h2 class="text-3xl md:text-5xl font-heading font-black text-slate-900 uppercase italic">The Visionaries</h2>
                <div class="w-16 h-1.5 bg-gold mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <?php
                $team = [
                    ['name' => 'Rajesh Sharma', 'role' => 'Managing Director', 'img' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a'],
                    ['name' => 'Priya Patel', 'role' => 'Head of Operations', 'img' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2'],
                    ['name' => 'Vikram Singh', 'role' => 'Global Sales Director', 'img' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7'],
                ];
                foreach ($team as $member):
                ?>
                <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-slate-100">
                    <div class="aspect-[3/4] overflow-hidden grayscale group-hover:grayscale-0 transition-all duration-700">
                        <img src="<?php echo $member['img']; ?>?auto=format&fit=crop&w=800" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                    </div>
                    <div class="p-8 text-center relative">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-8 h-0.5 bg-gold"></div>
                        <h3 class="text-2xl font-heading font-black text-slate-900 mb-1 italic"><?php echo $member['name']; ?></h3>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gold mb-4"><?php echo $member['role']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>
