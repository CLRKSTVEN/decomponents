<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Review</title>
    <link rel="icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="shortcut icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="apple-touch-icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>">
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
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .decom-main {
            padding: 48px 0;
            background: linear-gradient(to bottom, #f9fafb 0%, #ffffff 100%);
            min-height: calc(100vh - 200px);
        }

        .decom-container {
            max-width: 900px;
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

        /* Progress Indicator */
        .checkout-progress {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            padding: 0 24px;
        }

        .progress-step {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .progress-step.active {
            color: var(--primary-color);
        }

        .progress-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .progress-step.active .progress-dot {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .progress-arrow {
            color: #d1d5db;
            font-size: 1.2rem;
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

        /* Checkout Card */
        .checkout-card {
            background: #ffffff;
            border: 2px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        .checkout-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color));
        }

        .checkout-items-header {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--text-primary);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--bg-light);
        }

        .checkout-item {
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 20px;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid var(--border-color);
            transition: background 0.2s ease;
        }

        .checkout-item:hover {
            background: var(--bg-light);
            margin: 0 -16px;
            padding-left: 16px;
            padding-right: 16px;
            border-radius: 8px;
        }

        .checkout-item:last-child {
            border-bottom: none;
        }

        .checkout-item-image {
            position: relative;
        }

        .checkout-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid var(--border-color);
            background: #fff;
            transition: transform 0.2s ease;
        }

        .checkout-item:hover img {
            transform: scale(1.05);
            border-color: var(--primary-color);
        }

        .checkout-item-details {
            flex: 1;
        }

        .checkout-item-name {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-primary);
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .checkout-item-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .checkout-item-price {
            font-weight: 600;
            padding: 4px 10px;
            background: var(--bg-light);
            border-radius: 6px;
        }

        .checkout-item-quantity {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 6px;
            font-weight: 600;
        }

        .checkout-item-quantity::before {
            content: '×';
            font-weight: 700;
        }

        .checkout-item-subtotal {
            font-weight: 800;
            font-size: 1.15rem;
            color: var(--primary-color);
            text-align: right;
        }

        /* Summary Section */
        .checkout-summary-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--border-color), transparent);
            margin: 24px 0;
        }

        .checkout-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            padding-top: 8px;
        }

        .checkout-total-section {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .checkout-total-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .checkout-total {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            letter-spacing: -0.025em;
        }

        .checkout-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
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
            white-space: nowrap;
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

            .checkout-progress {
                gap: 8px;
                font-size: 0.8rem;
            }

            .progress-dot {
                width: 28px;
                height: 28px;
                font-size: 0.75rem;
            }

            .progress-step span:not(.progress-dot) {
                display: none;
            }

            .checkout-item {
                grid-template-columns: 80px 1fr;
                gap: 12px;
            }

            .checkout-item img {
                width: 80px;
                height: 80px;
            }

            .checkout-item-subtotal {
                grid-column: 2;
                text-align: left;
                margin-top: 8px;
            }

            .checkout-summary {
                flex-direction: column;
                align-items: stretch;
            }

            .checkout-total-section {
                text-align: center;
            }

            .checkout-actions {
                width: 100%;
                flex-direction: column-reverse;
            }

            .checkout-actions .decom-button {
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
                <h2>Checkout Review</h2>
                <p>Confirm quantities before proceeding to payment</p>
            </div>

            <!-- Progress Indicator -->
            <div class="checkout-progress">
                <div class="progress-step">
                    <span class="progress-dot">✓</span>
                    <span>Cart</span>
                </div>
                <span class="progress-arrow">→</span>
                <div class="progress-step active">
                    <span class="progress-dot">2</span>
                    <span>Review</span>
                </div>
                <span class="progress-arrow">→</span>
                <div class="progress-step">
                    <span class="progress-dot">3</span>
                    <span>Payment</span>
                </div>
            </div>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger" role="alert">
                    <span>⚠️</span>
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($cart)): ?>
                <div class="checkout-card">
                    <div class="checkout-items-header">
                        Order Summary
                    </div>

                    <?php foreach ($cart as $item): ?>
                        <?php
                        $name  = isset($item['product_name']) ? $item['product_name'] : ($item['name'] ?? 'Product');
                        $price = (float)($item['product_price'] ?? $item['price'] ?? 0);
                        $qty   = (int)($item['qty'] ?? $item['quantity'] ?? 1);
                        $image = isset($item['product_image']) ? $item['product_image'] : ($item['image'] ?? '');
                        $image = $image ? base_url(ltrim($image, '/')) : base_url('Pictures/DeComponents.jpeg');
                        $subtotal = $price * $qty;
                        ?>
                        <div class="checkout-item">
                            <div class="checkout-item-image">
                                <img src="<?= $image; ?>" alt="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="checkout-item-details">
                                <div class="checkout-item-name">
                                    <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                                <div class="checkout-item-meta">
                                    <span class="checkout-item-price">₱<?= number_format($price, 2); ?></span>
                                    <span class="checkout-item-quantity"><?= $qty; ?></span>
                                </div>
                            </div>
                            <div class="checkout-item-subtotal">
                                ₱<?= number_format($subtotal, 2); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="checkout-summary-divider"></div>

                    <div class="checkout-summary">
                        <a class="decom-button decom-button--ghost" href="<?= site_url('products'); ?>">
                            ← Back to Products
                        </a>
                        <div class="checkout-actions">
                            <div class="checkout-total-section">
                                <span class="checkout-total-label">Total Amount</span>
                                <div class="checkout-total">₱<?= number_format((float)$total, 2); ?></div>
                            </div>
                            <a class="decom-button" href="<?= site_url('Decomponents/payment_form'); ?>">
                                Proceed to Payment →
                            </a>
                        </div>
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
