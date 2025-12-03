<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News | DeComponents Admin</title>
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
    $resolveImage = function ($path) {
        if (empty($path)) {
            return base_url('Pictures/DeComponents.jpeg');
        }
        if (preg_match('#^https?://#i', $path) || strpos($path, '//') === 0) {
            return $path;
        }
        return base_url(ltrim($path, '/'));
    };
    $isEditing = !empty($editNews);
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
    $activeNav = 'news';
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
                <?php if ($isEditing): ?>
                    <a href="<?= site_url('Decomponents/news_admin'); ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i> Cancel edit</a>
                <?php endif; ?>
                <a href="<?= site_url('login/logout'); ?>" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </nav>

        <header class="hero">
            <h1>News</h1>
            <p>Publish and manage news displayed on the public site.</p>
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
                            <h5 class="card-title mb-3"><?= $isEditing ? 'Edit news post' : 'Add news post'; ?></h5>
                            <form action="<?= site_url('Decomponents/save_news' . ($isEditing ? '/' . (int)$editNews['id'] : '')); ?>" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" required value="<?= html_escape($editNews['title'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Excerpt</label>
                                    <textarea name="excerpt" rows="2" class="form-control" placeholder="Short teaser for listing cards"><?= html_escape($editNews['excerpt'] ?? ''); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Body</label>
                                    <textarea name="body" rows="6" class="form-control" placeholder="Full article body (plain text or HTML allowed)"><?= html_escape($editNews['body'] ?? ''); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Hero Image</label>
                                    <input type="file" name="image" class="form-control-file" accept="image/*">
                                    <small class="text-muted d-block">JPEG, PNG, GIF, or WEBP up to 4MB.</small>
                                    <input type="text" name="image_manual" class="form-control mt-2" placeholder="Or paste an image path/URL" value="<?= html_escape($editNews['image'] ?? ''); ?>">
                                    <?php if (!empty($editNews['image'])): ?>
                                        <div class="mt-2">
                                            <small class="text-muted d-block">Current</small>
                                            <img src="<?= $resolveImage($editNews['image']); ?>" alt="Current image" style="height:80px;border-radius:6px;border:1px solid #e5e7eb;">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-check mb-3">
                                    <input type="checkbox" name="is_published" class="form-check-input" id="isPublished" <?= !empty($editNews['is_published']) ? 'checked' : ''; ?>>
                                    <label for="isPublished" class="form-check-label">Publish now</label>
                                </div>
                                <button type="submit" class="btn btn-primary"><?= $isEditing ? 'Update' : 'Publish'; ?> post</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">All posts</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width:70px;">Image</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th style="width:130px;" class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($news)): ?>
                                            <?php foreach ($news as $row): ?>
                                                <tr>
                                                    <td>
                                                        <img src="<?= $resolveImage($row['image'] ?? ''); ?>" alt="<?= html_escape($row['title'] ?? 'News'); ?>" style="height:50px;width:auto;border-radius:6px;border:1px solid #e5e7eb;object-fit:cover;">
                                                    </td>
                                                    <td>
                                                        <div class="font-weight-bold"><?= html_escape($row['title'] ?? ''); ?></div>
                                                        <small class="text-muted"><?= !empty($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : ''; ?></small>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($row['is_published'])): ?>
                                                            <span class="badge badge-success">Published</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-secondary">Draft</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <a href="<?= site_url('Decomponents/news_admin?edit=' . (int)$row['id']); ?>" class="btn btn-sm btn-outline-primary">
                                                            Edit
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-news" data-id="<?= (int)$row['id']; ?>" data-title="<?= html_escape($row['title'] ?? 'this post'); ?>">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No news posts yet.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
    <script>
        (function() {
            const buttons = document.querySelectorAll('.btn-delete-news');
            if (!buttons.length) return;

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    const title = btn.getAttribute('data-title') || 'this post';
                    if (!id) return;

                    const proceed = () => window.location.href = '<?= site_url('Decomponents/delete_news/'); ?>' + id;

                    if (window.Swal && typeof Swal.fire === 'function') {
                        Swal.fire({
                            title: 'Delete this news post?',
                            text: title,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete',
                            cancelButtonText: 'Cancel',
                            focusCancel: true
                        }).then(result => {
                            if (result.isConfirmed) {
                                proceed();
                            }
                        });
                    } else if (confirm('Delete this news post?')) {
                        proceed();
                    }
                });
            });
        })();
    </script>
</body>

</html>
