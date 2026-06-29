<?php
$pageTitle  = 'Dashboard – CRM ';
$breadcrumb = ['Dashboard' => null];
require ROOT_PATH . '/views/layouts/header.php';




// Status badge helper
function statusBadge(string $s): string {
    $map = [
        'New'       => 'primary',
        'Contacted' => 'info',
        'Qualified' => 'warning',
        'Converted' => 'success',
        'Lost'      => 'danger',
    ];
    $c = $map[$s] ?? 'secondary';
    return "<span class=\"badge bg-$c\">$s</span>";
}
?>
<!-- checkinig -->

<!-- another -->
 <!-- another one -->

<!-- Stat cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary-subtle text-primary rounded-3 p-3 fs-3">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-number"><?= number_format($totalLeads) ?></div>
                    <div class="stat-label">Total Leads</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success-subtle text-success rounded-3 p-3 fs-3">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <div>
                    <div class="stat-number"><?= number_format($totalUsers) ?></div>
                    <div class="stat-label">Team Members</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning-subtle text-warning rounded-3 p-3 fs-3">
                    <i class="bi bi-activity"></i>
                </div>
                <div>
                    <div class="stat-number"><?= number_format($totalActivities) ?></div>
                    <div class="stat-label">Total Activities</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts + Recent Leads -->
<div class="row g-4 mb-4">

    <!-- Donut chart -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pt-3 pb-0">
                <h6 class="fw-semibold mb-0"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Leads by Status</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="statusChart" style="max-height:240px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent leads table -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pt-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Leads</h6>
                <a href="/crm/leads" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentLeads)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>No leads yet.
                        <a href="/crm/leads/create" class="d-block mt-2">Add your first lead</a>
                    </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($recentLeads as $lead): ?>
                            <tr>
                                <td>
                                    <div class="fw-medium"><?= htmlspecialchars($lead['name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($lead['email'] ?? '') ?></small>
                                </td>
                                <td><?= htmlspecialchars($lead['source'] ?? '—') ?></td>
                                <td><?= statusBadge($lead['status']) ?></td>
                                <td><small class="text-muted"><?= date('M d, Y', strtotime($lead['created_at'])) ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Activity Log -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0 pt-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0"><i class="bi bi-journal-text me-2 text-primary"></i>Recent Activity</h6>
    </div>
    <div class="card-body p-0">
        <?php if (empty($recentLogs)): ?>
            <div class="text-center text-muted py-4">No activity yet.</div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($recentLogs as $log): ?>
                    <tr>
                        <td><small class="fw-medium"><?= htmlspecialchars($log['user_name'] ?? 'System') ?></small></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($log['action']) ?></span></td>
                        <td><small class="text-muted"><?= htmlspecialchars($log['description']) ?></small></td>
                        <td><small class="text-muted"><?= date('M d, g:i a', strtotime($log['created_at'])) ?></small></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Chart data -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_keys($statusCounts)) ?>,
            datasets: [{
                data: <?= json_encode(array_values($statusCounts)) ?>,
                backgroundColor: ['#0d6efd','#0dcaf0','#ffc107','#198754','#dc3545'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 16, font: { size: 12 } } }
            },
            cutout: '65%',
        }
    });
});
</script>

<?php require ROOT_PATH . '/views/layouts/footer.php'; ?>
