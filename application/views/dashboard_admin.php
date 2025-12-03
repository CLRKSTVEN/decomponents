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

    <style>
        :root {
            --app-font: 'Lato', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --ink: #0f172a;
            --muted: #64748b;
            --accent: #0ea5e9;
            --accent-2: #14b8a6;
            --surface: #f4f6fb;
            --card: #ffffff;
            --shadow: 0 10px 35px rgba(15, 23, 42, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--surface);
            color: var(--ink);
            font-family: var(--app-font);
        }

        .page-shell {
            min-height: 100vh;
        }

        /* Top nav */
        .dash-nav {
            position: sticky;
            top: 0;
            z-index: 10;
            padding: 18px 3.5rem;
            display: flex;
            align-items: center;
            gap: 18px;
            justify-content: space-between;
            background: rgba(244, 246, 251, 0.9);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 800;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
        }

        .nav-brand small {
            display: block;
            color: var(--muted);
            font-weight: 500;
        }

        .nav-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .nav-pill {
            padding: 9px 14px;
            border-radius: 12px;
            background: transparent;
            color: var(--ink);
            font-weight: 600;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-pill:hover,
        .nav-pill.active {
            border-color: rgba(15, 23, 42, 0.12);
            background: #fff;
            box-shadow: var(--shadow);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            display: inline-block;
            background: #22c55e;
            box-shadow: 0 0 0 6px rgba(34, 197, 94, 0.18);
        }

        /* Hero */
        .hero {
            margin: 18px 3.5rem 24px;
            background:
                radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0)),
                linear-gradient(120deg, var(--accent), var(--accent-2));
            color: #fff;
            border-radius: 28px;
            padding: 32px 36px;
            box-shadow: var(--shadow);
        }

        .hero h1 {
            font-weight: 800;
            margin-bottom: 10px;
        }

        .hero p {
            color: rgba(255, 255, 255, 0.86);
            max-width: 620px;
        }

        .eyebrow {
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .hero-card {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 18px;
            padding: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .hero-card .label {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
        }

        .hero-card .value {
            font-size: 32px;
            font-weight: 800;
            margin: 4px 0;
        }

        /* Main container */
        .container-wide {
            padding: 0 3.5rem 3rem;
        }

        /* KPI cards */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
            margin-top: -60px;
        }

        .kpi-card {
            background: var(--card);
            border-radius: 18px;
            padding: 16px 18px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(148, 163, 184, 0.2);
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .kpi-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 20px;
            flex: 0 0 46px;
        }

        .kpi-meta span {
            color: var(--muted);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .kpi-meta h4 {
            margin: 4px 0 0;
            font-weight: 800;
        }

        /* Insight cards */
        .insight-card {
            background: var(--card);
            border-radius: 18px;
            padding: 18px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(148, 163, 184, 0.2);
            height: 100%;
        }

        .card-heading {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            color: var(--ink);
        }

        .card-heading small {
            margin-left: auto;
            color: var(--muted);
        }

        .table-card {
            margin-top: 16px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(15, 23, 42, 0.05);
            padding: 6px 12px;
            border-radius: 999px;
            font-weight: 600;
            color: var(--ink);
        }

        .table thead th {
            border-top: none;
            font-weight: 700;
        }

        .table tbody td {
            vertical-align: middle;
        }

        .badge-soft {
            padding: 6px 10px;
            border-radius: 10px;
            font-weight: 700;
        }

        .badge-soft.pending {
            background: rgba(245, 158, 11, 0.15);
            color: #b45309;
        }

        .badge-soft.paid {
            background: rgba(16, 185, 129, 0.15);
            color: #0f766e;
        }

        .badge-soft.fulfilled {
            background: rgba(59, 130, 246, 0.15);
            color: #1d4ed8;
        }

        .badge-soft.cancelled {
            background: rgba(148, 163, 184, 0.18);
            color: #475569;
        }

        /* Link grid (if you add quick links later) */
        .link-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin: 14px 0 20px;
        }

        .link-card {
            background: var(--card);
            border-radius: 14px;
            padding: 14px 16px;
            border: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 12px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            color: var(--ink);
        }

        .link-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.18);
        }

        .link-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 18px;
        }

        @media (max-width: 991px) {

            .dash-nav,
            .hero,
            .container-wide {
                padding-left: 18px;
                padding-right: 18px;
            }

            .dash-nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .hero {
                margin: 12px 0 18px;
            }

            .kpi-grid {
                margin-top: -40px;
            }
        }
    </style>
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
                <span class="text-muted">Live</span>
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
                    <h1>Commerce control room</h1>
                    <p>Monitor product health, orders, and customers without the sidebar noise. Everything you need is condensed into this streamlined view.</p>
                    <div class="hero-actions">
                        <a href="<?= site_url('Decomponents/orders'); ?>" class="btn btn-light text-primary">
                            <i class="bi bi-receipt"></i> Manage Orders
                        </a>
                        <a href="<?= site_url('Decomponents/products'); ?>" class="btn btn-outline-light">
                            <i class="bi bi-box-seam"></i> View Catalog
                        </a>
                    </div>
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
                    <div class="kpi-icon" style="background:#0ea5e9;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="kpi-meta">
                        <span>Products</span>
                        <h4><?= (int)($stats['products'] ?? 0); ?></h4>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-icon" style="background:#10b981;">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="kpi-meta">
                        <span>Orders</span>
                        <h4><?= (int)($stats['orders'] ?? 0); ?></h4>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-icon" style="background:#f59e0b;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="kpi-meta">
                        <span>Pending</span>
                        <h4><?= (int)($stats['pending'] ?? 0); ?></h4>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-icon" style="background:#3b82f6;">
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

            <!-- Recent Orders Table -->
            <div class="insight-card table-card">
                <div class="d-flex align-items-center mb-3">
                    <div class="card-heading mb-0">
                        <span>Recent Orders</span>
                    </div>
                    <a href="<?= site_url('Decomponents/orders'); ?>" class="ml-auto nav-pill">
                        View all
                    </a>
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
                                            <?php $statusClass = strtolower($order['status'] ?? ''); ?>
                                            <span class="badge-soft <?= $statusClass; ?>">
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
                    backgroundColor: ['#f59e0b', '#10b981', '#3b82f6', '#9ca3af'],
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
                        backgroundColor: '#0ea5e9'
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
    script src="<?= base_url(); ?>assets/libs/datatables/dataTables.buttons.min.js"></script>
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
