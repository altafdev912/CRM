<?php
/**
 * Front Controller — crm/index.php
 * All requests route through here.
 */

declare(strict_types=1);

// ── Bootstrap 
define('ROOT_PATH', __DIR__);
define('BASE_URL',  '/crm');          // Change to '' if crm/ IS your document root

// Session hardening
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_samesite', 'Strict');

session_start();

// Auto-loader (simple PSR-0-style)
spl_autoload_register(function (string $class): void {
    $map = [
        'Database'            => ROOT_PATH . '/config/database.php',
        'AuthMiddleware'      => ROOT_PATH . '/middleware/AuthMiddleware.php',
        'AuthController'      => ROOT_PATH . '/controllers/AuthController.php',
        'CompanyController'   => ROOT_PATH . '/controllers/CompanyController.php',
        'UserController'      => ROOT_PATH . '/controllers/UserController.php',
        'LeadController'      => ROOT_PATH . '/controllers/LeadController.php',
        'DashboardController' => ROOT_PATH . '/controllers/DashboardController.php',
        'Company'             => ROOT_PATH . '/models/Company.php',
        'User'                => ROOT_PATH . '/models/User.php',
        'Lead'                => ROOT_PATH . '/models/Lead.php',
        'ActivityLog'         => ROOT_PATH . '/models/ActivityLog.php',
    ];
    if (isset($map[$class])) {
        require_once $map[$class];
    }
});

// Helpers loaded globally
require_once ROOT_PATH . '/config/database.php';

// ── Router 
$requestUri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath      = BASE_URL;
$route         = str_replace($basePath, '', $requestUri);
$route         = trim($route, '/');
$route         = $route === '' ? 'dashboard' : $route;
$method        = $_SERVER['REQUEST_METHOD'];

// ── Dispatch 
switch (true) {

    // Auth routes (public)
    case $route === 'login':
        require_once ROOT_PATH . '/controllers/AuthController.php';
        $ctrl = new AuthController();
        $method === 'POST' ? $ctrl->login() : $ctrl->showLogin();
        break;

    case $route === 'register':
        require_once ROOT_PATH . '/controllers/CompanyController.php';
        $ctrl = new CompanyController();
        $method === 'POST' ? $ctrl->register() : $ctrl->showRegister();
        break;

    case $route === 'logout':
        require_once ROOT_PATH . '/controllers/AuthController.php';
        (new AuthController())->logout();
        break;

    // Dashboard
    case $route === 'dashboard':
        require_once ROOT_PATH . '/middleware/AuthMiddleware.php';
        AuthMiddleware::check();
        require_once ROOT_PATH . '/controllers/DashboardController.php';
        (new DashboardController())->index();
        break;

    // Leads
    case $route === 'leads':
        require_once ROOT_PATH . '/middleware/AuthMiddleware.php';
        AuthMiddleware::check();
        require_once ROOT_PATH . '/controllers/LeadController.php';
        $ctrl = new LeadController();
        $method === 'POST' ? $ctrl->store() : $ctrl->index();
        break;

    case $route === 'leads/create':
        require_once ROOT_PATH . '/middleware/AuthMiddleware.php';
        AuthMiddleware::check();
        require_once ROOT_PATH . '/controllers/LeadController.php';
        (new LeadController())->create();
        break;

    case preg_match('#^leads/(\d+)/edit$#', $route, $m) === 1:
        require_once ROOT_PATH . '/middleware/AuthMiddleware.php';
        AuthMiddleware::check();
        require_once ROOT_PATH . '/controllers/LeadController.php';
        $ctrl = new LeadController();
        $method === 'POST' ? $ctrl->update((int)$m[1]) : $ctrl->edit((int)$m[1]);
        break;

    case preg_match('#^leads/(\d+)/delete$#', $route, $m) === 1:
        require_once ROOT_PATH . '/middleware/AuthMiddleware.php';
        AuthMiddleware::check();
        require_once ROOT_PATH . '/controllers/LeadController.php';
        (new LeadController())->delete((int)$m[1]);
        break;

    // Users
    case $route === 'users':
        require_once ROOT_PATH . '/middleware/AuthMiddleware.php';
        AuthMiddleware::check('Admin');
        require_once ROOT_PATH . '/controllers/UserController.php';
        $ctrl = new UserController();
        $method === 'POST' ? $ctrl->store() : $ctrl->index();
        break;

    case $route === 'users/create':
        require_once ROOT_PATH . '/middleware/AuthMiddleware.php';
        AuthMiddleware::check('Admin');
        require_once ROOT_PATH . '/controllers/UserController.php';
        (new UserController())->create();
        break;

    case preg_match('#^users/(\d+)/edit$#', $route, $m) === 1:
        require_once ROOT_PATH . '/middleware/AuthMiddleware.php';
        AuthMiddleware::check('Admin');
        require_once ROOT_PATH . '/controllers/UserController.php';
        $ctrl = new UserController();
        $method === 'POST' ? $ctrl->update((int)$m[1]) : $ctrl->edit((int)$m[1]);
        break;

    case preg_match('#^users/(\d+)/delete$#', $route, $m) === 1:
        require_once ROOT_PATH . '/middleware/AuthMiddleware.php';
        AuthMiddleware::check('Admin');
        require_once ROOT_PATH . '/controllers/UserController.php';
        (new UserController())->delete((int)$m[1]);
        break;

    // 404
    default:
        http_response_code(404);
        require ROOT_PATH . '/views/layouts/404.php';
        break;
}
