<!DOCTYPE html>
<html lang="en">

<?php include(APPPATH . 'views/includes/head.php'); ?>

<body>
    <div id="wrapper">
        <?php include(APPPATH . 'views/includes/top-nav-bar.php'); ?>
        <?php include(APPPATH . 'views/includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row align-items-center mb-3">
                        <div class="col">
                            <h4 class="page-title">Shop Settings</h4>
                            <p class="text-muted mb-0">Control hero content, footer info, and branding.</p>
                        </div>
                    </div>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true">
                            <?= $this->session->flashdata('error'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true">
                            <?= $this->session->flashdata('success'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <form action="<?= site_url('Decomponents/save_settings'); ?>" method="post">
                                        <h5 class="card-title">Branding</h5>
                                        <div class="form-group">
                                            <label>Site Name</label>
                                            <input type="text" name="site_name" class="form-control" value="<?= htmlspecialchars($settings['site_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Tagline</label>
                                            <input type="text" name="tagline" class="form-control" value="<?= htmlspecialchars($settings['tagline'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Logo Path</label>
                                            <input type="text" name="logo_path" class="form-control" value="<?= htmlspecialchars($settings['logo_path'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            <small class="text-muted">Relative path, e.g., <code>Products/Logo1.png</code> or <code>upload/logo.png</code>.</small>
                                        </div>

                                        <hr>
                                        <h5 class="card-title">Contact</h5>
                                        <div class="form-group">
                                            <label>Support Email</label>
                                            <input type="email" name="support_email" class="form-control" value="<?= htmlspecialchars($settings['support_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Support Phone</label>
                                            <input type="text" name="support_phone" class="form-control" value="<?= htmlspecialchars($settings['support_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($settings['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>

                                        <hr>
                                        <h5 class="card-title">Social Links</h5>
                                        <div class="form-group">
                                            <label>Facebook URL</label>
                                            <input type="text" name="facebook_url" class="form-control" value="<?= htmlspecialchars($settings['facebook_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Instagram URL</label>
                                            <input type="text" name="instagram_url" class="form-control" value="<?= htmlspecialchars($settings['instagram_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>YouTube URL</label>
                                            <input type="text" name="youtube_url" class="form-control" value="<?= htmlspecialchars($settings['youtube_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>

                                        <hr>
                                        <h5 class="card-title">Hero Section</h5>
                                        <div class="form-group">
                                            <label>Hero Title</label>
                                            <input type="text" name="hero_title" class="form-control" value="<?= htmlspecialchars($settings['hero_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Hero Subtitle</label>
                                            <textarea name="hero_subtitle" class="form-control" rows="2"><?= htmlspecialchars($settings['hero_subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Hero CTA Label</label>
                                            <input type="text" name="hero_cta_label" class="form-control" value="<?= htmlspecialchars($settings['hero_cta_label'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Hero CTA Link (Controller/Route)</label>
                                            <input type="text" name="hero_cta_link" class="form-control" value="<?= htmlspecialchars($settings['hero_cta_link'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            <small class="text-muted">Example: <code>Decomponents/shop</code></small>
                                        </div>

                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-primary">Save Settings</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <?php include(APPPATH . 'views/includes/footer_plugins.php'); ?>
</body>

</html>
