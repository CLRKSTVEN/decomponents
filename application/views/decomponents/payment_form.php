<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
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
        }

        .decom-main {
            padding: 48px 0;
            background: linear-gradient(to bottom, #f9fafb 0%, #ffffff 100%);
            min-height: calc(100vh - 200px);
        }

        .decom-container {
            max-width: 1000px;
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

        .progress-step.completed {
            color: var(--success-color);
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

        .progress-step.completed .progress-dot {
            background: var(--success-color);
            color: white;
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

        /* Two Column Layout */
        .payment-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 24px;
            align-items: start;
        }

        /* Payment Card */
        .payment-card {
            background: #ffffff;
            border: 2px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            padding: 32px;
            position: relative;
            overflow: hidden;
        }

        .payment-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color));
        }

        .payment-form-header {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text-primary);
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--bg-light);
        }

        /* Order Summary Sidebar */
        .order-summary-card {
            position: sticky;
            top: 24px;
            background: #ffffff;
            border: 2px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            padding: 24px;
            overflow: hidden;
        }

        .order-summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--success-color), var(--primary-color));
        }

        .summary-header {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--text-primary);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--bg-light);
        }

        .summary-item {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
            transition: background 0.2s ease;
        }

        .summary-item:hover {
            background: var(--bg-light);
            margin: 0 -12px;
            padding-left: 12px;
            padding-right: 12px;
            border-radius: 8px;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item-image {
            width: 60px;
            height: 60px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .summary-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .summary-item-details {
            flex: 1;
            min-width: 0;
        }

        .summary-item-name {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--text-primary);
            margin-bottom: 4px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .summary-item-meta {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .summary-item-price {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--text-primary);
            text-align: right;
            white-space: nowrap;
        }

        .summary-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--border-color), transparent);
            margin: 16px 0;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            margin-top: 16px;
        }

        .summary-total-label {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-total-amount {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--primary-color);
            letter-spacing: -0.025em;
        }

        /* Form Styles */
        .decom-grid {
            display: grid;
            gap: 24px;
            margin-bottom: 24px;
        }

        .decom-grid--two {
            grid-template-columns: 1fr 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-group label::after {
            content: '*';
            color: var(--danger-color);
            font-size: 0.85rem;
        }

        .form-control {
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: white;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .form-control:hover {
            border-color: #d1d5db;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        select.form-control {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 40px;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 2px solid var(--bg-light);
        }

        /* Buttons */
        .decom-button {
            display: inline-block;
            padding: 14px 32px;
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

        /* Security Badge */
        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            margin-top: 24px;
            color: #166534;
            font-size: 0.85rem;
            font-weight: 600;
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
        @media (max-width: 992px) {
            .payment-layout {
                grid-template-columns: 1fr;
            }

            .order-summary-card {
                position: relative;
                top: 0;
                order: -1;
            }
        }

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

            .payment-card {
                padding: 24px 20px;
            }

            .order-summary-card {
                padding: 20px;
            }

            .decom-grid--two {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .form-actions .decom-button {
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
                <h2>Payment Details</h2>
                <p>Complete your order details</p>
            </div>

            <!-- Progress Indicator -->
            <div class="checkout-progress">
                <div class="progress-step completed">
                    <span class="progress-dot">✓</span>
                    <span>Cart</span>
                </div>
                <span class="progress-arrow">→</span>
                <div class="progress-step completed">
                    <span class="progress-dot">✓</span>
                    <span>Review</span>
                </div>
                <span class="progress-arrow">→</span>
                <div class="progress-step active">
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

            <div class="payment-layout">
                <!-- Main Payment Form -->
                <div class="payment-card">
                    <div class="payment-form-header">
                        Order Information
                    </div>

                    <form action="<?= site_url('Decomponents/process_payment'); ?>" method="post">
                        <div class="decom-grid decom-grid--two">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input
                                    type="text"
                                    name="full_name"
                                    class="form-control"
                                    required
                                    placeholder="Enter your full name"
                                    value="<?= htmlspecialchars($user->FirstName ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="form-group">
                                <label>Email Address</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    required
                                    placeholder="your.email@example.com"
                                    value="<?= htmlspecialchars($user->email ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>

                        <div class="decom-grid">
                            <div class="form-group">
                                <label>Shipping Address</label>
                                <textarea
                                    name="address"
                                    class="form-control"
                                    rows="3"
                                    required
                                    placeholder="Enter your complete shipping address"><?= htmlspecialchars($user->address ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Payment Method</label>
                                <select name="payment_method" class="form-control" required>
                                    <option value="">Select payment method</option>
                                    <option value="cod">💵 Cash on Delivery</option>
                                    <option value="card">💳 Credit/Debit Card</option>
                                    <option value="gcash">📱 GCash</option>
                                </select>
                            </div>
                        </div>

                        <div class="security-badge">
                            <span>🔒</span>
                            <span>Your information is secure and encrypted</span>
                        </div>

                        <div class="form-actions">
                            <a class="decom-button decom-button--ghost" href="<?= site_url('Decomponents/cart'); ?>">
                                ← Back to Cart
                            </a>
                            <button type="submit" class="decom-button">
                                Place Order →
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Order Summary Sidebar -->
                <?php if (!empty($cartItems)): ?>
                    <div class="order-summary-card">
                        <div class="summary-header">
                            Order Summary
                        </div>

                        <?php $total = 0; ?>
                        <?php foreach ($cartItems as $item): ?>
                            <?php
                            $name  = isset($item['product_name']) ? $item['product_name'] : ($item['name'] ?? 'Product');
                            $price = (float)($item['product_price'] ?? $item['price'] ?? 0);
                            $qty   = (int)($item['qty'] ?? $item['quantity'] ?? 1);
                            $image = isset($item['product_image']) ? $item['product_image'] : ($item['image'] ?? '');
                            $image = $image ? base_url(ltrim($image, '/')) : base_url('Pictures/DeComponents.jpeg');
                            $subtotal = $price * $qty;
                            $total += $subtotal;
                            ?>
                            <div class="summary-item">
                                <div class="summary-item-image">
                                    <img src="<?= $image; ?>" alt="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="summary-item-details">
                                    <div class="summary-item-name" title="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                    <div class="summary-item-meta">
                                        ₱<?= number_format($price, 2); ?> × <?= $qty; ?>
                                    </div>
                                </div>
                                <div class="summary-item-price">
                                    ₱<?= number_format($subtotal, 2); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="summary-divider"></div>

                        <div class="summary-total">
                            <span class="summary-total-label">Total</span>
                            <span class="summary-total-amount">₱<?= number_format($total, 2); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
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
