<?php
/**
 * Model: User
 */

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /** All users for a company */
    public function allByCompany(int $companyId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE company_id = :cid ORDER BY created_at DESC"
        );
        $stmt->execute([':cid' => $companyId]);
        return $stmt->fetchAll();
    }

    /** Count users for a company */
    public function countByCompany(int $companyId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM users WHERE company_id = :cid"
        );
        $stmt->execute([':cid' => $companyId]);
        return (int) $stmt->fetchColumn();
    }

    /** Find by id — enforces company isolation */
    public function findById(int $id, int $companyId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE id = :id AND company_id = :cid LIMIT 1"
        );
        $stmt->execute([':id' => $id, ':cid' => $companyId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Find by email (for login — no company filter needed at auth stage) */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = :email LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Create user; returns new id */
    public function create(array $data): int
    {
        $sql = "INSERT INTO users (company_id, name, email, password, role)
                VALUES (:company_id, :name, :email, :password, :role)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':company_id' => $data['company_id'],
            ':name'       => $data['name'],
            ':email'      => $data['email'],
            ':password'   => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            ':role'       => $data['role'] ?? 'User',
        ]);

        return (int) $this->db->lastInsertId();
    }

    /** Update user — enforces company isolation */
    public function update(int $id, int $companyId, array $data): bool
    {
        $fields = "name = :name, email = :email, role = :role";
        $params = [
            ':id'   => $id,
            ':cid'  => $companyId,
            ':name' => $data['name'],
            ':email'=> $data['email'],
            ':role' => $data['role'],
        ];

        // Only update password if provided
        if (!empty($data['password'])) {
            $fields .= ', password = :password';
            $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        }

        $stmt = $this->db->prepare(
            "UPDATE users SET $fields WHERE id = :id AND company_id = :cid"
        );
        return $stmt->execute($params);
    }

    /** Delete user — enforces company isolation */
    public function delete(int $id, int $companyId): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM users WHERE id = :id AND company_id = :cid"
        );
        return $stmt->execute([':id' => $id, ':cid' => $companyId]);
    }

    /** Email already used by another user (exclude current id on edit) */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        if ($excludeId) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM users WHERE email = :email AND id != :id"
            );
            $stmt->execute([':email' => $email, ':id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM users WHERE email = :email"
            );
            $stmt->execute([':email' => $email]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }
}
