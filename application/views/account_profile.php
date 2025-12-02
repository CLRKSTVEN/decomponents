<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>
    <div id="wrapper">
<?php include('includes/top-nav-bar.php'); ?>
<?php include('includes/sidebar.php'); ?>

        <?php
        $avatarPath = $user->profile_picture ?? '';
        if ($avatarPath) {
            $avatarUrl = preg_match('#^https?://#i', $avatarPath)
                ? $avatarPath
                : base_url(ltrim($avatarPath, '/'));
        } else {
            $avatarUrl = base_url('upload/profile/default.png');
        }
        ?>

        <div class="content-page">
            <div class="content">
                <!-- Normal container with padding -->
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap">
                                <div class="mb-2">
                                    <h4 class="page-title mb-0">Account Settings – Profile</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Centered, narrower card -->
                    <div class="row mt-3 justify-content-center">
                        <div class="col-12 col-md-10 col-lg-7 col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Profile Details</h5>

                                    <?php if ($this->session->flashdata('success')): ?>
                                        <div class="alert alert-success alert-dismissible fade show">
                                            <?= $this->session->flashdata('success'); ?>
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($this->session->flashdata('danger')): ?>
                                        <div class="alert alert-danger alert-dismissible fade show">
                                            <?= $this->session->flashdata('danger'); ?>
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        </div>
                                    <?php endif; ?>

                                    <?= validation_errors(
                                        '<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert">&times;</button>',
                                        '</div>'
                                    ); ?>

                                    <form action="<?= site_url('Decomponents/profile_update'); ?>" method="post" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>First Name</label>
                                            <input type="text" name="first_name" class="form-control"
                                                value="<?= set_value('first_name', $user->FirstName ?? ''); ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Last Name</label>
                                            <input type="text" name="last_name" class="form-control"
                                                value="<?= set_value('last_name', $user->LastName ?? ''); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Middle Name</label>
                                            <input type="text" name="middle_name" class="form-control"
                                                value="<?= set_value('middle_name', $user->MiddleName ?? ''); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="<?= set_value('email', $user->email ?? ''); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Shipping / Contact Address</label>
                                        <textarea name="address" rows="3" class="form-control" placeholder="Enter your preferred shipping address"><?= set_value('address', $address ?? ''); ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Avatar</label>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= $avatarUrl; ?>"
                                                alt="avatar"
                                                class="rounded-circle mr-3"
                                                width="60" height="60"
                                                onerror="this.src='<?= base_url('upload/profile/default.png'); ?>'">
                                            <input type="file" name="profile_picture" class="form-control-file" accept="image/*">
                                        </div>
                                        <small class="text-muted">JPG/PNG up to 2MB.</small>
                                    </div>

                                    <button type="submit" name="submit" value="1" class="btn btn-primary">
                                        Save Changes
                                    </button>
                                    </form>
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
</body>

</html>
