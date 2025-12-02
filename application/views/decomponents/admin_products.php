<!DOCTYPE html>
<html lang="en">

<?php include(APPPATH . 'views/includes/head.php'); ?>
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
?>

<body>
    <div id="wrapper">
        <!-- Topbar -->
        <?php include(APPPATH . 'views/includes/top-nav-bar.php'); ?>
        <!-- Sidebar -->
        <?php include(APPPATH . 'views/includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">

                    <div class="row mb-3 align-items-center">
                        <div class="col">
                            <h4 class="page-title mb-0">Products</h4>
                            <p class="text-muted mb-0">Create, edit, and remove products that appear in the public store.</p>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#productModal">
                                <i class="bi bi-plus-lg"></i> Add Product
                            </button>
                            <?php if (!empty($editProduct)): ?>
                                <a href="<?= site_url('Decomponents/products'); ?>" class="btn btn-light ml-1">
                                    <i class="bi bi-x-lg"></i> Cancel Edit
                                </a>
                            <?php endif; ?>
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

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Product List</h5>
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
                                                                    <span class="badge badge-success">Yes</span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-secondary">No</span>
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
                </div>
            </div>
        </div>
    </div>

    <?php include(APPPATH . 'views/includes/themecustomizer.php'); ?>

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
