<?php
/**
 * Controller: User
 * Admin-only user management (within same company).
 */

class UserController
{
    private User        $userModel;
    private ActivityLog $logModel;

    private int    $companyId;
    private int    $userId;
    private string $userName;

    public function __construct()
    {
        require_once ROOT_PATH . '/models/User.php';
        require_once ROOT_PATH . '/models/ActivityLog.php';
        $this->userModel = new User();
        $this->logModel  = new ActivityLog();
        $this->companyId = (int)($_SESSION['company_id'] ?? 0);
        $this->userId    = (int)($_SESSION['user_id']    ?? 0);
        $this->userName  = $_SESSION['user_name']        ?? '';
    }

    /** GET /users */
    public function index(): void
    {
        $users   = $this->userModel->allByCompany($this->companyId);
        $flash_success = $_SESSION['flash_success'] ?? null;
        $flash_error   = $_SESSION['flash_error']   ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);
        require ROOT_PATH . '/views/users/index.php';
    }

    /** GET /users/create */
    public function create(): void
    {
        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);
        require ROOT_PATH . '/views/users/create.php';
    }

    /** POST /users */
    public function store(): void
    {
        $name     = trim($_POST['name']     ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $role     = $_POST['role']          ?? 'User';

        $errors = [];
        if (empty($name))  $errors[] = 'Name is required.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        }
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
        if ($password !== $confirm)  $errors[] = 'Passwords do not match.';
        if (!in_array($role, ['Admin', 'User'])) $errors[] = 'Invalid role.';

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header('Location: /crm/users/create');
            exit;
        }

        if ($this->userModel->emailExists($email)) {
            $_SESSION['flash_error'] = 'That email is already in use.';
            header('Location: /crm/users/create');
            exit;
        }

        $newId = $this->userModel->create([
            'company_id' => $this->companyId,
            'name'       => $name,
            'email'      => $email,
            'password'   => $password,
            'role'       => $role,
        ]);

        $this->logModel->log(
            $this->companyId, $this->userId,
            'User Created',
            "{$this->userName} created user: $name (ID $newId)."
        );

        $_SESSION['flash_success'] = 'User created successfully.';
        header('Location: /crm/users');
        exit;
    }

    /** GET /users/{id}/edit */
    public function edit(int $id): void
    {
        $user = $this->userModel->findById($id, $this->companyId);
        if (!$user) {
            $_SESSION['flash_error'] = 'User not found.';
            header('Location: /crm/users');
            exit;
        }
        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);
        require ROOT_PATH . '/views/users/edit.php';
    }

    /** POST /users/{id}/edit */
    public function update(int $id): void
    {
        $user = $this->userModel->findById($id, $this->companyId);
        if (!$user) {
            $_SESSION['flash_error'] = 'User not found.';
            header('Location: /crm/users');
            exit;
        }

        $name     = trim($_POST['name']     ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $role     = $_POST['role']          ?? 'User';

        $errors = [];
        if (empty($name))  $errors[] = 'Name is required.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        }
        if (!empty($password) && strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }
        if (!empty($password) && $password !== $confirm) {
            $errors[] = 'Passwords do not match.';
        }
        if (!in_array($role, ['Admin', 'User'])) $errors[] = 'Invalid role.';

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header("Location: /crm/users/$id/edit");
            exit;
        }

        if ($this->userModel->emailExists($email, $id)) {
            $_SESSION['flash_error'] = 'That email is already in use.';
            header("Location: /crm/users/$id/edit");
            exit;
        }

        $this->userModel->update($id, $this->companyId, [
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
            'role'     => $role,
        ]);

        $this->logModel->log(
            $this->companyId, $this->userId,
            'User Updated',
            "{$this->userName} updated user: $name (ID $id)."
        );

        $_SESSION['flash_success'] = 'User updated successfully.';
        header('Location: /crm/users');
        exit;
    }

    /** GET /users/{id}/delete */
    public function delete(int $id): void
    {
        // Prevent self-deletion
        if ($id === $this->userId) {
            $_SESSION['flash_error'] = 'You cannot delete your own account.';
            header('Location: /crm/users');
            exit;
        }

        $user = $this->userModel->findById($id, $this->companyId);
        if (!$user) {
            $_SESSION['flash_error'] = 'User not found.';
            header('Location: /crm/users');
            exit;
        }

        $this->userModel->delete($id, $this->companyId);

        $this->logModel->log(
            $this->companyId, $this->userId,
            'User Deleted',
            "{$this->userName} deleted user: {$user['name']} (ID $id)."
        );

        $_SESSION['flash_success'] = 'User deleted.';
        header('Location: /crm/users');
        exit;
    }
}
