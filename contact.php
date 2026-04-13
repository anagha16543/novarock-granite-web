<?php 
include 'includes/header.php'; 

$success = false;
$error = false;

$preselected_product_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$product_name = "";

if ($preselected_product_id) {
    $p_stmt = $pdo->prepare("SELECT name FROM products WHERE id = ?");
    $p_stmt->execute([$preselected_product_id]);
    $product_name = $p_stmt->fetchColumn();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_inquiry'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $dial_code = $_POST['dial_code'] ?? '';
    $phone_num = $_POST['phone'] ?? '';
    $phone = $dial_code . " " . $phone_num;
    $country = $_POST['country'] ?? '';
    $message = $_POST['message'] ?? '';
    $type = $_POST['inquiryType'] ?? 'general';
    $urgency = $_POST['urgency'] ?? 'normal';
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;

    try {
        $subject = "[" . strtoupper($urgency) . "] " . ucfirst($type) . " Inquiry from " . $name;
        $stmt = $pdo->prepare("INSERT INTO inquiries (name, email, phone, country, subject, message, inquiry_type, urgency, product_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $country, $subject, $message, $type, $urgency, $product_id]);
        $success = true;
    } catch (PDOException $e) {
        $error = "Failed to submit inquiry: " . $e->getMessage();
    }
}
?>

<main class="bg-white min-h-screen pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-heading font-black text-slate-900 uppercase mb-4 tracking-tight">Connect With Us</h1>
            <div class="w-24 h-1.5 bg-gold mx-auto mb-6"></div>
            <p class="max-w-2xl mx-auto text-slate-600 font-medium italic">Our global export team is ready to assist with your architectural requirements.</p>
        </div>

        <?php if ($success): ?>
        <div class="max-w-xl mx-auto bg-green-50 border border-green-200 rounded-2xl p-12 text-center mb-16 animate-fadeIn">
            <div class="w-16 h-16 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">✓</div>
            <h2 class="text-2xl font-heading font-black text-slate-900 uppercase mb-4">Message Received</h2>
            <p class="text-slate-600 mb-8 font-medium">Thank you for reaching out. A Novarock export specialist will contact you within 24 hours.</p>
            <a href="contact.php" class="text-xs font-black uppercase tracking-widest text-gold border-b-2 border-gold pb-1">Send Another Message</a>
        </div>
        <?php else: ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            
            <!-- Sidebar Info -->
            <div class="lg:col-span-4 space-y-12">
                <div class="bg-slate-900 rounded-3xl p-10 text-white shadow-2xl relative overflow-hidden group">
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-gold/10 rounded-full blur-3xl group-hover:bg-gold/20 transition-all duration-700"></div>
                    
                    <h3 class="text-2xl font-heading font-black uppercase italic mb-8 border-b border-white/10 pb-4">Head Office</h3>
                    
                    <div class="space-y-8 relative z-10">
                        <div class="flex gap-4">
                            <span class="text-gold mt-1">📍</span>
                            <div class="space-y-1">
                                <p class="text-xs font-black uppercase tracking-widest text-slate-400">Location</p>
                                <p class="text-sm font-medium leading-relaxed">123 Industrial Area, Kishangarh,<br>Rajasthan, India - 305801</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <span class="text-gold mt-1">✉️</span>
                            <div class="space-y-1">
                                <p class="text-xs font-black uppercase tracking-widest text-slate-400">Email</p>
                                <a href="mailto:export@novarock.com" class="text-sm font-medium hover:text-gold transition-colors">export@novarock.com</a>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <span class="text-gold mt-1">📞</span>
                            <div class="space-y-1">
                                <p class="text-xs font-black uppercase tracking-widest text-slate-400">Phone</p>
                                <a href="tel:+919876543210" class="text-sm font-medium hover:text-gold transition-colors">+91 98765 43210</a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 pt-8 border-t border-white/10">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.25em] text-gold mb-4">Direct Connect</h4>
                        <a href="https://wa.me/919876543210" class="flex items-center justify-center gap-3 w-full bg-green-600 hover:bg-green-500 py-4 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                            Chat on WhatsApp
                        </a>
                    </div>
                </div>

                <div class="rounded-3xl overflow-hidden h-[300px] border border-slate-200 grayscale hover:grayscale-0 transition-all duration-1000 shadow-xl">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d114000.00!2d74.8!3d26.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjbCsDMwJzAwLjAiTiA3NMKwNDgnMDAuMCJF!5e0!3m2!1sen!2sin!4v1620000000000!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-8 bg-slate-50 rounded-3xl p-8 md:p-16 border border-slate-100 shadow-sm">
                <form action="contact.php" method="POST" class="space-y-8">
                    <?php if ($preselected_product_id): ?>
                    <input type="hidden" name="product_id" value="<?= $preselected_product_id ?>">
                    <div class="mb-8 p-6 bg-gold/5 border border-gold/20 rounded-2xl flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase text-gold tracking-widest mb-1">Inquiring About</p>
                            <h4 class="text-sm font-black uppercase text-slate-900"><?= h($product_name) ?></h4>
                        </div>
                        <a href="contact.php" class="text-[9px] font-black uppercase text-slate-400 hover:text-gold transition-colors">Clear Selection</a>
                    </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Full Name *</label>
                            <input type="text" name="name" placeholder="John Doe" required class="w-full bg-white border border-slate-200 rounded-xl px-5 py-4 text-sm font-bold focus:border-gold outline-none transition-all shadow-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Email Address *</label>
                            <input type="email" name="email" placeholder="john@example.com" required class="w-full bg-white border border-slate-200 rounded-xl px-5 py-4 text-sm font-bold focus:border-gold outline-none transition-all shadow-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Phone Number *</label>
                            <div class="flex gap-2">
                                <select name="dial_code" class="w-1/3 bg-white border border-slate-200 rounded-xl px-3 py-4 text-xs font-bold focus:border-gold outline-none shadow-sm cursor-pointer">
                                    <option value="+91">🇮🇳 +91</option>
                                    <option value="+1">🇺🇸 +1</option>
                                    <option value="+44">🇬🇧 +44</option>
                                    <option value="+971">🇦🇪 +971</option>
                                    <option value="+966">🇸🇦 +966</option>
                                    <option value="+61">🇦🇺 +61</option>
                                    <option value="+49">🇩🇪 +49</option>
                                    <option value="+33">🇫🇷 +33</option>
                                    <option value="+81">🇯🇵 +81</option>
                                    <option value="+86">🇨🇳 +86</option>
                                </select>
                                <input type="tel" name="phone" required placeholder="9876543210" class="w-2/3 bg-white border border-slate-200 rounded-xl px-5 py-4 text-sm font-bold focus:border-gold outline-none transition-all shadow-sm">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Country of Residence *</label>
                            <select name="country" required class="w-full bg-white border border-slate-200 rounded-xl px-5 py-4 text-sm font-bold focus:border-gold outline-none transition-all shadow-sm cursor-pointer appearance-none">
                                <option value="" disabled selected>Select your country</option>
                                <option value="India">India</option>
                                <option value="United States">United States</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="Oman">Oman</option>
                                <option value="Qatar">Qatar</option>
                                <option value="Kuwait">Kuwait</option>
                                <option value="Australia">Australia</option>
                                <option value="Germany">Germany</option>
                                <option value="France">France</option>
                                <option value="Italy">Italy</option>
                                <option value="Canada">Canada</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Inquiry Type</label>
                            <div class="relative">
                                <select name="inquiryType" class="w-full bg-white border border-slate-200 rounded-xl px-5 py-4 text-sm font-bold focus:border-gold outline-none appearance-none cursor-pointer">
                                    <option value="general">General Inquiry</option>
                                    <option value="export">Export Order</option>
                                    <option value="quote">Price Quote</option>
                                    <option value="partnership">Business Partnership</option>
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">▼</div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Urgency Level</label>
                            <div class="relative">
                                <select name="urgency" class="w-full bg-white border border-slate-200 rounded-xl px-5 py-4 text-sm font-bold focus:border-gold outline-none appearance-none cursor-pointer">
                                    <option value="normal">Standard (2-3 days)</option>
                                    <option value="urgent">Urgent (24 hours)</option>
                                    <option value="immediate">Immediate Requirement</option>
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">▼</div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Detailed Message *</label>
                        <textarea name="message" required rows="5" placeholder="Specify your stone dimensions, finish, and quantity requirements..." class="w-full bg-white border border-slate-200 rounded-xl px-5 py-4 text-sm font-bold focus:border-gold outline-none transition-all shadow-sm"></textarea>
                    </div>

                    <div class="pt-6">
                        <button type="submit" name="submit_inquiry" class="w-full bg-slate-900 text-white px-16 py-5 rounded-xl text-xs font-black uppercase tracking-[0.2em] hover:bg-gold hover:text-slate-950 transition-all shadow-xl shadow-slate-200 active:scale-95 flex items-center justify-center gap-3">
                            <span>🚀</span> Dispatch Inquiry
                        </button>
                    </div>

                    <?php if ($error): ?>
                    <div class="p-4 bg-red-50 border border-red-100 rounded-xl text-red-500 text-xs font-bold animate-pulse">
                        ⚠️ <?php echo $error; ?>
                    </div>
                    <?php endif; ?>
                </form>
            </div>

        </div>

        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
