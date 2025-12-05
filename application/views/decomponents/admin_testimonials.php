<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonials | DeComponents Admin</title>
    <link rel="icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="shortcut icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="apple-touch-icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>">
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/admin-ui.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php
    $resolveImage = function ($path) {
        if (!$path) {
            return base_url('upload/profile/avatar.png');
        }
        if (preg_match('#^https?://#i', $path) || strpos($path, '//') === 0) {
            return $path;
        }
        return base_url(ltrim($path, '/'));
    };
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
    $activeNav = 'testimonials';
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
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#testimonialModal">
                    <i class="bi bi-plus-lg"></i> Add Testimonial
                </button>
                <a href="<?= site_url('login/logout'); ?>" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </nav>

        <header class="hero">
            <h1>Testimonials</h1>
            <p>Manage homepage testimonials and spotlight your happiest customers.</p>
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

            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="card-title mb-0">Testimonials</h5>
                        <button class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#testimonialModal">
                            <i class="bi bi-plus-lg"></i> Add Testimonial
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="testimonialTable">
                            <thead>
                                <tr>
                                    <th style="width:70px;">Image</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Quote</th>
                                    <th>Active</th>
                                    <th style="width:140px;" class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($testimonials)): ?>
                                    <?php foreach ($testimonials as $t): ?>
                                        <tr>
                                            <td><img src="<?= $resolveImage($t['image'] ?? ''); ?>" alt="<?= htmlspecialchars($t['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" style="height:50px;border-radius:6px;border:1px solid #e5e7eb;"></td>
                                            <td><?= htmlspecialchars($t['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars($t['role'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars($t['quote'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <?php if (!empty($t['is_active'])): ?>
                                                    <span class="badge badge-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-right">
                                                <a href="<?= site_url('Decomponents/testimonials/' . (int)$t['id']); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a>
                                                <a href="<?= site_url('Decomponents/delete_testimonial/' . (int)$t['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this testimonial?');"><i class="bi bi-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No testimonials found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="testimonialModal" tabindex="-1" role="dialog" aria-labelledby="testimonialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testimonialModalLabel"><?= !empty($edit) ? 'Edit Testimonial' : 'Add Testimonial'; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="testimonialForm" action="<?= site_url('Decomponents/save_testimonial' . (!empty($edit['id']) ? '/' . $edit['id'] : '')); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" required value="<?= html_escape($edit['name'] ?? ''); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="role">Role/Company</label>
                                <input type="text" name="role" id="role" class="form-control" value="<?= html_escape($edit['role'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quote">Quote</label>
                            <textarea name="quote" id="quote" rows="3" class="form-control" required><?= html_escape($edit['quote'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image Upload</label>
                            <input type="file" name="image" id="image" class="form-control-file" accept="image/*">
                            <?php if (!empty($edit['image'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Current</small>
                                    <img src="<?= $resolveImage($edit['image']); ?>" alt="Current image" style="height:80px;border-radius:6px;border:1px solid #e5e7eb;">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="image_manual">Or Image Path/URL</label>
                            <input type="text" name="image_manual" id="image_manual" class="form-control" placeholder="upload/testimonials/pic.jpg or https://..." value="">
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" <?= !empty($edit['is_active']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="submit" form="testimonialForm" class="btn btn-primary"><?= !empty($edit) ? 'Update' : 'Add'; ?></button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    <script>
        (function() {
            $('#testimonialTable').DataTable({
                pageLength: 10,
                order: [[1, 'asc']],
                columnDefs: [{ orderable: false, targets: [0, 5] }]
            });

            <?php if (!empty($edit)): ?>
            $('#testimonialModal').modal('show');
            <?php endif; ?>
        })();
    </script>
</body>

</html>
