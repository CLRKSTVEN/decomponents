<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RTX 4070 Ti - DeComponents</title>
    <link rel="stylesheet" href="<?php echo base_url('design.css'); ?>">
</head>

<body>
    <header class="decom-header">
        <div class="decom-container">
            <img src="DeComponents.jpeg" alt="DeComponents Logo" class="decom-logo">
            <nav class="decom-nav">
                <a href="<?php echo site_url("home"); ?>" class="decom-nav-link">HOME</a>
                <a href="<?php echo site_url("products"); ?>" class="decom-nav-link">PRODUCTS</a>
                <a href="<?php echo site_url("about"); ?>" class="decom-nav-link">ABOUT US</a>
                <a href="<?php echo site_url("tradables"); ?>" class="decom-nav-link">TRADABLES</a>
                <a href="<?php echo site_url("news"); ?>" class="decom-nav-link">NEWS</a>
                <a href="<?php echo site_url("contact"); ?>" class="decom-nav-link">CONTACT</a>
            </nav>
        </div>
    </header>

    <div class="product-container">
        <div class="product-main">
            <div class="product-image">
                <img src="<?php echo base_url('Pictures/'); ?> alt=" RTX 4070 Ti">
            </div>
            <div class="product-info">
                <h1>RTX 4070 Ti</h1>
                <p>NVIDIA GeForce RTX 4070 Ti, 12GB GDDR6X, Ampere architecture, DLSS 3, Ray Tracing support, PCIe 4.0, 3 x DisplayPort 1.4a, 1 x HDMI 2.1, TDP 285W, and supports 4K gaming and content creation with exceptional performance.</p>
                <div class="product-price">
                    <h2>₱79,000</h2>
                    <div>
                        <button class="order-now">Order Now</button>
                        <button class="add-to-cart">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="related-products">
            <h2>Related Products</h2>
            <div class="product-grid">
                <div class="product-item">
                    <img src="<?php echo base_url('Pictures/'); ?> alt=" RTX 4070">
                </div>
                <div class="product-item">
                    <img src="<?php echo base_url('Pictures/'); ?> alt=" RTX 4070">
                </div>
                <div class="product-item">
                    <img src="<?php echo base_url('Pictures/'); ?> alt=" RTX 4070">
                </div>
                <div class="product-item">
                    <img src="<?php echo base_url('Pictures/'); ?> alt=" RTX 4070">
                </div>
            </div>
        </div>

        <div class="reviews">
            <h2>Reviews</h2>
            <div class="review-item">
                <img src="<?php echo base_url('Pictures/'); ?> alt=" User Avatar" class="review-avatar">
                <p>The Intel Core i7 processor and NVIDIA GeForce RTX 3070 graphics card make this laptop an absolute beast when it comes to gaming performance. I’ve been playing some of the most graphics-intensive games, like Cyberpunk 2077 and Call of Duty: Warzone, and the laptop handles them with zero lag and ultra-smooth frame rates. The 16GB of RAM is more than enough for multitasking, and the 1TB SSD gives me lightning-fast load times and tons of space for my game library.</p>
            </div>
        </div>
    </div>

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
            <a href="https://www.youtube.com/@DeComponents" target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
            <a href=" https://www.facebook.com/profile.php?id=61568617385907"target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
            <a href=" https://www.tiktok.com/@decomponents"target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
            <a href=" https://www.linkedin.com/in/de-components-934ba3337/"target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
            <a href=" #"target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
        </div>
        <div class=" decom-contact-email">
                <a href="mailto:decomponents.24@gmail.com">DeComponents.24@Gmail.com</a>
        </div>
        <p class="decom-copyright">© 2024 by DeComponents. All Rights Reserved.</p>
    </footer>
</body>

</html>