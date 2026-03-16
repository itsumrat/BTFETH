# 🚀 Onchain Investment Platform — Setup Guide

## Prerequisites (install these first)

| Tool | Download |
|------|----------|
| PHP 8.2+ | https://php.net/downloads |
| Composer | https://getcomposer.org |
| MySQL 8.0 | https://dev.mysql.com/downloads/mysql/ |
| Node.js 18+ | https://nodejs.org |

> **Tip:** Install [XAMPP](https://www.apachefriends.org) to get PHP + MySQL in one package.

---

## Step 1 — Create MySQL Database

Open **phpMyAdmin** (XAMPP) or **MySQL Workbench** and run:

```sql
CREATE DATABASE onchain CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## Step 2 — Place the Project

Move this `onchain/` folder to:
- **XAMPP:** `C:\xampp\htdocs\onchain\`
- **Mac/Linux:** `/var/www/html/onchain/`

---

## Step 3 — Install PHP Dependencies

Open terminal inside the `onchain/` folder:

```bash
composer install
```

---

## Step 4 — Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Then open `.env` and update:

```
DB_DATABASE=onchain
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

---

## Step 5 — Run Migrations & Seed

```bash
php artisan migrate
php artisan db:seed
```

This creates all tables and inserts:
- Admin user: `admin@onchain.finance` / `admin123`
- 6 demo customers: `john@email.com` / `password123` (all same password)
- Global deposit & withdrawal info

---

## Step 6 — Start the Server

```bash
php artisan serve
```

Then open your browser:

| Page | URL |
|------|-----|
| Homepage | http://127.0.0.1:8000 |
| Customer Login | http://127.0.0.1:8000/login |
| Customer Register | http://127.0.0.1:8000/register |
| Customer Dashboard | http://127.0.0.1:8000/dashboard |
| Admin Panel | http://127.0.0.1:8000/admin |

---

## Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@onchain.finance | admin123 |
| Demo Customer | john@email.com | password123 |
| Demo Customer | sarah@email.com | password123 |

---

## Project Structure

```
onchain/
├── app/
│   ├── Console/Commands/CalculateDailyProfit.php  ← Daily profit scheduler
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php            ← Customer dashboard
│   │   │   └── Admin/
│   │   │       ├── AdminDashboardController.php
│   │   │       ├── CustomerController.php
│   │   │       ├── TransactionController.php      ← Full CRUD + auto balance
│   │   │       └── PaymentInfoController.php      ← Global + per-customer
│   │   └── Middleware/AdminMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Transaction.php
│   │   ├── DepositInfo.php
│   │   └── WithdrawalInfo.php
│   └── Providers/AppServiceProvider.php
├── bootstrap/app.php                              ← Admin middleware registered here
├── database/
│   ├── migrations/                                ← 4 migration files
│   └── seeders/DatabaseSeeder.php                 ← Admin + demo data
├── public/
│   ├── css/style.css                              ← Customer CSS
│   ├── css/admin-style.css                        ← Admin CSS
│   └── index.php
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php                          ← Customer layout
│   │   └── admin.blade.php                        ← Admin layout (sidebar)
│   ├── auth/
│   │   ├── login.blade.php
│   │   ├── register.blade.php
│   │   └── forgot-password.blade.php
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   ├── customers.blade.php
│   │   ├── customer-detail.blade.php
│   │   ├── transactions.blade.php
│   │   └── payment-info.blade.php
│   ├── welcome.blade.php                          ← Homepage
│   ├── dashboard.blade.php                        ← Customer dashboard
│   ├── plans.blade.php
│   └── about.blade.php
└── routes/
    ├── web.php
    ├── auth.php
    └── console.php                                ← Scheduler config
```

---

## Daily Profit Scheduler

Profit is calculated automatically every night at midnight.

**Test it manually:**
```bash
php artisan profit:calculate
```

**For production server** — add one cron job:
```
* * * * * cd /path/to/onchain && php artisan schedule:run >> /dev/null 2>&1
```

---

## Useful Commands

```bash
php artisan migrate:fresh --seed   # Reset database completely
php artisan route:list             # See all routes
php artisan tinker                 # Interactive PHP shell
php artisan config:clear           # Clear config cache
php artisan cache:clear            # Clear app cache
```
