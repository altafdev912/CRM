<?php
$pageTitle  = 'Add User – CRM ';
$breadcrumb = ['Users' => '/crm/users', 'Add User' => null];
require ROOT_PATH . '/views/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Add User</h4>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php
        $action      = '/crm/users';
        $submitLabel = 'Create User';
        $isEdit      = false;
        $user        = [];
        require ROOT_PATH . '/views/users/_form.php';
        ?>
    </div>
</div>

<?php require ROOT_PATH . '/views/layouts/footer.php'; ?>
