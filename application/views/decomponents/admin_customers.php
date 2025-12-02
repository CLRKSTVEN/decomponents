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
                        .customer-card {
                            border-radius: 14px;
                            border: 1px solid #e5e7eb;
                            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
                        }
                        .avatar-ring {
                            width: 38px;
                            height: 38px;
                            border-radius: 50%;
                            overflow: hidden;
                            border: 2px solid #e5e7eb;
                            background: #f3f4f6;
                        }
                        .avatar-ring img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            display: block;
                        }
                    </style>

                    <div class="row mb-3 align-items-center">
                        <div class="col">
                            <h4 class="page-title mb-0">Customers</h4>
                            <p class="text-muted mb-0">All registered shoppers.</p>
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

                    <div class="card customer-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Customer</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($customers)): ?>
                                            <?php foreach ($customers as $idx => $cust): ?>
                                                <?php
                                                $cust = (array)$cust;
                                                $full = trim(($cust['FirstName'] ?? '') . ' ' . ($cust['MiddleName'] ?? '') . ' ' . ($cust['LastName'] ?? ''));
                                                if ($full === '') {
                                                    $full = $cust['email'] ?? 'Customer';
                                                }
                                                $avatar = $cust['profile_picture'] ?? '';
                                                if ($avatar && !preg_match('#^https?://#i', $avatar)) {
                                                    $avatar = base_url(ltrim($avatar, '/'));
                                                } elseif (!$avatar) {
                                                    $avatar = base_url('upload/profile/avatar.png');
                                                }
                                                $role = $cust['role'] ?? 'customer';
                                                $joined = $cust['created_at'] ?? '';
                                                $joinedFmt = $joined ? date('M d, Y', strtotime($joined)) : '—';
                                                ?>
                                                <tr>
                                                    <td><?= (int)$idx + 1; ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2" style="gap:10px;">
                                                            <div class="avatar-ring">
                                                                <img src="<?= $avatar; ?>" alt="<?= htmlspecialchars($full, ENT_QUOTES, 'UTF-8'); ?>">
                                                            </div>
                                                            <span class="font-weight-bold"><?= htmlspecialchars($full, ENT_QUOTES, 'UTF-8'); ?></span>
                                                        </div>
                                                    </td>
                                                    <td><?= htmlspecialchars($cust['email'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?= htmlspecialchars(ucfirst($role), ENT_QUOTES, 'UTF-8'); ?></td>
                                                    <td><?= htmlspecialchars($joinedFmt, ENT_QUOTES, 'UTF-8'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No customers found.</td>
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
