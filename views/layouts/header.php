<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'CRM System') ?></title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/crm/assets/css/app.css">
</head>
<body>

<!-- Sidebar + Top-nav wrapper -->
<div class="d-flex" id="app-wrapper">

    <!-- ── Sidebar ──────────────────────────────────────── -->
    <nav id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-white">
        <a href="/crm/dashboard" class="d-flex align-items-center mb-3 text-white text-decoration-none brand-link">
            <i class="bi bi-diagram-3-fill me-2 fs-4"></i>
            <span class="fs-5 fw-semibold">CRM </span>
        </a>
        <hr>

        <ul class="nav nav-pills flex-column mb-auto gap-1">
            <li class="nav-item">
                <a href="/crm/dashboard"
                   class="nav-link text-white <?= (strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false) ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="/crm/leads"
                   class="nav-link text-white <?= (strpos($_SERVER['REQUEST_URI'], '/leads') !== false) ? 'active' : '' ?>">
                    <i class="bi bi-people-fill me-2"></i> Leads
                </a>
            </li>
            <?php if (($_SESSION['user_role'] ?? '') === 'Admin'): ?>
            <li>
                <a href="/crm/users"
                   class="nav-link text-white <?= (strpos($_SERVER['REQUEST_URI'], '/users') !== false) ? 'active' : '' ?>">
                    <i class="bi bi-person-gear me-2"></i> Users
                </a>
            </li>
            <?php endif; ?>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
               data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar-sm me-2">
                    <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                </div>
                <div class="lh-sm">
                    <strong class="d-block text-truncate" style="max-width:130px;">
                        <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>
                    </strong>
                    <small class="text-muted"><?= htmlspecialchars($_SESSION['user_role'] ?? '') ?></small>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item text-danger" href="/crm/logout">
                    <i class="bi bi-box-arrow-left me-1"></i> Logout
                </a></li>
            </ul>
        </div>
    </nav>
    <!-- /Sidebar -->

    <!-- ── Main content area ────────────────────────────── -->
    <div id="main-content" class="flex-grow-1 d-flex flex-column">

        <!-- Top bar -->
        <header class="topbar d-flex align-items-center px-4 py-2 border-bottom bg-white shadow-sm">
            <button id="sidebarToggle" class="btn btn-sm btn-outline-secondary me-3">
                <i class="bi bi-list"></i>
            </button>
            <nav aria-label="breadcrumb" class="mb-0">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/crm/dashboard">Home</a></li>
                    <?php if (!empty($breadcrumb)): ?>
                        <?php foreach ($breadcrumb as $label => $url): ?>
                            <?php if ($url): ?>
                                <li class="breadcrumb-item"><a href="<?= $url ?>"><?= htmlspecialchars($label) ?></a></li>
                            <?php else: ?>
                                <li class="breadcrumb-item active"><?= htmlspecialchars($label) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ol>
            </nav>
        </header>

        <main class="flex-grow-1 p-4">
