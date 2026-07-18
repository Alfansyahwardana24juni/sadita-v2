#!/bin/bash

echo "🚀 SADITA V2 - Filament Setup Script"
echo "===================================="
echo ""

# Install Filament
echo "📦 Installing Filament..."
php artisan filament:install --panels

# Create Filament Resources
echo "📝 Creating Filament Resources..."
php artisan make:filament-resource Product --generate --view
php artisan make:filament-resource Category --generate --view  
php artisan make:filament-resource Warehouse --generate --view
php artisan make:filament-resource ProductStock --generate --view

# Install Spatie Permission
echo "🔐 Installing Spatie Laravel Permission..."
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate

echo ""
echo "✅ Setup Complete!"
echo ""
echo "Next steps:"
echo "1. Run: npm install && npm run dev"
echo "2. Run: php artisan serve"
echo "3. Visit: http://localhost:8000"
echo "4. Admin: http://localhost:8000/admin"
echo ""
