<?php 
include 'includes/header.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo '<div class="min-h-screen flex items-center justify-center text-slate-500 font-bold uppercase tracking-widest">Product not found. <a href="products.php" class="ml-4 text-gold border-b border-gold">Return to Catalog</a></div>';
    include 'includes/footer.php';
    exit;
}

// v3.0 Alpha: Analytics
$site_track = $pdo->query("SELECT analytics_enabled FROM site_settings WHERE id = 1")->fetch();
if ($site_track && $site_track->analytics_enabled) {
    $pdo->prepare("UPDATE products SET views = views + 1 WHERE id = ?")->execute([$id]);
}

// Fetch all images
$imgStmt = $pdo->prepare("SELECT url FROM product_images WHERE product_id = ?");
$imgStmt->execute([$id]);
$images = $imgStmt->fetchAll(PDO::FETCH_COLUMN);
$mainImg = !empty($images) ? $images[0] : 'assets/images/placeholder.jpg';
?>

<main class="bg-white min-h-screen pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center text-sm text-slate-400 mb-12 uppercase tracking-widest font-bold">
            <a href="index.php" class="hover:text-gold transition-colors">Home</a>
            <span class="mx-3 opacity-30">/</span>
            <a href="products.php?cat=<?php echo $product->category_id; ?>" class="hover:text-gold transition-colors"><?php echo h($product->category_name); ?></a>
            <span class="mx-3 opacity-30">/</span>
            <span class="text-slate-800"><?php echo h($product->name); ?></span>
        </nav>

        <div class="flex flex-col items-center mb-16">
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-heading font-black text-gold uppercase text-center mb-6 tracking-tight italic">
                <?php echo h($product->name); ?>
            </h1>
            <div class="flex flex-wrap justify-center gap-6 text-[11px] font-black uppercase tracking-[0.25em] text-slate-400">
                <span class="cursor-pointer hover:text-gold transition-colors">About</span>
                <span class="cursor-pointer hover:text-gold transition-colors">Sizes</span>
                <span class="cursor-pointer hover:text-gold transition-colors">Thickness</span>
                <span class="cursor-pointer hover:text-gold transition-colors">Suitable Uses</span>
                <span class="cursor-pointer hover:text-gold transition-colors">Inquiry</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
            
            <!-- Left: Image Gallery -->
            <div class="lg:col-span-7 flex flex-col-reverse md:flex-row gap-6">
                <!-- Thumbnails -->
                <?php if (count($images) > 1): ?>
                <div class="flex md:flex-col gap-4 overflow-x-auto md:overflow-y-auto max-h-[600px] no-scrollbar">
                    <?php foreach ($images as $img): ?>
                    <button onclick="changeMainImage('<?php echo ltrim($img, '/'); ?>')" class="w-20 h-20 rounded-lg overflow-hidden border border-slate-100 flex-shrink-0 transition-all hover:border-gold active:scale-95">
                        <img src="<?php echo ltrim($img, '/'); ?>" class="w-full h-full object-cover">
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Main View -->
                <div class="flex-grow bg-slate-50 rounded-2xl p-8 md:p-12 border border-slate-100 relative group aspect-square flex items-center justify-center">
                    <img id="main-product-image" src="<?php echo ltrim($mainImg, '/'); ?>" alt="<?php echo h($product->name); ?>" class="max-w-full max-h-full object-contain drop-shadow-2xl transition-all duration-500 group-hover:scale-105">
                </div>
            </div>

            <!-- Right: Product Info & Calculator -->
            <div class="lg:col-span-5 space-y-12">
                <div class="prose prose-slate max-w-none">
                    <p class="text-lg text-slate-600 leading-relaxed font-medium">
                        <?php echo h($product->description ?: 'Premium architectural stone selection. Hand-picked and factory finished for luxury global exports.'); ?>
                    </p>
                </div>

                <!-- Pricing Engine -->
                <div class="bg-white border-t border-slate-200 pt-10">
                    <div class="mb-10 text-center md:text-left">
                        <h3 class="text-xs font-black uppercase tracking-[0.3em] text-slate-900"><?php echo h($product->name); ?> CONFIGURATION</h3>
                    </div>

                    <!-- Config Sliders / Dropdowns -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-12">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Size Selection</label>
                            <select id="calc-size" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-sm font-bold bg-white focus:border-gold outline-none">
                                <option value="vertical">Vertical Slab</option>
                                <option value="horizontal">Horizontal Slab</option>
                                <option value="custom">Cut to Size</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Grade / Quality</label>
                            <select id="calc-grade" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-sm font-bold bg-white focus:border-gold outline-none">
                                <option value="premium">Premium Grade</option>
                                <option value="standard">Standard Grade</option>
                                <option value="commercial">Commercial Grade</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Thickness</label>
                            <select id="calc-thick" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-sm font-bold bg-white focus:border-gold outline-none">
                                <option value="18">18 MM</option>
                                <option value="20">20 MM</option>
                                <option value="30">30 MM</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Surface Finish</label>
                            <select id="calc-finish" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-sm font-bold bg-white focus:border-gold outline-none">
                                <option value="polished">Mirror Polished</option>
                                <option value="honed">Honed / Matte</option>
                                <option value="leather">Leather / Texture</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6 border-t border-slate-100">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Quantity</label>
                                <select id="calc-qty" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-sm font-bold bg-white focus:border-gold outline-none">
                                    <option value="1">1 Slab / Sample</option>
                                    <option value="5">5 Slabs</option>
                                    <option value="10">10 Slabs</option>
                                    <option value="20">20+ Slabs (Container)</option>
                                    <option value="50">Commercial Bulk</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Currency</label>
                                <select id="calc-currency" onchange="syncPriceConfig()" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-sm font-bold bg-white focus:border-gold outline-none">
                                    <option value="₹">₹ Indian Rupee (INR)</option>
                                    <option value="$">$ US Dollar (USD)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Measurement Unit</label>
                                <select id="calc-unit" onchange="syncPriceConfig()" class="w-full border border-slate-200 rounded-lg px-4 py-3 text-sm font-bold bg-white focus:border-gold outline-none">
                                    <option value="Ft">Sq. Ft.</option>
                                    <option value="Mtr">Sq. Mtr.</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <button onclick="viewCombination()" class="block w-full border-2 border-slate-900 text-slate-900 py-4 rounded-xl text-center text-xs font-black uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all">View Combination & Get Result</button>
                    </div>

                    <!-- Combination Summary (Hidden by default) -->
                    <div id="combination-panel" class="hidden mb-6 p-6 bg-gold/5 border border-gold/20 rounded-2xl">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-gold mb-4">Selected Combination</h4>
                        <div class="space-y-2 text-xs font-bold text-slate-700">
                            <div class="flex justify-between"><span>Size:</span> <span id="summary-size"></span></div>
                            <div class="flex justify-between"><span>Grade:</span> <span id="summary-grade"></span></div>
                            <div class="flex justify-between"><span>Thickness:</span> <span id="summary-thick"></span></div>
                            <div class="flex justify-between"><span>Finish:</span> <span id="summary-finish"></span></div>
                            <div class="flex justify-between border-t border-gold/10 pt-2 mt-2"><span>Quantity:</span> <span id="summary-qty"></span></div>
                        </div>
                    </div>

                    <!-- Result Panel (Hidden by default) -->
                    <div id="result-panel" class="hidden text-center py-10 bg-slate-50 rounded-2xl border border-slate-100 mb-10">
                        <div class="flex items-center justify-center gap-2 mb-2">
                            <span class="text-2xl font-light text-slate-400" id="display-currency">₹</span>
                            <span class="text-5xl font-heading font-black text-slate-900" id="display-price">0.00</span>
                        </div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Indicative Price per <span id="display-unit">Sq. Ft.</span></p>
                    </div>

                    <div class="space-y-4">
                        <a href="contact.php?id=<?php echo $product->id; ?>" class="block w-full bg-slate-900 text-white py-4 rounded-xl text-center text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-xl">Get Exact Delivered Quote</a>
                        <a href="calculator.php" class="block w-full border border-slate-200 text-slate-600 py-4 rounded-xl text-center text-xs font-bold uppercase tracking-widest hover:border-gold hover:text-gold transition-all">Open Area Calculator</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const productBasePrice = <?php echo (float)($product->price_per_sqft ?: 2.5); ?>;
    let currentConfig = {
        currency: '₹',
        unit: 'Ft'
    };

    function changeMainImage(url) {
        document.getElementById('main-product-image').src = url;
    }

    function syncPriceConfig() {
        currentConfig.currency = document.getElementById('calc-currency').value;
        currentConfig.unit = document.getElementById('calc-unit').value;
        
        // Only calculate automatically if the result panel is already visible
        if (!document.getElementById('result-panel').classList.contains('hidden')) {
            calculatePrice();
        }
    }

    function calculatePrice() {
        let price = productBasePrice;

        const size = document.getElementById('calc-size').value;
        const grade = document.getElementById('calc-grade').value;
        const thick = document.getElementById('calc-thick').value;
        const finish = document.getElementById('calc-finish').value;

        if (size === 'horizontal') price *= 1.15;
        if (size === 'custom') price *= 1.25;
        
        if (grade === 'premium') price *= 1.2;
        if (grade === 'commercial') price *= 0.85;

        if (thick === '20') price *= 1.1;
        if (thick === '30') price *= 1.35;

        if (finish === 'honed') price *= 1.05;
        if (finish === 'leather') price *= 1.15;

        // 1. Convert Unit first
        if (currentConfig.unit === 'Mtr') {
            price *= 10.764; // 1 SqMtr = 10.764 SqFt
            document.getElementById('display-unit').innerText = 'Sq. Mtr.';
        } else {
            document.getElementById('display-unit').innerText = 'Sq. Ft.';
        }

        // 2. Convert Currency
        if (currentConfig.currency === '₹') {
            price *= 83.50; // INR conversion
            document.getElementById('display-currency').innerText = '₹';
        } else {
            document.getElementById('display-currency').innerText = '$';
        }

        document.getElementById('display-price').innerText = price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function viewCombination() {
        const panel = document.getElementById('combination-panel');
        const resultPanel = document.getElementById('result-panel');
        
        // Update summary values
        document.getElementById('summary-size').innerText = document.getElementById('calc-size').options[document.getElementById('calc-size').selectedIndex].text;
        document.getElementById('summary-grade').innerText = document.getElementById('calc-grade').options[document.getElementById('calc-grade').selectedIndex].text;
        document.getElementById('summary-thick').innerText = document.getElementById('calc-thick').options[document.getElementById('calc-thick').selectedIndex].text;
        document.getElementById('summary-finish').innerText = document.getElementById('calc-finish').options[document.getElementById('calc-finish').selectedIndex].text;
        document.getElementById('summary-qty').innerText = document.getElementById('calc-qty').options[document.getElementById('calc-qty').selectedIndex].text;

        panel.classList.remove('hidden');
        resultPanel.classList.remove('hidden');
        
        // Calculate the price and update the UI
        calculatePrice();

        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

</script>

<?php include 'includes/footer.php'; ?>
