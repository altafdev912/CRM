<?php
/**
 * Model: Company
 */

class Company
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /** Create a new company; returns new id */
    public function create(array $data): int
    {
        $sql = "INSERT INTO companies (company_name, email, phone, address)
                VALUES (:company_name, :email, :phone, :address)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':company_name' => $data['company_name'],
            ':email'        => $data['email'],
            ':phone'        => $data['phone'] ?? null,
            ':address'      => $data['address'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    /** Find company by id */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM companies WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Find company by email (for duplicate check) */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM companies WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
