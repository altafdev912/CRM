<?php
$pageTitle  = 'Leads – CRM ';
$breadcrumb = ['Leads' => null];
require ROOT_PATH . '/views/layouts/header.php';

$statusColors = [
    'New'       => 'primary',
    'Contacted' => 'info',
    'Qualified' => 'warning',
    'Converted' => 'success',
    'Lost'      => 'danger',
];
?>

<!-- Flash messages -->
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

<!-- Header row -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Leads</h4>
        <small class="text-muted"><?= count($leads) ?> records</small>
    </div>
    <a href="/crm/leads/create" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>New Lead
    </a>
</div>

<!-- Status filter pills -->
<div class="mb-3 d-flex flex-wrap gap-2">
    <a href="/crm/leads"
       class="btn btn-sm <?= empty($_GET['status']) ? 'btn-dark' : 'btn-outline-secondary' ?>">
       All (<?= array_sum($statusCounts) ?>)
    </a>
    <?php foreach ($statusCounts as $s => $count): ?>
        <a href="/crm/leads?status=<?= urlencode($s) ?>"
           class="btn btn-sm <?= ($_GET['status'] ?? '') === $s ? "btn-{$statusColors[$s]}" : "btn-outline-{$statusColors[$s]}" ?>">
            <?= $s ?> (<?= $count ?>)
        </a>
    <?php endforeach; ?>
</div>

<!-- Leads table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if (empty($leads)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-people fs-1 d-block mb-3 text-secondary"></i>
                <h5>No leads found</h5>
                <p class="mb-3">Start by adding your first lead.</p>
                <a href="/crm/leads/create" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Add Lead
                </a>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="leadsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($leads as $i => $lead): ?>
                    <tr>
                        <td class="text-muted small"><?= $lead['id'] ?></td>
                        <td>
                            <div class="fw-medium"><?= htmlspecialchars($lead['name']) ?></div>
                            <?php if (!empty($lead['notes'])): ?>
                                <small class="text-muted text-truncate d-inline-block" style="max-width:200px;">
                                    <?= htmlspecialchars($lead['notes']) ?>
                                </small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($lead['email'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($lead['phone'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($lead['source'] ?? '—') ?></td>
                        <td>
                            <span class="badge bg-<?= $statusColors[$lead['status']] ?>">
                                <?= $lead['status'] ?>
                            </span>
                        </td>
                        <td><small class="text-muted"><?= date('M d, Y', strtotime($lead['created_at'])) ?></small></td>
                        <td class="text-end">
                            <a href="/crm/leads/<?= $lead['id'] ?>/edit"
                               class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    title="Delete"
                                    onclick="confirmDelete(<?= $lead['id'] ?>, '<?= htmlspecialchars(addslashes($lead['name'])) ?>')">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete confirmation modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger"><i class="bi bi-trash3-fill me-2"></i>Delete Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete lead <strong id="deleteLeadName"></strong>?
                This action cannot be undone.
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
    document.getElementById('deleteLeadName').textContent = name;
    document.getElementById('deleteConfirmBtn').href = '/crm/leads/' + id + '/delete';
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?php require ROOT_PATH . '/views/layouts/footer.php'; ?>
