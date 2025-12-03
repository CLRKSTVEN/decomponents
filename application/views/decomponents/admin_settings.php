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
    <style>
        :root {
            --ink: #0f172a;
            --muted: #64748b;
            --accent: #0ea5e9;
            --accent-2: #14b8a6;
            --surface: #f4f6fb;
            --card: #ffffff;
            --shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--surface);
            color: var(--ink);
            font-family: 'Lato', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        a { text-decoration: none; color: inherit; }
        .page-shell { min-height: 100vh; }
        .dash-nav {
            position: sticky;
            top: 0;
            z-index: 20;
            padding: 16px 3rem;
            display: flex;
            align-items: center;
            gap: 16px;
            justify-content: space-between;
            background: rgba(244, 246, 251, 0.92);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
        }
        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: grid;
            place-items: center;
            color: #fff;
            box-shadow: var(--shadow);
        }
        .nav-links { display: flex; gap: 8px; flex-wrap: wrap; }
        .nav-pill {
            padding: 9px 13px;
            border-radius: 12px;
            font-weight: 700;
            color: var(--muted);
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }
        .nav-pill:hover,
        .nav-pill.active {
            background: #fff;
            border-color: rgba(148, 163, 184, 0.3);
            color: var(--ink);
            box-shadow: var(--shadow);
        }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .hero {
            margin: 18px 3rem 0;
            background: linear-gradient(120deg, var(--accent), var(--accent-2));
            color: #fff;
            padding: 28px 32px;
            border-radius: 22px;
            box-shadow: var(--shadow);
        }
        .hero h1 { margin: 0 0 6px; font-weight: 800; }
        .hero p { margin: 0; color: rgba(255,255,255,0.86); max-width: 720px; }
        .container-wide { padding: 22px 3rem 3rem; }
        .card {
            border: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow: var(--shadow);
            border-radius: 18px;
        }
        .table thead th { border-top: none; font-weight: 800; }
        @media (max-width: 991px) {
            .dash-nav, .hero, .container-wide { padding-left: 18px; padding-right: 18px; }
            .dash-nav { flex-direction: column; align-items: flex-start; }
            .nav-actions { width: 100%; justify-content: flex-start; flex-wrap: wrap; }
        }
    </style>
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
