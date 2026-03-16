#!/bin/bash

echo "============================================"
echo "  ONCHAIN Investment Platform - Setup"
echo "============================================"
echo ""

echo "[1/5] Installing PHP dependencies..."
composer install
if [ $? -ne 0 ]; then
    echo "ERROR: composer install failed."
    exit 1
fi

echo ""
echo "[2/5] Copying .env file..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo ".env file created."
else
    echo ".env already exists, skipping."
fi

echo ""
echo "[3/5] Generating application key..."
php artisan key:generate

echo ""
echo "[4/5] Running database migrations..."
php artisan migrate
if [ $? -ne 0 ]; then
    echo "ERROR: Migration failed. Check your .env DB settings."
    exit 1
fi

echo ""
echo "[5/5] Seeding database with demo data..."
php artisan db:seed

echo ""
echo "============================================"
echo "  SETUP COMPLETE!"
echo "============================================"
echo ""
echo "  Admin login:    admin@onchain.finance / admin123"
echo "  Demo customer:  john@email.com / password123"
echo ""
echo "  Starting development server..."
echo "  Open: http://127.0.0.1:8000"
echo ""
php artisan serve
