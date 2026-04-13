<?php
include 'includes/header.php';

// Handle Management
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $name = $_POST['name'];
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        $stmt->execute([$name, $slug]);
        redirect('categories.php?success=added');
    }
    
    if (isset($_POST['delete_id'])) {
        $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$_POST['delete_id']]);
        redirect('categories.php?deleted=1');
    }
}

$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count FROM categories c ORDER BY name ASC")->fetchAll();
?>

<main class="p-10 flex-grow animate-fadeIn bg-white">
    <div class="max-w-4xl mx-auto">
        <header class="mb-12 flex justify-between items-end">
            <div>
                <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Stone Categorization</h2>
                <p class="text-slate-500 font-medium italic">Define and organize your material classifications for global inventory management.</p>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Form -->
            <div class="lg:col-span-5">
                <div class="bg-slate-900 rounded-3xl p-10 text-white shadow-2xl relative overflow-hidden group border border-slate-800">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-gold/10 rounded-full blur-3xl"></div>
                    <h3 class="text-xl font-black uppercase tracking-widest mb-8 border-b border-white/5 pb-4">Classification Engine</h3>
                    
                    <form method="POST" class="space-y-6 relative z-10">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Class Name (e.g. Italian Marble) *</label>
                            <input type="text" name="name" required class="w-full bg-white/5 border border-white/10 rounded-xl px-6 py-4 font-bold text-white focus:border-gold outline-none shadow-sm transition-all focus:bg-white/10 text-sm">
                        </div>

                        <div class="pt-6">
                            <button type="submit" name="add_category" class="w-full bg-gold text-black px-12 py-5 rounded-2xl text-[10px] font-black uppercase tracking-[0.25em] shadow-xl shadow-gold/20 hover:-translate-y-1 transition-all">Add Class to Repository</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden min-h-[500px]">
                    <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center text-xs font-black uppercase tracking-widest">
                        <span>Classification Ledger</span>
                        <span class="text-slate-400"><?= count($categories) ?> Active Classes</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-[9px] font-black uppercase text-slate-400 tracking-widest border-b border-slate-100">
                                <tr>
                                    <th class="py-5 px-8">Class Label</th>
                                    <th class="py-5 px-8 text-center">Items</th>
                                    <th class="py-5 px-8 text-right">Vault Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs divide-y divide-slate-50">
                                <?php if (empty($categories)): ?>
                                <tr><td colspan="3" class="py-20 text-center text-slate-300 font-bold uppercase tracking-widest italic animate-pulse">Repository empty.</td></tr>
                                <?php else: foreach ($categories as $cat): ?>
                                <tr class="hover:bg-slate-50 transition-all duration-300 group">
                                    <td class="py-6 px-8">
                                        <div class="font-black text-slate-900 uppercase mb-0.5 tracking-tight group-hover:text-gold transition-colors"><?= h($cat->name) ?></div>
                                        <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Identifier: <?= h($cat->slug) ?></div>
                                    </td>
                                    <td class="py-6 px-8 text-center font-black text-slate-400 group-hover:text-slate-900 transition-colors">
                                        <?= $cat->product_count ?>
                                    </td>
                                    <td class="py-6 px-8 text-right">
                                        <?php if ($cat->product_count == 0): ?>
                                        <form method="POST" onsubmit="return confirm('Purge this classification from repo?')">
                                            <input type="hidden" name="delete_id" value="<?= $cat->id ?>">
                                            <button type="submit" class="text-red-300 hover:text-red-600 transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                            </button>
                                        </form>
                                        <?php else: ?>
                                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest cursor-not-allowed italic" title="Cannot delete category containing active products.">Class Locked</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
