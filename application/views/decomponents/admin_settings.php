<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | DeComponents Admin</title>
    <link rel="icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="shortcut icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="apple-touch-icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>">
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/admin-ui.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php
    $navLinks = [
        ['key' => 'dashboard', 'label' => 'Overview', 'url' => site_url('Decomponents')],
        ['key' => 'products', 'label' => 'Products', 'url' => site_url('Decomponents/products')],
        ['key' => 'orders', 'label' => 'Orders', 'url' => site_url('Decomponents/orders')],
        ['key' => 'customers', 'label' => 'Customers', 'url' => site_url('Decomponents/customers')],
        ['key' => 'visuals', 'label' => 'Auth Visuals', 'url' => site_url('Decomponents/auth_visuals')],
        ['key' => 'testimonials', 'label' => 'Testimonials', 'url' => site_url('Decomponents/testimonials')],
        ['key' => 'news', 'label' => 'News', 'url' => site_url('Decomponents/news_admin')],
        ['key' => 'settings', 'label' => 'Settings', 'url' => site_url('Decomponents/settings')],
    ];
    $activeNav = 'settings';
    ?>
    <div class="page-shell">
        <nav class="dash-nav">
            <div class="brand">
                <div class="brand-mark">DC</div>
                <div>DeComponents</div>
            </div>
            <div class="nav-links">
                <?php foreach ($navLinks as $link): ?>
                    <a class="nav-pill <?= $activeNav === $link['key'] ? 'active' : ''; ?>" href="<?= $link['url']; ?>"><?= $link['label']; ?></a>
                <?php endforeach; ?>
            </div>
            <div class="nav-actions">
                <a href="<?= site_url('Decomponents'); ?>" class="btn btn-light btn-sm"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="<?= site_url('login/logout'); ?>" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </nav>

        <header class="hero">
            <h1>Shop Settings</h1>
            <p>Update site info, socials, and homepage hero content in one place.</p>
        </header>

        <div class="container-wide">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true">
                    <?= $this->session->flashdata('success'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true">
                    <?= $this->session->flashdata('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('Decomponents/save_settings'); ?>" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Brand</h5>
                                <div class="form-group">
                                    <label for="site_name">Site Name</label>
                                    <input type="text" id="site_name" name="site_name" class="form-control" value="<?= html_escape($settings['site_name'] ?? ''); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tagline">Tagline</label>
                                    <input type="text" id="tagline" name="tagline" class="form-control" value="<?= html_escape($settings['tagline'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="logo_path">Logo Path/URL</label>
                                    <input type="text" id="logo_path" name="logo_path" class="form-control" value="<?= html_escape($settings['logo_path'] ?? ''); ?>" placeholder="upload/logo.png or https://...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Contact</h5>
                                <div class="form-group">
                                    <label for="support_email">Support Email</label>
                                    <input type="email" id="support_email" name="support_email" class="form-control" value="<?= html_escape($settings['support_email'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="support_phone">Support Phone</label>
                                    <input type="text" id="support_phone" name="support_phone" class="form-control" value="<?= html_escape($settings['support_phone'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea id="address" name="address" rows="3" class="form-control"><?= html_escape($settings['address'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Social Links</h5>
                                <div class="form-group">
                                    <label for="facebook_url">Facebook URL</label>
                                    <input type="text" id="facebook_url" name="facebook_url" class="form-control" value="<?= html_escape($settings['facebook_url'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="instagram_url">Instagram URL</label>
                                    <input type="text" id="instagram_url" name="instagram_url" class="form-control" value="<?= html_escape($settings['instagram_url'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="youtube_url">YouTube URL</label>
                                    <input type="text" id="youtube_url" name="youtube_url" class="form-control" value="<?= html_escape($settings['youtube_url'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Homepage Hero</h5>
                                <div class="form-group">
                                    <label for="hero_title">Hero Title</label>
                                    <input type="text" id="hero_title" name="hero_title" class="form-control" value="<?= html_escape($settings['hero_title'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="hero_subtitle">Hero Subtitle</label>
                                    <textarea id="hero_subtitle" name="hero_subtitle" rows="3" class="form-control"><?= html_escape($settings['hero_subtitle'] ?? ''); ?></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="hero_cta_label">CTA Label</label>
                                        <input type="text" id="hero_cta_label" name="hero_cta_label" class="form-control" value="<?= html_escape($settings['hero_cta_label'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="hero_cta_link">CTA Link</label>
                                        <input type="text" id="hero_cta_link" name="hero_cta_link" class="form-control" value="<?= html_escape($settings['hero_cta_link'] ?? ''); ?>" placeholder="products or Decomponents/shop">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right mb-4">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>

</html>
