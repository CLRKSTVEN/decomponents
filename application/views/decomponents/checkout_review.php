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
                            <h4 class="page-title mb-0">Checkout Review</h4>
                            <p class="text-muted mb-0">Confirm quantities before proceeding to payment.</p>
                        </div>
                    </div>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('error'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">
                            <?php if (!empty($cart)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($cart as $item): ?>
                                                <?php
                                                    $name  = isset($item['product_name']) ? $item['product_name'] : ($item['name'] ?? 'Product');
                                                    $price = (float)($item['product_price'] ?? $item['price'] ?? 0);
                                                    $qty   = (int)($item['qty'] ?? $item['quantity'] ?? 1);
                                                    $subtotal = $price * $qty;
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td>₱<?= number_format($price, 2); ?></td>
                                                    <td><?= $qty; ?></td>
                                                    <td>₱<?= number_format($subtotal, 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <a href="<?= site_url('products'); ?>" class="btn btn-link">Back to Products</a>
                                    <div>
                                        <strong class="mr-3">Total: ₱<?= number_format((float)$total, 2); ?></strong>
                                        <a class="btn btn-primary" href="<?= site_url('Decomponents/payment_form'); ?>">Proceed to Payment</a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">Your cart is empty.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include(APPPATH . 'views/includes/themecustomizer.php'); ?>
    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>

</html>
