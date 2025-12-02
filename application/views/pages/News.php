<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tradables - DeComponents</title>
    <link rel="icon" href="<?php echo base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="shortcut icon" href="<?php echo base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="apple-touch-icon" href="<?php echo base_url('Pictures/Decomponents.jpeg'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/design.css'); ?>">

</head>

<body>
    <?php $this->load->view('partials/navbar'); ?>

    <main class="decom-main">
        <div class="decom-container">
            <?php
            $newsItems = isset($newsItems) ? $newsItems : [];
            $hero = $newsItems[0] ?? null;
            $heroImg = $hero && !empty($hero['image'])
                ? (preg_match('#^https?://#i', $hero['image']) || strpos($hero['image'], '//') === 0 ? $hero['image'] : base_url($hero['image']))
                : base_url('Pictures/GPU.jpg');
            ?>
            <div class="decom-showcase-hero">
                <img src="<?= $heroImg; ?>" alt="Latest news hero">
                <div class="decom-container">
                    <h1 class="decom-AboutUS-header">Latest News</h1>
                    <?php if ($hero): ?>
                        <p style="color:#f1f5f9;max-width:720px;margin-top:12px;line-height:1.5;">
                            <?= html_escape($hero['title']); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="decom-showcase-products">
                <?php if (!empty($newsItems)): ?>
                    <?php foreach ($newsItems as $item): ?>
                        <?php
                        $img = !empty($item['image'])
                            ? (preg_match('#^https?://#i', $item['image']) || strpos($item['image'], '//') === 0 ? $item['image'] : base_url($item['image']))
                            : base_url('Pictures/DeComponents.jpeg');
                        $excerpt = !empty($item['excerpt']) ? $item['excerpt'] : '';
                        $body = !empty($item['body']) ? $item['body'] : '';
                        ?>
                        <div class="decom-showcase-item">
                            <div class="decom-showcase-image">
                                <img src="<?= $img; ?>" alt="<?= html_escape($item['title'] ?? 'News'); ?>">
                            </div>
                            <div class="decom-showcase-content">
                                <h2><?= html_escape($item['title'] ?? ''); ?></h2>
                                <?php if ($excerpt): ?>
                                    <p><?= nl2br(html_escape($excerpt)); ?></p>
                                <?php endif; ?>
                                <?php if ($body): ?>
                                    <p><?= nl2br(html_escape($body)); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="padding:32px 0;text-align:center;">
                        <p class="text-muted">No news posts yet. Please check back soon.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
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
            <a href="https://www.youtube.com/@DeComponents" target="_blank" aria-label="YouTube"><img src="<?php echo base_url('Pictures/Yutob.webp'); ?>" alt="YouTube"></a>
            <a href="https://www.facebook.com/profile.php?id=61568617385907" target="_blank" aria-label="Facebook"><img src="<?php echo base_url('Pictures/Fishbuk.webp'); ?>" alt="Facebook"></a>
            <a href="https://www.tiktok.com/@decomponents" target="_blank" aria-label="TikTok"><img src="<?php echo base_url('Pictures/Tiktook.png'); ?>" alt="TikTok"></a>
            <a href="https://www.linkedin.com/in/de-components-934ba3337/" target="_blank" aria-label="LinkedIn"><img src="<?php echo base_url('Pictures/linkedin.png'); ?>" alt="LinkedIn"></a>
            <a href="#" target="_blank" aria-label="Instagram"><img src="<?php echo base_url('Pictures/Instagram.png'); ?>" alt="Instagram"></a>
        </div>
        <div class=" decom-contact-email">
                <a href="mailto:decomponents.24@gmail.com">DeComponents.24@Gmail.com</a>
        </div>
        <p class="decom-copyright">© 2024 by DeComponents. All Rights Reserved.</p>
    </footer>
</body>

</html>
