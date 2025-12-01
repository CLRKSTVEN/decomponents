<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<style>
    body {
        font-family: var(--app-font);
        background: #f8fafc;
    }

    .page-title-box h4 {
        font-weight: 800;
        letter-spacing: -0.01em;
    }

    .card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.04);
    }

    .card-title {
        font-weight: 800;
        color: #111827;
    }

    .table thead th {
        text-transform: uppercase;
        font-size: 0.78rem;
        letter-spacing: 0.08em;
        color: #6b7280;
        border-bottom-width: 2px;
    }

    .badge-medal {
        font-weight: 800;
        padding: 6px 10px;
        border-radius: 10px;
    }

    .badge-gold {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-silver {
        background: #e5e7eb;
        color: #374151;
    }

    .badge-bronze {
        background: #ffedd5;
        color: #9a3412;
    }
</style>

<body>
    <div id="wrapper">

        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">

                    <!-- Page title -->
                    <?php
                    $validation_list = validation_errors('<li>', '</li>');
                    $meet_title = isset($meet->meet_title) ? $meet->meet_title : 'Provincial Meet';
                    $meet_year  = isset($meet->meet_year)  ? $meet->meet_year  : date('Y');
                    ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap">
                                <div class="mb-2">
                                    <h4 class="page-title mb-0">
                                        <?= htmlspecialchars($meet_title . ' ' . $meet_year, ENT_QUOTES, 'UTF-8'); ?> – Admin
                                    </h4>
                                </div>
                                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">

                                    <!-- Primary action -->
                                    <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#winnerModal">
                                        <i class="mdi mdi-plus"></i> New Entry
                                    </button>
                                    <!-- Secondary action -->
                                    <a href="<?= site_url('provincial/standings'); ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="mdi mdi-trophy"></i> View Standings
                                    </a>

                                    <!-- Settings -->
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#meetHeaderModal">
                                        <i class="mdi mdi-cog-outline"></i> Meet Header
                                    </button>

                                </div>

                            </div>
                            <!-- Updated gradient: green + blue, no purple -->
                            <hr style="border:0;height:2px;background:linear-gradient(90deg,#059669 0%,#0ea5e9 50%,#22c55e 100%);border-radius:999px;margin-top:4px;">
                        </div>
                    </div>

                    <!-- Alerts -->
                    <div class="row">
                        <div class="col-lg-8">
                            <?php if ($this->session->flashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show">
                                    <?= $this->session->flashdata('success'); ?>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($validation_list)): ?>
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <ul class="mb-0 pl-3" style="list-style: disc;">
                                        <?= $validation_list; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Main content -->
                    <div class="row">
                        <!-- Recent winners preview -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h5 class="card-title mb-0">Recent winners</h5>
                                            <small class="text-muted">Latest entries saved to the standings.</small>
                                        </div>

                                    </div>

                                    <?php $recent_winners = isset($recent_winners) ? $recent_winners : array(); ?>
                                    <?php if (!empty($recent_winners)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Event</th>
                                                        <th>Group</th>
                                                        <th>Category</th>
                                                        <th>Winner</th>
                                                        <th class="text-center">Medal</th>
                                                        <th>Municipality</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($recent_winners as $row): ?>
                                                        <?php
                                                        $badgeClass = 'badge-silver';
                                                        if ($row->medal === 'Gold') {
                                                            $badgeClass = 'badge-gold';
                                                        } elseif ($row->medal === 'Bronze') {
                                                            $badgeClass = 'badge-bronze';
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row->event_name, ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td><?= htmlspecialchars($row->event_group, ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td><?= htmlspecialchars($row->category ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td><?= htmlspecialchars($row->full_name, ENT_QUOTES, 'UTF-8'); ?></td>
                                                            <td class="text-center">
                                                                <span class="badge badge-medal <?= $badgeClass; ?>"><?= htmlspecialchars($row->medal, ENT_QUOTES, 'UTF-8'); ?></span>
                                                            </td>
                                                            <td><?= htmlspecialchars($row->municipality, ENT_QUOTES, 'UTF-8'); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center text-muted py-4">
                                            <i class="mdi mdi-trophy-outline" style="font-size: 1.6rem;"></i>
                                            <p class="mb-0">No entries yet. Click “New Entry” to start encoding.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php include('includes/footer.php'); ?>

        </div>

    </div>

    <?php include('includes/footer_plugins.php'); ?>

    <!-- Meet Header Modal -->
    <?php
    $meet_title = isset($meet->meet_title) ? $meet->meet_title : 'Provincial Meet';
    $meet_year  = isset($meet->meet_year)  ? $meet->meet_year  : date('Y');
    $subtitle   = isset($meet->subtitle)
        ? $meet->subtitle
        : 'Live results encoded by authorized officials. Read-only landing page for public viewing.';
    ?>
    <div class="modal fade" id="meetHeaderModal" tabindex="-1" role="dialog" aria-labelledby="meetHeaderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="meetHeaderModalLabel"><i class="mdi mdi-cog-outline"></i> Meet Header Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?= form_open('provincial/update_meet_settings'); ?>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label>Meet Title</label>
                        <input type="text" name="meet_title" class="form-control form-control-sm"
                            value="<?= htmlspecialchars($meet_title, ENT_QUOTES, 'UTF-8'); ?>"
                            required>
                    </div>

                    <div class="form-group mb-2">
                        <label>Year</label>
                        <input type="text" name="meet_year" class="form-control form-control-sm"
                            placeholder="e.x: 2025 or 2025–2026"
                            value="<?= htmlspecialchars($meet_year, ENT_QUOTES, 'UTF-8'); ?>"
                            required>
                    </div>

                    <div class="form-group mb-2">
                        <label>Subtitle (optional)</label>
                        <textarea name="subtitle" class="form-control form-control-sm" rows="2"
                            placeholder="Shown under the title on the landing page"><?= htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Header</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>

    <!-- Add Winner Modal -->
    <div class="modal fade" id="winnerModal" tabindex="-1" role="dialog" aria-labelledby="winnerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="winnerModalLabel"><i class="mdi mdi-plus-circle-outline"></i> New Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?= form_open('provincial/admin'); ?>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control"
                                value="<?= set_value('first_name'); ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Middle Name</label>
                            <input type="text" name="middle_name" class="form-control"
                                value="<?= set_value('middle_name'); ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control"
                                value="<?= set_value('last_name'); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Event <span class="text-danger">*</span></label>
                        <input type="text" name="event_name" class="form-control"
                            placeholder="e.x: 100m Dash (Boys)"
                            value="<?= set_value('event_name'); ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Group <span class="text-danger">*</span></label>
                            <select name="event_group" class="form-control" required>
                                <option value="">-- Select Group --</option>
                                <option value="Elementary" <?= set_select('event_group', 'Elementary'); ?>>Elementary</option>
                                <option value="Secondary" <?= set_select('event_group', 'Secondary'); ?>>Secondary</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Category</label>
                            <input type="text" name="category" class="form-control"
                                placeholder="e.x: Boys Team"
                                value="<?= set_value('category'); ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Medal <span class="text-danger">*</span></label>
                            <select name="medal" class="form-control" required>
                                <option value="">-- Select Medal --</option>
                                <option value="Gold" <?= set_select('medal', 'Gold');   ?>>Gold</option>
                                <option value="Silver" <?= set_select('medal', 'Silver'); ?>>Silver</option>
                                <option value="Bronze" <?= set_select('medal', 'Bronze'); ?>>Bronze</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Municipality <span class="text-danger">*</span></label>
                        <input type="text" name="municipality" class="form-control"
                            placeholder="Input Address"
                            value="<?= set_value('municipality'); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit" value="1" class="btn btn-success">
                        <i class="mdi mdi-content-save-outline"></i> Save Winner
                    </button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            <?php if (!empty($validation_list)): ?>
                $('#winnerModal').modal('show');
            <?php endif; ?>
        });
    </script>

</body>

</html>