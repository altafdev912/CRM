<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – CRM </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/crm/assets/css/app.css">
</head>
<body class="auth-bg">

<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="auth-card card shadow-lg border-0 p-0" style="width:100%;max-width:440px;">

        <!-- Card header -->
        <div class="card-header auth-header text-center py-4 border-0">
            <i class="bi bi-diagram-3-fill fs-1 text-white"></i>
            <h4 class="text-white fw-bold mt-2 mb-0">CRM </h4>
            <small class="text-white-50">Multi-Tenant CRM Platform</small>
        </div>

        <div class="card-body p-4">
            <h5 class="fw-semibold mb-4">Sign in to your account</h5>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php
            $success = $_SESSION['flash_success'] ?? null;
            unset($_SESSION['flash_success']);
            if ($success): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/crm/login" novalidate>
                <div class="mb-3">
                    <label class="form-label fw-medium">Email address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control"
                               placeholder="you@company.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="loginPassword"
                               class="form-control" placeholder="••••••••" required>
                        <button class="btn btn-outline-secondary" type="button"
                                onclick="togglePassword('loginPassword', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                </button>
            </form>
        </div>

        <div class="card-footer bg-transparent text-center py-3">
            <span class="text-muted small">Don't have an account?</span>
            <a href="/crm/register" class="small fw-semibold ms-1">Register your company</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword(id, btn) {
    const inp = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        inp.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
</body>
</html>
