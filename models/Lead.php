<?php
/**
 * Model: Lead
 */

class Lead
{
    private PDO $db;

    public const STATUSES = ['New', 'Contacted', 'Qualified', 'Converted', 'Lost'];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /** All leads for a company (with optional status filter) */
    public function allByCompany(int $companyId, ?string $status = null): array
    {
        $sql = "SELECT * FROM leads WHERE company_id = :cid";
        $params = [':cid' => $companyId];

        if ($status !== null && in_array($status, self::STATUSES, true)) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Recent N leads for a company */
    public function recent(int $companyId, int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM leads WHERE company_id = :cid ORDER BY created_at DESC LIMIT :lim"
        );
        $stmt->bindValue(':cid', $companyId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit,     PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Count all leads for a company */
    public function countByCompany(int $companyId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM leads WHERE company_id = :cid"
        );
        $stmt->execute([':cid' => $companyId]);
        return (int) $stmt->fetchColumn();
    }

    /** Count by status for a company */
    public function countByStatus(int $companyId): array
    {
        $stmt = $this->db->prepare(
            "SELECT status, COUNT(*) AS total
             FROM leads
             WHERE company_id = :cid
             GROUP BY status"
        );
        $stmt->execute([':cid' => $companyId]);
        $rows = $stmt->fetchAll();
        $result = array_fill_keys(self::STATUSES, 0);
        foreach ($rows as $row) {
            $result[$row['status']] = (int) $row['total'];
        }
        return $result;
    }

    /** Find by id — enforces company isolation */
    public function findById(int $id, int $companyId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM leads WHERE id = :id AND company_id = :cid LIMIT 1"
        );
        $stmt->execute([':id' => $id, ':cid' => $companyId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Create lead; returns new id */
    public function create(array $data): int
    {
        $sql = "INSERT INTO leads (company_id, name, email, phone, source, status, notes)
                VALUES (:company_id, :name, :email, :phone, :source, :status, :notes)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':company_id' => $data['company_id'],
            ':name'       => $data['name'],
            ':email'      => $data['email']  ?? null,
            ':phone'      => $data['phone']  ?? null,
            ':source'     => $data['source'] ?? null,
            ':status'     => $data['status'] ?? 'New',
            ':notes'      => $data['notes']  ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    /** Update lead — enforces company isolation */
    public function update(int $id, int $companyId, array $data): bool
    {
        $sql = "UPDATE leads
                SET name = :name, email = :email, phone = :phone,
                    source = :source, status = :status, notes = :notes
                WHERE id = :id AND company_id = :cid";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'     => $id,
            ':cid'    => $companyId,
            ':name'   => $data['name'],
            ':email'  => $data['email']  ?? null,
            ':phone'  => $data['phone']  ?? null,
            ':source' => $data['source'] ?? null,
            ':status' => $data['status'],
            ':notes'  => $data['notes']  ?? null,
        ]);
    }

    /** Delete lead — enforces company isolation */
    public function delete(int $id, int $companyId): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM leads WHERE id = :id AND company_id = :cid"
        );
        return $stmt->execute([':id' => $id, ':cid' => $companyId]);
    }
}
