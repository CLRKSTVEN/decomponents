<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeComponents - Computer Hardware Store</title>
    <link rel="stylesheet" href="<?php echo base_url('design.css'); ?>">
</head>

<body>
    <header class="decom-header">
        <div class="decom-container">
            <img src="<?php echo base_url('Pictures/DeComponents.jpeg'); ?>" alt="DeComponents Logo" class="decom-logo">
            <nav class="decom-nav">
                <div class="decom-container">
                    <a href="<?php echo site_url("home"); ?>" class="decom-nav-link">HOME</a>
                    <a href="<?php echo site_url("products"); ?>" class="decom-nav-link">PRODUCTS</a>
                    <a href="<?php echo site_url("about"); ?>" class="decom-nav-link">ABOUT US</a>
                    <a href="<?php echo site_url("tradables"); ?>" class="decom-nav-link">TRADABLES</a>
                    <a href="<?php echo site_url("news"); ?>" class="decom-nav-link">NEWS</a>
                    <a href="<?php echo site_url("contact"); ?>" class="decom-nav-link">CONTACT</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="decom-main">
        <div class="decom-container">
            <div class="decom-hero">
                <img src="<?php echo base_url('Pictures/intelcpu.jpg'); ?>" alt="Latest Intel Processors" class="decom-hero-image">
            </div>

            <!-- Video Section -->
            <div class="decom-video-section">
                <div class="decom-video-grid">
                    <div class="decom-video-card">
                        <div class="decom-video-wrapper">
                            <iframe
                                src="https://www.youtube.com/embed/your-video-id-1"
                                title="Intel Core Processors"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <h3 class="decom-video-title">Intel Core Series</h3>
                        <p class="decom-video-description">Discover the power of Intel's latest processors</p>
                    </div>

                    <div class="decom-video-card">
                        <div class="decom-video-wrapper">
                            <iframe
                                src="https://www.youtube.com/embed/ymDmvQccQ8g"
                                title="Graphics Cards Showcase"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <h3 class="decom-video-title">Graphics Cards</h3>
                        <p class="decom-video-description">Explore our range of high-performance GPUs</p>
                    </div>

                    <div class="decom-video-card">
                        <div class="decom-video-wrapper">
                            <iframe
                                src="https://www.youtube.com/embed/your-video-id-3"
                                title="Gaming PC Builds"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <h3 class="decom-video-title">PC Build Guides</h3>
                        <p class="decom-video-description">Step-by-step hardware installation tutorials</p>
                    </div>
                </div>
            </div>

            <div class="decom-category-grid">
                <div class="decom-info-card">
                    <div class="decom-info-icon">
                        <img src="<?php echo base_url('Pictures/Trade.png'); ?>" alt="Icon">
                    </div>
                    <p>At Decomponents, you have the opportunity to trade products with ease, whether you're looking to sell or exchange items. Our platform provides a seamless process to connect buyers and sellers, offering a trusted environment for both personal and business transactions.</p>
                </div>
                <div class="decom-info-card">
                    <div class="decom-info-icon">
                        <img src="<?php echo base_url('Pictures/search.png'); ?>" alt="Icon">
                    </div>
                    <p>Explore a diverse range of products across various categories on our platform. From the latest tech gadgets to everyday essentials, Decomponents offers an intuitive browsing experience, helping you quickly find exactly what you're looking for.</p>
                </div>
                <div class="decom-info-card">
                    <div class="decom-info-icon">
                        <img src="<?php echo base_url('Pictures/Popular.png'); ?>" alt="Icon">
                    </div>
                    <p>Discover top brands, both new and well-established, all in one place. Whether you're after the latest innovations or trusted favorites, Decomponents brings you a curated selection of products from the most popular and emerging brands in the market.</p>
                </div>
                <div class="decom-info-card">
                    <div class="decom-info-icon">
                        <img src="<?php echo base_url('Pictures/Develop.png'); ?>" alt="Icon">
                    </div>
                    <p>Join us in making Decomponents the future of online shopping. We're constantly evolving, and your feedback and support will help us innovate and create a platform that delivers even more value, convenience, and accessibility to users worldwide.</p>
                </div>
            </div>

            <div class="decom-category-grid">
                <div class="decom-category">
                    <a href=" target=" _blank">
                        <img src="<?php echo base_url('Pictures/Graphics.webp'); ?>" alt="Graphics Card" class="decom-category-image">
                    </a>
                    <h3 class="decom-category-title">Graphics Card</h3>
                </div>
                <div class="decom-category">
                    <img src="<?php echo base_url('Pictures/often.webp'); ?>" alt="CPU" class="decom-category-image">
                    <h1 class="decom-category-title">CPU</h1>
                </div>
                <div class="decom-category">
                    <img src="<?php echo base_url('Pictures/powsu.webp'); ?>" alt="Power Supply" class="decom-category-image">
                    <h3 class="decom-category-title">Power Supply</h3>
                </div>
                <div class="decom-category">
                    <img src="<?php echo base_url('Pictures/moboth.webp'); ?>" alt="Mother Board" class="decom-category-image">
                    <h3 class="decom-category-title">Mother Board</h3>
                </div>
            </div>

            <div class="decom-hero">
                <img src="<?php echo base_url('Pictures/mewomewo.jpg'); ?>" alt="RTX 4070 Ti Banner" class="decom-hero-image">
            </div>

            <div class="decom-testimonials">
                <h2 class="decom-testimonials-title">What People Have to say about us</h2>
                <div class="decom-testimonials-container">
                    <div class="decom-testimonials-content">
                        <div class="decom-testimonial-card">
                            <div class="decom-testimonial-icon"></div>
                            <p>As a frequent online shopper, I've come across my fair share of e-commerce websites, but Decomponents stands out in a few ways, both for its simplicity and the variety of products it offers. Here's my honest review after spending some time browsing and shopping on the platform.</p>
                        </div>
                        <div class="decom-testimonial-card">
                            <div class="decom-testimonial-icon"></div>
                            <p>From the moment I landed on the website, I was impressed by how clean and organized everything was. The homepage is straightforward, with clear categories like Tech, Gadgets, and Accessories that made finding what I was looking for a breeze. The search bar was particularly helpful when I was looking for a specific product. There was no clutter, and everything felt intuitive.</p>
                        </div>
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
        <div class="decom-contact-email">
            <a href="mailto:decomponents.24@gmail.com">DeComponents.24@Gmail.com</a>
        </div>
        <p class="decom-copyright">© 2024 by DeComponents. All Rights Reserved.</p>
    </footer>
</body>

</html>