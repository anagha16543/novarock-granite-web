<?php 
include 'includes/header.php'; 

// Fetch Engineering Settings
$settings = $pdo->query("SELECT * FROM calculator_settings WHERE id = 1")->fetch();
if (!$settings) {
    // Default Fallback
    $settings = (object)[
        'weight_per_sqm' => 45.00,
        'thickness_multipliers' => '{"18mm": 1, "20mm": 1.1, "30mm": 1.5}',
        'slab_width' => 300,
        'slab_height' => 180,
        'wastage_percent' => 10
    ];
}
$multipliers = json_decode($settings->thickness_multipliers, true);
?>


<main class="bg-[#f9fafb] min-h-screen pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-7xl font-heading font-black text-slate-900 uppercase mb-4 tracking-tight italic">Stone Calculators</h1>
            <div class="w-24 h-1.5 bg-gold mx-auto mb-6"></div>
            <p class="text-slate-600 max-w-2xl mx-auto font-medium">
                A robust suite of construction estimators for bulk planning and architectural logistics.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php
            $calculators = [
                ['id' => 'weight', 'title' => 'Granite Weight', 'image' => 'assets/images/calc_weight.png', 'desc' => 'Calculate weight based on dimensions and thickness.'],
                ['id' => 'flooring', 'title' => 'Flooring Area', 'image' => 'assets/images/calc_flooring.png', 'desc' => 'Estimate total square feet/meters for flooring.'],
                ['id' => 'countertop', 'title' => 'Countertop Area', 'image' => 'assets/images/calc_countertop.png', 'desc' => 'Perfect for kitchen platforms and vanity tops.'],
                ['id' => 'steps', 'title' => 'Steps & Raiser', 'image' => 'assets/images/calc_steps.png', 'desc' => 'Calculate requirements for full staircases.'],
                ['id' => 'density', 'title' => 'Density Calc', 'image' => 'assets/images/calc_density.png', 'desc' => 'Convert between density, mass, and volume.'],
                ['id' => 'shipping', 'title' => 'Container Capacity', 'image' => 'assets/images/calc_shipping.png', 'desc' => 'Estimate slabs per 20ft/40ft container.'],
                ['id' => 'blocks', 'title' => 'Block Volume', 'image' => 'assets/images/calc_blocks.png', 'desc' => 'Length, Height and Width of Granite Blocks.'],
            ];

            foreach ($calculators as $calc):
            ?>
            <div onclick="openCalculator('<?php echo $calc['id']; ?>')" class="group cursor-pointer bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                <div class="aspect-square bg-slate-50 rounded-2xl overflow-hidden mb-6 flex items-center justify-center p-8 border border-slate-50 group-hover:bg-white transition-colors">
                    <img src="<?php echo $calc['image']; ?>" alt="<?php echo $calc['title']; ?>" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700">
                </div>
                <div class="space-y-3">
                    <h3 class="text-xl font-heading font-black text-slate-900 uppercase italic group-hover:text-gold transition-colors"><?php echo $calc['title']; ?></h3>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed"><?php echo $calc['desc']; ?></p>
                    <div class="pt-4">
                        <span class="inline-block bg-slate-900 text-white px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest group-hover:bg-gold transition-all">Launch</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<!-- Calculator Modal -->
<div id="calc-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md animate-fadeIn">
    <div class="bg-white rounded-[2rem] w-full max-w-2xl shadow-2xl relative overflow-hidden flex flex-col md:flex-row h-auto max-h-[90vh]">
        
        <!-- Left: Inputs -->
        <div class="md:w-3/5 p-8 md:p-12 border-b md:border-b-0 md:border-r border-slate-100 overflow-y-auto">
            <button onclick="closeCalculator()" class="absolute top-6 right-6 text-slate-300 hover:text-slate-900 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="inline-flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-gold/10 rounded-xl flex items-center justify-center text-gold">📊</div>
                <h2 id="modal-title" class="text-2xl font-heading font-black text-slate-900 uppercase italic">Calculator</h2>
            </div>
            
            <form id="calc-form" onsubmit="handleCalculate(event)" class="space-y-6">
                <!-- Inputs injected here -->
                <div id="calc-inputs" class="space-y-4"></div>
                <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl text-xs font-black uppercase tracking-[0.2em] shadow-xl hover:bg-gold hover:-translate-y-0.5 transition-all active:scale-95">Calculate Now</button>
            </form>
        </div>

        <!-- Right: Results -->
        <div class="md:w-2/5 p-8 md:p-12 bg-slate-50 flex flex-col justify-center items-center text-center">
            <div id="result-placeholder" class="space-y-4">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-slate-300 mx-auto shadow-sm">⏳</div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Awaiting Input</p>
            </div>
            <div id="result-display" class="hidden space-y-2 animate-zoomIn">
                <p id="result-label" class="text-[10px] font-black text-gold uppercase tracking-[0.3em]">Estimated Weight</p>
                <div id="result-value" class="text-5xl font-heading font-black text-slate-900 italic">0.00</div>
                <p id="result-unit" class="text-xs font-bold text-slate-400 uppercase">Kilograms</p>
            </div>
        </div>
    </div>
</div>

<script>
    let activeCalc = '';

    function openCalculator(id) {
        activeCalc = id;
        const modal = document.getElementById('calc-modal');
        const title = document.getElementById('modal-title');
        const inputs = document.getElementById('calc-inputs');
        const resPlace = document.getElementById('result-placeholder');
        const resDisp = document.getElementById('result-display');
        
        resPlace.classList.remove('hidden');
        resDisp.classList.add('hidden');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        const configs = {
            weight: {
                title: 'Weight Estimator',
                html: `
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Length</label><input type="number" step="any" id="length" value="10" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold focus:ring-2 ring-gold/20 outline-none"></div>
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Width</label><input type="number" step="any" id="width" value="5" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold focus:ring-2 ring-gold/20 outline-none"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Unit</label><select id="unit" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold outline-none"><option value="ft">Feet</option><option value="m">Meters</option></select></div>
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Thickness (mm)</label><input type="number" id="thickness" value="18" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold outline-none"></div>
                    </div>`
            },
            flooring: {
                title: 'Flooring Area',
                html: `
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Length</label><input type="number" step="any" id="length" value="20" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"></div>
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Width</label><input type="number" step="any" id="width" value="15" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"></div>
                    </div>
                    <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Unit</label><select id="unit" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"><option value="ft">Square Feet</option><option value="m">Square Meters</option></select></div>`
            },
            countertop: {
                title: 'Countertop Area',
                html: `
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Length (Inches)</label><input type="number" step="any" id="length" value="120" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"></div>
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Width (Inches)</label><input type="number" step="any" id="width" value="30" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"></div>
                    </div>`
            },
            steps: {
                title: 'Staircase Steps',
                html: `
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Step Length (ft)</label><input type="number" id="length" value="4" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"></div>
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Step Width (ft)</label><input type="number" id="width" value="1.2" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"></div>
                    </div>
                    <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Number of Steps</label><input type="number" id="count" value="15" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"></div>`
            },
            density: {
                title: 'Density Tracker',
                html: `
                    <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Mass (kg)</label><input type="number" id="mass" value="1000" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"></div>
                    <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Volume (m³)</label><input type="number" step="any" id="vol" value="0.37" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"></div>`
            },
            shipping: {
                title: 'Shipping Hub',
                html: `
                    <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Container Type</label><select id="ctype" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"><option value="20">20ft Container (27 Tons)</option><option value="40">40ft Container (28 Tons)</option></select></div>
                    <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">Thickness (mm)</label><select id="thick" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"><option value="18">18mm</option><option value="20">20mm</option><option value="30">30mm</option></select></div>`
            },
            blocks: {
                title: 'Block Volume',
                html: `
                    <div class="grid grid-cols-3 gap-3">
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">L (m)</label><input type="number" step="any" id="l" value="3.2" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"></div>
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">W (m)</label><input type="number" step="any" id="w" value="1.8" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"></div>
                        <div class="space-y-1"><label class="text-[10px] font-black uppercase text-slate-400 px-1">H (m)</label><input type="number" step="any" id="h" value="1.8" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 font-bold"></div>
                    </div>`
            }
        };

        const config = configs[id] || { title: 'Calculator', html: '<p>Under Development</p>' };
        title.innerText = config.title;
        inputs.innerHTML = config.html;
    }

    function closeCalculator() {
        document.getElementById('calc-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function handleCalculate(e) {
        e.preventDefault();
        const resPlace = document.getElementById('result-placeholder');
        const resDisp = document.getElementById('result-display');
        const valDisp = document.getElementById('result-value');
        const labelDisp = document.getElementById('result-label');
        const unitDisp = document.getElementById('result-unit');
        
        resPlace.classList.add('hidden');
        resDisp.classList.remove('hidden');

        let result = 0;
        let label = '';
        let unit = '';

        // Internal Calibration from Database
        const CAL_WEIGHT = <?= floatval($settings->weight_per_sqm ?? 45.0) ?>;
        const CAL_WASTAGE = <?= floatval($settings->wastage_percent ?? 10.0) ?>;
        const CAL_MULTIS = <?= !empty($settings->thickness_multipliers) ? $settings->thickness_multipliers : '{}' ?>;

        if (activeCalc === 'weight') {
            const L = parseFloat(document.getElementById('length').value);
            const W = parseFloat(document.getElementById('width').value);
            const T = parseFloat(document.getElementById('thickness').value);
            const U = document.getElementById('unit').value;
            let areaSqM = U === 'ft' ? (L * 0.3048) * (W * 0.3048) : L * W;
            // Apply wastage
            areaSqM = areaSqM * (1 + (CAL_WASTAGE / 100));
            
            // Standardize density for weight base
            // (Base weight per sqm is usually for 18mm)
            const thicknessKey = T + 'mm';
            const multiplier = CAL_MULTIS[thicknessKey] || (T / 18);
            
            result = areaSqM * CAL_WEIGHT * multiplier;
            label = 'Estimated Weight';
            unit = 'Kilograms (kg)';
        } else if (activeCalc === 'flooring') {
            const L = parseFloat(document.getElementById('length').value);
            const W = parseFloat(document.getElementById('width').value);
            const U = document.getElementById('unit').value;
            result = L * W;
            label = 'Total Area';
            unit = U === 'ft' ? 'Square Feet (sqft)' : 'Square Meters (sqm)';
        } else if (activeCalc === 'countertop') {
            const L = parseFloat(document.getElementById('length').value);
            const W = parseFloat(document.getElementById('width').value);
            result = (L * W) / 144;
            label = 'Surface Area';
            unit = 'Square Feet (sqft)';
        } else if (activeCalc === 'steps') {
            const L = parseFloat(document.getElementById('length').value);
            const W = parseFloat(document.getElementById('width').value);
            const C = parseInt(document.getElementById('count').value);
            result = L * W * C;
            label = 'Step Material';
            unit = 'Square Feet (sqft)';
        } else if (activeCalc === 'density') {
            const M = parseFloat(document.getElementById('mass').value);
            const V = parseFloat(document.getElementById('vol').value);
            result = M / V;
            label = 'Material Density';
            unit = 'kg/m³';
        } else if (activeCalc === 'blocks') {
            const L = parseFloat(document.getElementById('l').value);
            const W = parseFloat(document.getElementById('w').value);
            const H = parseFloat(document.getElementById('h').value);
            result = L * W * H;
            label = 'Block Volume';
            unit = 'Cubic Meters (m³)';
        } else if (activeCalc === 'shipping') {
            const CT = document.getElementById('ctype').value;
            const TH = document.getElementById('thick').value;
            const cap = CT === '20' ? 27000 : 28000; // kg
            const weightPerM2 = (TH / 18) * 45; // kg/m2 roughly
            result = cap / weightPerM2;
            label = 'Max Loading';
            unit = 'Square Meters (sqm)';
        }

        valDisp.innerText = result.toLocaleString(undefined, { maximumFractionDigits: 2 });
        labelDisp.innerText = label;
        unitDisp.innerText = unit;
    }
</script>

<?php include 'includes/footer.php'; ?>
