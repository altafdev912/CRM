<?php
/**
 * Controller: Auth
 * Handles Login & Logout.
 */

class AuthController
{
    private User $userModel;
    private ActivityLog $logModel;

    public function __construct()
    {
        require_once ROOT_PATH . '/models/User.php';
        require_once ROOT_PATH . '/models/ActivityLog.php';
        $this->userModel = new User();
        $this->logModel  = new ActivityLog();
    }

    /** GET /login */
    public function showLogin(): void
    {
        if (!empty($_SESSION['user_id'])) {
            header('Location: /crm/dashboard');
            exit;
        }
        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);
        require ROOT_PATH . '/views/auth/login.php';
    }

    /** POST /login */
    public function login(): void
    {
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        // Input validation
        if (empty($email) || empty($password)) {
            $_SESSION['flash_error'] = 'Email and password are required.';
            header('Location: /crm/login');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Invalid email format.';
            header('Location: /crm/login');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['flash_error'] = 'Invalid email or password.';
            header('Location: /crm/login');
            exit;
        }

        // Regenerate session ID to prevent fixation
        session_regenerate_id(true);

        // Store minimal info in session
        $_SESSION['user_id']     = $user['id'];
        $_SESSION['user_name']   = $user['name'];
        $_SESSION['user_email']  = $user['email'];
        $_SESSION['user_role']   = $user['role'];
        $_SESSION['company_id']  = $user['company_id'];

        // Log activity
        $this->logModel->log(
            $user['company_id'],
            $user['id'],
            'User Logged In',
            "{$user['name']} logged in."
        );

        header('Location: /crm/dashboard');
        exit;
    }

    /** GET /logout */
    public function logout(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->logModel->log(
                $_SESSION['company_id'],
                $_SESSION['user_id'],
                'User Logged Out',
                "{$_SESSION['user_name']} logged out."
            );
        }

        // Destroy session completely
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(
                session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']
            );
        }
        session_destroy();

        header('Location: /crm/login');
        exit;
    }
}
