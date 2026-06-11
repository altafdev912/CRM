<?php
$pageTitle  = 'Edit Lead – CRM ';
$breadcrumb = ['Leads' => '/crm/leads', 'Edit Lead' => null];
require ROOT_PATH . '/views/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="bi bi-pencil-square me-2 text-primary"></i>
        Edit Lead: <?= htmlspecialchars($lead['name']) ?>
    </h4>
    <span class="text-muted small">ID #<?= $lead['id'] ?></span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php
        $action      = "/crm/leads/{$lead['id']}/edit";
        $submitLabel = 'Update Lead';
        require ROOT_PATH . '/views/leads/_form.php';
        ?>
    </div>
</div>

<?php require ROOT_PATH . '/views/layouts/footer.php'; ?>
