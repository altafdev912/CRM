<?php
$pageTitle  = 'Users – CRM ';
$breadcrumb = ['Users' => null];
require ROOT_PATH . '/views/layouts/header.php';
?>

<?php if (!empty($flash_success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($flash_success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (!empty($flash_error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $flash_error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Team Users</h4>
        <small class="text-muted"><?= count($users) ?> members</small>
    </div>
    <a href="/crm/users/create" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i>Add User
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if (empty($users)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-people fs-1 d-block mb-3"></i>
                <h5>No users yet</h5>
                <a href="/crm/users/create" class="btn btn-primary mt-2">
                    <i class="bi bi-person-plus me-1"></i>Add First User
                </a>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td class="text-muted small"><?= $u['id'] ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm flex-shrink-0">
                                    <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                </div>
                                <span class="fw-medium"><?= htmlspecialchars($u['name']) ?></span>
                                <?php if ((int)$u['id'] === (int)$_SESSION['user_id']): ?>
                                    <span class="badge bg-light text-secondary border">You</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="badge <?= $u['role'] === 'Admin' ? 'bg-danger' : 'bg-secondary' ?>">
                                <?= $u['role'] ?>
                            </span>
                        </td>
                        <td><small class="text-muted"><?= date('M d, Y', strtotime($u['created_at'])) ?></small></td>
                        <td class="text-end">
                            <a href="/crm/users/<?= $u['id'] ?>/edit"
                               class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    title="Delete"
                                    onclick="confirmDelete(<?= $u['id'] ?>, '<?= htmlspecialchars(addslashes($u['name'])) ?>')">
                                <i class="bi bi-trash3"></i>
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger"><i class="bi bi-trash3-fill me-2"></i>Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Remove user <strong id="deleteUserName"></strong>? This cannot be undone.
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <a id="deleteConfirmBtn" href="#" class="btn btn-danger">
                    <i class="bi bi-trash3 me-1"></i>Delete
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('deleteConfirmBtn').href = '/crm/users/' + id + '/delete';
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?php require ROOT_PATH . '/views/layouts/footer.php'; ?>
