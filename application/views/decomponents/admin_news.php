<!DOCTYPE html>
<html lang="en">

<?php include(APPPATH . 'views/includes/head.php'); ?>

<?php
$resolveImage = function ($path) {
    if (empty($path)) {
        return base_url('Pictures/DeComponents.jpeg');
    }
    if (preg_match('#^https?://#i', $path) || strpos($path, '//') === 0) {
        return $path;
    }
    return base_url(ltrim($path, '/'));
};
$isEditing = !empty($editNews);
?>

<body>
    <div id="wrapper">
        <?php include(APPPATH . 'views/includes/top-nav-bar.php'); ?>
        <?php include(APPPATH . 'views/includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row align-items-center mb-3">
                        <div class="col">
                            <h4 class="page-title mb-0">News</h4>
                            <p class="text-muted mb-0">Publish and manage news displayed on the public site.</p>
                        </div>
                        <?php if ($isEditing): ?>
                            <div class="col-auto">
                                <a href="<?= site_url('Decomponents/news_admin'); ?>" class="btn btn-light">
                                    <i class="bi bi-x-lg"></i> Cancel edit
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

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

                    <div class="row">
                        <div class="col-lg-5">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3"><?= $isEditing ? 'Edit news post' : 'Add news post'; ?></h5>
                                    <form action="<?= site_url('Decomponents/save_news' . ($isEditing ? '/' . (int)$editNews['id'] : '')); ?>" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control" required
                                                value="<?= html_escape($editNews['title'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Excerpt</label>
                                            <textarea name="excerpt" rows="2" class="form-control" placeholder="Short teaser for listing cards"><?= html_escape($editNews['excerpt'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Body</label>
                                            <textarea name="body" rows="6" class="form-control" placeholder="Full article body (plain text or HTML allowed)"><?= html_escape($editNews['body'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Hero Image</label>
                                            <input type="file" name="image" class="form-control-file" accept="image/*">
                                            <small class="text-muted d-block">JPEG, PNG, GIF, or WEBP up to 4MB.</small>
                                            <input type="text" name="image_manual" class="form-control mt-2" placeholder="Or paste an image path/URL"
                                                value="<?= html_escape($editNews['image'] ?? ''); ?>">
                                            <?php if (!empty($editNews['image'])): ?>
                                                <div class="mt-2">
                                                    <small class="text-muted d-block">Current</small>
                                                    <img src="<?= $resolveImage($editNews['image']); ?>" alt="Current image" style="height:80px;border-radius:6px;border:1px solid #e5e7eb;">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input type="checkbox" name="is_published" class="form-check-input" id="isPublished"
                                                <?= !empty($editNews['is_published']) ? 'checked' : ''; ?>>
                                            <label for="isPublished" class="form-check-label">Publish now</label>
                                        </div>
                                        <button type="submit" class="btn btn-primary"><?= $isEditing ? 'Update' : 'Publish'; ?> post</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">All posts</h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="width:70px;">Image</th>
                                                    <th>Title</th>
                                                    <th>Status</th>
                                                    <th style="width:130px;" class="text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($news)): ?>
                                                    <?php foreach ($news as $row): ?>
                                                        <tr>
                                                            <td>
                                                                <img src="<?= $resolveImage($row['image'] ?? ''); ?>"
                                                                    alt="<?= html_escape($row['title'] ?? 'News'); ?>"
                                                                    style="height:50px;width:auto;border-radius:6px;border:1px solid #e5e7eb;object-fit:cover;">
                                                            </td>
                                                            <td>
                                                                <div class="font-weight-bold"><?= html_escape($row['title'] ?? ''); ?></div>
                                                                <small class="text-muted"><?= !empty($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : ''; ?></small>
                                                            </td>
                                                            <td>
                                                                <?php if (!empty($row['is_published'])): ?>
                                                                    <span class="badge badge-success">Published</span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-secondary">Draft</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-right">
                                                                <a href="<?= site_url('Decomponents/news_admin?edit=' . (int)$row['id']); ?>" class="btn btn-sm btn-outline-primary">
                                                                    Edit
                                                                </a>
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-sm btn-outline-danger btn-delete-news"
                                                                    data-id="<?= (int)$row['id']; ?>"
                                                                    data-title="<?= html_escape($row['title'] ?? 'this post'); ?>">
                                                                    Delete
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">No news posts yet.</td>
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
    <script>
        (function() {
            const buttons = document.querySelectorAll('.btn-delete-news');
            if (!buttons.length) return;

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    const title = btn.getAttribute('data-title') || 'this post';
                    if (!id) return;

                    const proceed = () => window.location.href = '<?= site_url('Decomponents/delete_news/'); ?>' + id;

                    if (window.Swal && typeof Swal.fire === 'function') {
                        Swal.fire({
                            title: 'Delete this news post?',
                            text: title,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete',
                            cancelButtonText: 'Cancel',
                            focusCancel: true
                        }).then(result => {
                            if (result.isConfirmed) {
                                proceed();
                            }
                        });
                    } else if (confirm('Delete this news post?')) {
                        proceed();
                    }
                });
            });
        })();
    </script>
</body>

</html>
