<?php
$pageTitle  = 'New Lead – CRM ';
$breadcrumb = ['Leads' => '/crm/leads', 'New Lead' => null];
require ROOT_PATH . '/views/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-person-plus-fill me-2 text-primary"></i>New Lead</h4>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php
        $action      = '/crm/leads';
        $submitLabel = 'Create Lead';
        $lead        = [];
        require ROOT_PATH . '/views/leads/_form.php';
        ?>
    </div>
</div>

<?php require ROOT_PATH . '/views/layouts/footer.php'; ?>
