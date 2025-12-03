<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | DeComponents Admin</title>
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
            letter-spacing: 0.3px;
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
        .badge-soft {
            padding: 6px 10px;
            border-radius: 10px;
            font-weight: 700;
        }
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
        if (!$path) {
            return base_url('upload/profile/avatar.png');
        }
        if (preg_match('#^https?://#i', $path) || strpos($path, '//') === 0) {
            return $path;
        }
        return base_url($path);
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
    $activeNav = 'products';
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
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#productModal">
                    <i class="bi bi-plus-lg"></i> Add Product
                </button>
                <?php if (!empty($editProduct)): ?>
                    <a href="<?= site_url('Decomponents/products'); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg"></i> Cancel Edit
                    </a>
                <?php endif; ?>
                <a href="<?= site_url('login/logout'); ?>" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </nav>

        <header class="hero">
            <h1>Product catalog</h1>
            <p>Create, edit, and curate the items that appear in your store. Modal opens for new or existing items.</p>
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
                        <h5 class="card-title mb-0">Product List</h5>
                        <button class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#productModal">
                            <i class="bi bi-plus-lg"></i> Add Product
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="adminProductsTable">
                            <thead>
                                <tr>
                                    <th style="width:70px;">Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Featured</th>
                                    <th class="text-right" style="width:120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $prod): ?>
                                        <tr>
                                            <td>
                                                <img src="<?= $resolveImage($prod['image'] ?? ''); ?>" alt="<?= htmlspecialchars($prod['name'] ?? 'Product', ENT_QUOTES, 'UTF-8'); ?>" style="height:50px;border-radius:6px;border:1px solid #e5e7eb;">
                                            </td>
                                            <td><?= htmlspecialchars($prod['name'] ?? 'Product', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars($prod['category_name'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>₱<?= number_format((float)($prod['price'] ?? 0), 2); ?></td>
                                            <td><?= (int)($prod['inventory'] ?? 0); ?></td>
                                            <td>
                                                <?php if (!empty($prod['is_featured'])): ?>
                                                    <span class="badge badge-soft bg-success text-white">Yes</span>
                                                <?php else: ?>
                                                    <span class="badge badge-soft bg-secondary text-white">No</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-right">
                                                <a href="<?= site_url('Decomponents/products?edit=' . (int)$prod['id']); ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="<?= site_url('Decomponents/delete_product/' . (int)$prod['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?');">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No products found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel"><?= !empty($editProduct) ? 'Edit Product' : 'Add Product'; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="productForm" action="<?= site_url('Decomponents/save_product' . (!empty($editProduct['id']) ? '/' . $editProduct['id'] : '')); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control" required value="<?= !empty($editProduct['name']) ? htmlspecialchars($editProduct['name'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="category_id">Category</label>
                                <select id="category_id" name="category_id" class="form-control" required>
                                    <option value="">Select a category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <?php
                                            $label = $cat['name'];
                                            $idVal = (int)$cat['id'];
                                            $selected = !empty($editProduct['category_id']) && (int)$editProduct['category_id'] === $idVal ? 'selected' : '';
                                            $allowed = in_array(strtolower($cat['slug'] ?? ''), ['cpu', 'power-supply', 'gpu'], true) || stripos($label, 'cpu') !== false || stripos($label, 'power') !== false || stripos($label, 'gpu') !== false;
                                            if (!$allowed) {
                                                continue;
                                            }
                                        ?>
                                        <option value="<?= $idVal; ?>" <?= $selected; ?>>
                                            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (empty($categories)): ?>
                                    <small class="text-danger d-block mt-1">No categories found. Add at least one category record.</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="price">Price</label>
                                <input type="number" step="0.01" min="0" id="price" name="price" class="form-control" required value="<?= isset($editProduct['price']) ? (float)$editProduct['price'] : ''; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inventory">Inventory</label>
                                <input type="number" min="0" id="inventory" name="inventory" class="form-control" value="<?= isset($editProduct['inventory']) ? (int)$editProduct['inventory'] : 0; ?>">
                            </div>
                            <div class="form-group col-md-4 d-flex align-items-center">
                                <div class="form-check mt-3">
                                    <input type="checkbox" id="is_featured" name="is_featured" class="form-check-input" <?= !empty($editProduct['is_featured']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_featured">Featured</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="3" class="form-control" placeholder="Short product description"><?= !empty($editProduct['description']) ? htmlspecialchars($editProduct['description'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Image (optional)</label>
                            <input type="file" id="image" name="image" class="form-control-file" accept="image/*">
                            <?php if (!empty($editProduct['image'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Current</small>
                                    <img src="<?= $resolveImage($editProduct['image']); ?>" alt="Current image" style="height:80px;border-radius:6px;border:1px solid #e5e7eb;">
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="submit" form="productForm" class="btn btn-primary"><?= !empty($editProduct) ? 'Update Product' : 'Add Product'; ?></button>
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
            $('#adminProductsTable').DataTable({
                pageLength: 10,
                order: [[1, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [0, 6] }
                ]
            });

            <?php if (!empty($editProduct)): ?>
            $('#productModal').modal('show');
            <?php endif; ?>
        })();
    </script>
</body>

</html>
