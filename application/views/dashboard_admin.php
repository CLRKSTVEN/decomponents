<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>DeComponents | Admin Control</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin dashboard" name="description" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>" type="image/jpeg">
    <link rel="apple-touch-icon" href="<?= base_url('Pictures/Decomponents.jpeg'); ?>">

    <!-- Vendor CSS -->
    <link href="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/libs/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />

    <!-- Core CSS -->
    <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
    <link href="<?= base_url(); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

    <!-- DataTables -->
    <link href="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url(); ?>assets/libs/datatables/select.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Icons / Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/admin-ui.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php
    // Build order metrics and status counts from recent orders
    $pie = ['pending' => 0, 'paid' => 0, 'fulfilled' => 0, 'cancelled' => 0];
    $totalOrdersView = 0;
    $totalSalesView = 0.0;
    $itemsSold = 0;
    $itemsChartLabels = [];
    $itemsChartData = [];

    $CI = &get_instance();

    if ($CI->db->table_exists('order_items')) {
        $rowSum = $CI->db->select_sum('quantity')->get('order_items')->row_array();
        $itemsSold = (int)($rowSum['quantity'] ?? 0);

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

    if ($itemsSold === 0 && $totalOrdersView > 0) {
        $itemsSold = $totalOrdersView;
        if (empty($itemsChartLabels)) {
            $itemsChartLabels = ['Orders'];
            $itemsChartData = [$totalOrdersView];
        }
    }
    ?>

    <div class="page-shell">

        <!-- Top Nav -->
        <nav class="dash-nav">
            <div class="nav-brand">
                <div class="brand-mark">DC</div>
                <div>
                    <small>Decomponents</small>
                    Admin Control
                </div>
            </div>

            <div class="nav-links">
                <a href="<?= site_url('Decomponents'); ?>" class="nav-pill active">Overview</a>
                <a href="<?= site_url('Decomponents/products'); ?>" class="nav-pill">Products</a>
                <a href="<?= site_url('Decomponents/orders'); ?>" class="nav-pill">Orders</a>
                <a href="<?= site_url('Decomponents/customers'); ?>" class="nav-pill">Customers</a>
                <a href="<?= site_url('Decomponents/auth_visuals'); ?>" class="nav-pill">Auth Visuals</a>
                <a href="<?= site_url('Decomponents/testimonials'); ?>" class="nav-pill">Testimonials</a>
                <a href="<?= site_url('Decomponents/news_admin'); ?>" class="nav-pill">News</a>
                <a href="<?= site_url('Decomponents/settings'); ?>" class="nav-pill">Settings</a>
            </div>

            <div class="nav-actions">
                <span class="status-dot"></span>
                <span class="text-light small">Live</span>
                <a href="<?= site_url('Decomponents/products'); ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Add Product
                </a>
                <a href="<?= site_url('login/logout'); ?>" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </nav>

        <!-- Hero -->
        <header class="hero">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="eyebrow">Today</div>
                    <h1>Operations board</h1>
                    <p>Track the pulse of sales, stock, and customers without the clutter. Every moving piece that keeps the shop running sits in this view.</p>

                </div>
                <div class="col-lg-5">
                    <div class="d-flex flex-column gap-2 hero-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="label">Sales tracked</span>
                            <span class="pill">
                                <i class="bi bi-activity"></i> Live
                            </span>
                        </div>
                        <div class="value">₱<?= number_format($totalSalesView, 2); ?></div>
                        <div class="d-flex justify-content-between text-white-50">
                            <span><i class="bi bi-cart-check"></i> Orders: <?= (int)$totalOrdersView; ?></span>
                            <span><i class="bi bi-box"></i> Items sold: <?= (int)$itemsSold; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container-wide">

            <!-- KPIs -->
            <section class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background:var(--accent);color:#fff;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="kpi-meta">
                        <span>Products</span>
                        <h4><?= (int)($stats['products'] ?? 0); ?></h4>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-icon" style="background:var(--accent-2);color:#fff;">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="kpi-meta">
                        <span>Orders</span>
                        <h4><?= (int)($stats['orders'] ?? 0); ?></h4>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-icon" style="background:#dbeafe;color:#0f172a;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="kpi-meta">
                        <span>Pending</span>
                        <h4><?= (int)($stats['pending'] ?? 0); ?></h4>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-icon" style="background:#a7f3d0;color:#065f46;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="kpi-meta">
                        <span>Customers</span>
                        <h4><?= (int)($stats['customers'] ?? 0); ?></h4>
                    </div>
                </div>
            </section>

            <!-- Summary cards -->
            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <div class="insight-card">
                        <div class="d-flex align-items-start">
                            <div>
                                <div class="card-heading">
                                    <i class="bi bi-currency-exchange text-primary"></i>
                                    Total Sales
                                </div>
                                <p class="text-muted mb-1">Sum of listed orders</p>
                            </div>
                            <span class="pill">
                                <i class="bi bi-arrow-up-right"></i>
                                <?= $totalOrdersView ? 'Active' : 'No orders'; ?>
                            </span>
                        </div>
                        <h3 class="mt-2 text-primary">₱<?= number_format($totalSalesView, 2); ?></h3>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="insight-card">
                        <div class="d-flex align-items-start">
                            <div>
                                <div class="card-heading">
                                    <i class="bi bi-bag-check text-success"></i>
                                    Items Sold
                                </div>
                                <p class="text-muted mb-1">Sum of item quantities</p>
                            </div>
                            <span class="pill text-success">
                                <i class="bi bi-check2-all"></i> Synced
                            </span>
                        </div>
                        <h3 class="mt-2 text-success"><?= (int)$itemsSold; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <div class="insight-card">
                        <div class="card-heading">
                            <span>Order Status (last 100)</span>
                            <small>Pending / Paid / Fulfilled / Cancelled</small>
                        </div>
                        <canvas id="adminOrdersBar" height="220"></canvas>
                    </div>
                </div>

                <div class="col-lg-6 mb-3">
                    <div class="insight-card">
                        <div class="card-heading">
                            <span>Items Sold</span>
                            <small>Top performers</small>
                        </div>
                        <canvas id="adminItemsBar" height="220"></canvas>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- Vendor JS -->
    <script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/moment/moment.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/jquery-scrollto/jquery.scrollTo.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/fullcalendar/fullcalendar.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/pages/calendar.init.js"></script>
    <script src="<?= base_url(); ?>assets/js/pages/jquery.chat.js"></script>
    <script src="<?= base_url(); ?>assets/js/pages/jquery.todo.js"></script>
    <script src="<?= base_url(); ?>assets/libs/morris-js/morris.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/raphael/raphael.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/pages/dashboard.init.js"></script>
    <script src="<?= base_url(); ?>assets/js/app.min.js"></script>

    <!-- Charts -->
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
                    backgroundColor: ['#93c5fd', '#2563eb', '#10b981', '#cbd5e1'],
                    borderWidth: 0
                }]
            };

            new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
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
                        backgroundColor: '#2563eb'
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        })();
    </script>

    <!-- DataTables -->
    <script src="<?= base_url(); ?>assets/libs/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.buttons.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/buttons.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/jszip/jszip.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/pdfmake/pdfmake.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/pdfmake/vfs_fonts.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/buttons.html5.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/buttons.print.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.keyTable.min.js"></script>
    <script src="<?= base_url(); ?>assets/libs/datatables/dataTables.select.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/pages/datatables.init.js"></script>

</body>

</html>
