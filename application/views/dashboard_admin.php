<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>

    <div id="wrapper"><!-- Begin page -->

        <!-- Topbar Start -->
        <?php include('includes/top-nav-bar.php'); ?>
        <!-- end Topbar --> <!-- ========== Left Sidebar Start ========== -->

        <!-- Lef Side bar -->
        <?php include('includes/sidebar.php'); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">

                    <div class="row mb-3 align-items-center">
                        <div class="col">
                            <h4 class="page-title">Admin Dashboard</h4>
                            <p class="text-muted mb-0">Quick overview of your store.</p>
                        </div>
                        <div class="col-auto">
                            <a href="<?= site_url('Ezshop/products'); ?>" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i> Add Product
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="card mini-stat bg-primary text-white">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="bi bi-box-seam h3 float-right"></i>
                                        <h6 class="text-uppercase mt-0">Products</h6>
                                        <h4 class="font-weight-bold mb-0"><?= (int)($stats['products'] ?? 0); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card mini-stat bg-success text-white">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="bi bi-receipt h3 float-right"></i>
                                        <h6 class="text-uppercase mt-0">Orders</h6>
                                        <h4 class="font-weight-bold mb-0"><?= (int)($stats['orders'] ?? 0); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card mini-stat bg-warning text-white">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="bi bi-clock-history h3 float-right"></i>
                                        <h6 class="text-uppercase mt-0">Pending</h6>
                                        <h4 class="font-weight-bold mb-0"><?= (int)($stats['pending'] ?? 0); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="card mini-stat bg-info text-white">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <i class="bi bi-people h3 float-right"></i>
                                        <h6 class="text-uppercase mt-0">Customers</h6>
                                        <h4 class="font-weight-bold mb-0"><?= (int)($stats['customers'] ?? 0); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    // Build order metrics and status counts from recent orders
                    $pie = ['pending' => 0, 'paid' => 0, 'fulfilled' => 0, 'cancelled' => 0];
                    $totalOrdersView = 0;
                    $totalSalesView = 0.0;
                    $itemsSold = 0;
                    $itemsChartLabels = [];
                    $itemsChartData = [];
                    // Sum quantities from order_items table if it exists
                    $CI = &get_instance();
                    if ($CI->db->table_exists('order_items')) {
                        $rowSum = $CI->db->select_sum('quantity')->get('order_items')->row_array();
                        $itemsSold = (int)($rowSum['quantity'] ?? 0);
                        // top 5 products by qty
                        $CI->db->select('p.name AS label, SUM(oi.quantity) AS qty', false)
                            ->from('order_items oi')
                            ->join('products p', 'p.id = oi.product_id', 'left')
                            ->group_by('oi.product_id')
                            ->order_by('qty', 'DESC')
                            ->limit(5);
                        $rows = $CI->db->get()->result_array();
                        foreach ($rows as $r) {
                            $itemsChartLabels[] = $r['label'] ?: 'Item';
                            $itemsChartData[] = (int)$r['qty'];
                        }
                    }
                    if (!empty($recentOrders)) {
                        foreach ($recentOrders as $o) {
                            $totalOrdersView++;
                            $totalSalesView += (float)($o['total_amount'] ?? 0);
                            $s = strtolower($o['status'] ?? '');
                            if (isset($pie[$s])) {
                                $pie[$s]++;
                            }
                        }
                    }
                    // Fallback if no order_items data
                    if ($itemsSold === 0 && $totalOrdersView > 0) {
                        $itemsSold = $totalOrdersView;
                        if (empty($itemsChartLabels)) {
                            $itemsChartLabels = ['Orders'];
                            $itemsChartData = [$totalOrdersView];
                        }
                    }
                    ?>

                    <div class="row mb-3">
                        <div class="col-md-6 col-sm-6">
                            <div class="card mini-stat bg-light">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <h6 class="text-uppercase mt-0 text-muted">Total Sales</h6>
                                        <h4 class="font-weight-bold mb-0 text-primary">₱<?= number_format($totalSalesView, 2); ?></h4>
                                        <small class="text-muted">sum of listed orders</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="card mini-stat bg-light">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <h6 class="text-uppercase mt-0 text-muted">Items Sold</h6>
                                        <h4 class="font-weight-bold mb-0 text-success"><?= (int)$itemsSold; ?></h4>
                                        <small class="text-muted">sum of item quantities</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <h5 class="card-title mb-0">Order Status (last 100)</h5>
                                        <small class="ml-auto text-muted">Pending / Paid / Shipped / Cancelled</small>
                                    </div>
                                    <canvas id="adminOrdersBar" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <h5 class="card-title mb-0">Items Sold</h5>
                                        <small class="ml-auto text-muted">Total quantities</small>
                                    </div>
                                    <canvas id="adminItemsBar" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <h5 class="card-title mb-0">Recent Orders</h5>
                                        <a href="<?= site_url('Ezshop/orders'); ?>" class="ml-auto text-primary">View all</a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Customer</th>
                                                    <th>Email</th>
                                                    <th>Total</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($recentOrders)): ?>
                                                    <?php foreach ($recentOrders as $rIdx => $order): ?>
                                                        <tr>
                                                            <td><?= (int)$rIdx + 1; ?></td>
                                                            <td><?= htmlspecialchars($order['full_name'] ?: 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td><?= htmlspecialchars($order['email'] ?: 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td>₱<?= number_format((float)$order['total_amount'], 2); ?></td>
                                                            <td>
                                                                <span class="badge badge-<?= $order['status'] === 'paid' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'secondary'); ?>">
                                                                    <?= htmlspecialchars(ucfirst($order['status']), ENT_QUOTES, 'UTF-8'); ?>
                                                                </span>
                                                            </td>
                                                            <td><?= htmlspecialchars(date('M d, Y', strtotime($order['created_at'])), ENT_QUOTES, 'UTF-8'); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">No recent orders.</td>
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
        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div><!-- END wrapper -->


    <!-- Right Sidebar -->
    <?php include('includes/themecustomizer.php'); ?>
    <!-- /Right-bar -->


    <!-- Vendor js -->
    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>

    <script src="<?= base_url(); ?>assets/libs/moment/moment.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/jquery-scrollto/jquery.scrollTo.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <script src="<?= base_url(); ?>assets/libs/fullcalendar/fullcalendar.min.js"></script>

    <!-- Calendar init -->
    <script src="<?= base_url(); ?>assets/js/pages/calendar.init.js"></script>

    <!-- Chat app -->
    <script src="<?= base_url(); ?>assets/js/pages/jquery.chat.js"></script>

    <!-- Todo app -->
    <script src="<?= base_url(); ?>assets/js/pages/jquery.todo.js"></script>

    <!--Morris Chart-->
    <script src="<?= base_url(); ?>assets/libs/morris-js/morris.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/raphael/raphael.min.js"></script>

    <!-- Sparkline charts -->
    <script src="<?= base_url(); ?>assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>

    <!-- Dashboard init JS -->
    <script src="<?= base_url(); ?>assets/js/pages/dashboard.init.js"></script>

    <!-- App js -->
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        (function() {
            const ctx = document.getElementById('adminOrdersBar');
            if (!ctx) return;
            const data = {
                labels: ['Pending', 'Paid', 'Fulfilled', 'Cancelled'],
                datasets: [{
                    data: [
                        <?= (int)$pie['pending']; ?>,
                        <?= (int)$pie['paid']; ?>,
                        <?= (int)$pie['fulfilled']; ?>,
                        <?= (int)$pie['cancelled']; ?>
                    ],
                    backgroundColor: ['#f59e0b', '#10b981', '#3b82f6', '#9ca3af'],
                    borderWidth: 1,
                    label: 'Orders'
                }]
            };
            new Chart(ctx, {
                type: 'bar',
                data,
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });
        })();

        (function() {
            const ctx = document.getElementById('adminItemsBar');
            if (!ctx) return;
            const labels = <?= json_encode($itemsChartLabels); ?>;
            const values = <?= json_encode($itemsChartData); ?>;
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Qty Sold',
                        data: values,
                        backgroundColor: '#3b82f6'
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });
        })();
    </script>

    <script src="<?= base_url(); ?>assets/libs/jquery-ui/jquery-ui.min.js"></script>
    <!-- Required datatable js -->
    <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.buttons.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/buttons.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/jszip/jszip.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/pdfmake/pdfmake.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/pdfmake/vfs_fonts.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/buttons.html5.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/buttons.print.min.js"></script>

    <!-- Responsive examples -->
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.keyTable.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.select.min.js"></script>

    <!-- Datatables init -->
    <script src="<?= base_url(); ?>assets/js/pages/datatables.init.js"></script>

</body>




</html>
