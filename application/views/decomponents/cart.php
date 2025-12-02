<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') : 'Cart'; ?></title>

    <link rel="icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/design.css'); ?>">

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --bg-light: #f9fafb;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
        }

        .decom-main {
            padding: 48px 0;
            background: linear-gradient(to bottom, #f9fafb 0%, #ffffff 100%);
            min-height: calc(100vh - 200px);
        }

        .decom-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .section-header {
            margin-bottom: 32px;
            text-align: center;
        }

        .section-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: -0.025em;
        }

        .section-header p {
            font-size: 1rem;
            color: var(--text-secondary);
        }

        /* Alert Styles */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.95rem;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        /* Cart Grid */
        .cart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .cart-card {
            border-radius: 16px;
            border: 1px solid var(--border-color);
            padding: 16px;
            background: #ffffff;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .cart-card::before {
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

        .cart-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
            border-color: var(--primary-color);
        }

        .cart-card:hover::before {
            transform: scaleX(1);
        }

        .cart-card-image-wrapper {
            width: 100%;
            aspect-ratio: 4 / 3;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            margin-bottom: 8px;
            position: relative;
        }

        .cart-card-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .cart-card:hover .cart-card-image-wrapper img {
            transform: scale(1.05);
        }

        .cart-card-title {
            font-weight: 700;
            font-size: 1rem;
            line-height: 1.4;
            min-height: 2.6em;
            color: var(--text-primary);
        }

        .cart-card-meta {
            font-size: 0.9rem;
            color: var(--text-secondary);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: var(--bg-light);
            border-radius: 8px;
        }

        .cart-card-price {
            font-weight: 600;
        }

        .cart-card-quantity {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .cart-card-quantity::before {
            content: '×';
            font-weight: 700;
            color: var(--text-secondary);
        }

        .cart-card-subtotal {
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid var(--border-color);
            font-weight: 800;
            font-size: 1.15rem;
            text-align: right;
            color: var(--primary-color);
        }

        .cart-card-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-remove-btn {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 8px 10px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .cart-remove-btn:hover {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Cart Summary */
        .cart-summary {
            border-radius: 16px;
            border: 2px solid var(--border-color);
            padding: 28px 32px;
            background: #ffffff;
            box-shadow: var(--shadow-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
        }

        .cart-summary-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .cart-summary-total {
            text-align: right;
        }

        .cart-summary-total .label {
            font-size: 0.95rem;
            color: var(--text-secondary);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .cart-summary-total .amount {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            display: block;
            margin-bottom: 16px;
            letter-spacing: -0.025em;
        }

        /* Buttons */
        .decom-button {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 2px solid transparent;
            text-align: center;
        }

        .decom-button:not(.decom-button--ghost) {
            background: var(--primary-color);
            color: white;
        }

        .decom-button:not(.decom-button--ghost):hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
        }

        .decom-button--ghost {
            background: white;
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .decom-button--ghost:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: #f0f7ff;
        }

        /* Empty Cart */
        .empty-cart-container {
            text-align: center;
            padding: 64px 24px;
        }

        .empty-cart-icon {
            font-size: 4rem;
            margin-bottom: 24px;
            opacity: 0.5;
        }

        .decom-empty {
            font-size: 1.25rem;
            color: var(--text-secondary);
            margin-bottom: 24px;
        }

        /* Footer Styles */
        .decom-footer {
            margin-top: 64px;
            padding: 32px 24px;
            background: #f9fafb;
            border-top: 1px solid var(--border-color);
        }

        .decom-footer-nav,
        .decom-social-links,
        .decom-contact-email,
        .decom-copyright {
            max-width: 1200px;
            margin: 0 auto;
        }

        .decom-footer-nav {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            gap: 32px;
            flex-wrap: wrap;
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
            margin: 16px auto;
            gap: 16px;
            display: flex;
            justify-content: center;
        }

        .decom-social-links a {
            display: block;
            transition: transform 0.2s ease;
        }

        .decom-social-links a:hover {
            transform: scale(1.1);
        }

        .decom-social-links img {
            display: block;
            width: 32px;
            height: 32px;
            border-radius: 8px;
        }

        .decom-contact-email {
            text-align: center;
            margin-top: 16px;
        }

        .decom-contact-email a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.2s ease;
        }

        .decom-contact-email a:hover {
            color: var(--primary-color);
        }

        .decom-copyright {
            text-align: center;
            margin-top: 16px;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .section-header h2 {
                font-size: 1.5rem;
            }

            .cart-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .cart-summary {
                flex-direction: column;
                padding: 20px;
            }

            .cart-summary-actions {
                width: 100%;
            }

            .cart-summary-actions .decom-button {
                width: 100%;
            }

            .cart-summary-total {
                width: 100%;
                text-align: center;
            }

            .cart-summary-total .decom-button {
                width: 100%;
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

    <main class="decom-main">
        <div class="decom-container">
            <div class="section-header">
                <h2>Your Cart</h2>
                <p>Review items before checkout</p>
            </div>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger" role="alert">
                    <span>⚠️</span>
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success" role="alert">
                    <span>✓</span>
                    <?= $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($cartItems)): ?>
                <?php $total = 0; ?>

                <!-- GRID OF CART ITEMS -->
                <div class="cart-grid">
                    <?php foreach ($cartItems as $index => $item): ?>
                        <?php
                        $name  = isset($item['product_name']) ? $item['product_name'] : ($item['name'] ?? 'Product');
                        $price = (float)($item['product_price'] ?? $item['price'] ?? 0);
                        $qty   = (int)($item['qty'] ?? $item['quantity'] ?? 1);
                        $image = isset($item['product_image']) ? $item['product_image'] : ($item['image'] ?? '');
                        $image = $image ? base_url(ltrim($image, '/')) : base_url('Pictures/DeComponents.jpeg');
                        $subtotal = $price * $qty;
                        $total += $subtotal;
                        ?>
                        <div class="cart-card">
                            <div class="cart-card-image-wrapper">
                                <img src="<?= $image; ?>" alt="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="cart-card-title">
                                <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
                            </div>

                            <div class="cart-card-meta">
                                <span class="cart-card-price">₱<?= number_format($price, 2); ?></span>
                                <span class="cart-card-quantity"><?= $qty; ?></span>
                            </div>

                            <div class="cart-card-subtotal">
                                ₱<?= number_format($subtotal, 2); ?>
                            </div>

                            <div class="cart-card-actions">
                                <a class="cart-remove-btn" href="<?= site_url('Decomponents/remove_cart_item/' . $index); ?>" onclick="return confirm('Remove this item from your cart?');">
                                    <span>🗑</span> Delete
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- SUMMARY + BUTTONS -->
                <div class="cart-summary">
                    <div class="cart-summary-actions">
                        <a class="decom-button decom-button--ghost" href="<?= site_url('products'); ?>">
                            ← Continue Shopping
                        </a>
                        <a class="decom-button decom-button--ghost" href="<?= site_url('Decomponents/track_order'); ?>">
                            Order History
                        </a>
                    </div>

                    <div class="cart-summary-total">
                        <span class="label">Total</span>
                        <span class="amount">₱<?= number_format($total, 2); ?></span>
                        <a class="decom-button" href="<?= site_url('Decomponents/checkout_review'); ?>">
                            Proceed to Checkout →
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-cart-container">
                    <div class="empty-cart-icon">🛒</div>
                    <p class="decom-empty">Your cart is empty</p>
                    <a class="decom-button" href="<?= site_url('products'); ?>">Start Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php if ($this->session->flashdata('cart_cleared') || empty($cartItems)): ?>
        <script>
            (function() {
                try {
                    localStorage.removeItem('cart');
                } catch (e) {}
            })();
        </script>
    <?php endif; ?>

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
            <a href="https://www.youtube.com/@DeComponents" target="_blank">
                <img src="<?= base_url('Pictures/Yutob.webp'); ?>" alt="YouTube">
            </a>
            <a href="https://www.facebook.com/profile.php?id=61568617385907" target="_blank">
                <img src="<?= base_url('Pictures/Fishbuk.webp'); ?>" alt="Facebook">
            </a>
            <a href="https://www.tiktok.com/@decomponents" target="_blank">
                <img src="<?= base_url('Pictures/Tiktook.png'); ?>" alt="TikTok">
            </a>
            <a href="https://www.linkedin.com/in/de-components-934ba3337/" target="_blank">
                <img src="<?= base_url('Pictures/linkedin.png'); ?>" alt="LinkedIn">
            </a>
            <a href="#" target="_blank">
                <img src="<?= base_url('Pictures/Instagram.png'); ?>" alt="Instagram">
            </a>
        </div>

        <div class="decom-contact-email">
            <a href="mailto:decomponents.24@gmail.com">DeComponents.24@Gmail.com</a>
        </div>

        <p class="decom-copyright">
            © 2024 by DeComponents. All Rights Reserved.
        </p>
    </footer>
</body>

</html>
