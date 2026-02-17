#!/bin/bash

# Perks Family Feature Setup Script
# This script sets up the Perks Family feature

echo "🚀 Setting up Perks Family Feature..."
echo ""

# Step 1: Run migrations
echo "📦 Running database migrations..."
php artisan migrate

if [ $? -eq 0 ]; then
    echo "✅ Migrations completed successfully!"
else
    echo "❌ Migration failed. Please check your database connection."
    exit 1
fi

echo ""

# Step 2: Clear caches
echo "🧹 Clearing application caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "✅ Caches cleared!"
echo ""

# Step 3: Display success message
echo "🎉 Perks Family Feature Setup Complete!"
echo ""
echo "📍 Access Points:"
echo "   - User Portal: http://localhost:8000/family-members"
echo "   - Admin Panel: http://localhost:8000/admin (Navigate to 'ოჯახის წევრები')"
echo ""
echo "📖 For detailed documentation, see: PERKS_FAMILY_IMPLEMENTATION.md"
echo ""
echo "✨ Happy coding!"

