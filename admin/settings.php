<?php
include 'includes/header.php';

// Fetch current setting record (row 1)
$settings = $pdo->query("SELECT * FROM calculator_settings WHERE id = 1")->fetch();
$site = $pdo->query("SELECT * FROM site_settings WHERE id = 1")->fetch();

// Create default if missing
if (!$settings) {
    $pdo->exec("INSERT INTO calculator_settings (id) VALUES (1)");
    $settings = $pdo->query("SELECT * FROM calculator_settings WHERE id = 1")->fetch();
}
if (!$site) {
    $pdo->exec("INSERT INTO site_settings (id) VALUES (1)");
    $site = $pdo->query("SELECT * FROM site_settings WHERE id = 1")->fetch();
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_calc'])) {
        $weight = (float)$_POST['weight_per_sqm'];
        $width = (float)$_POST['slab_width'];
        $height = (float)$_POST['slab_height'];
        $wastage = (float)$_POST['wastage_percent'];
        
        $multipliers = [];
        foreach ($_POST['thick_name'] as $i => $name) {
            if (!empty($name)) {
                $multipliers[$name] = (float)$_POST['thick_val'][$i];
            }
        }
        $multi_json = json_encode($multipliers);

        $stmt = $pdo->prepare("UPDATE calculator_settings SET weight_per_sqm=?, thickness_multipliers=?, slab_width=?, slab_height=?, wastage_percent=? WHERE id=1");
        $stmt->execute([$weight, $multi_json, $width, $height, $wastage]);
        redirect('settings.php?success=calc');
    }

    if (isset($_POST['save_seo'])) {
        $title = $_POST['site_title'];
        $desc = $_POST['site_description'];
        $email = $_POST['contact_email'];
        $phone = $_POST['contact_phone'];
        $address = $_POST['office_address'];
        $keywords = $_POST['meta_keywords'];
        $analytics = isset($_POST['analytics_enabled']) ? 1 : 0;

        $stmt = $pdo->prepare("UPDATE site_settings SET site_title=?, site_description=?, contact_email=?, contact_phone=?, office_address=?, meta_keywords=?, analytics_enabled=? WHERE id=1");
        $stmt->execute([$title, $desc, $email, $phone, $address, $keywords, $analytics]);
        redirect('settings.php?success=seo');
    }
}

$multipliers = json_decode($settings->thickness_multipliers, true) ?: [];
?>

<main class="p-10 flex-grow animate-fadeIn bg-white">
    <header class="mb-12">
        <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Enterprise Calibration v3.0</h2>
        <p class="text-slate-500 font-medium italic">Strategically manage Global Branding, SEO, and Engineering Multipliers.</p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Dashboard Navigation -->
        <div class="lg:col-span-3 space-y-4">
            <button onclick="switchTab('calc')" id="btn-calc" class="w-full text-left px-8 py-5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all bg-slate-900 text-white shadow-xl">📊 Logistics & Math</button>
            <button onclick="switchTab('seo')" id="btn-seo" class="w-full text-left px-8 py-5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all bg-white text-slate-400 hover:bg-slate-50 border border-slate-100">🌍 Branding & SEO</button>
        </div>

        <div class="lg:col-span-9">
            <!-- Calculator Tab -->
            <div id="tab-calc" class="space-y-10 animate-slideIn">
                <form method="POST" class="space-y-10">
                    <div class="bg-[#fafafa] rounded-3xl p-10 border border-slate-100 shadow-sm space-y-10">
                        <div class="grid grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Base Weight per SQM (kg/m²)*</label>
                                <input type="number" step="0.01" name="weight_per_sqm" value="<?= $settings->weight_per_sqm ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm transition-all focus:bg-white text-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Default Wastage Allowance (%)*</label>
                                <input type="number" step="0.1" name="wastage_percent" value="<?= $settings->wastage_percent ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm transition-all focus:bg-white text-sm">
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-200">
                            <h4 class="text-xs font-black uppercase tracking-[0.25em] text-gold mb-8">Thickness Weight Multipliers</h4>
                            <div id="multiplier-rows" class="space-y-4">
                                <?php foreach ($multipliers as $name => $val): ?>
                                <div class="flex gap-4 multiplier-row">
                                    <input type="text" name="thick_name[]" value="<?= h($name) ?>" placeholder="Thickness Name" class="flex-grow bg-white border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-900 outline-none text-sm">
                                    <input type="number" step="0.01" name="thick_val[]" value="<?= $val ?>" class="w-32 bg-white border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-900 outline-none text-sm">
                                    <button type="button" onclick="this.parentElement.remove()" class="text-red-300 hover:text-red-500 p-2">✕</button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" onclick="addMultiplierRow()" class="mt-6 text-[10px] font-black uppercase tracking-widest text-slate-900 hover:text-gold transition-colors">+ Add Dimension Classification</button>
                        </div>
                    </div>
                    <button type="submit" name="save_calc" class="w-full bg-slate-900 text-white px-12 py-5 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-gold hover:text-black transition-all">Synchronize Logistics Engine</button>
                </form>
            </div>

            <!-- SEO Tab -->
            <div id="tab-seo" class="hidden space-y-10 animate-slideIn">
                <form method="POST" class="space-y-10">
                    <div class="bg-[#fafafa] rounded-3xl p-10 border border-slate-100 shadow-sm space-y-10">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Enterprise Designation (Site Title)*</label>
                            <input type="text" name="site_title" value="<?= h($site->site_title) ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm transition-all focus:bg-white text-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Mission Statement (Meta Description)*</label>
                            <textarea name="site_description" rows="3" required class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm transition-all focus:bg-white text-sm"><?= h($site->site_description) ?></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Primary Contact Email*</label>
                                <input type="email" name="contact_email" value="<?= h($site->contact_email) ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Global Sales Hotline*</label>
                                <input type="text" name="contact_phone" value="<?= h($site->contact_phone) ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">SEO Meta Keywords (Comma Separated)</label>
                            <input type="text" name="meta_keywords" value="<?= h($site->meta_keywords) ?>" class="w-full bg-white border border-slate-200 rounded-xl px-6 py-4 font-bold text-slate-900 focus:border-gold outline-none shadow-sm">
                        </div>

                        <div class="flex items-center gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <input type="checkbox" name="analytics_enabled" id="ana" <?= $site->analytics_enabled ? 'checked' : '' ?> class="w-6 h-6 accent-gold cursor-pointer">
                            <label for="ana" class="text-xs font-black uppercase text-slate-900 cursor-pointer">Enable Market Intelligence (Product View Tracking)</label>
                        </div>
                    </div>
                    <button type="submit" name="save_seo" class="w-full bg-slate-900 text-white px-12 py-5 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-gold hover:text-black transition-all">Update Global Identity</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function switchTab(tab) {
        document.getElementById('tab-calc').classList.add('hidden');
        document.getElementById('tab-seo').classList.add('hidden');
        document.getElementById('btn-calc').className = "w-full text-left px-8 py-5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all bg-white text-slate-400 border border-slate-100";
        document.getElementById('btn-seo').className = "w-full text-left px-8 py-5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all bg-white text-slate-400 border border-slate-100";
        
        document.getElementById('tab-' + tab).classList.remove('hidden');
        document.getElementById('btn-' + tab).className = "w-full text-left px-8 py-5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all bg-slate-900 text-white shadow-xl";
    }

    function addMultiplierRow() {
        const container = document.getElementById('multiplier-rows');
        const div = document.createElement('div');
        div.className = 'flex gap-4 multiplier-row animate-slideIn';
        div.innerHTML = `
            <input type="text" name="thick_name[]" placeholder="Thickness Name" class="flex-grow bg-white border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-900 outline-none text-sm">
            <input type="number" step="0.01" name="thick_val[]" value="1.00" class="w-32 bg-white border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-900 outline-none text-sm">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-300 hover:text-red-500 p-2">✕</button>
        `;
        container.appendChild(div);
    }
</script>

<?php include 'includes/footer.php'; ?>
