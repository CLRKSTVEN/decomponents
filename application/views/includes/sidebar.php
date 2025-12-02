<style>
    .left-side-menu .metismenu li>a {
        display: flex;
        align-items: center;
    }

    .left-side-menu .metismenu li>a i {
        margin-right: 8px;
    }
</style>

<?php
$uri   = trim(uri_string(), '/');
$levelRaw = $this->session->userdata('level') ?? 'customer';
$level = is_string($levelRaw) ? ucfirst(strtolower($levelRaw)) : 'Customer';

$active = function ($prefix) use ($uri) {
    $prefix = trim($prefix, '/');
    if ($prefix === '') {
        return $uri === '' ? 'active mm-active' : '';
    }
    return (strpos($uri, $prefix) === 0) ? 'active mm-active' : '';
};
?>

<div class="left-side-menu">
    <div class="slimscroll-menu">
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">

                <?php if ($level === 'Admin'): ?>

                    <li class="menu-title">DECOMPONENTS ADMIN</li>

                    <li class="<?= $active('Decomponents/admin'); ?>">
                        <a href="<?= site_url('Decomponents/admin'); ?>" class="waves-effect">
                            <i class="bi bi-speedometer2"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>

                    <li class="<?= $active('home'); ?>">
                        <a href="<?= site_url('home'); ?>" class="waves-effect" target="_blank">
                            <i class="bi bi-house-door"></i>
                            <span> View Store </span>
                        </a>
                    </li>

                    <li class="<?= $active('Decomponents/products'); ?>">
                        <a href="<?= site_url('Decomponents/products'); ?>" class="waves-effect">
                            <i class="bi bi-tags"></i>
                            <span> Products </span>
                        </a>
                    </li>

                    <li class="<?= $active('Decomponents/orders'); ?>">
                        <a href="<?= site_url('Decomponents/orders'); ?>" class="waves-effect">
                            <i class="bi bi-receipt-cutoff"></i>
                            <span> Orders </span>
                        </a>
                    </li>

                    <li class="<?= $active('Decomponents/customers'); ?>">
                        <a href="<?= site_url('Decomponents/customers'); ?>" class="waves-effect">
                            <i class="bi bi-people"></i>
                            <span> Customers </span>
                        </a>
                    </li>

                    <li class="<?= $active('Decomponents/staff'); ?>">
                        <a href="<?= site_url('Decomponents/staff'); ?>" class="waves-effect">
                            <i class="bi bi-headset"></i>
                            <span> Support Staff </span>
                        </a>
                    </li>

                    <li class="<?= $active('Decomponents/auth_visuals'); ?>">
                        <a href="<?= site_url('Decomponents/auth_visuals'); ?>" class="waves-effect">
                            <i class="bi bi-images"></i>
                            <span> Auth Visuals </span>
                        </a>
                    </li>

                    <li class="<?= $active('Decomponents/testimonials'); ?>">
                        <a href="<?= site_url('Decomponents/testimonials'); ?>" class="waves-effect">
                            <i class="bi bi-chat-quote"></i>
                            <span> Testimonials </span>
                        </a>
                    </li>
                    <li class="<?= $active('Decomponents/news_admin'); ?>">
                        <a href="<?= site_url('Decomponents/news_admin'); ?>" class="waves-effect">
                            <i class="bi bi-newspaper"></i>
                            <span> News </span>
                        </a>
                    </li>

                    <li class="<?= $active('Decomponents/settings'); ?>">
                        <a href="<?= site_url('Decomponents/settings'); ?>" class="waves-effect">
                            <i class="bi bi-gear"></i>
                            <span> Shop Settings </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= site_url('login/logout'); ?>" class="waves-effect logout-confirm">
                            <i class="bi bi-box-arrow-right"></i>
                            <span> Logout </span>
                        </a>
                    </li>

                <?php else: ?>

                    <li class="menu-title">STORE</li>

                    <li class="<?= $active('Decomponents/shop'); ?>">
                        <a href="<?= site_url('Decomponents/shop'); ?>" class="waves-effect">
                            <i class="bi bi-bag-heart"></i>
                            <span> Shop </span>
                        </a>
                    </li>

                    <li class="<?= $active('Decomponents/cart'); ?>">
                        <a href="<?= site_url('Decomponents/cart'); ?>" class="waves-effect">
                            <i class="bi bi-cart3"></i>
                            <span> My Cart </span>
                        </a>
                    </li>

                    <li class="<?= $active('Decomponents/profile'); ?>">
                        <a href="<?= site_url('Decomponents/profile'); ?>" class="waves-effect">
                            <i class="bi bi-person-circle"></i>
                            <span> My Profile </span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= site_url('login/logout'); ?>" class="waves-effect logout-confirm">
                            <i class="bi bi-box-arrow-right"></i>
                            <span> Logout </span>
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>

        <div class="clearfix"></div>
    </div>
</div>

<script>
    (function() {
        var links = document.querySelectorAll('.logout-confirm');
        if (!links.length) return;

        Array.prototype.forEach.call(links, function(link) {
            if (link.__logoutConfirmBound) return;
            link.__logoutConfirmBound = true;

            link.addEventListener('click', function(e) {
                e.preventDefault();
                var url = this.getAttribute('href');

                if (window.Swal && Swal.fire) {
                    Swal.fire({
                        title: 'Log out?',
                        text: 'You will be logged out of your DeComponents account.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, log out',
                        cancelButtonText: 'Cancel'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                } else if (confirm('Are you sure you want to log out?')) {
                    window.location.href = url;
                }
            });
        });
    })();
</script>
