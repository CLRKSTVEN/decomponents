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
                    <div class="row mb-3 align-items-center">
                        <div class="col">
                            <h4 class="page-title mb-0">Shop Settings</h4>
                            <p class="text-muted mb-0">Update site info, socials, and homepage hero content.</p>
                        </div>
                    </div>

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
                                <div class="card">
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
                                <div class="card">
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
                                <div class="card">
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
                                <div class="card">
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
        </div>
    </div>

    <?php include(APPPATH . 'views/includes/themecustomizer.php'); ?>
    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>

</html>
