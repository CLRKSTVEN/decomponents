<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tradables - DeComponents</title>
    <link rel="icon" href="<?php echo base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="shortcut icon" href="<?php echo base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="apple-touch-icon" href="<?php echo base_url('Pictures/Decomponents.jpeg'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/design.css'); ?>">

</head>

<body>
    <?php $this->load->view('partials/navbar'); ?>

    <main class="decom-main">
        <div class="decom-container">
            <div class="decom-showcase-hero">
                <img src="<?php echo base_url('Pictures/GPU.jpg'); ?>" alt="GPU News">
                <div class="decom-container">
                <h1 class="decom-AboutUS-header">Latest News</h1>

            </div>
            <div class="decom-showcase-products">
                <div class="decom-showcase-item">
                    <div class="decom-showcase-image">
                        <img src="<?php echo base_url('Pictures/RTX50.jpg'); ?>" alt="RTX 50 Series">
                    </div>
                    <div class=" decom-showcase-content">
                        <h2>NVIDIA GeForce 50 Series: What to Expect from the Upcoming Big Tech GPU Revolution</h2>
                        <p>While the GeForce RTX 40 series, based on the Ada Lovelace architecture, has already set a new benchmark for performance, it seems that NVIDIA is already looking ahead to their next-gen GPUs, the RTX 50 series. Sources from industry insiders suggest that the RTX 50 cards will be based on an even more advanced architecture, codenamed Lovelace 2, designed to push the boundaries of ray tracing, DLSS technology, and AI-driven performance.</p>
                        <br>
                        <p>While specific details remain under wraps, early reports indicate that the RTX 50 series will focus heavily on next-gen gaming experiences, with 4K, 8K, and even 16K gaming becoming increasingly feasible at smooth frame rates. The GPUs will feature an improved fabrication process (likely 3nm or smaller) and powerful custom chips that will offer not only higher raw performance but also better power efficiency and thermal management.</p>
                    </div>
                </div>

                <div class="decom-showcase-item">
                    <div class="decom-showcase-image">
                        <img src="<?php echo base_url('Pictures/RAEDON7000.jpg'); ?>" alt="Radeon 7000">
                    </div>
                    <div class=" decom-showcase-content">
                        <h2>AMD Radeon’s Latest Graphics Cards: The Future of Gaming and Content Creation</h2>
                        <p>As AMD continues its battle for dominance in the GPU market, the company is once again raising the bar with the release of its latest Radeon RX 7000 series graphics cards. With the RDNA 3 architecture, AMD has made significant strides in gaming and productivity performance, and the new GPUs are positioning themselves as formidable contenders against NVIDIA's high-end offerings. Here’s everything we know about AMD’s upcoming Radeon RX 7800 XT, Radeon RX 7900 XT, and Radeon RX 7950 XT—the most powerful GPUs to date from the red team.</p>
                        <br>
                        <p>The new Radeon RX 7000 series represents a significant leap forward for AMD, and with RDNA 3, the company is well-positioned to continue its challenge to NVIDIA’s dominance in the GPU space. With powerful performance improvements in ray tracing, AI upscaling, and 4K gaming, AMD is delivering impressive alternatives to NVIDIA’s high-end graphics cards. If AMD continues on this trajectory, the Radeon RX 7000 series may just be the best choice for gamers and content creators looking for top-tier performance without breaking the bank.</p>
                    </div>
                </div>
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
