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
if (is_array($cart) && count($cart) > 0) {
    foreach ($cart as $item) {
        if (is_array($item) && isset($item['qty'])) {
            $cartCount += (int)$item['qty'];
        } elseif (is_array($item) && isset($item['quantity'])) {
            $cartCount += (int)$item['quantity'];
        }
    }
}
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

        .decom-cart-button span:not(.decom-cart-badge) {
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
            <a href="<?= site_url('Decomponents/cart'); ?>" class="decom-cart-button">
                <span class="decom-cart-icon">🛒</span>
                <span>Cart</span>
                <span class="decom-cart-badge" data-cart-count="<?= (int)$cartCount; ?>"><?= (int)$cartCount; ?></span>
            </a>

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