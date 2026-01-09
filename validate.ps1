# Production Readiness Validation Script (PowerShell)
Write-Host "🔍 Validating production readiness..." -ForegroundColor Cyan
Write-Host ""

$Errors = 0
$Warnings = 0

# Check if .env exists
if (Test-Path ".env") {
    Write-Host "✅ .env file exists (for local testing)" -ForegroundColor Green
    
    $envContent = Get-Content ".env" -Raw
    
    if ($envContent -match "APP_ENV=production") {
        Write-Host "✅ APP_ENV is set to production" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Warning: APP_ENV is not set to production" -ForegroundColor Yellow
        $Warnings++
    }
    
    if ($envContent -match "APP_DEBUG=false") {
        Write-Host "✅ APP_DEBUG is set to false" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Warning: APP_DEBUG is not set to false" -ForegroundColor Yellow
        $Warnings++
    }
    
    if ($envContent -match "DB_CONNECTION=mysql") {
        Write-Host "✅ DB_CONNECTION is set to mysql" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Warning: DB_CONNECTION is not set to mysql" -ForegroundColor Yellow
        $Warnings++
    }
    
    if ($envContent -match "LOG_CHANNEL=stderr") {
        Write-Host "✅ LOG_CHANNEL is set to stderr" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Warning: LOG_CHANNEL should be stderr for Render" -ForegroundColor Yellow
        $Warnings++
    }
} else {
    Write-Host "INFO: .env file not found (this is OK for production deployment)" -ForegroundColor Cyan
}

Write-Host ""

# Check if .env.example exists
if (Test-Path ".env.example") {
    Write-Host "✅ .env.example exists" -ForegroundColor Green
    
    $envExampleContent = Get-Content ".env.example" -Raw
    
    if ($envExampleContent -match "DB_CONNECTION=mysql") {
        Write-Host "✅ .env.example has MySQL configuration" -ForegroundColor Green
    } else {
        Write-Host "❌ ERROR: .env.example does not have MySQL configuration" -ForegroundColor Red
        $Errors++
    }
} else {
    Write-Host "❌ ERROR: .env.example not found" -ForegroundColor Red
    $Errors++
}

Write-Host ""

# Check configuration files
if (Test-Path "config/database.php") {
    $dbConfig = Get-Content "config/database.php" -Raw
    if ($dbConfig -match "DB_CONNECTION.*mysql") {
        Write-Host "✅ Default database connection is MySQL" -ForegroundColor Green
    } else {
        Write-Host "❌ ERROR: Default database connection is not MySQL" -ForegroundColor Red
        $Errors++
    }
} else {
    Write-Host "❌ ERROR: config/database.php not found" -ForegroundColor Red
    $Errors++
}

Write-Host ""

# Check Vite configuration
if (Test-Path "vite.config.js") {
    $viteConfig = Get-Content "vite.config.js" -Raw
    if ($viteConfig -match "@tailwindcss/vite") {
        Write-Host "✅ Vite is configured for Tailwind CSS v4" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Warning: Vite might not be configured correctly for Tailwind CSS v4" -ForegroundColor Yellow
        $Warnings++
    }
} else {
    Write-Host "❌ ERROR: vite.config.js not found" -ForegroundColor Red
    $Errors++
}

Write-Host ""

# Check if node_modules exists
if (Test-Path "node_modules") {
    Write-Host "✅ node_modules directory exists" -ForegroundColor Green
} else {
    Write-Host "⚠️  Warning: node_modules not found. Run npm install" -ForegroundColor Yellow
    $Warnings++
}

# Check if vendor directory exists
if (Test-Path "vendor") {
    Write-Host "✅ vendor directory exists" -ForegroundColor Green
} else {
    Write-Host "⚠️  Warning: vendor not found. Run composer install" -ForegroundColor Yellow
    $Warnings++
}

Write-Host ""

# Check if public/build exists
if (Test-Path "public/build") {
    Write-Host "✅ public/build directory exists (assets built)" -ForegroundColor Green
} else {
    Write-Host "⚠️  Warning: public/build not found. Run npm run build" -ForegroundColor Yellow
    $Warnings++
}

Write-Host ""

# Check deployment scripts
if (Test-Path "build.sh") {
    Write-Host "✅ build.sh script exists" -ForegroundColor Green
} else {
    Write-Host "❌ ERROR: build.sh not found" -ForegroundColor Red
    $Errors++
}

if (Test-Path "start.sh") {
    Write-Host "✅ start.sh script exists" -ForegroundColor Green
} else {
    Write-Host "❌ ERROR: start.sh not found" -ForegroundColor Red
    $Errors++
}

Write-Host ""

# Check .gitignore
if (Test-Path ".gitignore") {
    $gitignoreContent = Get-Content ".gitignore" -Raw
    
    if ($gitignoreContent -match "^\.env$" -or $gitignoreContent -match "`n\.env`n") {
        Write-Host "✅ .env is in .gitignore" -ForegroundColor Green
    } else {
        Write-Host "❌ ERROR: .env is NOT in .gitignore (security risk!)" -ForegroundColor Red
        $Errors++
    }
    
    if ($gitignoreContent -match "node_modules") {
        Write-Host "✅ node_modules is in .gitignore" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Warning: node_modules should be in .gitignore" -ForegroundColor Yellow
        $Warnings++
    }
    
    if ($gitignoreContent -match "vendor") {
        Write-Host "✅ vendor is in .gitignore" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Warning: vendor should be in .gitignore" -ForegroundColor Yellow
        $Warnings++
    }
} else {
    Write-Host "❌ ERROR: .gitignore not found" -ForegroundColor Red
    $Errors++
}

Write-Host ""

# Check migrations directory
if (Test-Path "database/migrations") {
    $migrationCount = (Get-ChildItem "database/migrations/*.php" -ErrorAction SilentlyContinue).Count
    if ($migrationCount -gt 0) {
        Write-Host "✅ Found $migrationCount migration files" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Warning: No migration files found" -ForegroundColor Yellow
        $Warnings++
    }
} else {
    Write-Host "❌ ERROR: database/migrations directory not found" -ForegroundColor Red
    $Errors++
}

Write-Host ""
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host "Validation Summary:" -ForegroundColor Cyan
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan

if ($Errors -eq 0 -and $Warnings -eq 0) {
    Write-Host "✅ All checks passed! Application is ready for production deployment." -ForegroundColor Green
    Write-Host ""
    Write-Host "Next steps:"
    Write-Host "1. Push code to GitHub/GitLab"
    Write-Host "2. Setup MySQL database"
    Write-Host "3. Create Web Service on Render"
    Write-Host "4. Set environment variables in Render"
    Write-Host "5. Deploy!"
    Write-Host ""
    Write-Host "See RENDER_DEPLOYMENT.md for detailed instructions."
    exit 0
} elseif ($Errors -eq 0) {
    Write-Host "WARNING: $Warnings warning(s) found, but no critical errors." -ForegroundColor Yellow
    Write-Host "Review warnings above before deploying."
    exit 0
} else {
    Write-Host "ERROR: $Errors error(s) and $Warnings warning(s) found." -ForegroundColor Red
    Write-Host "Fix errors before deploying to production"
    exit 1
}
