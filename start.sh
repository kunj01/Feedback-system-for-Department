#!/bin/bash

# Production Start Script for Render Deployment
echo "🚀 Starting application..."

# Run database migrations
echo "📊 Running database migrations..."
php artisan migrate --force

if [ $? -ne 0 ]; then
    echo "⚠️  Migration failed, but continuing..."
fi

# Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link

if [ $? -ne 0 ]; then
    echo "⚠️  Storage link already exists or failed, continuing..."
fi

# Clear permission cache (Spatie)
echo "🔐 Clearing permission cache..."
php artisan permission:cache-reset

if [ $? -ne 0 ]; then
    echo "⚠️  Permission cache reset failed, continuing..."
fi

# Start the server
echo "🌐 Starting web server on port 10000..."
php artisan serve --host=0.0.0.0 --port=10000
