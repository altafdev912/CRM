<?php
/**
 * Controller: Lead
 * Full CRUD with multi-tenant isolation.
 */

class LeadController
{
    private Lead        $leadModel;
    private ActivityLog $logModel;

    private int    $companyId;
    private int    $userId;
    private string $userName;

    public function __construct()
    {
        require_once ROOT_PATH . '/models/Lead.php';
        require_once ROOT_PATH . '/models/ActivityLog.php';
        $this->leadModel = new Lead();
        $this->logModel  = new ActivityLog();
        $this->companyId = (int)($_SESSION['company_id'] ?? 0);
        $this->userId    = (int)($_SESSION['user_id']    ?? 0);
        $this->userName  = $_SESSION['user_name']        ?? '';
    }

    /** GET /leads */
    public function index(): void
    {
        $statusFilter = $_GET['status'] ?? null;
        $leads = $this->leadModel->allByCompany($this->companyId, $statusFilter);
        $statusCounts = $this->leadModel->countByStatus($this->companyId);
        $flash_success = $_SESSION['flash_success'] ?? null;
        $flash_error   = $_SESSION['flash_error']   ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
        require ROOT_PATH . '/views/leads/index.php';
    }

    /** GET /leads/create */
    public function create(): void
    {
        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);
        require ROOT_PATH . '/views/leads/create.php';
    }

    /** POST /leads */
    public function store(): void
    {
        $data = $this->sanitizeInput();

        if (!$this->validate($data)) {
            header('Location: /crm/leads/create');
            exit;
        }

        $data['company_id'] = $this->companyId;
        $newId = $this->leadModel->create($data);

        $this->logModel->log(
            $this->companyId, $this->userId,
            'Lead Created',
            "{$this->userName} created lead: {$data['name']} (ID $newId)."
        );

        $_SESSION['flash_success'] = 'Lead created successfully.';
        header('Location: /crm/leads');
        exit;
    }

    /** GET /leads/{id}/edit */
    public function edit(int $id): void
    {
        $lead = $this->leadModel->findById($id, $this->companyId);
        if (!$lead) {
            $_SESSION['flash_error'] = 'Lead not found.';
            header('Location: /crm/leads');
            exit;
        }
        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);
        require ROOT_PATH . '/views/leads/edit.php';
    }

    /** POST /leads/{id}/edit */
    public function update(int $id): void
    {
        $lead = $this->leadModel->findById($id, $this->companyId);
        if (!$lead) {
            $_SESSION['flash_error'] = 'Lead not found.';
            header('Location: /crm/leads');
            exit;
        }

        $data = $this->sanitizeInput();

        if (!$this->validate($data)) {
            header("Location: /crm/leads/$id/edit");
            exit;
        }

        $this->leadModel->update($id, $this->companyId, $data);

        $this->logModel->log(
            $this->companyId, $this->userId,
            'Lead Updated',
            "{$this->userName} updated lead: {$data['name']} (ID $id)."
        );

        $_SESSION['flash_success'] = 'Lead updated successfully.';
        header('Location: /crm/leads');
        exit;
    }

    /** GET /leads/{id}/delete */
    public function delete(int $id): void
    {
        $lead = $this->leadModel->findById($id, $this->companyId);
        if (!$lead) {
            $_SESSION['flash_error'] = 'Lead not found.';
            header('Location: /crm/leads');
            exit;
        }

        $this->leadModel->delete($id, $this->companyId);

        $this->logModel->log(
            $this->companyId, $this->userId,
            'Lead Deleted',
            "{$this->userName} deleted lead: {$lead['name']} (ID $id)."
        );

        $_SESSION['flash_success'] = 'Lead deleted.';
        header('Location: /crm/leads');
        exit;
    }

    // ── Helpers 

    private function sanitizeInput(): array
    {
        return [
            'name'   => trim($_POST['name']   ?? ''),
            'email'  => trim($_POST['email']  ?? ''),
            'phone'  => trim($_POST['phone']  ?? ''),
            'source' => trim($_POST['source'] ?? ''),
            'status' => trim($_POST['status'] ?? 'New'),
            'notes'  => trim($_POST['notes']  ?? ''),
        ];
    }

    private function validate(array $data): bool
    {
        if (empty($data['name'])) {
            $_SESSION['flash_error'] = 'Lead name is required.';
            return false;
        }
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Invalid email format.';
            return false;
        }
        if (!in_array($data['status'], Lead::STATUSES, true)) {
            $_SESSION['flash_error'] = 'Invalid status.';
            return false;
        }
        return true;
    }
}
