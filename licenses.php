<?php include 'includes/header.php'; ?>

<main class="bg-white min-h-screen pt-32 pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="max-w-4xl mx-auto text-center mb-24 space-y-4">
            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 mb-4 block">International Credentials</span>
            <h1 class="text-4xl md:text-7xl font-heading font-black text-slate-900 leading-tight uppercase italic">Trust. Quality.<br><span class="text-gold">Excellence.</span></h1>
            <div class="w-24 h-1 bg-gold mx-auto mb-10"></div>
            <p class="text-lg md:text-xl text-slate-500 font-medium leading-relaxed max-w-2xl mx-auto">
                Novarock International is committed to global standards and complete legal transparency. We maintain all necessary certifications to ensure your stone solutions are delivered with absolute precision.
            </p>
        </div>

        <!-- Certificates Grid -->
        <div class="space-y-32">
            <?php
            $certificates = [
                [
                    'title' => 'Import Export Code (IEC)',
                    'org' => 'Authorized by DGFT, India',
                    'desc' => 'The mandatory license for any merchant exporting from India. It ensures legal compliance with the Directorate General of Foreign Trade.',
                    'img' => 'assets/images/licenses/IEC.jpg'
                ],
                [
                    'title' => 'GST Registration',
                    'org' => 'MSME Registered Entity',
                    'desc' => 'Full compliance with India\'s Goods and Services Tax system, ensuring transparent financial operations for international clients.',
                    'img' => 'assets/images/licenses/Gst registration.png'
                ],
                [
                    'title' => 'ISO 9001:2015',
                    'org' => 'Quality Management System',
                    'desc' => 'Internationally recognized certification for the highest quality management in natural stone manufacturing and distribution.',
                    'img' => 'assets/images/licenses/Quality certificate.png'
                ],
                [
                    'title' => 'Certificate of Incorporation',
                    'org' => 'Ministry of Corporate Affairs',
                    'desc' => 'Official registration document validating our standing as a recognized business entity under the Indian Companies Act.',
                    'img' => 'assets/images/licenses/Incorporation.jpg'
                ]
            ];

            foreach ($certificates as $idx => $cert):
                $isEven = $idx % 2 == 0;
            ?>
            <div class="flex flex-col lg:flex-row gap-20 items-center <?php echo $isEven ? '' : 'lg:flex-row-reverse'; ?>">
                <!-- Visual Side -->
                <div class="w-full lg:w-1/2">
                    <div class="relative group">
                        <div class="absolute -inset-6 bg-slate-50 rounded-2xl -z-10 group-hover:bg-slate-100 transition-all duration-700"></div>
                        <div class="aspect-[4/3] bg-white border border-slate-200 rounded-xl shadow-sm flex items-center justify-center relative overflow-hidden group-hover:shadow-2xl transition-all duration-500 scale-95 group-hover:scale-100">
                            <img src="<?php echo $cert['img']; ?>" alt="<?php echo $cert['title']; ?>" class="w-full h-full object-cover">
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <a href="<?php echo $cert['img']; ?>?download" class="bg-white text-slate-900 px-8 py-3 rounded-md text-[10px] font-black uppercase tracking-widest shadow-2xl hover:bg-gold hover:text-white transition-all">View Document</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Side -->
                <div class="w-full lg:w-1/2 space-y-8">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gold mb-3"><?php echo $cert['org']; ?></p>
                        <h2 class="text-3xl md:text-5xl font-heading font-black text-slate-900 leading-tight uppercase italic mb-6"><?php echo $cert['title']; ?></h2>
                        <p class="text-lg text-slate-500 leading-relaxed font-medium"><?php echo $cert['desc']; ?></p>
                    </div>

                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <span class="w-2 h-2 bg-gold rounded-full"></span> Customs Clearance
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <span class="w-2 h-2 bg-gold rounded-full"></span> Global Export Recognition
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <span class="w-2 h-2 bg-gold rounded-full"></span> Legal Business Status
                        </li>
                    </ul>

                    <div class="pt-8">
                        <a href="contact.php" class="inline-block text-xs font-black uppercase tracking-widest border-b-2 border-slate-900 pb-1 hover:text-gold hover:border-gold transition-colors">Enquire About Compliance →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- CTA Section -->
        <div class="mt-40 pt-32 border-t border-slate-200 text-center">
            <h2 class="text-3xl font-heading font-black text-slate-900 italic uppercase mb-6">Need specific documentation?</h2>
            <p class="text-slate-500 mb-12 max-w-xl mx-auto font-medium">Our export team can provide individual compliance certificates for specific country and trade region requirements.</p>
            <a href="contact.php" class="bg-slate-900 text-white px-12 py-5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gold shadow-2xl transition-all">Contact Compliance Team</a>
        </div>

    </div>
</main>

<?php include 'includes/footer.php'; ?>
