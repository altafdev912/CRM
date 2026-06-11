# CRM  – Multi-Tenant CRM System

A complete, production-ready Multi-Tenant CRM built with **Core PHP**, **MySQL**, **Bootstrap 5**, and **PDO**.

---

## Tech Stack

| Layer       | Technology                          |
|-------------|-------------------------------------|
| Backend     | PHP 8.1+ (no framework)             |
| Database    | MySQL 8.0+ via PDO                  |
| Frontend    | Bootstrap 5.3, Bootstrap Icons, Chart.js |
| Auth        | Session-based + password_hash()     |
| Pattern     | MVC (manual routing via index.php)  |

---

## Setup Instructions

### 1. Clone / copy the project

```
/var/www/html/crm/    ← place the project here
```

### 2. Create the database

```sql
CREATE DATABASE crm_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Then import the schema:

```bash
mysql -u root -p crm_db < database/crm.sql
```

### 3. Configure DB credentials

Edit `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'crm_db');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
```

If your CRM lives at the domain root (not `/crm/`), update in `index.php`:

```php
define('BASE_URL', '');   // empty string if at root
```

And in `.htaccess`:

```apache
RewriteBase /
```

### 4. Enable Apache mod_rewrite

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Make sure your VirtualHost has `AllowOverride All`.

### 5. Access the application

```
http://localhost/crm/login
```

Demo credentials (from seed data in crm.sql):

| Role  | Email              | Password    |
|-------|--------------------|-------------|


### 6. Register your own company

Visit `http://localhost/crm/register` to create a fresh company account.

---

## Project Structure

```
crm/
├── .htaccess                  # Apache URL rewriting + security headers
├── index.php                  # Front controller / router
│
├── config/
│   └── database.php           # PDO singleton
│
├── controllers/
│   ├── AuthController.php     # Login / Logout
│   ├── CompanyController.php  # Company registration
│   ├── UserController.php     # User CRUD (Admin only)
│   ├── LeadController.php     # Lead CRUD
│   └── DashboardController.php
│
├── models/
│   ├── Company.php
│   ├── User.php
│   ├── Lead.php
│   └── ActivityLog.php
│
├── views/
│   ├── layouts/
│   │   ├── header.php         # Sidebar + topbar
│   │   ├── footer.php         # JS includes
│   │   └── 404.php
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── dashboard/
│   │   └── index.php
│   ├── leads/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── _form.php          # Shared form partial
│   └── users/
│       ├── index.php
│       ├── create.php
│       ├── edit.php
│       └── _form.php
│
├── middleware/
│   └── AuthMiddleware.php     # Route protection + role check
│
├── assets/
│   ├── css/app.css
│   └── js/app.js
│
└── database/
    └── crm.sql                # Schema + demo data
```

---

## Security Features

- **PDO Prepared Statements** — all DB queries parameterised; zero SQL injection risk
- **Multi-Tenant Isolation** — every query filters by `company_id` from session
- **XSS Protection** — all output escaped with `htmlspecialchars()`
- **Password Hashing** — `password_hash()` with BCRYPT cost 12
- **Session Hardening** — `httponly`, `strict_mode`, `SameSite=Strict`, `session_regenerate_id()` on login
- **Input Validation** — server-side validation on all forms
- **Role-Based Access** — Admin-only routes enforced at middleware level
- **Apache Headers** — `X-Frame-Options`, `X-Content-Type-Options`, `X-XSS-Protection`

---

## ERD Summary

```
companies (1) ──< users    (N)
companies (1) ──< leads    (N)
companies (1) ──< activity_logs (N)
users     (1) ──< activity_logs (N)   [SET NULL on user delete]
```

---

## License

MIT — free for personal and commercial use.
