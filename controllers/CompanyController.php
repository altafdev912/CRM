<?php
/**
 * Controller: Company
 * Handles company self-registration.
 */

class CompanyController
{
    private Company $companyModel;
    private User    $userModel;

    public function __construct()
    {
        require_once ROOT_PATH . '/models/Company.php';
        require_once ROOT_PATH . '/models/User.php';
        $this->companyModel = new Company();
        $this->userModel    = new User();
    }

    /** GET /register */
    public function showRegister(): void
    {
        if (!empty($_SESSION['user_id'])) {
            header('Location: /crm/dashboard');
            exit;
        }
        $error   = $_SESSION['flash_error']   ?? null;
        $success = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_error'], $_SESSION['flash_success']);
        require ROOT_PATH . '/views/auth/register.php';
    }

    /** POST /register */
    public function register(): void
    {
        $company_name = trim($_POST['company_name'] ?? '');
        $email        = trim($_POST['email']        ?? '');
        $phone        = trim($_POST['phone']        ?? '');
        $address      = trim($_POST['address']      ?? '');
        $admin_name   = trim($_POST['admin_name']   ?? '');
        $password     = $_POST['password']          ?? '';
        $confirm      = $_POST['confirm_password']  ?? '';

        // Validate
        $errors = [];
        if (empty($company_name)) $errors[] = 'Company name is required.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid company email is required.';
        }
        if (empty($admin_name)) $errors[] = 'Admin name is required.';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
        if ($password !== $confirm)  $errors[] = 'Passwords do not match.';

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header('Location: /crm/register');
            exit;
        }

        // Check duplicate company email
        if ($this->companyModel->findByEmail($email)) {
            $_SESSION['flash_error'] = 'An account with this email already exists.';
            header('Location: /crm/register');
            exit;
        }

        // Check duplicate user email
        if ($this->userModel->findByEmail($email)) {
            $_SESSION['flash_error'] = 'That email is already registered.';
            header('Location: /crm/register');
            exit;
        }

        // Create company
        $companyId = $this->companyModel->create([
            'company_name' => $company_name,
            'email'        => $email,
            'phone'        => $phone,
            'address'      => $address,
        ]);

        // Create admin user (same email as company)
        $this->userModel->create([
            'company_id' => $companyId,
            'name'       => $admin_name,
            'email'      => $email,
            'password'   => $password,
            'role'       => 'Admin',
        ]);

        $_SESSION['flash_success'] = 'Account created! You can now log in.';
        header('Location: /crm/login');
        exit;
    }
}
