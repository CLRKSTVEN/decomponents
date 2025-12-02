<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link rel="icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="shortcut icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="apple-touch-icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/design.css'); ?>">
</head>
<body>
    <?php $this->load->view('partials/navbar'); ?>

    <main class="decom-main">
        <div class="decom-container" style="text-align:center; max-width:680px;">
            <div class="section-header">
                <h2>Thank you for your order!</h2>
                <p>We’re preparing your items. A confirmation email will be sent shortly.</p>
            </div>
            <div class="card section-card" style="padding:24px;">
                <p class="text-muted">You can track your orders from your account page. If you need help, reach out to support.</p>
                <div style="margin-top:16px; display:flex; justify-content:center; gap:12px; flex-wrap:wrap;">
                    <a class="decom-button" href="<?= site_url('products'); ?>">Continue Shopping</a>
                    <a class="decom-button decom-button--ghost" href="<?= site_url('Decomponents/profile'); ?>">View Profile</a>
                </div>
            </div>
        </div>
    </main>

    <footer class="decom-footer">
        <nav class="decom-footer-nav">
            <a href="<?= site_url('home'); ?>">HOME</a>
            <a href="<?= site_url('products'); ?>">PRODUCTS</a>
            <a href="<?= site_url('about'); ?>">ABOUT US</a>
            <a href="<?= site_url('tradables'); ?>">TRADE</a>
            <a href="<?= site_url('news'); ?>">NEWS</a>
            <a href="<?= site_url('contact'); ?>">CONTACT</a>
        </nav>
        <div class="decom-social-links">
            <a href="https://www.youtube.com/@DeComponents" target="_blank"><img src="<?= base_url('Pictures/Yutob.webp'); ?>" alt="YouTube"></a>
            <a href="https://www.facebook.com/profile.php?id=61568617385907" target="_blank"><img src="<?= base_url('Pictures/Fishbuk.webp'); ?>" alt="Facebook"></a>
            <a href="https://www.tiktok.com/@decomponents" target="_blank"><img src="<?= base_url('Pictures/Tiktook.png'); ?>" alt="TikTok"></a>
            <a href="https://www.linkedin.com/in/de-components-934ba3337/" target="_blank"><img src="<?= base_url('Pictures/linkedin.png'); ?>" alt="LinkedIn"></a>
            <a href="#" target="_blank"><img src="<?= base_url('Pictures/Instagram.png'); ?>" alt="Instagram"></a>
        </div>
        <div class="decom-contact-email">
            <a href="mailto:decomponents.24@gmail.com">DeComponents.24@Gmail.com</a>
        </div>
        <p class="decom-copyright">© 2024 by DeComponents. All Rights Reserved.</p>
    </footer>
</body>
</html>
