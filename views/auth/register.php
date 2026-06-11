<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Company – CRM </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/crm/assets/css/app.css">
</head>
<body class="auth-bg">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-lg border-0">

                <div class="card-header auth-header text-center py-4 border-0">
                    <i class="bi bi-building-add fs-1 text-white"></i>
                    <h4 class="text-white fw-bold mt-2 mb-0">Create Your CRM Account</h4>
                    <small class="text-white-50">Register your company and get started</small>
                </div>

                <div class="card-body p-4">

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/crm/register" novalidate>

                        <h6 class="text-muted text-uppercase fw-bold small mb-3">
                            <i class="bi bi-building me-1"></i>Company Information
                        </h6>

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-medium">Company Name <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" class="form-control"
                                       placeholder="Acme Corporation"
                                       value="<?= htmlspecialchars($_POST['company_name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Phone</label>
                                <input type="tel" name="phone" class="form-control"
                                       placeholder="+1-555-0100"
                                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Company Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="admin@company.com"
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                            <div class="form-text">This will also be your admin login email.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Address</label>
                            <textarea name="address" class="form-control" rows="2"
                                      placeholder="123 Main St, City, Country"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                        </div>

                        <hr>
                        <h6 class="text-muted text-uppercase fw-bold small mb-3">
                            <i class="bi bi-person-badge me-1"></i>Admin Account
                        </h6>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Your Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="admin_name" class="form-control"
                                   placeholder="John Smith"
                                   value="<?= htmlspecialchars($_POST['admin_name'] ?? '') ?>" required>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" id="regPassword"
                                           class="form-control" placeholder="Min. 8 characters" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('regPassword', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="confirm_password"
                                       class="form-control" placeholder="Repeat password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-rocket-takeoff me-2"></i>Create Account
                        </button>
                    </form>
                </div>

                <div class="card-footer bg-transparent text-center py-3">
                    <span class="text-muted small">Already have an account?</span>
                    <a href="/crm/login" class="small fw-semibold ms-1">Sign in</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword(id, btn) {
    const inp = document.getElementById(id);
    const icon = btn.querySelector('i');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
}
</script>
</body>
</html>
