<!DOCTYPE html>
<html lang="en">

<?php include(APPPATH . 'views/includes/head.php'); ?>

<body>
    <div id="wrapper">
        <!-- Topbar -->
        <?php include(APPPATH . 'views/includes/top-nav-bar.php'); ?>
        <!-- Sidebar -->
        <?php include(APPPATH . 'views/includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <style>
                        .order-kpi {
                            border-radius: 14px;
                            border: 1px solid #e5e7eb;
                            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
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
                        .status-pill {
                            display: inline-flex;
                            align-items: center;
                            gap: 6px;
                            padding: 6px 10px;
                            border-radius: 999px;
                            font-weight: 700;
                            font-size: 12px;
                            text-transform: uppercase;
                            letter-spacing: 0.4px;
                        }
                        .status-pill.pending { background: #fff7ed; color: #c2410c; }
                        .status-pill.paid { background: #ecfdf3; color: #15803d; }
                        .status-pill.fulfilled { background: #e0f2fe; color: #0369a1; }
                        .status-pill.cancelled { background: #f3f4f6; color: #374151; }
                        .order-actions form { margin-bottom: 6px; }
                        .order-actions button { width: 100%; }
                    </style>

                    <div class="row mb-3 align-items-center">
                        <div class="col">
                            <h4 class="page-title mb-0">Orders</h4>
                            <p class="text-muted mb-0">Review, accept, and mark shipments.</p>
                        </div>
                    </div>

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('success'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
        </div>

    </div>

    <?php include(APPPATH . 'views/includes/footer_plugins.php'); ?>
</body>

</html>
