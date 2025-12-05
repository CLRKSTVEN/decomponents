<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth Visuals | DeComponents Admin</title>
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
    $activeNav = 'visuals';
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
                <a href="<?= site_url('Decomponents/auth_visuals'); ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i> Clear Edit</a>
                <a href="<?= site_url('login/logout'); ?>" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </nav>

        <header class="hero">
            <h1>Auth Visuals</h1>
            <p>Tune the login and signup hero copy, badges, and imagery with a quick view of what is live.</p>
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

            <div class="row">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><?= !empty($edit_visual) ? 'Edit Visual' : 'Add Visual'; ?></h5>
                            <form action="<?= site_url('Decomponents/save_auth_visual'); ?>" method="post" enctype="multipart/form-data">
                                <?php if (!empty($edit_visual['id'])): ?>
                                    <input type="hidden" name="id" value="<?= (int)$edit_visual['id']; ?>">
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="page">Page</label>
                                    <select name="page" id="page" class="form-control" required>
                                        <option value="">Choose page</option>
                                        <option value="login" <?= (!empty($edit_visual['page']) && $edit_visual['page'] === 'login') ? 'selected' : ''; ?>>Login</option>
                                        <option value="signup" <?= (!empty($edit_visual['page']) && $edit_visual['page'] === 'signup') ? 'selected' : ''; ?>>Signup</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="badge">Badge (optional)</label>
                                    <input type="text" name="badge" id="badge" class="form-control" value="<?= html_escape($edit_visual['badge'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="headline">Headline</label>
                                    <input type="text" name="headline" id="headline" class="form-control" required value="<?= html_escape($edit_visual['headline'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="subheadline">Subheadline</label>
                                    <textarea name="subheadline" id="subheadline" rows="2" class="form-control"><?= html_escape($edit_visual['subheadline'] ?? ''); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Features (optional)</label>
                                    <input type="text" name="feature_one" class="form-control mb-2" placeholder="Feature 1" value="<?= html_escape($edit_visual['feature_one'] ?? ''); ?>">
                                    <input type="text" name="feature_two" class="form-control mb-2" placeholder="Feature 2" value="<?= html_escape($edit_visual['feature_two'] ?? ''); ?>">
                                    <input type="text" name="feature_three" class="form-control mb-2" placeholder="Feature 3" value="<?= html_escape($edit_visual['feature_three'] ?? ''); ?>">
                                    <input type="text" name="feature_four" class="form-control" placeholder="Feature 4" value="<?= html_escape($edit_visual['feature_four'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="image_path">Image Upload</label>
                                    <input type="file" name="image_path" id="image_path" class="form-control-file" accept="image/*">
                                    <?php if (!empty($edit_visual['image_path'])): ?>
                                        <div class="mt-2">
                                            <small class="text-muted d-block">Current</small>
                                            <img src="<?= base_url($edit_visual['image_path']); ?>" alt="Current visual" style="height:80px;border-radius:6px;border:1px solid #e5e7eb;">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="image_path_manual">Or Image Path/URL</label>
                                    <input type="text" name="image_path_manual" id="image_path_manual" class="form-control" placeholder="upload/auth/hero.jpg or https://..." value="">
                                </div>
                                <div class="form-check mb-3">
                                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" <?= !empty($edit_visual['is_active']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_active">Set as active for this page</label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block"><?= !empty($edit_visual) ? 'Update Visual' : 'Create Visual'; ?></button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Existing Visuals</h5>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Page</th>
                                            <th>Headline</th>
                                            <th>Active</th>
                                            <th style="width:130px;" class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($visuals)): ?>
                                            <?php foreach ($visuals as $v): ?>
                                                <tr>
                                                    <td><?= ucfirst(html_escape($v['page'])); ?></td>
                                                    <td><?= html_escape($v['headline']); ?></td>
                                                    <td>
                                                        <?php if (!empty($v['is_active'])): ?>
                                                            <span class="badge badge-success">Active</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-secondary">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <a href="<?= site_url('Decomponents/auth_visuals/' . (int)$v['id']); ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <a href="<?= site_url('Decomponents/delete_auth_visual/' . (int)$v['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this visual?');">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No visuals created yet.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Currently Active</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Login</h6>
                                    <p class="mb-1 font-weight-bold"><?= html_escape($active_login['headline'] ?? ''); ?></p>
                                    <small class="text-muted"><?= html_escape($active_login['subheadline'] ?? ''); ?></small>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Signup</h6>
                                    <p class="mb-1 font-weight-bold"><?= html_escape($active_signup['headline'] ?? ''); ?></p>
                                    <small class="text-muted"><?= html_escape($active_signup['subheadline'] ?? ''); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>

</html>
