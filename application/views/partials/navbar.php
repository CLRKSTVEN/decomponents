<?php
$isLoggedIn = (bool)$this->session->userdata('ez_user_id');
$userName   = trim((string)$this->session->userdata('ez_user_name'));
if ($userName === '' && $this->session->userdata('fname')) {
    $userName = trim((string)$this->session->userdata('fname'));
}
if ($userName === '' && $this->session->userdata('email')) {
    $userName = (string)$this->session->userdata('email');
}
$userLevel = strtolower((string)$this->session->userdata('level'));
$avatar    = $this->session->userdata('ez_user_avatar') ?: 'upload/profile/avatar.png';
$cartCount = 0;
$cart      = $this->session->userdata('ez_cart') ?? [];
$cartPreview = [];
$cartPreviewTotal = 0;
$cartPreviewLimit = 4;
if (is_array($cart) && count($cart) > 0) {
    foreach ($cart as $item) {
        if (!is_array($item)) {
            continue;
        }
        $qty = (int)($item['qty'] ?? $item['quantity'] ?? 0);
        $cartCount += $qty;
        if ($qty <= 0) {
            continue;
        }
        $name  = isset($item['product_name']) ? $item['product_name'] : ($item['name'] ?? 'Product');
        $price = (float)($item['product_price'] ?? $item['price'] ?? 0);
        $imagePath = isset($item['product_image']) ? $item['product_image'] : ($item['image'] ?? '');
        $image = $imagePath ? base_url(ltrim($imagePath, '/')) : base_url('Pictures/DeComponents.jpeg');
        $subtotal = $price * $qty;
        $cartPreviewTotal += $subtotal;
        if (count($cartPreview) < $cartPreviewLimit) {
            $cartPreview[] = [
                'name' => $name,
                'price' => $price,
                'qty' => $qty,
                'image' => $image,
                'subtotal' => $subtotal,
            ];
        }
    }
}
$cartHasMore = max(0, is_array($cart) ? count($cart) - count($cartPreview) : 0);
?>

<style>
    /* Enhanced Navigation Styles */
    .decom-header {
        position: sticky;
        top: 0;
        z-index: 100;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid #e5e7eb;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
    }

    .decom-header.scrolled {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .decom-header-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 32px;
        min-height: 72px;
    }

    /* Logo Section */
    .decom-logo-section {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .decom-logo {
        height: 48px;
        width: auto;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .decom-logo:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    /* Navigation */
    .decom-nav {
        display: flex;
        align-items: center;
        gap: 4px;
        flex: 1;
        justify-content: center;
    }

    .decom-nav-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: 4px;
        color: #1f2937;
        font-size: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.025em;
        padding: 10px 16px;
        border-radius: 8px;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .decom-nav-link::after {
        content: '';
        position: absolute;
        bottom: 6px;
        left: 50%;
        transform: translateX(-50%) scaleX(0);
        width: 24px;
        height: 2px;
        background: #2563eb;
        border-radius: 2px;
        transition: transform 0.2s ease;
    }

    .decom-nav-link:hover {
        color: #2563eb;
        background: rgba(37, 99, 235, 0.06);
    }

    .decom-nav-link:hover::after {
        transform: translateX(-50%) scaleX(1);
    }

    /* Dropdown */
    .decom-nav-dropdown {
        position: relative;
    }

    .decom-nav-dropdown>button {
        background: transparent;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }

    .decom-nav-dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%);
        background: #ffffff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        border-radius: 12px;
        min-width: 200px;
        opacity: 0;
        visibility: hidden;
        transform: translateX(-50%) translateY(-8px);
        transition: all 0.2s ease;
        overflow: hidden;
        z-index: 110;
    }

    .decom-nav-dropdown:hover .decom-nav-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    /* Support click/tap to open dropdowns (accessibility for touch devices) */
    .decom-nav-dropdown.open .decom-nav-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    .decom-nav-dropdown-menu a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        color: #1f2937;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .decom-nav-dropdown-menu a:hover {
        background: rgba(37, 99, 235, 0.06);
        border-left-color: #2563eb;
        color: #2563eb;
    }

    /* User Actions */
    .decom-user-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    /* Cart Button */
    .decom-cart-button {
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        border: none;
        cursor: pointer;
    }

    .decom-cart-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    .decom-cart-icon {
        font-size: 1.1rem;
    }

    .decom-cart-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        background: white;
        color: #2563eb;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .decom-cart-dropdown {
        position: relative;
    }

    .decom-cart-dropdown .decom-cart-button {
        min-width: 140px;
        justify-content: center;
    }

    .decom-cart-button .decom-cart-caret {
        font-size: 0.75rem;
        opacity: 0.85;
    }

    .decom-cart-menu {
        position: absolute;
        right: 0;
        top: calc(100% + 10px);
        width: 340px;
        max-width: calc(100vw - 32px);
        max-height: 460px;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 14px 34px rgba(0, 0, 0, 0.14);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-6px);
        transition: all 0.2s ease;
        z-index: 120;
        display: flex;
        flex-direction: column;
    }

    .decom-cart-dropdown:hover .decom-cart-menu,
    .decom-cart-dropdown.open .decom-cart-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .cart-menu-header {
        padding: 14px 16px 10px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .cart-menu-title {
        font-weight: 800;
        color: #1f2937;
        font-size: 1rem;
    }

    .cart-menu-subtitle {
        font-size: 0.82rem;
        color: #6b7280;
        margin-top: 2px;
    }

    .cart-menu-count {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
        border-radius: 999px;
        padding: 6px 10px;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .cart-menu-list {
        max-height: 280px;
        overflow-y: auto;
        padding: 8px 10px;
    }

    .cart-menu-item {
        display: grid;
        grid-template-columns: 56px 1fr auto;
        gap: 10px;
        padding: 8px;
        border-radius: 10px;
        transition: background 0.15s ease;
    }

    .cart-menu-item:hover {
        background: #f9fafb;
    }

    .cart-menu-thumb {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    .cart-menu-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .cart-menu-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }

    .cart-menu-name {
        font-weight: 700;
        font-size: 0.92rem;
        color: #111827;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .cart-menu-meta {
        font-size: 0.82rem;
        color: #6b7280;
    }

    .cart-menu-price {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        gap: 4px;
        min-width: 90px;
        text-align: right;
    }

    .cart-menu-price strong {
        color: #2563eb;
        font-size: 0.95rem;
    }

    .cart-menu-more {
        padding: 8px 12px;
        color: #6b7280;
        font-size: 0.85rem;
        border-top: 1px dashed #e5e7eb;
        margin: 4px 10px 0;
    }

    .cart-menu-empty {
        padding: 20px;
        text-align: center;
        color: #6b7280;
    }

    .cart-menu-empty strong {
        display: block;
        color: #111827;
        margin-bottom: 6px;
    }

    .cart-menu-total {
        padding: 12px 16px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
        color: #111827;
    }

    .cart-menu-total span:last-child {
        color: #2563eb;
        font-size: 1.05rem;
    }

    .cart-menu-actions {
        display: flex;
        gap: 10px;
        padding: 14px 16px 16px;
    }

    .cart-menu-actions a {
        flex: 1;
        text-align: center;
        padding: 10px 12px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        border: 1px solid #e5e7eb;
        color: #1f2937;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .cart-menu-actions a:last-child {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.18);
    }

    .cart-menu-actions a:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 14px rgba(0, 0, 0, 0.08);
    }

    /* Avatar Dropdown */
    .decom-avatar-dropdown {
        position: relative;
    }

    .decom-avatar-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 4px 12px 4px 4px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .decom-avatar-btn:hover {
        border-color: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    }

    .decom-avatar-btn img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .decom-avatar-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1f2937;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .decom-avatar-chevron {
        font-size: 0.75rem;
        color: #6b7280;
        transition: transform 0.2s ease;
    }

    .decom-avatar-dropdown:hover .decom-avatar-chevron {
        transform: rotate(180deg);
    }

    .decom-avatar-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        border-radius: 12px;
        min-width: 220px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px);
        transition: all 0.2s ease;
        overflow: hidden;
        z-index: 110;
    }

    .decom-avatar-dropdown:hover .decom-avatar-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .decom-avatar-menu-header {
        padding: 16px;
        background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%);
        border-bottom: 1px solid #e5e7eb;
    }

    .decom-avatar-menu-name {
        font-weight: 700;
        font-size: 0.95rem;
        color: #1f2937;
        margin-bottom: 2px;
    }

    .decom-avatar-menu-role {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .decom-avatar-menu a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        color: #1f2937;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .decom-avatar-menu a:hover {
        background: rgba(37, 99, 235, 0.06);
        border-left-color: #2563eb;
        color: #2563eb;
    }

    .decom-avatar-menu a.logout-link {
        border-top: 1px solid #e5e7eb;
        color: #ef4444;
    }

    .decom-avatar-menu a.logout-link:hover {
        background: rgba(239, 68, 68, 0.06);
        border-left-color: #ef4444;
    }

    /* Login Button */
    .decom-login-button {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        background: white;
        color: #2563eb;
        border: 2px solid #2563eb;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .decom-login-button:hover {
        background: #2563eb;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
    }

    /* Mobile Menu Toggle */
    .decom-mobile-toggle {
        display: none;
        flex-direction: column;
        gap: 5px;
        padding: 8px;
        background: transparent;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .decom-mobile-toggle:hover {
        border-color: #2563eb;
    }

    .decom-mobile-toggle span {
        width: 24px;
        height: 2px;
        background: #1f2937;
        border-radius: 2px;
        transition: all 0.2s ease;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .decom-nav {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            flex-direction: column;
            gap: 0;
            background: white;
            border-top: 1px solid #e5e7eb;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            padding: 16px 0;
        }

        .decom-nav.mobile-active {
            display: flex;
        }

        .decom-nav-link {
            width: 100%;
            justify-content: flex-start;
            border-radius: 0;
            padding: 14px 24px;
        }

        .decom-nav-link::after {
            display: none;
        }

        .decom-nav-dropdown-menu {
            position: static;
            transform: none;
            box-shadow: none;
            border: none;
            border-radius: 0;
            background: #f9fafb;
            margin-top: 4px;
        }

        .decom-nav-dropdown:hover .decom-nav-dropdown-menu {
            transform: none;
        }

        .decom-mobile-toggle {
            display: flex;
        }

        .decom-header-container {
            gap: 16px;
        }

        .decom-avatar-name {
            display: none;
        }
    }

    @media (max-width: 640px) {
        .decom-header-container {
            padding: 0 16px;
            min-height: 64px;
        }

        .decom-logo {
            height: 40px;
        }

        .decom-cart-button .decom-cart-label,
        .decom-cart-button .decom-cart-caret {
            display: none;
        }

        .decom-avatar-btn {
            padding: 4px;
        }
    }
</style>

<header class="decom-header">
    <div class="decom-header-container">
        <!-- Logo Section -->
        <div class="decom-logo-section">
            <a href="<?php echo site_url('home'); ?>">
                <img src="<?php echo base_url('Pictures/DeComponents.jpeg'); ?>" alt="DeComponents Logo" class="decom-logo">
            </a>
        </div>

        <!-- Navigation -->
        <nav class="decom-nav" id="mainNav">
            <a href="<?php echo site_url('home'); ?>" class="decom-nav-link">
                HOME
            </a>

            <div class="decom-nav-dropdown">
                <button class="decom-nav-link">
                    PRODUCTS
                    <span>▾</span>
                </button>
                <div class="decom-nav-dropdown-menu">
                    <a href="<?= site_url('products'); ?>">🛍️ All Products</a>
                    <a href="<?= site_url('Decomponents/shop/gpu'); ?>">🎮 GPU</a>
                    <a href="<?= site_url('Decomponents/shop/cpu'); ?>">⚡ CPU</a>
                    <a href="<?= site_url('Decomponents/shop/power-supply'); ?>">🔌 Power Supply</a>
                </div>
            </div>

            <a href="<?php echo site_url('about'); ?>" class="decom-nav-link">
                ABOUT US
            </a>

            <a href="<?php echo site_url('tradables'); ?>" class="decom-nav-link">
                TRADABLES
            </a>

            <a href="<?php echo site_url('news'); ?>" class="decom-nav-link">
                NEWS
            </a>

            <a href="<?php echo site_url('contact'); ?>" class="decom-nav-link">
                CONTACT
            </a>
        </nav>

        <!-- User Actions -->
        <div class="decom-user-actions">
            <!-- Cart Button -->
            <div class="decom-cart-dropdown">
                <button type="button" class="decom-cart-button decom-cart-toggle">
                    <span class="decom-cart-icon">🛒</span>
                    <span class="decom-cart-label">Cart</span>
                    <span class="decom-cart-badge" data-cart-count="<?= (int)$cartCount; ?>"><?= (int)$cartCount; ?></span>
                    <span class="decom-cart-caret">▾</span>
                </button>
                <div class="decom-cart-menu">
                    <div class="cart-menu-header">
                        <div>
                            <div class="cart-menu-title">My Orders</div>
                            <div class="cart-menu-subtitle">Live cart updates</div>
                        </div>
                        <span class="cart-menu-count"><?= (int)$cartCount; ?> item<?= (int)$cartCount === 1 ? '' : 's'; ?></span>
                    </div>

                    <?php if (!empty($cartPreview)): ?>
                        <div class="cart-menu-list">
                            <?php foreach ($cartPreview as $preview): ?>
                                <div class="cart-menu-item">
                                    <div class="cart-menu-thumb">
                                        <img src="<?= $preview['image']; ?>" alt="<?= htmlspecialchars($preview['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="cart-menu-details">
                                        <div class="cart-menu-name" title="<?= htmlspecialchars($preview['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <?= htmlspecialchars($preview['name'], ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                        <div class="cart-menu-meta">
                                            ₱<?= number_format($preview['price'], 2); ?> × <?= (int)$preview['qty']; ?>
                                        </div>
                                    </div>
                                    <div class="cart-menu-price">
                                        <span class="cart-menu-meta">Subtotal</span>
                                        <strong>₱<?= number_format($preview['subtotal'], 2); ?></strong>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <?php if ($cartHasMore > 0): ?>
                                <div class="cart-menu-more">
                                    +<?= $cartHasMore; ?> more item<?= $cartHasMore === 1 ? '' : 's'; ?> in cart
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="cart-menu-total">
                            <span>Total</span>
                            <span>₱<?= number_format($cartPreviewTotal, 2); ?></span>
                        </div>
                    <?php else: ?>
                        <div class="cart-menu-empty">
                            <strong>Your cart is empty</strong>
                            Add products to see them here.
                        </div>
                    <?php endif; ?>

                    <div class="cart-menu-actions">
                        <a href="<?= site_url('Decomponents/cart'); ?>">View cart</a>
                        <a href="<?= site_url('Decomponents/checkout_review'); ?>">Checkout</a>
                    </div>
                </div>
            </div>

            <!-- User Menu -->
            <?php if ($isLoggedIn): ?>
                <div class="decom-avatar-dropdown">
                    <button class="decom-avatar-btn">
                        <img src="<?= base_url($avatar); ?>" alt="User avatar">
                        <span class="decom-avatar-name"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="decom-avatar-chevron">▾</span>
                    </button>
                    <div class="decom-avatar-menu">
                        <div class="decom-avatar-menu-header">
                            <div class="decom-avatar-menu-name"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></div>
                            <div class="decom-avatar-menu-role"><?= htmlspecialchars($userLevel, ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                        <?php if ($userLevel === 'admin'): ?>
                            <a href="<?= site_url('Decomponents/admin'); ?>">
                                📊 Dashboard
                            </a>
                        <?php endif; ?>
                        <a href="<?= site_url('Decomponents/profile'); ?>">
                            👤 Profile
                        </a>
                        <a href="<?= site_url('Decomponents/track_order'); ?>">
                            📦 My Orders
                        </a>
                        <a href="<?= site_url('login/logout'); ?>" class="logout-link">
                            🚪 Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= site_url('home_page'); ?>" class="decom-login-button">
                    <span>🔐</span>
                    Login
                </a>
            <?php endif; ?>

            <!-- Mobile Menu Toggle -->
            <button class="decom-mobile-toggle" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</header>

<script>
    // Mobile menu toggle
    function toggleMobileMenu() {
        const nav = document.getElementById('mainNav');
        nav.classList.toggle('mobile-active');
    }

    // Add scroll effect to header
    let lastScroll = 0;
    const header = document.querySelector('.decom-header');

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;

        if (currentScroll > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        lastScroll = currentScroll;
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        const nav = document.getElementById('mainNav');
        const toggle = document.querySelector('.decom-mobile-toggle');

        if (nav.classList.contains('mobile-active') &&
            !nav.contains(e.target) &&
            !toggle.contains(e.target)) {
            nav.classList.remove('mobile-active');
        }
    });

    // Dropdown click / tap support (accessibility for touch devices)
    (function() {
        const dropdowns = document.querySelectorAll('.decom-nav-dropdown');

        // Toggle open class on button click
        dropdowns.forEach(drop => {
            const btn = drop.querySelector('button');
            if (!btn) return;
            btn.addEventListener('click', function(ev) {
                ev.preventDefault();
                // Close other open dropdowns
                dropdowns.forEach(d => d.classList.remove('open'));
                drop.classList.toggle('open');
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(ev) {
            const inside = ev.target.closest && ev.target.closest('.decom-nav-dropdown');
            if (!inside) {
                dropdowns.forEach(d => d.classList.remove('open'));
            }
        });

        // Close on ESC
        document.addEventListener('keydown', function(ev) {
            if (ev.key === 'Escape' || ev.key === 'Esc') {
                dropdowns.forEach(d => d.classList.remove('open'));
            }
        });
    })();

    // Cart dropdown toggle (for tap/click)
    (function() {
        const cartDropdown = document.querySelector('.decom-cart-dropdown');
        if (!cartDropdown) return;
        const cartToggle = cartDropdown.querySelector('.decom-cart-toggle');
        if (!cartToggle) return;
        cartToggle.addEventListener('click', function(ev) {
            ev.preventDefault();
            cartDropdown.classList.toggle('open');
        });

        document.addEventListener('click', function(ev) {
            if (!cartDropdown.contains(ev.target)) {
                cartDropdown.classList.remove('open');
            }
        });

        document.addEventListener('keydown', function(ev) {
            if (ev.key === 'Escape' || ev.key === 'Esc') {
                cartDropdown.classList.remove('open');
            }
        });
    })();

    // Sync cart badge with localStorage and server count
    (function() {
        var badge = document.querySelector('.decom-cart-badge');
        var iconSpan = document.querySelector('.icon-cart span');
        if (!badge) return;
        var serverCount = parseInt(badge.getAttribute('data-cart-count') || '0', 10) || 0;
        var count = serverCount;
        try {
            var storedCart = JSON.parse(localStorage.getItem('cart') || '[]');
            if (Array.isArray(storedCart)) {
                var storedCount = storedCart.reduce(function(sum, item) {
                    return sum + (parseInt(item.quantity || item.qty || 0, 10) || 0);
                }, 0);
                // If server is empty but local has stale items, clear local.
                if (serverCount === 0 && storedCount > 0) {
                    localStorage.removeItem('cart');
                    count = 0;
                } else if (serverCount > 0) {
                    // Prefer server count when available to avoid inflated badge.
                    count = serverCount;
                } else {
                    count = storedCount;
                }
            }
        } catch (e) {}
        badge.innerText = count;
        if (iconSpan) {
            iconSpan.innerText = count;
        }
    })();
</script>
