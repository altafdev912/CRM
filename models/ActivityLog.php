<?php
/**
 * Model: ActivityLog
 */

class ActivityLog
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /** Log an action */
    public function log(int $companyId, ?int $userId, string $action, string $description = ''): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO activity_logs (company_id, user_id, action, description)
             VALUES (:company_id, :user_id, :action, :description)"
        );
        $stmt->execute([
            ':company_id'  => $companyId,
            ':user_id'     => $userId,
            ':action'      => $action,
            ':description' => $description,
        ]);
    }

    /** Recent N activity logs for a company (joined with user name) */
    public function recent(int $companyId, int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            "SELECT al.*, u.name AS user_name
             FROM activity_logs al
             LEFT JOIN users u ON u.id = al.user_id
             WHERE al.company_id = :cid
             ORDER BY al.created_at DESC
             LIMIT :lim"
        );
        $stmt->bindValue(':cid', $companyId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit,     PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Count all logs for a company */
    public function countByCompany(int $companyId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM activity_logs WHERE company_id = :cid"
        );
        $stmt->execute([':cid' => $companyId]);
        return (int) $stmt->fetchColumn();
    }

    /** All logs (paginated) */
    public function allByCompany(int $companyId, int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare(
            "SELECT al.*, u.name AS user_name
             FROM activity_logs al
             LEFT JOIN users u ON u.id = al.user_id
             WHERE al.company_id = :cid
             ORDER BY al.created_at DESC
             LIMIT :lim OFFSET :off"
        );
        $stmt->bindValue(':cid', $companyId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $perPage,   PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset,    PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
