<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') : 'My Orders'; ?></title>
    <link rel="icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="shortcut icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="apple-touch-icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/design.css'); ?>">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --bg-light: #f9fafb;
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
        }

        .decom-main {
            padding: 48px 0 64px;
            background: linear-gradient(to bottom, #f9fafb 0%, #ffffff 100%);
            min-height: calc(100vh - 200px);
        }

        .decom-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .section-header {
            margin-bottom: 24px;
            text-align: center;
        }

        .section-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .section-header p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .thanks-banner {
            background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%);
            border: 1px solid #dbeafe;
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-lg);
        }

        .orders-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .thanks-banner strong {
            color: #1d4ed8;
            font-size: 1rem;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            border: 1px solid transparent;
        }

        .alert-danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .alert-success {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
        }

        .orders-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .order-card {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 18px;
            background: white;
            box-shadow: var(--shadow-lg);
            display: grid;
            grid-template-columns: 90px 1fr auto;
            gap: 16px;
            align-items: center;
        }

        .order-thumb {
            width: 90px;
            height: 90px;
            border-radius: 12px;
            overflow: hidden;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
        }

        .order-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .order-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .order-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .order-title {
            font-weight: 800;
            color: var(--text-primary);
            font-size: 1.05rem;
        }

        .order-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .order-status.pending { background: #fff7ed; color: #c2410c; }
        .order-status.paid { background: #ecfdf3; color: #15803d; }
        .order-status.fulfilled { background: #e0f2fe; color: #0369a1; }
        .order-status.cancelled { background: #f3f4f6; color: #374151; }

        .order-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-end;
        }

        .order-total {
            font-weight: 800;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            border: 2px dashed var(--border-color);
            border-radius: 16px;
            background: #f9fafb;
        }

        .empty-state h3 {
            font-size: 1.4rem;
            margin-bottom: 8px;
            color: var(--text-primary);
        }

        .empty-state p {
            color: var(--text-secondary);
            margin-bottom: 16px;
        }

        .decom-button {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 2px solid transparent;
            color: white;
            background: var(--primary-color);
        }

        .decom-button:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }

        .decom-button--ghost {
            background: white;
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .decom-button--ghost:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: #eff6ff;
        }

        @media (max-width: 720px) {
            .order-card {
                grid-template-columns: 1fr;
                text-align: left;
            }
            .orders-toolbar {
                flex-direction: column;
                align-items: flex-start;
            }
            .order-actions {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <?php $this->load->view('partials/navbar'); ?>

    <main class="decom-main">
        <div class="decom-container">
            <div class="section-header">
                <h2>My Orders</h2>
                <p>Track your recent purchases and their status.</p>
            </div>

            <div class="orders-toolbar">
                <div class="thanks-banner" style="margin:0; flex:1;">
                    <span>🎉</span>
                    <div>
                        <strong>Thank you for your order!</strong><br>
                        We’re preparing your items. A confirmation email will be sent shortly.
                    </div>
                </div>
                <div style="display:flex; gap:8px; flex-shrink:0; flex-wrap:wrap;">
                    <a href="<?= site_url('Decomponents/track_order?history=1'); ?>" class="decom-button decom-button--ghost">Order History</a>
                    <a href="<?= site_url('Decomponents/clear_cart'); ?>" class="decom-button decom-button--ghost">Clear Cart</a>
                </div>
            </div>

            <?php if (!empty($cartCleared) && empty($showHistory)): ?>
                <div class="alert alert-success">
                    Cart cleared. Your cart is empty. Click “Order History” to view past orders.
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?= $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('cart_cleared')): ?>
                <script>
                    (function() {
                        try {
                            localStorage.removeItem('cart');
                        } catch (e) {}
                    })();
                </script>
            <?php endif; ?>

            <?php if (!empty($orders) && !empty($showHistory)): ?>
                <div class="orders-grid">
                    <?php foreach ($orders as $orderRaw): ?>
                        <?php
                        $order = (array)$orderRaw;
                        $status = strtolower($order['status'] ?? 'pending');
                        $statusLabel = [
                            'pending'   => 'Pending',
                            'paid'      => 'Paid',
                            'fulfilled' => 'Shipped',
                            'cancelled' => 'Cancelled',
                        ][$status] ?? 'Pending';
                        $imgPath = $order['product_image'] ?? '';
                        $img = $imgPath ? base_url(ltrim($imgPath, '/')) : base_url('Pictures/DeComponents.jpeg');
                        $title = !empty($order['product_name']) ? $order['product_name'] : 'Your Order #' . ($order['id'] ?? '');
                        $qty = (int)($order['quantity'] ?? 0);
                        $total = (float)($order['total_amount'] ?? 0);
                        $dateVal = $order['created_at'] ?? '';
                        $formattedDate = $dateVal ? date('M d, Y h:i A', strtotime($dateVal)) : '—';
                        ?>
                        <div class="order-card">
                            <div class="order-thumb">
                                <img src="<?= $img; ?>" alt="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="order-info">
                                <div class="order-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></div>
                                <div class="order-meta">
                                    <span>ID: #<?= (int)($order['id'] ?? 0); ?></span>
                                    <span>Date: <?= htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span>Items: <?= $qty > 0 ? $qty : '—'; ?></span>
                                </div>
                                <div class="order-total">Total: ₱<?= number_format($total, 2); ?></div>
                            </div>
                            <div class="order-actions">
                                <span class="order-status <?= $status; ?>"><?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                                <a href="<?= site_url('Decomponents/contact'); ?>" class="decom-button decom-button--ghost">Need help?</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <h3>No orders yet</h3>
                    <p>When you place an order, you’ll see it listed here.</p>
                    <a href="<?= site_url('products'); ?>" class="decom-button">Shop products</a>
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
