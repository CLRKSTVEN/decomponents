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
?>
<header class="decom-header">
    <div class="decom-container">
        <img src="<?php echo base_url('Pictures/DeComponents.jpeg'); ?>" alt="DeComponents Logo" class="decom-logo">
        <nav class="decom-nav">
            <div class="decom-container">
                <a href="<?php echo site_url('home'); ?>" class="decom-nav-link">HOME</a>
                <a href="<?php echo site_url('products'); ?>" class="decom-nav-link">PRODUCTS</a>
                <a href="<?php echo site_url('about'); ?>" class="decom-nav-link">ABOUT US</a>
                <a href="<?php echo site_url('tradables'); ?>" class="decom-nav-link">TRADABLES</a>
                <a href="<?php echo site_url('news'); ?>" class="decom-nav-link">NEWS</a>
                <a href="<?php echo site_url('contact'); ?>" class="decom-nav-link">CONTACT</a>
                <div class="decom-user-meta">
                    <?php if ($isLoggedIn): ?>
                        <span class="decom-user-name">Hi, <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php if ($userLevel === 'admin'): ?>
                            <a href="<?= site_url('Decomponents/admin'); ?>" class="decom-nav-link decom-nav-link--pill">Dashboard</a>
                        <?php endif; ?>
                        <a href="<?= site_url('login/logout'); ?>" class="decom-nav-link decom-nav-link--pill">Logout</a>
                    <?php else: ?>
                        <a href="<?= site_url('home_page'); ?>" class="decom-nav-link decom-nav-link--pill">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</header>
