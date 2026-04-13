<?php 
include 'includes/header.php'; 

// Fetch gallery items from DB
$stmt = $pdo->query("SELECT * FROM gallery_images WHERE url IS NOT NULL AND url != '' ORDER BY created_at DESC");
$galleryItems = $stmt->fetchAll();

// Define categories explicitly in the requested order
$forcedOrder = ['Commercial', 'Export Shipments', 'Factory', 'Residential'];
$dbCategories = array_unique(array_filter(array_column($galleryItems, 'category')));
$categories = array_intersect($forcedOrder, $dbCategories);
// Append any extra ones found in DB not in the forced list
$others = array_diff($dbCategories, $forcedOrder, ['YouTube links', 'YouTube Links']);
$categories = array_merge($categories, $others);

// Helper function to ensure youtube links are embeddable
function getYoutubeEmbedUrl($url) {
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match)) {
        return 'https://www.youtube.com/embed/' . $match[1];
    }
    return $url;
}
?>

<main class="bg-white min-h-screen pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-heading font-black text-slate-900 uppercase mb-4 tracking-tight">Project Gallery</h1>
            <div class="w-24 h-1.5 bg-gold mx-auto mb-6"></div>
            <p class="max-w-2xl mx-auto text-slate-600 font-medium">A visual journey through our finest installations, luxury projects, and state-of-the-art processing facilities.</p>
        </div>

        <!-- Filter Tabs -->
        <div class="flex flex-wrap justify-center gap-3 mb-16">
            <button onclick="filterGallery('All')" class="gallery-filter-btn px-8 py-2.5 rounded-full text-[11px] font-black uppercase tracking-[0.2em] transition-all bg-slate-900 text-white shadow-xl" data-category="All">All Projects</button>
            
            <?php 
            foreach ($categories as $cat): 
            ?>
            <button onclick="filterGallery('<?php echo h($cat); ?>')" class="gallery-filter-btn px-8 py-2.5 rounded-full text-[11px] font-black uppercase tracking-[0.2em] transition-all bg-slate-100 text-slate-500 hover:bg-slate-200" data-category="<?php echo h($cat); ?>"><?php echo h($cat); ?></button>
            <?php endforeach; ?>

            <div class="flex gap-2">
                <button onclick="filterGallery('Transformations')" class="gallery-filter-btn px-4 py-2.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] transition-all bg-slate-100 text-slate-500 hover:bg-slate-200" data-category="Transformations">Stone Transformations</button>
                <button onclick="filterGallery('YouTube links')" class="gallery-filter-btn px-4 py-2.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] transition-all bg-slate-100 text-slate-500 hover:bg-slate-200" data-category="YouTube links">YouTube links</button>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div id="gallery-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($galleryItems as $item): ?>
            <div class="gallery-item group relative aspect-square rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all cursor-pointer bg-slate-900" data-category="<?php echo h($item->category); ?>">
                <?php if (strpos($item->url, 'youtube.com') !== false || strpos($item->url, 'youtu.be') !== false): ?>
                    <iframe src="<?= h(getYoutubeEmbedUrl($item->url)) ?>" class="w-full h-full pointer-events-none" frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <img src="<?php echo h($item->url); ?>" alt="<?php echo h($item->title); ?>" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                <?php endif; ?>
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-8 <?php echo (strpos($item->url, 'youtube') !== false) ? 'pointer-events-none' : ''; ?>">
                    <p class="text-[10px] font-black text-gold uppercase tracking-[0.3em] mb-2"><?php echo h($item->category); ?></p>
                    <h3 class="text-xl font-heading font-bold text-white uppercase italic"><?php echo h($item->title); ?></h3>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Transformations Section (Initially Hidden) -->
        <div id="transformations-section" class="hidden animate-fadeIn">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Transformation 1 -->
                <div class="space-y-6">
                    <h3 class="text-2xl font-heading font-black text-slate-800 uppercase italic">Master Suite Elevation</h3>
                    <div class="relative aspect-[4/3] rounded-2xl overflow-hidden shadow-2xl group cursor-ew-resize border border-slate-200">
                        <img src="https://images.unsplash.com/photo-1620626011761-996317b8d101?auto=format&fit=crop&w=1200" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4 bg-gold text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest z-20 shadow-lg">Novarock Statuario</div>
                        
                        <!-- Before Overlay -->
                        <div class="absolute inset-0 w-full h-full [clip-path:polygon(0_0,50%_0,50%_100%,0_100%)] group-hover:[clip-path:polygon(0_0,0_0,0_100%,0_100%)] transition-all duration-1000 ease-in-out z-10 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1620626011761-996317b8d101?auto=format&fit=crop&w=1200" class="w-full h-full object-cover filter sepia-[0.7] brightness-[0.8] contrast-[0.7] saturate-[0.4] blur-[0.5px]">
                            <div class="absolute top-4 left-4 bg-white/90 text-slate-900 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">Dated Style</div>
                        </div>
                        
                        <!-- Slider Bar -->
                        <div class="absolute inset-y-0 left-1/2 w-1.5 bg-white shadow-[0_0_20px_rgba(0,0,0,0.5)] group-hover:left-0 transition-all duration-1000 ease-in-out -translate-x-1/2 z-30 flex items-center justify-center">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-2xl text-gold border-2 border-gold font-bold">↔</div>
                        </div>
                    </div>
                </div>

                <!-- Transformation 2 -->
                <div class="space-y-6">
                    <h3 class="text-2xl font-heading font-black text-slate-800 uppercase italic">Corporate Lobby Prestige</h3>
                    <div class="relative aspect-[4/3] rounded-2xl overflow-hidden shadow-2xl group cursor-ew-resize border border-slate-200">
                        <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=1200" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4 bg-gold text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest z-20 shadow-lg">Premium Marble</div>
                        
                        <!-- Before Overlay -->
                        <div class="absolute inset-0 w-full h-full [clip-path:polygon(0_0,50%_0,50%_100%,0_100%)] group-hover:[clip-path:polygon(0_0,0_0,0_100%,0_100%)] transition-all duration-1000 ease-in-out z-10 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=1200" class="w-full h-full object-cover filter sepia-[0.6] contrast-[0.6] brightness-[0.7] saturate-[0.3]">
                            <div class="absolute top-4 left-4 bg-white/90 text-slate-900 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">Aged Interior</div>
                        </div>
                        
                        <div class="absolute inset-y-0 left-1/2 w-1.5 bg-white shadow-[0_0_20px_rgba(0,0,0,0.5)] group-hover:left-0 transition-all duration-1000 ease-in-out -translate-x-1/2 z-30 flex items-center justify-center">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-2xl text-gold border-2 border-gold font-bold">↔</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- YouTube Links Section (Initially Hidden) -->
        <div id="youtube-links-section" class="hidden animate-fadeIn">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-center gap-4 mb-16 text-center">
                    <div class="w-12 h-[2px] bg-gold hidden md:block"></div>
                    <h2 class="text-3xl md:text-5xl font-heading font-black text-slate-900 uppercase italic tracking-tight">YouTube Links</h2>
                    <div class="w-12 h-[2px] bg-gold hidden md:block"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php 
                    // Retrieve dynamic youtube links added by admin
                    $dbYouTubeLinks = array_filter($galleryItems, function($item) {
                        return strcasecmp($item->category, 'YouTube links') === 0;
                    });
                    
                    foreach ($dbYouTubeLinks as $yt): ?>
                    <div class="aspect-video rounded-2xl overflow-hidden shadow-2xl border border-slate-200">
                        <iframe class="w-full h-full" src="<?= h(getYoutubeEmbedUrl($yt->url)) ?>" title="<?= h($yt->title) ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <?php endforeach; ?>

                    <!-- YouTube Link 1 (Default) -->
                    <div class="aspect-video rounded-2xl overflow-hidden shadow-2xl border border-slate-200">
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/KbhUAOP79uw" title="YouTube video 1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    
                    <!-- YouTube Link 2 (Default) -->
                    <div class="aspect-video rounded-2xl overflow-hidden shadow-2xl border border-slate-200">
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/ScMzIvxBSi4" title="YouTube video 2" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>

                    <!-- YouTube Link 3 (Default) -->
                    <div class="aspect-video rounded-2xl overflow-hidden shadow-2xl border border-slate-200">
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/EngW7tLk6R8" title="YouTube video 3" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Section -->
        <div class="mt-32 pt-20 border-t border-slate-100">
            <div class="max-w-5xl mx-auto">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-[2px] bg-gold"></div>
                    <h2 class="text-2xl md:text-4xl font-heading font-black text-slate-900 uppercase italic italic tracking-tight">The Journey of Natural Stone</h2>
                </div>
                
                <div class="relative aspect-video bg-black rounded-3xl overflow-hidden shadow-[0_32px_64px_-16px_rgba(0,0,0,0.3)] border border-slate-200 group">
                    <iframe 
                        class="absolute inset-0 w-full h-full opacity-90 group-hover:opacity-100 transition-opacity duration-700" 
                        src="https://www.youtube.com/embed/KbhUAOP79uw?autoplay=0&mute=0&controls=1" 
                        title="Stone Journey" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                    <!-- Overlay shadow for premium look -->
                    <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/40 to-transparent pointer-events-none"></div>
                </div>
                
                <div class="mt-10 text-center">
                    <p class="text-slate-500 font-medium italic">Observe the precision and scale of our operations from block extraction to final export packaging.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function filterGallery(category) {
        const grid = document.getElementById('gallery-grid');
        const transformSection = document.getElementById('transformations-section');
        const youtubeSection = document.getElementById('youtube-links-section');
        const items = document.querySelectorAll('.gallery-item');
        const btns = document.querySelectorAll('.gallery-filter-btn');

        // Update Button States
        btns.forEach(btn => {
            const btnCat = btn.getAttribute('data-category');
            if (btnCat === category) {
                btn.classList.remove('bg-slate-100', 'text-slate-500');
                btn.classList.add('bg-slate-900', 'text-white', 'shadow-xl');
            } else {
                btn.classList.remove('bg-slate-900', 'text-white', 'shadow-xl');
                btn.classList.add('bg-slate-100', 'text-slate-500');
            }
        });

        // Toggle Sections
        grid.classList.add('hidden');
        transformSection.classList.add('hidden');
        youtubeSection.classList.add('hidden');

        if (category === 'Transformations') {
            transformSection.classList.remove('hidden');
        } else if (category === 'YouTube links') {
            youtubeSection.classList.remove('hidden');
        } else {
            grid.classList.remove('hidden');
            items.forEach(item => {
                if (category === 'All' || item.getAttribute('data-category') === category) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }
    }
</script>

<?php include 'includes/footer.php'; ?>
