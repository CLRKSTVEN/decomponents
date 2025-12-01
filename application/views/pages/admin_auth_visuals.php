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

                    <div class="row align-items-center mb-3">
                        <div class="col">
                            <h4 class="page-title mb-0">Auth Visuals</h4>
                            <p class="text-muted mb-0">Manage the hero content and imagery shown on the Login and Signup screens.</p>
                        </div>
                        <div class="col-auto">
                            <a href="<?= site_url('Ezshop/auth_visuals'); ?>" class="btn btn-light">
                                <i class="bi bi-plus-circle"></i> New visual
                            </a>
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

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('success'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php
                    $featureList = function ($visual) {
                        if (!$visual) {
                            return [];
                        }
                        $keys = ['feature_one', 'feature_two', 'feature_three', 'feature_four'];
                        $items = [];
                        foreach ($keys as $key) {
                            if (!empty($visual[$key])) {
                                $items[] = $visual[$key];
                            }
                        }
                        return $items;
                    };
                    $editing = $edit_visual ?? null;
                    ?>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm mr-2">
                                            <span class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                <i class="bi bi-brush"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-0"><?= $editing ? 'Edit visual #' . (int)$editing['id'] : 'Create a new visual'; ?></h5>
                                            <small class="text-muted">Mark as active to publish it. Only one active visual per page is used.</small>
                                        </div>
                                    </div>

                                    <form action="<?= site_url('Ezshop/save_auth_visual'); ?>" method="post" enctype="multipart/form-data">
                                        <?php if ($editing): ?>
                                            <input type="hidden" name="id" value="<?= (int)$editing['id']; ?>">
                                        <?php endif; ?>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Screen</label>
                                                <select name="page" class="form-control" required>
                                                    <?php
                                                    $selectedPage = $editing['page'] ?? 'login';
                                                    ?>
                                                    <option value="login" <?= ($selectedPage === 'login') ? 'selected' : ''; ?>>Login</option>
                                                    <option value="signup" <?= ($selectedPage === 'signup') ? 'selected' : ''; ?>>Signup</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Badge (eyebrow)</label>
                                                <input type="text" name="badge" class="form-control" value="<?= htmlspecialchars($editing['badge'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g., Editorial Picks">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Headline <span class="text-danger">*</span></label>
                                            <input type="text" name="headline" class="form-control" value="<?= htmlspecialchars($editing['headline'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required placeholder="Headline for the hero area">
                                        </div>

                                        <div class="form-group">
                                            <label>Subheadline</label>
                                            <textarea name="subheadline" class="form-control" rows="2" placeholder="Short supporting copy"><?= htmlspecialchars($editing['subheadline'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Feature 1</label>
                                                <input type="text" name="feature_one" class="form-control" value="<?= htmlspecialchars($editing['feature_one'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Feature 2</label>
                                                <input type="text" name="feature_two" class="form-control" value="<?= htmlspecialchars($editing['feature_two'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Feature 3</label>
                                                <input type="text" name="feature_three" class="form-control" value="<?= htmlspecialchars($editing['feature_three'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Feature 4</label>
                                                <input type="text" name="feature_four" class="form-control" value="<?= htmlspecialchars($editing['feature_four'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Use an existing image path</label>
                                            <input type="text" name="image_path_manual" class="form-control" value="<?= htmlspecialchars($editing['image_path'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g., Products/advertise2.jpg or upload below">
                                            <small class="text-muted">You can paste a relative path from your server, or upload a new image file.</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Upload new hero image</label>
                                            <input type="file" name="image_path" class="form-control-file" accept="image/*">
                                            <small class="text-muted">JPEG, PNG, GIF, or WEBP up to 4MB. Uploading overrides the path above.</small>
                                        </div>

                                        <div class="form-group form-check">
                                            <?php
                                            $isActive = isset($editing['is_active']) ? (int)$editing['is_active'] === 1 : true;
                                            ?>
                                            <input type="checkbox" name="is_active" class="form-check-input" id="authVisualActive" value="1" <?= $isActive ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="authVisualActive">Set as active for this screen</label>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <button type="submit" class="btn btn-primary mr-2">
                                                <i class="bi bi-save"></i> Save visual
                                            </button>
                                            <a href="<?= site_url('Ezshop/auth_visuals'); ?>" class="btn btn-outline-secondary">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-lightning-charge text-primary mr-2"></i>
                                        <div>
                                            <h5 class="card-title mb-0">Currently active</h5>
                                            <small class="text-muted">These are the visuals visitors see on the auth screens.</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="border rounded p-3 h-100">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge badge-primary mr-2">Login</span>
                                                    <small class="text-muted"><?= htmlspecialchars($active_login['badge'] ?? 'Login', ENT_QUOTES, 'UTF-8'); ?></small>
                                                </div>
                                                <h6 class="mb-1"><?= htmlspecialchars($active_login['headline'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h6>
                                                <small class="text-muted d-block mb-2"><?= htmlspecialchars($active_login['subheadline'] ?? '', ENT_QUOTES, 'UTF-8'); ?></small>
                                                <?php $loginFeatures = $featureList($active_login); ?>
                                                <?php if (!empty($loginFeatures)): ?>
                                                    <div class="d-flex flex-wrap" style="gap: 6px;">
                                                        <?php foreach ($loginFeatures as $feat): ?>
                                                            <span class="badge badge-light text-muted mr-1 mb-1"><?= htmlspecialchars($feat, ENT_QUOTES, 'UTF-8'); ?></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="mt-2 rounded overflow-hidden position-relative" style="height:140px;">
                                                    <?php $loginImg = base_url($active_login['image_path'] ?? 'Products/advertise2.jpg'); ?>
                                                    <img src="<?= $loginImg; ?>" alt="Login visual" class="w-100 h-100" style="object-fit: cover;" onerror="this.src='<?= base_url('Products/clothes-3.jpg'); ?>';">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mt-3 mt-sm-0">
                                            <div class="border rounded p-3 h-100">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge badge-success mr-2">Signup</span>
                                                    <small class="text-muted"><?= htmlspecialchars($active_signup['badge'] ?? 'Signup', ENT_QUOTES, 'UTF-8'); ?></small>
                                                </div>
                                                <h6 class="mb-1"><?= htmlspecialchars($active_signup['headline'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h6>
                                                <small class="text-muted d-block mb-2"><?= htmlspecialchars($active_signup['subheadline'] ?? '', ENT_QUOTES, 'UTF-8'); ?></small>
                                                <?php $signupFeatures = $featureList($active_signup); ?>
                                                <?php if (!empty($signupFeatures)): ?>
                                                    <div class="d-flex flex-wrap" style="gap: 6px;">
                                                        <?php foreach ($signupFeatures as $feat): ?>
                                                            <span class="badge badge-light text-muted mr-1 mb-1"><?= htmlspecialchars($feat, ENT_QUOTES, 'UTF-8'); ?></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="mt-2 rounded overflow-hidden position-relative" style="height:140px;">
                                                    <?php $signupImg = base_url($active_signup['image_path'] ?? 'Products/advertise3.jpg'); ?>
                                                    <img src="<?= $signupImg; ?>" alt="Signup visual" class="w-100 h-100" style="object-fit: cover;" onerror="this.src='<?= base_url('Products/clothes-3.jpg'); ?>';">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-collection mr-2 text-secondary"></i>
                                        <h5 class="card-title mb-0">All visuals</h5>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Screen</th>
                                                    <th>Headline</th>
                                                    <th>Image</th>
                                                    <th>Status</th>
                                                    <th>Updated</th>
                                                    <th class="text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($visuals)): ?>
                                                    <?php foreach ($visuals as $visual): ?>
                                                        <tr>
                                                            <td><?= (int)$visual['id']; ?></td>
                                                            <td><span class="badge badge-<?= $visual['page'] === 'login' ? 'primary' : 'success'; ?>"><?= ucfirst($visual['page']); ?></span></td>
                                                            <td>
                                                                <div class="font-weight-bold"><?= htmlspecialchars($visual['headline'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                                                                <small class="text-muted d-block text-truncate" style="max-width:220px;"><?= htmlspecialchars($visual['subheadline'] ?? '', ENT_QUOTES, 'UTF-8'); ?></small>
                                                            </td>
                                                            <td>
                                                                <?php $thumb = !empty($visual['image_path']) ? base_url($visual['image_path']) : ''; ?>
                                                                <?php if ($thumb): ?>
                                                                    <img src="<?= $thumb; ?>" alt="Visual" style="height:46px; width:80px; object-fit: cover;" onerror="this.style.visibility='hidden';">
                                                                <?php else: ?>
                                                                    <span class="text-muted">—</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if (!empty($visual['is_active'])): ?>
                                                                    <span class="badge badge-success">Active</span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-secondary">Draft</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <small class="text-muted"><?= htmlspecialchars($visual['updated_at'] ?? $visual['created_at'] ?? '', ENT_QUOTES, 'UTF-8'); ?></small>
                                                            </td>
                                                            <td class="text-right">
                                                                <a href="<?= site_url('Ezshop/auth_visuals/' . $visual['id']); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                                                <a href="<?= site_url('Ezshop/delete_auth_visual/' . $visual['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this visual?');">Delete</a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted py-4">No visuals saved yet. Create one using the form.</td>
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
    </div>

    <?php include(APPPATH . 'views/includes/footer_plugins.php'); ?>
</body>

</html>
