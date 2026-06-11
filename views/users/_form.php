<?php
/**
 * Shared user form partial.
 * Expects: $user (array|null), $action (string), $submitLabel (string), $isEdit (bool)
 */
$user   = $user   ?? [];
$isEdit = $isEdit ?? false;
?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?>
    </div>
<?php endif; ?>

<form method="POST" action="<?= $action ?>" novalidate>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                   placeholder="Jane Smith"
                   value="<?= htmlspecialchars($user['name'] ?? $_POST['name'] ?? '') ?>"
                   required>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-medium">Email Address <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control"
                   placeholder="jane@company.com"
                   value="<?= htmlspecialchars($user['email'] ?? $_POST['email'] ?? '') ?>"
                   required>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-medium">Role <span class="text-danger">*</span></label>
            <select name="role" class="form-select">
                <?php
                $currentRole = $user['role'] ?? $_POST['role'] ?? 'User';
                foreach (['Admin', 'User'] as $r):
                ?>
                    <option value="<?= $r ?>" <?= $currentRole === $r ? 'selected' : '' ?>><?= $r ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-medium">
                Password <?= $isEdit ? '' : '<span class="text-danger">*</span>' ?>
            </label>
            <div class="input-group">
                <input type="password" name="password" id="userPassword" class="form-control"
                       placeholder="<?= $isEdit ? 'Leave blank to keep current' : 'Min. 8 characters' ?>"
                       <?= $isEdit ? '' : 'required' ?>>
                <button class="btn btn-outline-secondary" type="button"
                        onclick="togglePassword('userPassword', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-medium">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control"
                   placeholder="Repeat password">
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i><?= $submitLabel ?? 'Save' ?>
        </button>
        <a href="/crm/users" class="btn btn-outline-secondary">
            <i class="bi bi-x-lg me-1"></i>Cancel
        </a>
    </div>
</form>

<script>
function togglePassword(id, btn) {
    const inp  = document.getElementById(id);
    const icon = btn.querySelector('i');
    inp.type   = inp.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
}
</script>
