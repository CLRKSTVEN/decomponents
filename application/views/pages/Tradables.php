<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treydables - DeComponents</title>
    <link rel="icon" href="<?php echo base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="shortcut icon" href="<?php echo base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="apple-touch-icon" href="<?php echo base_url('Pictures/Decomponents.jpeg'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/design.css'); ?>">
</head>

<body>
    <?php $this->load->view('partials/navbar'); ?>

    <main class="decom-main">
        <div class="decom-container">
            <div class="decom-trade-list">

                <div class="decom-about-trading">
                    <h1>ALL ABOUT TRADING</h1>
                    <p>At Decomponents, we offer a seamless and secure platform for trading high-quality tech components. Whether you're looking to buy, sell, or exchange products, our user-friendly interface ensures a hassle-free experience. Discover top brands, compare prices, and get the best deals on everything from GPUs to motherboards. Join our growing community of traders and contribute to building the future of tech. Try it now!</p>
                    <a href="<?php echo site_url("trading"); ?>" class="decom-start-trading" target="_blank">Start Trading</a>
                </div>

                <div class="decom-trade-item">
                    <div class="decom-trade-image">
                        <img src="<?php echo base_url('Pictures/4070Ti.png'); ?>" alt="RTX 4070Ti">
                    </div>
                    <div class="decom-trade-content">
                        <h2>RTX 4070Ti</h2>
                        <p>The card is barely used and in pristine condition with all original packaging and accessories. Perfect for 1440p and 4K gaming, offering incredible performance and ray-tracing capabilities. Looking for a fair trade for either a higher-tier GPU like the RTX 4080 or 4090, or equivalent value in cash or other PC components. Open to negotiating offers.</p>
                        <a href="<?php echo site_url("trade-now"); ?>" class="decom-trade-button" target="_blank">Trade Now</a>
                    </div>
                </div>

                <div class="decom-trade-item">
                    <div class="decom-trade-image">
                        <img src="<?php echo base_url('Pictures/2080Ti.png'); ?>" alt="RTX 2080Ti">
                    </div>
                    <div class="decom-trade-content">
                        <h2>RTX 2080Ti</h2>
                        <p>The RTX 2080 Ti has been a powerhouse for 1440p and 4K gaming, delivering smooth performance and great ray tracing capabilities. It’s in excellent working condition, no overheating issues, and has never been overclocked. Looking for a fair trade for a newer model like the RTX 3070, RTX 3080, or RTX 4070 Ti. I’m also open to offers for other high-performance components or cash.</p>
                        <a href="#" class="decom-trade-button" target="_blank">Trade Now</a>
                    </div>
                </div>
    </main>

    <footer class="decom-footer">
        <nav class="decom-footer-nav">
            <a href="<?php echo site_url("home"); ?>">HOME</a>
            <a href="<?php echo site_url("products"); ?>">PRODUCTS</a>
            <a href="<?php echo site_url("about"); ?>">ABOUT US</a>
            <a href="<?php echo site_url("tradables"); ?>">TRADE</a>
            <a href="<?php echo site_url("news"); ?>">NEWS</a>
            <a href="<?php echo site_url("contact"); ?>">CONTACT</a>
        </nav>
        <div class="decom-social-links">
            <a href="https://www.youtube.com/@DeComponents" target="_blank"><img src="<?php echo base_url('Pictures/Yutob.webp'); ?>" alt="YouTube"></a>
            <a href="https://www.facebook.com/profile.php?id=61568617385907" target="_blank"><img src="<?php echo base_url('Pictures/Fishbuk.webp'); ?>" alt="Facebook"></a>
            <a href="https://www.tiktok.com/@decomponents" target="_blank"><img src="<?php echo base_url('Pictures/Tiktook.png'); ?>" alt="TikTok"></a>
            <a href="https://www.linkedin.com/in/de-components-934ba3337/" target="_blank"><img src="<?php echo base_url('Pictures/linkedin.png'); ?>" alt="LinkedIn"></a>
            <a href="#" target="_blank"><img src="<?php echo base_url('Pictures/Instagram.png'); ?>" alt="Instagram"></a>
        </div>
        <div class=" decom-contact-email">
                <a href="mailto:decomponents.24@gmail.com">DeComponents.24@Gmail.com</a>
        </div>
        <p class="decom-copyright">© 2024 by DeComponents. All Rights Reserved.</p>
    </footer>
</body>

</html>
