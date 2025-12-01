<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - DeComponents</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/design.css'); ?>">
    <?php
    $isLoggedIn = (bool)$this->session->userdata('ez_user_id') || (bool)$this->session->userdata('userID');
    $loginUrl = site_url('home_page.php');
    $afterLoginUrl = site_url('products') . '?showCart=1';
    $addToCartUrl = site_url('Decomponents/add_to_cart');
    ?>
    <script>
        window.PRODUCT_DATA = <?php echo json_encode(isset($products) ? $products : []); ?>;
        window.DE_IS_LOGGED_IN = <?php echo json_encode($isLoggedIn); ?>;
        window.DE_LOGIN_URL = <?php echo json_encode($loginUrl); ?>;
        window.DE_AFTER_LOGIN_URL = <?php echo json_encode($afterLoginUrl); ?>;
        window.DE_ADD_TO_CART_URL = <?php echo json_encode($addToCartUrl); ?>;
    </script>
</head>

<body>
    <?php $this->load->view('partials/navbar'); ?>

    <main class="decom-main">
        <div class="decom-category-grid decom-category-grid--compact">
            <div class="decom-category" data-category="GPU" role="button" tabindex="0">
                <img src="<?php echo base_url('Pictures/Graphics.webp'); ?>" alt="Graphics Card" class="decom-category-image">
                <h3 class="decom-category-title">Graphics Card</h3>
            </div>
            <div class="decom-category" data-category="CPU" role="button" tabindex="0">
                <img src="<?php echo base_url('Pictures/often.webp'); ?>" alt="CPU" class="decom-category-image">
                <h3 class="decom-category-title">CPU</h3>
            </div>
            <div class="decom-category" data-category="Power Supply" role="button" tabindex="0">
                <img src="<?php echo base_url('Pictures/powsu.webp'); ?>" alt="Power Supply" class="decom-category-image">
                <h3 class="decom-category-title">Power Supply</h3>
            </div>
            <div class="decom-category" data-category="Motherboard" role="button" tabindex="0">
                <img src="<?php echo base_url('Pictures/moboth.webp'); ?>" alt="Motherboard" class="decom-category-image">
                <h3 class="decom-category-title">Motherboard</h3>
            </div>
        </div>

        <div class="container product-toolbar">
            <div class="product-toolbar__left">
                <div class="title">PRODUCT LIST</div>
                <p class="product-subtitle">Filter by category and add components straight to your cart.</p>
            </div>
            <div class="product-toolbar__right">
                <label class="filter-label" for="categoryFilter">Category
                    <select id="categoryFilter" class="filter-select">
                        <option value="all">All categories</option>
                    </select>
                </label>
                <div class="icon-cart" title="View cart">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 15a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0h8m-8 0-1-4m9 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-9-4h10l2-7H3m2 7L3 4m0 0-.792-3H1" />
                    </svg>
                    <span>0</span>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="product-count" id="productCount">
                Showing <?php echo isset($products) ? count($products) : 0; ?> items
            </div>
            <div class="listProduct">
                <?php if (!empty($products)) : ?>
                    <?php foreach ($products as $product) : ?>
                        <div class="item product-card" data-id="<?php echo $product['id']; ?>">
                            <div class="product-image-frame">
                                <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                            <div class="product-info">
                                <p class="product-category"><?php echo htmlspecialchars($product['category']); ?></p>
                                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                            </div>
                            <div class="product-footer">
                                <div class="price">$<?php echo $product['price']; ?></div>
                                <button class="addCart">Add To Cart</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <p class="decom-empty" style="<?php echo empty($products) ? '' : 'display:none;'; ?>">No products found in this category. Add images to the products/ folder to show them here.</p>
        </div>

        <div class="cartTab">
            <h1>Shopping Cart</h1>
            <div class="listCart"></div>
            <div class="btn">
                <button class="close">CLOSE</button>
                <button class="checkOut">Check Out</button>
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
