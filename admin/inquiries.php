<?php
include 'includes/header.php';

// Mark as read if viewing specific inquiry
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("UPDATE inquiries SET status = 'read' WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $inquiry = $pdo->prepare("SELECT * FROM inquiries WHERE id = ?");
    $inquiry->execute([$_GET['id']]);
    $iq = $inquiry->fetch();
}

// Delete inquiry
if (isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM inquiries WHERE id = ?");
    $stmt->execute([$_POST['delete_id']]);
    redirect('inquiries.php');
}

$inquiries = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC")->fetchAll();
?>

<main class="p-10 flex-grow animate-fadeIn bg-white">
    <header class="mb-12 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tight">Global Inquiries</h2>
            <p class="text-slate-500 font-medium italic">Manage and respond to client requests from international markets.</p>
        </div>
        <div class="flex gap-4">
            <button onclick="window.print()" class="text-[10px] font-black uppercase text-slate-400 hover:text-slate-600 tracking-widest flex items-center gap-2">📄 Export Ledger</button>
        </div>
    </header>

    <?php if (isset($iq)): ?>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-md">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-12 relative shadow-2xl animate-scaleIn border border-slate-100">
            <a href="inquiries.php" class="absolute top-8 right-8 text-slate-400 hover:text-slate-900 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </a>
            
            <div class="mb-10 pb-6 border-b border-slate-100 flex justify-between items-start">
                <div>
                    <h3 class="text-3xl font-black text-slate-900 uppercase italic tracking-tight mb-2"><?= h($iq->name) ?></h3>
                    <p class="text-xs font-black text-gold uppercase tracking-[0.25em]"><?= h($iq->email) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Received On</p>
                    <p class="text-xs font-bold text-slate-900 uppercase"><?= date('M d, Y | H:i', strtotime($iq->created_at)) ?></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 mb-10">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Phone Number</p>
                    <p class="text-sm font-bold text-slate-900"><?= h($iq->phone ?: 'Not provided') ?></p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Inquiry Source</p>
                    <p class="text-sm font-bold text-slate-900 capitalize"><?= h($iq->subject ?: 'General Request') ?></p>
                </div>
            </div>

            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 mb-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Message Content</p>
                <div class="text-base text-slate-700 leading-relaxed font-medium italic">"<?= nl2br(h($iq->message)) ?>"</div>
            </div>

            <div class="flex gap-4">
                <a href="mailto:<?= h($iq->email) ?>" class="bg-slate-900 text-white px-10 py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gold transition-all shadow-xl shadow-slate-200">Reply Now</a>
                <form method="POST" onsubmit="return confirm('Permanently delete this inquiry from the register?')">
                    <input type="hidden" name="delete_id" value="<?= $iq->id ?>">
                    <button type="submit" class="border-2 border-red-500 text-red-500 hover:bg-red-500 hover:text-white px-10 py-4 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Destroy Record</button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="bg-[#fafafa] rounded-3xl shadow-sm border border-slate-200 overflow-hidden min-h-[600px]">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-900 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] border-b border-slate-800">
                    <tr>
                        <th class="py-6 px-10">Contact Details</th>
                        <th class="py-6 px-10">Subject</th>
                        <th class="py-6 px-10">Timestamp</th>
                        <th class="py-6 px-10">Status</th>
                        <th class="py-6 px-10 text-right">Vault Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    <?php if (empty($inquiries)): ?>
                    <tr><td colspan="5" class="py-40 text-center text-slate-400 font-bold uppercase tracking-[0.5em] italic animate-pulse">Inquiry register currently empty.</td></tr>
                    <?php else: foreach ($inquiries as $iq): ?>
                    <tr class="hover:bg-white transition-all duration-300 <?= $iq->status == 'unread' ? 'border-l-4 border-l-gold bg-gold/5' : '' ?>">
                        <td class="py-6 px-10">
                            <div class="font-black text-slate-900 uppercase mb-1 tracking-tight"><?php echo h($iq->name); ?></div>
                            <div class="text-[10px] text-slate-400 font-bold italic tracking-wider"><?php echo h($iq->email); ?></div>
                        </td>
                        <td class="py-6 px-10">
                            <div class="font-bold text-slate-700 capitalize"><?php echo h($iq->subject ?: 'General inquiry'); ?></div>
                        </td>
                        <td class="py-6 px-10">
                            <div class="text-[10px] font-black text-slate-500 uppercase tracking-tighter"><?php echo date('M d, Y', strtotime($iq->created_at)); ?></div>
                            <div class="text-[9px] text-slate-300 font-bold uppercase"><?php echo date('H:i:s', strtotime($iq->created_at)); ?></div>
                        </td>
                        <td class="py-6 px-10">
                            <span class="text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest <?= $iq->status == 'unread' ? 'bg-red-500 text-white shadow-lg' : 'bg-slate-200 text-slate-500' ?>">
                                <?= h($iq->status) ?>
                            </span>
                        </td>
                        <td class="py-6 px-10 text-right flex justify-end gap-3">
                            <a href="inquiries.php?id=<?= $iq->id ?>" class="bg-white text-slate-900 hover:text-gold px-4 py-2 rounded-lg border-2 border-slate-100 hover:border-gold font-black uppercase text-[10px] transition-all">Details</a>
                            <form method="POST" onsubmit="return confirm('Confirm deletion?')">
                                <input type="hidden" name="delete_id" value="<?= $iq->id ?>">
                                <button type="submit" class="bg-red-50/50 text-red-400 hover:text-red-600 px-3 py-2 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
