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
                    <style>
                        .order-kpi {
                            border-radius: 14px;
                            border: 1px solid #e5e7eb;
                            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
                        }
                        .order-kpi h6 {
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                            font-size: 12px;
                            color: #6b7280;
                            margin-bottom: 6px;
                        }
                        .order-kpi .value {
                            font-size: 22px;
                            font-weight: 800;
                            color: #111827;
                        }
                        .order-kpi .sub {
                            font-size: 12px;
                            color: #9ca3af;
                        }
                    </style>

                    <div class="row mb-3 align-items-center">
                        <div class="col">
                            <h4 class="page-title">Orders</h4>
                            <p class="text-muted mb-0">Latest 100 orders.</p>
                        </div>
                    </div>

                    <?php
                    $totalSales = 0;
                    $statusCounts = ['pending' => 0, 'paid' => 0, 'fulfilled' => 0, 'cancelled' => 0];
                    if (!empty($orders)) {
                        foreach ($orders as $o) {
                            $totalSales += (float)($o['total_amount'] ?? 0);
                            $s = strtolower($o['status'] ?? '');
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
                                    <div class="sub">in this view</div>
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
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Customer</th>
                                            <th>Email</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Payment</th>
                                            <th>Date</th>
                                            <th>Status Select</th>
                                            <th>Quick Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($orders)): ?>
                                            <?php foreach ($orders as $idx => $order): ?>
                                                <tr>
                                                    <td><?= (int)$idx + 1; ?></td>
                                                    <td><?= htmlspecialchars($order['full_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?= htmlspecialchars($order['email'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td>₱<?= number_format((float)($order['total_amount'] ?? 0), 2); ?></td>
                                                    <td>
                                                        <?php
                                                        $badgeClass = 'secondary';
                                                        $statusLabel = ucfirst($order['status']);
                                                        if ($order['status'] === 'paid') {
                                                            $badgeClass = 'success';
                                                        } elseif ($order['status'] === 'pending') {
                                                            $badgeClass = 'warning';
                                                        } elseif ($order['status'] === 'fulfilled') {
                                                            $badgeClass = 'info';
                                                            $statusLabel = 'Shipped';
                                                        } elseif ($order['status'] === 'cancelled') {
                                                            $badgeClass = 'dark';
                                                        }
                                                        ?>
                                                        <span class="badge badge-<?= $badgeClass; ?>">
                                                            <?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8'); ?>
                                                        </span>
                                                    </td>
                                                    <td><?= htmlspecialchars($order['payment_method'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?= htmlspecialchars(date('M d, Y', strtotime($order['created_at'])), ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td>
                                                        <form method="post" action="<?= site_url('Decomponents/update_order_status'); ?>" class="form-inline">
                                                            <input type="hidden" name="order_id" value="<?= (int)$order['id']; ?>">
                                                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit();">
                                                                <?php
                                                                $options = ['pending' => 'Pending', 'paid' => 'Paid', 'fulfilled' => 'Fulfilled', 'cancelled' => 'Cancelled'];
                                                                foreach ($options as $val => $label): ?>
                                                                    <option value="<?= $val; ?>" <?= ($order['status'] === $val) ? 'selected' : ''; ?>><?= $label; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column gap-1" style="gap:6px;">
                                                            <?php if (($order['status'] ?? '') === 'pending'): ?>
                                                                <form method="post" action="<?= site_url('Decomponents/update_order_status'); ?>" style="margin:0;">
                                                                    <input type="hidden" name="order_id" value="<?= (int)$order['id']; ?>">
                                                                    <input type="hidden" name="status" value="paid">
                                                                    <button type="submit" class="btn btn-sm btn-outline-primary">Approve (Mark Paid)</button>
                                                                </form>
                                                            <?php endif; ?>

                                                            <?php if (($order['status'] ?? '') !== 'fulfilled'): ?>
                                                                <form method="post" action="<?= site_url('Decomponents/update_order_status'); ?>" style="margin:0;">
                                                                    <input type="hidden" name="order_id" value="<?= (int)$order['id']; ?>">
                                                                    <input type="hidden" name="status" value="fulfilled">
                                                                    <button type="submit" class="btn btn-sm btn-outline-success">Mark Shipped</button>
                                                                </form>
                                                            <?php else: ?>
                                                                <span class="text-success small">Shipped</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">No orders found.</td>
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

    <?php include(APPPATH . 'views/includes/footer_plugins.php'); ?>
</body>

</html>
