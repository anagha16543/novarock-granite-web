<?php
require_once '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin->password)) {
            $_SESSION['admin_id'] = $admin->id;
            $_SESSION['admin_user'] = $admin->email;
            redirect('index.php');
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'All fields are required.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Novarock CMS</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-gold: #C6A75E;
        }
    </style>
</head>
<body class="bg-[#0b0d10] flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black text-white tracking-widest uppercase">Novarock Admin</h1>
            <p class="text-gold text-xs font-bold tracking-[0.3em] uppercase mt-2">Content Management System</p>
        </div>

        <form action="login.php" method="POST" class="bg-white/5 backdrop-blur-xl border border-white/10 p-8 rounded-2xl shadow-2xl space-y-6">
            <?php if ($error): ?>
                <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-3 rounded text-sm text-center">
                    <?php echo h($error); ?>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-white text-xs font-bold uppercase tracking-widest mb-2">Email Address</label>
                <input type="email" name="email" required placeholder="admin@novarock.com" class="w-full bg-white/10 border border-white/20 rounded-lg p-3 text-white focus:border-gold outline-none transition-colors">
            </div>

            <div>
                <label class="block text-white text-xs font-bold uppercase tracking-widest mb-2">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full bg-white/10 border border-white/20 rounded-lg p-3 text-white focus:border-gold outline-none transition-colors">
            </div>

            <button type="submit" class="w-full bg-gold hover:bg-[#b2914e] text-black font-black py-4 rounded-xl shadow-lg transition-all transform hover:-translate-y-1 active:translate-y-0">
                SECURE LOGIN
            </button>
        </form>
        
        <p class="text-center mt-8 text-white/40 text-xs uppercase tracking-widest">
            &copy; <?php echo date('Y'); ?> Novarock International
        </p>
    </div>
</body>
</html>
