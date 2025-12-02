<?php
$cartCount = isset($cartCount) ? (int)$cartCount : 0;
$avatarImg = !empty($user->profile_picture)
    ? base_url($user->profile_picture)
    : base_url('upload/profile/avatar.png');
$activeCat = $category ?? 'women';
?>

<style>
    /* Header styles */
    .ezshop-header {
        position: sticky;
        top: 0;
        z-index: 1020;
        background: linear-gradient(-45deg, #667eea 0%, #764ba2 50%, #667eea 100%);
        background-size: 200% 200%;
        animation: headerGradient 10s ease infinite;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        padding: 18px 28px;
        backdrop-filter: blur(20px);
    }

    @keyframes headerGradient {

        0%,
        100% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }
    }

    .header-row {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: nowrap;
        min-height: 64px;
    }

    .ezshop-brand-wrapper {
        display: flex;
        align-items: center;
        color: white;
    }

    .ezshop-brand-wrapper img {
        height: 42px;
        width: auto;
        filter: none;
        /* Subtle shadow so light logos stay visible on the gradient */
        -webkit-filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.35));
        filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.35));
    }

    .ezshop-brand {
        font-weight: 800;
        font-size: 22px;
        letter-spacing: 0.5px;
        color: white;
        margin-left: 12px;
    }

    .header-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: nowrap;
        flex-grow: 1;
    }

    .ezshop-dropdown .dropdown-toggle {
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #fff;
        font-weight: 700;
        border-radius: 14px;
        padding: 10px 16px;
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .ezshop-dropdown .dropdown-toggle:hover,
    .ezshop-dropdown .dropdown-toggle:focus {
        background: #fff;
        color: #5b67e9;
    }

    .ezshop-dropdown .dropdown-menu {
        min-width: 230px;
        border-radius: 12px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.18);
        border: 0;
        padding: 10px;
        z-index: 1060;
    }

    .ezshop-dropdown .dropdown-item {
        font-weight: 600;
        border-radius: 10px;
        padding: 10px 12px;
    }

    .ezshop-dropdown .dropdown-item.active,
    .ezshop-dropdown .dropdown-item:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
    }

    .ezshop-pill {
        display: inline-flex;
        align-items: center;
        padding: 10px 16px;
        min-height: 42px;
        border-radius: 50px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        font-size: 13px;
        font-weight: 600;
        color: white;
        margin: 0;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .ezshop-pill i {
        margin-right: 8px;
        font-size: 16px;
    }

    .ezshop-pill a {
        color: white;
        text-decoration: none;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
    }

    .cart-badge {
        background: white;
        color: #667eea;
        padding: 2px 10px;
        border-radius: 12px;
        font-weight: 700;
        margin-left: 6px;
    }

    .header-right {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: nowrap;
    }

    .avatar-btn img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.7);
        cursor: pointer;
    }

    .header-user {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #fff;
        font-weight: 700;
    }

    .avatar-name {
        color: #fff;
        font-weight: 700;
        text-shadow: 0 1px 6px rgba(0, 0, 0, 0.25);
        font-size: 14px;
        white-space: nowrap;
    }
</style>
<style>
    /* Make sure Bootstrap Icons use the correct font */
    .bi {
        font-family: "bootstrap-icons" !important;
        font-style: normal;
        font-weight: normal;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
    }
</style>

<header class="ezshop-header">
    <div class="header-row">
        <div class="ezshop-brand-wrapper">
            <img src="<?= base_url('Products/Logo1.png'); ?>" alt="EZ-Shop" onerror="this.style.display='none';">
            <span class="ezshop-brand">EZ-Shop</span>
        </div>

        <div class="header-controls">
            <div class="ezshop-pill">
                <i class="bi bi-house-door"></i>
                <a href="<?= site_url('Ezshop/shop'); ?>">Home</a>
            </div>
            <div class="dropdown ezshop-dropdown">
                <button class="btn dropdown-toggle" type="button" id="categoryDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Categories
                </button>
                <div class="dropdown-menu" aria-labelledby="categoryDropdown">
                    <a class="dropdown-item <?= $activeCat === 'women' ? 'active' : ''; ?>" href="<?= site_url('Ezshop/shop/women'); ?>">
                        <i class="bi bi-emoji-smile"></i> Womenswear
                    </a>
                    <a class="dropdown-item <?= $activeCat === 'men' ? 'active' : ''; ?>" href="<?= site_url('Ezshop/shop/men'); ?>">
                        <i class="bi bi-person"></i> Menswear
                    </a>
                    <a class="dropdown-item <?= $activeCat === 'shoes' ? 'active' : ''; ?>" href="<?= site_url('Ezshop/shop/shoes'); ?>">
                        <i class="bi bi-basket2"></i> Shoes
                    </a>
                    <a class="dropdown-item <?= $activeCat === 'accessories' ? 'active' : ''; ?>" href="<?= site_url('Ezshop/shop/accessories'); ?>">
                        <i class="bi bi-watch"></i> Watches &amp; Accessories
                    </a>
                    <a class="dropdown-item <?= $activeCat === 'bottoms' ? 'active' : ''; ?>" href="<?= site_url('Ezshop/shop/bottoms'); ?>">
                        <i class="bi bi-collection"></i> Bottoms
                    </a>
                    <a class="dropdown-item <?= $activeCat === 'others' ? 'active' : ''; ?>" href="<?= site_url('Ezshop/shop/others'); ?>">
                        <i class="bi bi-collection-play"></i> Tops &amp; Tees
                    </a>
                </div>
            </div>
            <div class="dropdown ezshop-dropdown">
                <button class="btn dropdown-toggle" type="button" id="infoDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Info
                </button>
                <div class="dropdown-menu" aria-labelledby="infoDropdown">
                    <a class="dropdown-item" href="<?= site_url('Ezshop/about'); ?>">
                        <i class="bi bi-info-circle"></i> About Us
                    </a>
                    <a class="dropdown-item" href="<?= site_url('Ezshop/contact'); ?>">
                        <i class="bi bi-envelope"></i> Contact Us
                    </a>
                    <a class="dropdown-item requires-login" href="<?= site_url('Ezshop/track_order'); ?>">
                        <i class="bi bi-truck"></i> My Orders
                    </a>
                </div>
            </div>
            <div class="ezshop-pill">
                <i class="bi bi-bag-check"></i>
                <a href="<?= site_url('Ezshop/cart'); ?>">
                    Cart <span class="cart-badge"><?= $cartCount; ?></span>
                </a>
            </div>
        </div>

        <div class="header-right">
            <?php if (!empty($user)): ?>
                <div class="dropdown header-user">
                    <?php
                    $displayName = !empty($user->fullname_sorted)
                        ? $user->fullname_sorted
                        : ($user->fullname ?? 'My Account');
                    ?>
                    <span class="avatar-name"><?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?></span>
                    <button class="btn avatar-btn dropdown-toggle" type="button" id="avatarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background:transparent; border:0; padding:0;">
                        <img src="<?= $avatarImg; ?>" alt="Profile">
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="avatarDropdown">
                        <?php
                        $displayName = !empty($user->fullname_sorted)
                            ? $user->fullname_sorted
                            : ($user->fullname ?? 'My Account');
                        ?>
                        <span class="dropdown-item-text text-muted">
                            <?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= site_url('Ezshop/profile'); ?>">
                            <i class="bi bi-person-circle"></i> Profile
                        </a>
                        <a class="dropdown-item requires-login" href="<?= site_url('Ezshop/track_order'); ?>">
                            <i class="bi bi-truck"></i> My Orders
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= site_url('Ezshop/logout'); ?>">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= site_url('Ezshop/login'); ?>" class="btn btn-logout">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
    (function() {
        const isLoggedIn = <?= !empty($user) ? 'true' : 'false'; ?>;
        const loginUrl = '<?= site_url("Ezshop/login"); ?>';
        const hasSwal = (typeof Swal !== 'undefined' && typeof Swal.fire === 'function');

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.requires-login').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    if (isLoggedIn) return;
                    e.preventDefault();
                    if (hasSwal) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Login required',
                            text: 'Please log in to access this page.',
                            showCancelButton: true,
                            cancelButtonText: 'Close',
                            confirmButtonText: 'Go to Login',
                            confirmButtonColor: '#f59e0b'
                        }).then(function(res) {
                            if (res.isConfirmed) {
                                window.location.assign(loginUrl);
                            }
                        });
                    } else {
                        if (confirm('Please log in to continue. Go to login now?')) {
                            window.location.assign(loginUrl);
                        }
                    }
                });
            });
        });
    })();
</script>