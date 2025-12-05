<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | DeComponents Admin</title>
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
    $activeNav = 'orders';
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
            <h1>Orders</h1>
            <p>Review, accept, and ship customer orders from a single, tidy table.</p>
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

            <?php
            $totalSales = 0;
            $statusCounts = ['pending' => 0, 'paid' => 0, 'fulfilled' => 0, 'cancelled' => 0];
            if (!empty($orders)) {
                foreach ($orders as $o) {
                    $row = (array)$o;
                    $totalSales += (float)($row['total_amount'] ?? 0);
                    $s = strtolower($row['status'] ?? '');
                    if (isset($statusCounts[$s])) {
                        $statusCounts[$s]++;
                    }
                }
            }
            $totalOrders = !empty($orders) ? count($orders) : 0;
            ?>

            <div class="row mb-3">
                <div class="col-md-3 mb-2">
                    <div class="card order-kpi">
                        <div class="card-body">
                            <h6>Total Orders</h6>
                            <div class="value"><?= (int)$totalOrders; ?></div>
                            <div class="sub">latest <?= $totalOrders; ?> shown</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="card order-kpi">
                        <div class="card-body">
                            <h6>Total Sales</h6>
                            <div class="value text-primary">₱<?= number_format($totalSales, 2); ?></div>
                            <div class="sub">sum of listed orders</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="card order-kpi">
                        <div class="card-body">
                            <h6>Pending</h6>
                            <div class="value text-warning"><?= (int)$statusCounts['pending']; ?></div>
                            <div class="sub">awaiting approval</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="card order-kpi">
                        <div class="card-body">
                            <h6>Shipped</h6>
                            <div class="value text-info"><?= (int)$statusCounts['fulfilled']; ?></div>
                            <div class="sub">marked fulfilled</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <h5 class="card-title mb-0">Recent Orders</h5>
                        <a href="<?= site_url('Decomponents'); ?>" class="nav-pill ml-auto">Back to dashboard</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Date</th>
                                    <th>Update</th>
                                    <th>Quick Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orders)): ?>
                                    <?php foreach ($orders as $idx => $orderRaw): ?>
                                        <?php $order = (array)$orderRaw; ?>
                                        <tr>
                                            <td><?= (int)$idx + 1; ?></td>
                                            <td><?= htmlspecialchars($order['full_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars($order['email'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= (int)($order['quantity'] ?? 0); ?></td>
                                            <td>₱<?= number_format((float)($order['total_amount'] ?? 0), 2); ?></td>
                                            <td>
                                                <?php
                                                $status = strtolower($order['status'] ?? 'pending');
                                                $statusLabel = [
                                                    'pending'   => 'Pending',
                                                    'paid'      => 'Paid',
                                                    'fulfilled' => 'Shipped',
                                                    'cancelled' => 'Cancelled',
                                                ][$status] ?? 'Pending';
                                                ?>
                                                <span class="status-pill <?= $status; ?>">
                                                    <?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8'); ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($order['payment_method'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <?php
                                                $dateVal = $order['created_at'] ?? '';
                                                $formattedDate = $dateVal ? date('M d, Y h:i A', strtotime($dateVal)) : '—';
                                                ?>
                                                <?= htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8'); ?>
                                            </td>
                                            <td>
                                                <form method="post" action="<?= site_url('Decomponents/update_order_status'); ?>" class="form-inline">
                                                    <input type="hidden" name="order_id" value="<?= (int)($order['id'] ?? 0); ?>">
                                                    <select name="status" class="form-control form-control-sm" onchange="this.form.submit();">
                                                        <?php
                                                        $options = ['pending' => 'Pending', 'paid' => 'Paid', 'fulfilled' => 'Shipped', 'cancelled' => 'Cancelled'];
                                                        foreach ($options as $val => $label): ?>
                                                            <option value="<?= $val; ?>" <?= ($status === $val) ? 'selected' : ''; ?>><?= $label; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </form>
                                            </td>
                                            <td class="order-actions" style="min-width: 170px;">
                                                <?php if ($status === 'pending'): ?>
                                                    <form method="post" action="<?= site_url('Decomponents/update_order_status'); ?>">
                                                        <input type="hidden" name="order_id" value="<?= (int)($order['id'] ?? 0); ?>">
                                                        <input type="hidden" name="status" value="paid">
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">Accept (Mark Paid)</button>
                                                    </form>
                                                <?php endif; ?>

                                                <?php if ($status !== 'fulfilled'): ?>
                                                    <form method="post" action="<?= site_url('Decomponents/update_order_status'); ?>">
                                                        <input type="hidden" name="order_id" value="<?= (int)($order['id'] ?? 0); ?>">
                                                        <input type="hidden" name="status" value="fulfilled">
                                                        <button type="submit" class="btn btn-sm btn-outline-success">Mark Shipped</button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-success small">Already shipped</span>
                                                <?php endif; ?>

                                                <?php if ($status !== 'cancelled'): ?>
                                                    <form method="post" action="<?= site_url('Decomponents/update_order_status'); ?>">
                                                        <input type="hidden" name="order_id" value="<?= (int)($order['id'] ?? 0); ?>">
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Cancel</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">No orders found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>
</body>

</html>
