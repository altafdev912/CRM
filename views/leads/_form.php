<?php

$lead = $lead ?? [];
?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?>
    </div>
<?php endif; ?>

<form method="POST" action="<?= $action ?>" novalidate>

    <div class="row g-3">
        <!-- Name -->
        <div class="col-md-6">
            <label class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                   placeholder="Jane Doe"
                   value="<?= htmlspecialchars($lead['name'] ?? $_POST['name'] ?? '') ?>"
                   required>
        </div>

        <!-- Email -->
        <div class="col-md-6">
            <label class="form-label fw-medium">Email</label>
            <input type="email" name="email" class="form-control"
                   placeholder="jane@example.com"
                   value="<?= htmlspecialchars($lead['email'] ?? $_POST['email'] ?? '') ?>">
        </div>

        <!-- Phone -->
        <div class="col-md-4">
            <label class="form-label fw-medium">Phone</label>
            <input type="tel" name="phone" class="form-control"
                   placeholder="+1-555-0100"
                   value="<?= htmlspecialchars($lead['phone'] ?? $_POST['phone'] ?? '') ?>">
        </div>

        <!-- Source -->
        <div class="col-md-4">
            <label class="form-label fw-medium">Source</label>
            <input type="text" name="source" class="form-control"
                   placeholder="Website, Referral, Social…"
                   value="<?= htmlspecialchars($lead['source'] ?? $_POST['source'] ?? '') ?>">
        </div>

        <!-- Status -->
        <div class="col-md-4">
            <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select" required>
                <?php
                $current = $lead['status'] ?? $_POST['status'] ?? 'New';
                foreach (\Lead::STATUSES as $s):
                ?>
                    <option value="<?= $s ?>" <?= $current === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Notes -->
        <div class="col-12">
            <label class="form-label fw-medium">Notes</label>
            <textarea name="notes" class="form-control" rows="4"
                      placeholder="Any relevant notes about this lead…"><?= htmlspecialchars($lead['notes'] ?? $_POST['notes'] ?? '') ?></textarea>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i><?= $submitLabel ?? 'Save' ?>
        </button>
        <a href="/crm/leads" class="btn btn-outline-secondary">
            <i class="bi bi-x-lg me-1"></i>Cancel
        </a>
    </div>
</form>
