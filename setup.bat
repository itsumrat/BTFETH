@echo off
echo ============================================
echo   ONCHAIN Investment Platform - Setup
echo ============================================
echo.

echo [1/5] Installing PHP dependencies...
call composer install
if %errorlevel% neq 0 (
    echo ERROR: composer install failed. Make sure Composer is installed.
    pause
    exit /b 1
)

echo.
echo [2/5] Copying .env file...
if not exist .env (
    copy .env.example .env
    echo .env file created.
) else (
    echo .env already exists, skipping.
)

echo.
echo [3/5] Generating application key...
php artisan key:generate

echo.
echo [4/5] Running database migrations...
php artisan migrate
if %errorlevel% neq 0 (
    echo ERROR: Migration failed.
    echo Make sure MySQL is running and your .env DB settings are correct.
    pause
    exit /b 1
)

echo.
echo [5/5] Seeding database with demo data...
php artisan db:seed

echo.
echo ============================================
echo   SETUP COMPLETE!
echo ============================================
echo.
echo   Admin login:    admin@onchain.finance / admin123
echo   Demo customer:  john@email.com / password123
echo.
echo   Starting development server...
echo   Open: http://127.0.0.1:8000
echo.
php artisan serve
pause
