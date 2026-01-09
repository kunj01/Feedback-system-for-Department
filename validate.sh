#!/bin/bash

# Production Readiness Validation Script
echo "🔍 Validating production readiness..."
echo ""

ERRORS=0
WARNINGS=0

# Check if .env exists (shouldn't in production, but need to check locally)
if [ -f ".env" ]; then
    echo "✅ .env file exists (for local testing)"
    
    # Check critical environment variables
    if grep -q "APP_ENV=production" .env; then
        echo "✅ APP_ENV is set to production"
    else
        echo "⚠️  Warning: APP_ENV is not set to production"
        WARNINGS=$((WARNINGS + 1))
    fi
    
    if grep -q "APP_DEBUG=false" .env; then
        echo "✅ APP_DEBUG is set to false"
    else
        echo "⚠️  Warning: APP_DEBUG is not set to false"
        WARNINGS=$((WARNINGS + 1))
    fi
    
    if grep -q "DB_CONNECTION=mysql" .env; then
        echo "✅ DB_CONNECTION is set to mysql"
    else
        echo "⚠️  Warning: DB_CONNECTION is not set to mysql"
        WARNINGS=$((WARNINGS + 1))
    fi
    
    if grep -q "LOG_CHANNEL=stderr" .env; then
        echo "✅ LOG_CHANNEL is set to stderr"
    else
        echo "⚠️  Warning: LOG_CHANNEL should be stderr for Render"
        WARNINGS=$((WARNINGS + 1))
    fi
else
    echo "ℹ️  .env file not found (this is OK for production deployment)"
fi

echo ""

# Check if .env.example exists and is updated
if [ -f ".env.example" ]; then
    echo "✅ .env.example exists"
    
    if grep -q "DB_CONNECTION=mysql" .env.example; then
        echo "✅ .env.example has MySQL configuration"
    else
        echo "❌ ERROR: .env.example doesn't have MySQL configuration"
        ERRORS=$((ERRORS + 1))
    fi
else
    echo "❌ ERROR: .env.example not found"
    ERRORS=$((ERRORS + 1))
fi

echo ""

# Check configuration files
if [ -f "config/database.php" ]; then
    if grep -q "'default' => env('DB_CONNECTION', 'mysql')" config/database.php; then
        echo "✅ Default database connection is MySQL"
    else
        echo "❌ ERROR: Default database connection is not MySQL"
        ERRORS=$((ERRORS + 1))
    fi
else
    echo "❌ ERROR: config/database.php not found"
    ERRORS=$((ERRORS + 1))
fi

echo ""

# Check Vite configuration
if [ -f "vite.config.js" ]; then
    if grep -q "@tailwindcss/vite" vite.config.js; then
        echo "✅ Vite is configured for Tailwind CSS v4"
    else
        echo "⚠️  Warning: Vite might not be configured correctly for Tailwind CSS v4"
        WARNINGS=$((WARNINGS + 1))
    fi
else
    echo "❌ ERROR: vite.config.js not found"
    ERRORS=$((ERRORS + 1))
fi

echo ""

# Check if node_modules exists
if [ -d "node_modules" ]; then
    echo "✅ node_modules directory exists"
else
    echo "⚠️  Warning: node_modules not found. Run 'npm install'"
    WARNINGS=$((WARNINGS + 1))
fi

# Check if vendor directory exists
if [ -d "vendor" ]; then
    echo "✅ vendor directory exists"
else
    echo "⚠️  Warning: vendor not found. Run 'composer install'"
    WARNINGS=$((WARNINGS + 1))
fi

echo ""

# Check if public/build exists (built assets)
if [ -d "public/build" ]; then
    echo "✅ public/build directory exists (assets built)"
else
    echo "⚠️  Warning: public/build not found. Run 'npm run build'"
    WARNINGS=$((WARNINGS + 1))
fi

echo ""

# Check deployment scripts
if [ -f "build.sh" ]; then
    echo "✅ build.sh script exists"
    if [ -x "build.sh" ]; then
        echo "✅ build.sh is executable"
    else
        echo "⚠️  Warning: build.sh is not executable. Run 'chmod +x build.sh'"
        WARNINGS=$((WARNINGS + 1))
    fi
else
    echo "❌ ERROR: build.sh not found"
    ERRORS=$((ERRORS + 1))
fi

if [ -f "start.sh" ]; then
    echo "✅ start.sh script exists"
    if [ -x "start.sh" ]; then
        echo "✅ start.sh is executable"
    else
        echo "⚠️  Warning: start.sh is not executable. Run 'chmod +x start.sh'"
        WARNINGS=$((WARNINGS + 1))
    fi
else
    echo "❌ ERROR: start.sh not found"
    ERRORS=$((ERRORS + 1))
fi

echo ""

# Check .gitignore
if [ -f ".gitignore" ]; then
    if grep -q "^\.env$" .gitignore; then
        echo "✅ .env is in .gitignore"
    else
        echo "❌ ERROR: .env is NOT in .gitignore (security risk!)"
        ERRORS=$((ERRORS + 1))
    fi
    
    if grep -q "node_modules" .gitignore; then
        echo "✅ node_modules is in .gitignore"
    else
        echo "⚠️  Warning: node_modules should be in .gitignore"
        WARNINGS=$((WARNINGS + 1))
    fi
    
    if grep -q "vendor" .gitignore; then
        echo "✅ vendor is in .gitignore"
    else
        echo "⚠️  Warning: vendor should be in .gitignore"
        WARNINGS=$((WARNINGS + 1))
    fi
else
    echo "❌ ERROR: .gitignore not found"
    ERRORS=$((ERRORS + 1))
fi

echo ""

# Check migrations directory
if [ -d "database/migrations" ]; then
    MIGRATION_COUNT=$(ls -1 database/migrations/*.php 2>/dev/null | wc -l)
    if [ $MIGRATION_COUNT -gt 0 ]; then
        echo "✅ Found $MIGRATION_COUNT migration files"
    else
        echo "⚠️  Warning: No migration files found"
        WARNINGS=$((WARNINGS + 1))
    fi
else
    echo "❌ ERROR: database/migrations directory not found"
    ERRORS=$((ERRORS + 1))
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Validation Summary:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if [ $ERRORS -eq 0 ] && [ $WARNINGS -eq 0 ]; then
    echo "✅ All checks passed! Application is ready for production deployment."
    echo ""
    echo "Next steps:"
    echo "1. Push code to GitHub/GitLab"
    echo "2. Setup MySQL database (PlanetScale, AWS RDS, etc.)"
    echo "3. Create Web Service on Render"
    echo "4. Set environment variables in Render"
    echo "5. Deploy!"
    echo ""
    echo "See RENDER_DEPLOYMENT.md for detailed instructions."
    exit 0
elif [ $ERRORS -eq 0 ]; then
    echo "⚠️  $WARNINGS warning(s) found, but no critical errors."
    echo "Review warnings above before deploying."
    exit 0
else
    echo "❌ $ERRORS error(s) and $WARNINGS warning(s) found."
    echo "Fix errors before deploying to production!"
    exit 1
fi
