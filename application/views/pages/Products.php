<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - DeComponents</title>
    <link rel="icon" href="<?php echo base_url('Pictures/Decomponents.jpeg'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/design.css'); ?>">

    <?php
    $isLoggedIn     = (bool)$this->session->userdata('ez_user_id') || (bool)$this->session->userdata('userID');
    $loginUrl       = site_url('home_page.php');
    $afterLoginUrl  = site_url('products') . '?showCart=1';
    $addToCartUrl   = site_url('Decomponents/add_to_cart');
    $sessionCart    = $this->session->userdata('ez_cart') ?? [];
    $sessionCartCount = 0;
    foreach ($sessionCart as $ci) {
        $sessionCartCount += (int)($ci['qty'] ?? $ci['quantity'] ?? 1);
    }
    ?>

    <script>
        window.PRODUCT_DATA = <?php echo json_encode(isset($products) ? $products : []); ?>;
        window.DE_IS_LOGGED_IN = <?php echo json_encode($isLoggedIn); ?>;
        window.DE_LOGIN_URL = <?php echo json_encode($loginUrl); ?>;
        window.DE_AFTER_LOGIN_URL = <?php echo json_encode($afterLoginUrl); ?>;
        window.DE_ADD_TO_CART_URL = <?php echo json_encode($addToCartUrl); ?>;
        window.DE_SERVER_CART_COUNT = <?php echo (int)$sessionCartCount; ?>;
    </script>

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

        /* Section headers */
        .section-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .section-header h2 {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: -0.025em;
        }

        .section-header p {
            font-size: 1rem;
            color: var(--text-secondary);
        }

        /* Category grid */
        .decom-category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .decom-category {
            background: #ffffff;
            border: 2px solid var(--border-color);
            border-radius: 20px;
            padding: 20px;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            cursor: pointer;
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
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .decom-category:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .decom-category:hover::before {
            transform: scaleX(1);
        }

        .decom-category-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 16px;
            margin-bottom: 16px;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
        }

        .decom-category-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        /* Toolbar */
        .product-toolbar {
            margin-bottom: 24px;
            margin-top: 8px;
            background: #ffffff;
            border-radius: 18px;
            padding: 18px 20px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .product-toolbar__left .title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        .product-subtitle {
            margin: 4px 0 0;
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .product-toolbar__right {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .filter-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .filter-select {
            min-width: 180px;
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            font-size: 0.9rem;
            color: var(--text-primary);
            background: #ffffff;
            outline: none;
        }

        .filter-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.25);
        }

        .decom-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            background: var(--primary-color);
            color: #ffffff;
            border-radius: 999px;
            border: none;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        }

        .decom-button:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.35);
        }

        .decom-button--ghost {
            background: #ffffff;
            color: var(--primary-color);
            border: 1px solid var(--border-color);
            box-shadow: none;
        }

        .decom-button--ghost:hover {
            background: #eff6ff;
            border-color: var(--primary-color);
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15);
        }

        .icon-cart {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            border: 1px solid var(--border-color);
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .icon-cart:hover {
            border-color: var(--primary-color);
            background: #eff6ff;
            box-shadow: var(--shadow-md);
        }

        .icon-cart svg {
            width: 20px;
            height: 20px;
            color: var(--text-primary);
        }

        .icon-cart span {
            position: absolute;
            top: -6px;
            right: -6px;
            min-width: 20px;
            height: 20px;
            padding: 0 4px;
            border-radius: 999px;
            background: #ef4444;
            color: #ffffff;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            box-shadow: 0 0 0 2px #ffffff;
        }

        /* Product list */
        .product-count {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 16px;
        }

        .listProduct {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 24px;
        }

        .product-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            padding: 16px 16px 18px;
            display: flex;
            flex-direction: column;
            transition: all 0.2s ease;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .product-image-frame {
            width: 100%;
            height: 190px;
            border-radius: 16px;
            overflow: hidden;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            margin-bottom: 12px;
        }

        .product-image-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-bottom: 12px;
        }

        .product-category {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--primary-color);
            margin-bottom: 4px;
        }

        .product-info h2 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 6px;
        }

        .product-description {
            color: var(--text-secondary);
            font-size: 0.85rem;
            line-height: 1.6;
            margin: 0;
        }

        .product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: 8px;
        }

        .price {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--primary-color);
        }

        .addCart {
            border: none;
            border-radius: 999px;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            background: var(--primary-color);
            color: #ffffff;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .addCart:hover {
            background: var(--primary-hover);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
            transform: translateY(-1px);
        }

        .decom-empty {
            margin-top: 24px;
            font-size: 0.95rem;
            color: var(--text-secondary);
            text-align: center;
        }

        /* Modals */
        .de-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(3px);
        }

        .de-modal {
            background: #ffffff;
            padding: 20px 20px 16px;
            border-radius: 18px;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.35);
            font-family: inherit;
            border: 1px solid var(--border-color);
        }

        .de-modal h3 {
            margin: 0 0 8px;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .de-modal p {
            margin: 0 0 12px;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .de-modal .de-modal-actions {
            text-align: right;
            margin-top: 6px;
        }

        .de-btn {
            display: inline-block;
            padding: 7px 14px;
            border-radius: 999px;
            border: 1px solid var(--border-color);
            background: #f9fafb;
            color: var(--text-primary);
            cursor: pointer;
            margin-left: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .de-btn:hover {
            background: #e5e7eb;
        }

        .de-btn.primary {
            background: var(--primary-color);
            color: #ffffff;
            border-color: var(--primary-color);
        }

        .de-btn.primary:hover {
            background: var(--primary-hover);
        }

        #checkoutItemsList {
            font-size: 0.85rem;
        }

        #checkoutItemsList .checkout-item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 6px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        #checkoutItemsList .checkout-item-name {
            flex: 1;
            font-weight: 500;
            color: var(--text-primary);
        }

        #checkoutItemsList input[type="number"] {
            width: 70px;
            padding: 4px 6px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 0.85rem;
        }

        /* Footer overrides to ensure consistency with home page */
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

        /* Responsive */
        @media (max-width: 768px) {
            .decom-container {
                padding: 0 16px;
            }

            .product-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .product-toolbar__left {
                width: 100%;
            }

            .product-toolbar__right {
                width: 100%;
                justify-content: space-between;
            }

            .filter-select {
                min-width: 0;
                width: 100%;
            }

            .listProduct {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php $this->load->view('partials/navbar'); ?>

    <main class="decom-main">
        <div class="decom-container">
            <!-- Page header -->
            <div class="section-header" style="margin-bottom: 24px;">
                <h2>All Products</h2>
                <p>Browse PC components and add them straight to your cart.</p>
            </div>

            <!-- Category quick filter -->
            <section>
                <div class="decom-category-grid decom-category-grid--compact">
                    <div class="decom-category" data-category="GPU" role="button" tabindex="0">
                        <img src="<?php echo base_url('Pictures/Graphics.webp'); ?>" alt="Graphics Card" class="decom-category-image">
                        <h3 class="decom-category-title">Graphics Cards</h3>
                        <p class="product-description">High-performance GPUs for gaming, content creation, and rendering.</p>
                    </div>
                    <div class="decom-category" data-category="CPU" role="button" tabindex="0">
                        <img src="<?php echo base_url('Pictures/often.webp'); ?>" alt="CPU" class="decom-category-image">
                        <h3 class="decom-category-title">Processors</h3>
                        <p class="product-description">Multi-core CPUs built for speed, efficiency, and multitasking.</p>
                    </div>
                    <div class="decom-category" data-category="Power Supply" role="button" tabindex="0">
                        <img src="<?php echo base_url('Pictures/powsu.webp'); ?>" alt="Power Supply" class="decom-category-image">
                        <h3 class="decom-category-title">Power Supplies</h3>
                        <p class="product-description">Reliable PSUs to keep your system stable and protected.</p>
                    </div>
                    <div class="decom-category" data-category="Motherboard" role="button" tabindex="0">
                        <img src="<?php echo base_url('Pictures/moboth.webp'); ?>" alt="Motherboard" class="decom-category-image">
                        <h3 class="decom-category-title">Motherboards</h3>
                        <p class="product-description">The foundation of your build, ready for upgrades and expansions.</p>
                    </div>
                </div>
            </section>

            <!-- Toolbar -->
            <div class="product-toolbar">
                <div class="product-toolbar__left">
                    <div class="title">Product List</div>
                    <p class="product-subtitle">Filter by category, then add selected components to your cart.</p>
                </div>
                <div class="product-toolbar__right">
                    <label class="filter-label" for="categoryFilter">
                        Category
                        <select id="categoryFilter" class="filter-select">
                            <option value="all">All categories</option>
                        </select>
                    </label>

                </div>
            </div>

            <!-- Products -->
            <div class="product-count" id="productCount" style="display: none;">
                Showing 0 items
            </div>

            <div class="listProduct">
                <?php if (!empty($products)) : ?>
                    <?php foreach ($products as $product) : ?>
                        <div class="item product-card" data-id="<?php echo $product['id']; ?>">
                            <div class="product-image-frame">
                                <img src="<?php echo $product['image']; ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                            <div class="product-info">
                                <p class="product-category">
                                    <?php echo htmlspecialchars($product['category']); ?>
                                </p>
                                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                                <p class="product-description">
                                    <?php echo htmlspecialchars($product['description']); ?>
                                </p>
                            </div>
                            <div class="product-footer">
                                <div class="price">
                                    $<?php echo $product['price']; ?>
                                </div>
                                <button class="addCart">Add To Cart</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <p class="decom-empty" style="<?php echo empty($products) ? '' : 'display:none;'; ?>">
                No products found in this category. Add images to the products folder to show them here.
            </p>
        </div>

        <!-- Login required modal -->
        <div id="loginModal" class="de-modal-backdrop" aria-hidden="true">
            <div class="de-modal" role="dialog" aria-modal="true" aria-labelledby="loginModalTitle">
                <h3 id="loginModalTitle">Login required</h3>
                <p>You need to login first to continue to checkout.</p>
                <div class="de-modal-actions">
                    <button class="de-btn loginClose">Close</button>
                    <button class="de-btn primary loginNow">Login</button>
                </div>
            </div>
        </div>

        <!-- Add-to-cart confirmation modal -->
        <div id="addCartModal" class="de-modal-backdrop" aria-hidden="true">
            <div class="de-modal" role="dialog" aria-modal="true" aria-labelledby="addCartModalTitle">
                <h3 id="addCartModalTitle">Added to cart</h3>
                <p id="addCartModalMsg">Product added to your cart.</p>
                <div class="de-modal-actions">
                    <button class="de-btn continueShopping">Continue</button>
                    <button class="de-btn primary viewCart">View Cart</button>
                </div>
            </div>
        </div>

        <!-- Checkout quantity selection modal -->
        <div id="checkoutQtyModal" class="de-modal-backdrop" aria-hidden="true">
            <div class="de-modal" role="dialog" aria-modal="true" aria-labelledby="checkoutQtyTitle">
                <h3 id="checkoutQtyTitle">Select quantities</h3>
                <p id="checkoutQtyHelp">Adjust quantities for the items in your cart before checkout.</p>
                <div id="checkoutItemsList" style="max-height:260px;overflow:auto;margin-bottom:12px"></div>
                <div class="de-modal-actions">
                    <button class="de-btn checkoutQtyClose">Close</button>
                    <button class="de-btn primary checkoutQtyProceed">Proceed to Payment</button>
                </div>
            </div>
        </div>

        <script src="<?php echo base_url('app.js'); ?>"></script>
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
            <a href="https://www.youtube.com/@DeComponents" target="_blank">
                <img src="<?php echo base_url('Pictures/Yutob.webp'); ?>" alt="YouTube">
            </a>
            <a href="https://www.facebook.com/profile.php?id=61568617385907" target="_blank">
                <img src="<?php echo base_url('Pictures/Fishbuk.webp'); ?>" alt="Facebook">
            </a>
            <a href="https://www.tiktok.com/@decomponents" target="_blank">
                <img src="<?php echo base_url('Pictures/Tiktook.png'); ?>" alt="TikTok">
            </a>
            <a href="https://www.linkedin.com/in/de-components-934ba3337/" target="_blank">
                <img src="<?php echo base_url('Pictures/linkedin.png'); ?>" alt="LinkedIn">
            </a>
            <a href="#" target="_blank">
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
