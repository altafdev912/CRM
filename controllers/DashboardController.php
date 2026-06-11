<?php
/**
 * Controller: Dashboard
 */

class DashboardController
{
    public function index(): void
    {
        require_once ROOT_PATH . '/models/Lead.php';
        require_once ROOT_PATH . '/models/User.php';
        require_once ROOT_PATH . '/models/ActivityLog.php';

        $companyId = (int)$_SESSION['company_id'];

        $leadModel = new Lead();
        $userModel = new User();
        $logModel  = new ActivityLog();

        $totalLeads      = $leadModel->countByCompany($companyId);
        $totalUsers      = $userModel->countByCompany($companyId);
        $totalActivities = $logModel->countByCompany($companyId);
        $recentLeads     = $leadModel->recent($companyId, 5);
        $recentLogs      = $logModel->recent($companyId, 8);
        $statusCounts    = $leadModel->countByStatus($companyId);

        require ROOT_PATH . '/views/dashboard/index.php';
    }
}
