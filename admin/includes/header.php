<?php
require_once __DIR__ . '/../../includes/db.php';
require_login();

$current_page = basename($_SERVER['PHP_SELF']);
$unread_count = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'unread'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novarock Admin | Premium Stone Export</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-gold: #C6A75E;
        }
        @layer base {
            body { @apply bg-gray-50 text-slate-900; }
        }
    </style>
</head>
<body class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col p-6 border-r border-slate-800 fixed h-full z-50">
        <div class="mb-10 pb-4 border-b border-white/5">
            <h1 class="text-xl font-black text-gold tracking-widest uppercase">NOVAROCK CMS</h1>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Enterprise Administration</p>
        </div>
        
        <nav class="flex-grow space-y-2">
            <a href="index.php" class="flex items-center gap-3 py-3 px-4 rounded-lg transition-all <?= $current_page == 'index.php' ? 'bg-white/10 text-gold font-bold shadow-lg' : 'hover:bg-white/5 text-slate-400 hover:text-white' ?>">
                <span>📊</span> Dashboard
            </a>
            <a href="products.php" class="flex items-center gap-3 py-3 px-4 rounded-lg transition-all <?= $current_page == 'products.php' ? 'bg-white/10 text-gold font-bold shadow-lg' : 'hover:bg-white/5 text-slate-400 hover:text-white' ?>">
                <span>💎</span> Products
            </a>
            <a href="gallery.php" class="flex items-center gap-3 py-3 px-4 rounded-lg transition-all <?= $current_page == 'gallery.php' ? 'bg-white/10 text-gold font-bold shadow-lg' : 'hover:bg-white/5 text-slate-400 hover:text-white' ?>">
                <span>🖼️</span> Gallery
            </a>
            <a href="categories.php" class="flex items-center gap-3 py-3 px-4 rounded-lg transition-all <?= $current_page == 'categories.php' ? 'bg-white/10 text-gold font-bold shadow-lg' : 'hover:bg-white/5 text-slate-400 hover:text-white' ?>">
                <span>📂</span> Categories
            </a>
            <a href="inquiries.php" class="flex items-center justify-between py-3 px-4 rounded-lg transition-all <?= $current_page == 'inquiries.php' ? 'bg-white/10 text-gold font-bold shadow-lg' : 'hover:bg-white/5 text-slate-400 hover:text-white' ?>">
                <span class="flex items-center gap-3"><span>📩</span> Inquiries</span>
                <?php if ($unread_count > 0): ?>
                <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full ring-4 ring-slate-900"><?= $unread_count ?></span>
                <?php endif; ?>
            </a>
            <a href="analytics.php" class="flex items-center gap-3 py-3 px-4 rounded-lg transition-all <?= $current_page == 'analytics.php' ? 'bg-white/10 text-gold font-bold shadow-lg' : 'hover:bg-white/5 text-slate-400 hover:text-white' ?>">
                <span>🔍</span> Market Intelligence
            </a>
            <div class="pt-4 border-t border-white/5 mt-4">
                <a href="settings.php" class="flex items-center gap-3 py-3 px-4 rounded-lg transition-all <?= $current_page == 'settings.php' ? 'bg-white/10 text-gold font-bold shadow-lg' : 'hover:bg-white/5 text-slate-400 hover:text-white' ?>">
                    <span>⚙️</span> Calculator Settings
                </a>
            </div>
        </nav>

        <div class="mt-auto border-t border-white/5 pt-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gold/20 flex items-center justify-center text-gold font-bold text-xs">
                    <?= strtoupper(substr($_SESSION['admin_user'], 0, 1)) ?>
                </div>
                <span class="text-xs text-slate-400 font-bold capitalize"><?= h($_SESSION['admin_user']) ?></span>
            </div>
            <a href="logout.php" class="text-red-400 hover:text-red-300 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </a>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-grow ml-64 min-h-screen flex flex-col">
