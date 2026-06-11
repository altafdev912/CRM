<?php
/**
 * AuthMiddleware
 * Protects routes — redirects to login if unauthenticated.
 * Optionally restricts to a specific role.
 */

class AuthMiddleware
{
    /**
     * @param string|null $requiredRole  'Admin' | 'User' | null (any authenticated user)
     */
    public static function check(?string $requiredRole = null): void
    {
        if (empty($_SESSION['user_id'])) {
            self::redirect('/crm/login');
        }

        if ($requiredRole !== null && ($_SESSION['user_role'] ?? '') !== $requiredRole) {
            // Insufficient privilege — send back to dashboard
            $_SESSION['flash_error'] = 'You do not have permission to access that page.';
            self::redirect('/crm/dashboard');
        }
    }

    /** Redirect helper */
    public static function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }

    /** Return true if current user is Admin */
    public static function isAdmin(): bool
    {
        return ($_SESSION['user_role'] ?? '') === 'Admin';
    }
}
