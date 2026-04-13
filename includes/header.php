<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

// Simple translation logic placeholder
function __($key) {
    $langs = [
        'en' => [
            'home' => 'Home',
            'products' => 'Products',
            'gallery' => 'Gallery',
            'licenses' => 'Licenses',
            'about' => 'About Us',
            'contact' => 'Contact',
            'get_quote' => 'Get Quote',
        ],
        'hi' => [
            'home' => 'होम',
            'products' => 'उत्पाद',
            'gallery' => 'गैलरी',
            'licenses' => 'लाइसेंस',
            'about' => 'हमारे बारे में',
            'contact' => 'संपर्क करें',
            'get_quote' => 'कोट प्राप्त करें',
        ]
    ];
    $lang = $_SESSION['lang'] ?? 'en';
    return $langs[$lang][$key] ?? $key;
}
// Fetch Global site Settings
$site = $pdo->query("SELECT * FROM site_settings WHERE id = 1")->fetch();
if (!$site) {
    $site = (object)[
        'site_title' => 'Novarock International',
        'site_description' => 'Premium Stone Export & Manufacturing',
        'meta_keywords' => 'granite, marble, stone export'
    ];
}
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?? 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h($site->site_title); ?> | Premium Stone Export</title>
    
    <!-- Meta Tags -->
    <meta name="description" content="<?php echo h($site->site_description); ?>">
    <meta name="keywords" content="<?php echo h($site->meta_keywords); ?>">
    
    <!-- Tailwind CSS 4 CDN (or compatible version) -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style type="text/tailwindcss">
        @theme {
            --color-primary: #C6A75E;
            --color-secondary: #FAFAFA;
            --color-accent: #000000;
            --color-dark: #111111;
            --color-gold: #C6A75E;
            --font-heading: 'Outfit', sans-serif;
            --font-sans: 'Inter', sans-serif;
        }
        
        @layer base {
            body { 
                @apply font-sans bg-white text-slate-900;
                scroll-behavior: smooth;
            }
            h1, h2, h3, h4 { @apply font-heading; }
        }
        
        @layer components {
            .glass-card {
                @apply bg-white/70 backdrop-blur-xl border border-white/40 shadow-xl;
            }
            .btn-gold {
                @apply bg-gold text-black px-8 py-3 rounded-md font-bold uppercase tracking-widest transition-all hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0;
            }
            .scroll-reveal {
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.6s cubic-bezier(0.22, 1, 0.36, 1);
            }
            .scroll-reveal.active {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="overflow-x-hidden">

<!-- Header -->
<header id="main-header" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-gradient-to-b from-black/80 to-transparent py-4 md:py-6">
    <div class="max-w-7xl mx-auto px-4 md:px-6 flex justify-between items-center">
        <a href="index.php" class="relative h-12 w-48 sm:w-56 md:h-16 md:w-64 flex items-center transition-opacity hover:opacity-80">
            <img src="assets/images/logo-transparent.png" alt="NOVAROCK" class="h-full w-full object-contain object-left drop-shadow-lg">
        </a>

        <nav class="hidden md:flex items-center space-x-6">
            <?php
            $navLinks = [
                ['name' => __('home'), 'href' => 'index.php'],
                ['name' => __('products'), 'href' => 'products.php'],
                ['name' => __('gallery'), 'href' => 'gallery.php'],
                ['name' => __('licenses'), 'href' => 'licenses.php'],
                ['name' => __('about'), 'href' => 'about.php'],
                ['name' => __('contact'), 'href' => 'contact.php'],
            ];
            foreach ($navLinks as $link):
            ?>
            <a href="<?php echo $link['href']; ?>" class="group relative text-base font-extrabold transition-colors uppercase tracking-widest text-gold hover:text-white pb-1 drop-shadow-md">
                <?php echo $link['name']; ?>
                <span class="absolute left-0 bottom-0 w-0 h-[3px] bg-gold transition-all duration-300 group-hover:w-full shadow-[0_0_10px_rgba(198,167,94,0.5)]"></span>
            </a>
            <?php endforeach; ?>
        </nav>

        <!-- Mobile Toggle -->
        <button id="mobile-menu-toggle" class="md:hidden text-white focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden absolute top-full left-0 w-full bg-black/95 backdrop-blur-lg border-t border-white/10 p-6 flex flex-col space-y-4">
        <?php foreach ($navLinks as $link): ?>
            <a href="<?php echo $link['href']; ?>" class="text-xl font-bold text-white hover:text-gold"><?php echo $link['name']; ?></a>
        <?php endforeach; ?>
        <a href="contact.php" class="bg-gold text-black text-center py-3 rounded-md font-bold">Get Quote</a>
    </div>
</header>

<script>
    // Header Scroll Logic
    const header = document.getElementById('main-header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.remove('bg-gradient-to-b', 'from-black/80', 'to-transparent', 'py-4', 'md:py-6');
            header.classList.add('bg-black/90', 'backdrop-blur-md', 'border-b', 'border-white/10', 'py-3', 'md:py-4');
        } else {
            header.classList.add('bg-gradient-to-b', 'from-black/80', 'to-transparent', 'py-4', 'md:py-6');
            header.classList.remove('bg-black/90', 'backdrop-blur-md', 'border-b', 'border-white/10', 'py-3', 'md:py-4');
        }
    });

    // Mobile Menu Logic
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');
    toggle.addEventListener('click', () => menu.classList.toggle('hidden'));
</script>
