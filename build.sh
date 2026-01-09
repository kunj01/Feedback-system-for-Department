#!/bin/bash

# Production Build Script for Render Deployment
echo "🚀 Starting production build..."

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Check if composer install was successful
if [ $? -ne 0 ]; then
    echo "❌ Composer install failed"
    exit 1
fi

# Install Node dependencies
echo "📦 Installing Node dependencies..."
npm ci --production=false

# Check if npm install was successful
if [ $? -ne 0 ]; then
    echo "❌ npm install failed"
    exit 1
fi

# Build frontend assets
echo "🎨 Building frontend assets..."
npm run build

# Check if build was successful
if [ $? -ne 0 ]; then
    echo "❌ Frontend build failed"
    exit 1
fi

# Clear and optimize Laravel caches
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build completed successfully!"
