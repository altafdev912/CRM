<?php
$pageTitle  = 'Edit User – CRM ';
$breadcrumb = ['Users' => '/crm/users', 'Edit User' => null];
require ROOT_PATH . '/views/layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="bi bi-person-gear me-2 text-primary"></i>
        Edit User: <?= htmlspecialchars($user['name']) ?>
    </h4>
    <span class="text-muted small">ID #<?= $user['id'] ?></span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php
        $action      = "/crm/users/{$user['id']}/edit";
        $submitLabel = 'Update User';
        $isEdit      = true;
        require ROOT_PATH . '/views/users/_form.php';
        ?>
    </div>
</div>

<?php require ROOT_PATH . '/views/layouts/footer.php'; ?>
