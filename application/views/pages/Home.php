<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeComponents - Computer Hardware Store</title>
    <link rel="icon" href="<?php echo base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="shortcut icon" href="<?php echo base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="apple-touch-icon" href="<?php echo base_url('Pictures/Decomponents.jpeg'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/design.css'); ?>">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --success-color: #10b981;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --bg-light: #f9fafb;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
        }

        .decom-main {
            padding: 48px 0 64px;
            background: linear-gradient(to bottom, #f9fafb 0%, #ffffff 100%);
        }

        .decom-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Hero Section */
        .decom-hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: center;
            background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%);
            border-radius: 24px;
            padding: 64px 56px;
            margin-bottom: 80px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .decom-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color));
        }

        .decom-hero-content h1 {
            font-size: 3rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 16px;
            line-height: 1.2;
            letter-spacing: -0.025em;
        }

        .decom-hero-content p {
            font-size: 1.25rem;
            color: var(--text-secondary);
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .decom-button {
            display: inline-block;
            padding: 16px 40px;
            background: var(--primary-color);
            color: white;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .decom-button:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.4);
        }

        .decom-hero-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border: 2px solid white;
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 12px;
            letter-spacing: -0.025em;
        }

        .section-header p {
            font-size: 1.125rem;
            color: var(--text-secondary);
        }

        /* Featured Products */
        .decom-featured-products {
            margin-bottom: 80px;
        }

        .decom-category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 32px;
            margin-bottom: 80px;
        }

        .decom-category {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .decom-category::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .decom-category:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .decom-category:hover::before {
            transform: scaleX(1);
        }

        .decom-category-image {
            width: 100%;
            height: 240px;
            object-fit: cover;
            border-radius: 16px;
            margin-bottom: 20px;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
        }

        .decom-category-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 12px;
        }

        .product-description {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 16px;
            flex: 1;
        }

        .price {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-top: auto;
        }

        /* Video Section */
        .decom-video-section {
            margin-bottom: 80px;
        }

        .decom-video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 32px;
        }

        .decom-video-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .decom-video-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .decom-video-wrapper {
            position: relative;
            aspect-ratio: 16 / 9;
            background: var(--bg-light);
        }

        .decom-video-wrapper iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .decom-video-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 20px 24px 8px;
        }

        .decom-video-description {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin: 0 24px 20px;
        }

        /* Info Cards */
        .decom-info-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 20px;
            padding: 32px;
            display: flex;
            gap: 20px;
            align-items: flex-start;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .decom-info-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .decom-info-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 2px solid var(--border-color);
        }

        .decom-info-icon img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }

        .decom-info-card p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.7;
            margin: 0;
        }

        /* Banner Image */
        .decom-banner {
            margin: 80px 0;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 2px solid var(--border-color);
        }

        .decom-banner img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            display: block;
        }

        /* Testimonials */
        .decom-testimonials {
            margin-bottom: 80px;
        }

        .decom-testimonials-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 48px;
            letter-spacing: -0.025em;
        }

        .decom-testimonials-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 32px;
        }

        .decom-testimonial-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 20px;
            padding: 32px;
            box-shadow: var(--shadow-md);
            position: relative;
            transition: all 0.3s ease;
        }

        .decom-testimonial-card::before {
            content: '"';
            position: absolute;
            top: 20px;
            left: 24px;
            font-size: 4rem;
            font-weight: 700;
            color: #e0f2fe;
            line-height: 1;
        }

        .decom-testimonial-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .decom-testimonial-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);
            border: 2px solid var(--border-color);
            margin-bottom: 16px;
        }

        .decom-testimonial-card p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.7;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        /* Footer */
        .decom-footer {
            margin-top: 80px;
            padding: 48px 24px;
            background: var(--bg-light);
            border-top: 1px solid var(--border-color);
        }

        .decom-footer-nav {
            display: flex;
            justify-content: center;
            gap: 32px;
            flex-wrap: wrap;
            margin-bottom: 32px;
        }

        .decom-footer-nav a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: color 0.2s ease;
        }

        .decom-footer-nav a:hover {
            color: var(--primary-color);
        }

        .decom-social-links {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .decom-social-links a {
            display: block;
            transition: transform 0.2s ease;
        }

        .decom-social-links a:hover {
            transform: scale(1.1);
        }

        .decom-social-links img {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
        }

        .decom-contact-email {
            text-align: center;
            margin-bottom: 16px;
        }

        .decom-contact-email a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: color 0.2s ease;
        }

        .decom-contact-email a:hover {
            color: var(--primary-hover);
        }

        .decom-copyright {
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .decom-hero {
                grid-template-columns: 1fr;
                gap: 32px;
                padding: 48px 40px;
            }

            .decom-hero-content h1 {
                font-size: 2.5rem;
            }

            .section-header h2 {
                font-size: 2rem;
            }

            .decom-testimonials-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .decom-container {
                padding: 0 16px;
            }

            .decom-hero {
                padding: 32px 24px;
                margin-bottom: 48px;
            }

            .decom-hero-content h1 {
                font-size: 2rem;
            }

            .decom-hero-content p {
                font-size: 1rem;
            }

            .decom-hero-image {
                height: 300px;
            }

            .section-header h2,
            .decom-testimonials-title {
                font-size: 1.75rem;
            }

            .decom-category-grid {
                grid-template-columns: 1fr;
                gap: 24px;
                margin-bottom: 48px;
            }

            .decom-testimonials-container {
                grid-template-columns: 1fr;
            }

            .decom-banner img {
                height: 250px;
            }

            .decom-footer-nav {
                flex-direction: column;
                gap: 16px;
                align-items: center;
            }
        }
    </style>
</head>

<body>
    <?php $this->load->view('partials/navbar'); ?>

    <?php
    $heroTitle = isset($siteSettings['hero_title']) ? $siteSettings['hero_title'] : 'DeComponents - Computer Hardware Store';
    $heroSubtitle = isset($siteSettings['hero_subtitle']) ? $siteSettings['hero_subtitle'] : 'Performance parts for your next build.';
    $heroCtaLabel = isset($siteSettings['hero_cta_label']) ? $siteSettings['hero_cta_label'] : 'Shop Now';
    $heroCtaLink  = isset($siteSettings['hero_cta_link']) ? $siteSettings['hero_cta_link'] : 'products';
    $featuredProducts = isset($featuredProducts) ? $featuredProducts : [];
    $testimonials     = isset($testimonials) ? $testimonials : [];
    $resolveImage = function ($path) {
        if (!$path) {
            return base_url('Pictures/DeComponents.jpeg');
        }
        if (preg_match('#^https?://#i', $path) || strpos($path, '//') === 0) {
            return $path;
        }
        return base_url(ltrim($path, '/'));
    };
    ?>

    <main class="decom-main">
        <div class="decom-container">
            <!-- Hero Section -->
            <div class="decom-hero">
                <div class="decom-hero-content">
                    <h1><?= html_escape($heroTitle); ?></h1>
                    <p><?= html_escape($heroSubtitle); ?></p>
                    <a class="decom-button" href="<?= site_url($heroCtaLink); ?>"><?= html_escape($heroCtaLabel); ?></a>
                </div>
                <img src="<?= base_url('Pictures/intelcpu.jpg'); ?>" alt="Latest Intel Processors" class="decom-hero-image">
            </div>

            <!-- Featured Products -->
            <?php if (!empty($featuredProducts)): ?>
                <section class="decom-featured-products">
                    <div class="section-header">
                        <h2>Featured Products</h2>
                        <p>Discover our handpicked selection of top-tier components</p>
                    </div>
                    <div class="decom-category-grid">
                        <?php foreach ($featuredProducts as $prod): ?>
                            <div class="decom-category">
                                <img src="<?= $resolveImage($prod['image'] ?? ''); ?>"
                                    alt="<?= htmlspecialchars($prod['name'] ?? 'Product', ENT_QUOTES, 'UTF-8'); ?>"
                                    class="decom-category-image">
                                <h3 class="decom-category-title"><?= htmlspecialchars($prod['name'] ?? 'Product', ENT_QUOTES, 'UTF-8'); ?></h3>
                                <p class="product-description"><?= htmlspecialchars($prod['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                                <div class="price">₱<?= number_format((float)($prod['price'] ?? 0), 2); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Video Section -->
            <section class="decom-video-section">
                <div class="section-header">
                    <h2>Explore Our Products</h2>
                    <p>Watch detailed reviews and build guides</p>
                </div>
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
            </section>

            <!-- Info Cards -->
            <section>
                <div class="section-header">
                    <h2>Why Choose DeComponents</h2>
                    <p>Your trusted partner in PC building excellence</p>
                </div>
                <div class="decom-category-grid">
                    <div class="decom-info-card">
                        <div class="decom-info-icon">
                            <img src="<?php echo base_url('Pictures/Trade.png'); ?>" alt="Trade Icon">
                        </div>
                        <p>Trade products with ease through our secure platform. Connect with buyers and sellers in a trusted environment for seamless personal and business transactions.</p>
                    </div>
                    <div class="decom-info-card">
                        <div class="decom-info-icon">
                            <img src="<?php echo base_url('Pictures/search.png'); ?>" alt="Search Icon">
                        </div>
                        <p>Explore a diverse range of products across various categories. Our intuitive browsing experience helps you quickly find exactly what you're looking for.</p>
                    </div>
                    <div class="decom-info-card">
                        <div class="decom-info-icon">
                            <img src="<?php echo base_url('Pictures/Popular.png'); ?>" alt="Popular Icon">
                        </div>
                        <p>Discover top brands, both new and well-established. Access a curated selection from the most popular and emerging brands in the market.</p>
                    </div>
                    <div class="decom-info-card">
                        <div class="decom-info-icon">
                            <img src="<?php echo base_url('Pictures/Develop.png'); ?>" alt="Development Icon">
                        </div>
                        <p>Join us in shaping the future of online shopping. We're constantly evolving with your feedback to deliver even more value, convenience, and accessibility.</p>
                    </div>
                </div>
            </section>

            <!-- Product Categories -->
            <section>
                <div class="section-header">
                    <h2>Shop by Category</h2>
                    <p>Browse our complete range of components</p>
                </div>
                <div class="decom-category-grid">
                    <div class="decom-category">
                        <img src="<?php echo base_url('Pictures/Graphics.webp'); ?>" alt="Graphics Card" class="decom-category-image">
                        <h3 class="decom-category-title">Graphics Cards</h3>
                    </div>
                    <div class="decom-category">
                        <img src="<?php echo base_url('Pictures/often.webp'); ?>" alt="CPU" class="decom-category-image">
                        <h3 class="decom-category-title">Processors</h3>
                    </div>
                    <div class="decom-category">
                        <img src="<?php echo base_url('Pictures/powsu.webp'); ?>" alt="Power Supply" class="decom-category-image">
                        <h3 class="decom-category-title">Power Supplies</h3>
                    </div>
                    <div class="decom-category">
                        <img src="<?php echo base_url('Pictures/moboth.webp'); ?>" alt="Mother Board" class="decom-category-image">
                        <h3 class="decom-category-title">Motherboards</h3>
                    </div>
                </div>
            </section>

            <!-- Banner -->
            <div class="decom-banner">
                <img src="<?php echo base_url('Pictures/mewomewo.jpg'); ?>" alt="RTX 4070 Ti Banner">
            </div>

            <!-- Testimonials -->
            <section class="decom-testimonials">
                <h2 class="decom-testimonials-title">What Our Customers Say</h2>
                <div class="decom-testimonials-container">
                    <div class="decom-testimonial-card">
                        <div class="decom-testimonial-icon"></div>
                        <p>As a frequent online shopper, I've come across my fair share of e-commerce websites, but DeComponents stands out in a few ways, both for its simplicity and the variety of products it offers. This is my honest review after spending time browsing and shopping on the platform.</p>
                    </div>
                    <div class="decom-testimonial-card">
                        <div class="decom-testimonial-icon"></div>
                        <p>From the moment I landed on the website, I was impressed by how clean and organized everything was. The homepage features clear categories like Tech, Gadgets, and Accessories that made finding what I was looking for a breeze. The search functionality is particularly helpful, with no clutter and an intuitive interface.</p>
                    </div>
                </div>
            </section>
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
            <a href="https://www.youtube.com/@DeComponents" target="_blank" aria-label="YouTube">
                <img src="<?php echo base_url('Pictures/Yutob.webp'); ?>" alt="YouTube">
            </a>
            <a href="https://www.facebook.com/profile.php?id=61568617385907" target="_blank" aria-label="Facebook">
                <img src="<?php echo base_url('Pictures/Fishbuk.webp'); ?>" alt="Facebook">
            </a>
            <a href="https://www.tiktok.com/@decomponents" target="_blank" aria-label="TikTok">
                <img src="<?php echo base_url('Pictures/Tiktook.png'); ?>" alt="TikTok">
            </a>
            <a href="https://www.linkedin.com/in/de-components-934ba3337/" target="_blank" aria-label="LinkedIn">
                <img src="<?php echo base_url('Pictures/linkedin.png'); ?>" alt="LinkedIn">
            </a>
            <a href="#" target="_blank" aria-label="Instagram">
                <img src="<?php echo base_url('Pictures/Instagram.png'); ?>" alt="Instagram">
            </a>
        </div>
        <div class="decom-contact-email">
            <a href="mailto:decomponents.24@gmail.com">DeComponents.24@Gmail.com</a>
        </div>
        <p class="decom-copyright">© 2024 by DeComponents. All Rights Reserved.</p>
    </footer>
</body>

</html>
