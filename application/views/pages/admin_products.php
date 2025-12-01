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

                    <!-- Header + New Product button -->
                    <div class="row align-items-center mb-3">
                        <div class="col">
                            <h4 class="page-title mb-0">Products</h4>
                            <p class="text-muted mb-0">Add, edit, or delete products shown in the store.</p>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" id="btnNewProduct">
                                <i class="bi bi-plus-lg"></i> New Product
                            </button>
                        </div>
                    </div>

                    <!-- Flash messages -->
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('error'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('success'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Products Table Card -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">All Products</h5>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0" id="productsTable">
                                    <thead>
                                        <tr>
                                            <th style="width:70px;">Image</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Inventory</th>
                                            <th>Featured</th>
                                            <th style="width:120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($products)): ?>
                                            <?php foreach ($products as $p): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($p['image'])): ?>
                                                            <img src="<?= base_url($p['image']); ?>"
                                                                alt="<?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                style="height:48px;width:auto;border-radius:4px;object-fit:cover;">
                                                        <?php else: ?>
                                                            <span class="text-muted">N/A</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?= htmlspecialchars($p['category_name'] ?: 'Uncategorized', ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td>₱<?= number_format($p['price'], 2); ?></td>
                                                    <td><?= (int)$p['inventory']; ?></td>
                                                    <td>
                                                        <?= $p['is_featured']
                                                            ? '<span class="badge badge-success">Yes</span>'
                                                            : '<span class="badge badge-secondary">No</span>'; ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-secondary btn-edit-product"
                                                            data-id="<?= $p['id']; ?>"
                                                            data-name="<?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                                            data-category="<?= (int)$p['category_id']; ?>"
                                                            data-price="<?= (float)$p['price']; ?>"
                                                            data-inventory="<?= (int)$p['inventory']; ?>"
                                                            data-description="<?= htmlspecialchars($p['description'], ENT_QUOTES, 'UTF-8'); ?>"
                                                            data-featured="<?= (int)$p['is_featured']; ?>"
                                                            data-image="<?= htmlspecialchars($p['image'], ENT_QUOTES, 'UTF-8'); ?>">
                                                            Edit
                                                        </button>
                                                        <a class="btn btn-sm btn-outline-danger"
                                                            href="<?= site_url('Ezshop/delete_product/' . $p['id']); ?>"
                                                            onclick="return confirm('Delete this product?');">
                                                            Delete
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
                    </div><!-- end card -->

                </div><!-- container-fluid -->
            </div><!-- content -->
        </div><!-- content-page -->
    </div><!-- wrapper -->

    <?php include(APPPATH . 'views/includes/footer_plugins.php'); ?>

    <!-- PRODUCT MODAL -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog"
        aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="productForm" action="<?= site_url('Ezshop/save_product'); ?>"
                method="post" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="product_id" id="productId" value="">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Name</label>
                            <input type="text" class="form-control"
                                name="name" id="name" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="category_id">Category</label>
                            <select class="form-control" name="category_id"
                                id="category_id" required>
                                <option value="">Select category</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?= $c['id']; ?>">
                                        <?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="price">Price</label>
                            <input type="number" step="0.01" min="0"
                                class="form-control" name="price" id="price" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inventory">Inventory</label>
                            <input type="number" min="0" class="form-control"
                                name="inventory" id="inventory" value="0">
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-center">
                            <div class="form-check mt-3">
                                <input type="checkbox" class="form-check-input"
                                    name="is_featured" id="is_featured" value="1">
                                <label class="form-check-label" for="is_featured">
                                    Featured product
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description"
                            id="description" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control-file"
                            name="image" id="image" accept="image/*">
                        <small class="form-text text-muted">
                            Upload to change image. Leave blank to keep current image.
                        </small>

                        <div class="mt-2" id="currentImage" style="display:none;">
                            <span class="text-muted small d-block">Current image:</span>
                            <img src="" alt="Current" style="max-height:90px;border-radius:4px;">
                        </div>
                    </div>
                </div><!-- modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                        data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function() {
            // Init DataTable
            $(document).ready(function() {
                $('#productsTable').DataTable({
                    order: [
                        [1, 'asc']
                    ],
                    columnDefs: [{
                        orderable: false,
                        targets: [0, 6]
                    }]
                });
            });

            const $modal = $('#productModal');
            const $form = $('#productForm');
            const $modalTitle = $('#productModalLabel');
            const $currentImage = $('#currentImage');
            const $currentImgTag = $('#currentImage img');
            const baseUrl = '<?= base_url(); ?>';

            // Reset form for Add mode
            function resetForm() {
                $form[0].reset();
                $('#productId').val('');
                $form.attr('action', '<?= site_url('Ezshop/save_product'); ?>');
                $modalTitle.text('Add Product');
                $currentImage.hide();
            }

            // New Product button
            $('#btnNewProduct').on('click', function() {
                resetForm();
                $modal.modal('show');
            });

            // Edit product buttons
            $('.btn-edit-product').on('click', function() {
                const btn = $(this);

                resetForm(); // clear first

                const id = btn.data('id');
                const name = btn.data('name');
                const category = btn.data('category');
                const price = btn.data('price');
                const inventory = btn.data('inventory');
                const description = btn.data('description');
                const featured = btn.data('featured');
                const image = btn.data('image');

                $modalTitle.text('Edit Product');
                $('#productId').val(id);
                $('#name').val(name);
                $('#category_id').val(category);
                $('#price').val(price);
                $('#inventory').val(inventory);
                $('#description').val(description);
                $('#is_featured').prop('checked', String(featured) === '1');

                // set action with id
                $form.attr('action', '<?= site_url('Ezshop/save_product/'); ?>' + id);

                if (image) {
                    $currentImgTag.attr('src', baseUrl + image);
                    $currentImage.show();
                }

                $modal.modal('show');
            });
        })();
    </script>

</body>

</html>