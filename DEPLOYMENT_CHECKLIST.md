# Production Deployment Checklist

Use this checklist before deploying to production on Render.

## Pre-Deployment Checklist

### 1. Environment Configuration
- [ ] `.env` file is NOT committed (check `.gitignore`)
- [ ] `.env.example` is updated with all required variables
- [ ] `APP_ENV` is set to `production`
- [ ] `APP_DEBUG` is set to `false`
- [ ] `APP_KEY` is generated (run `php artisan key:generate`)
- [ ] `APP_URL` is set to your Render URL

### 2. Database Configuration
- [ ] `DB_CONNECTION` is set to `mysql`
- [ ] MySQL database credentials are ready
- [ ] Database is accessible from Render
- [ ] All migrations are tested locally with MySQL
- [ ] Database tables use correct charset (utf8mb4)

### 3. Frontend Build
- [ ] Run `npm install` locally
- [ ] Run `npm run build` and verify it completes successfully
- [ ] Check `public/build` directory contains compiled assets
- [ ] Verify Tailwind CSS v4 is configured correctly
- [ ] Test all pages load assets correctly

### 4. Authentication & Authorization
- [ ] Sanctum migrations are present
- [ ] Spatie Permission migrations are present
- [ ] `SANCTUM_STATEFUL_DOMAINS` is configured
- [ ] Session configuration is production-ready
- [ ] Cookie settings are secure (HTTPS only)

### 5. Security
- [ ] All sensitive data is in environment variables
- [ ] No hardcoded passwords or API keys in code
- [ ] CSRF protection is enabled
- [ ] Rate limiting is configured
- [ ] File upload validation is in place
- [ ] SQL injection protection (use Eloquent/Query Builder)

### 6. Storage & Files
- [ ] Storage disk is configured (`local` or `s3`)
- [ ] Storage symlink will be created on deployment
- [ ] File uploads are validated (size, type)
- [ ] Consider cloud storage for production

### 7. Caching & Performance
- [ ] Cache driver is configured (database recommended)
- [ ] Session driver is configured (database recommended)
- [ ] Queue driver is configured (database or Redis)
- [ ] Config caching is in build script
- [ ] Route caching is in build script
- [ ] View caching is in build script

### 8. Logging & Monitoring
- [ ] `LOG_CHANNEL` is set to `stderr` (for Render)
- [ ] `LOG_LEVEL` is set to `error` or `warning`
- [ ] Error pages are user-friendly (not showing debug info)

### 9. Email Configuration
- [ ] Mail driver is configured
- [ ] SMTP credentials are set (if using SMTP)
- [ ] Test email sending works
- [ ] `MAIL_FROM_ADDRESS` is set correctly

### 10. Dependencies
- [ ] All Composer dependencies are compatible with PHP 8.2+
- [ ] No dev dependencies in production build
- [ ] Lock files are committed (`composer.lock`, `package-lock.json`)

## Deployment Commands

### Build Command (Render)
```bash
composer install --no-dev --optimize-autoloader && npm install && npm run build && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

Or use:
```bash
bash build.sh
```

### Start Command (Render)
```bash
php artisan migrate --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=10000
```

Or use:
```bash
bash start.sh
```

## Testing Before Deployment

### Local Tests with MySQL
1. Create a local MySQL database
2. Update `.env` to use MySQL:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_test_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Run migrations: `php artisan migrate:fresh`
4. Test the application thoroughly

### Production-like Environment
1. Set environment to production: `APP_ENV=production`
2. Disable debug: `APP_DEBUG=false`
3. Clear all caches: `php artisan optimize:clear`
4. Build frontend: `npm run build`
5. Test all critical features

### Database Migration Test
```bash
# Test migrations on fresh database
php artisan migrate:fresh --seed

# Test rollback (if needed)
php artisan migrate:rollback

# Re-run migrations
php artisan migrate
```

### Frontend Build Test
```bash
# Clean previous builds
rm -rf public/build node_modules

# Fresh install and build
npm install
npm run build

# Verify build artifacts
ls -la public/build
```

## Post-Deployment Verification

After deployment to Render:

### 1. Health Checks
- [ ] Application loads successfully
- [ ] No 500 errors in logs
- [ ] Database connection works
- [ ] Assets load correctly (CSS, JS, images)

### 2. Authentication Tests
- [ ] User registration works
- [ ] User login works
- [ ] Password reset works (if implemented)
- [ ] Session persistence works

### 3. Core Features
- [ ] Test all CRUD operations
- [ ] Test file uploads (if applicable)
- [ ] Test form submissions
- [ ] Test data validation

### 4. Performance
- [ ] Page load times are acceptable
- [ ] No memory leaks
- [ ] Database queries are optimized
- [ ] Assets are cached properly

### 5. Initial Data Setup
- [ ] Run seeders (if needed): `php artisan db:seed`
- [ ] Create admin user
- [ ] Assign roles and permissions
- [ ] Test admin panel access

## Troubleshooting Common Issues

### Issue: 500 Internal Server Error
**Solutions:**
- Check Render logs
- Verify APP_KEY is set
- Ensure migrations ran successfully
- Check file permissions
- Verify all environment variables are set

### Issue: Assets Not Loading (404)
**Solutions:**
- Verify `npm run build` completed successfully
- Check `APP_URL` matches your Render URL
- Clear browser cache
- Check `public/build` directory exists

### Issue: Database Connection Failed
**Solutions:**
- Verify database credentials
- Check database host is accessible
- Ensure database exists
- Test connection with MySQL client

### Issue: Session/Cookie Issues
**Solutions:**
- Set `SESSION_SECURE_COOKIE=true` for HTTPS
- Configure `SESSION_DOMAIN` correctly
- Set `SANCTUM_STATEFUL_DOMAINS`
- Clear browser cookies

### Issue: Permission Denied Errors
**Solutions:**
- Run `php artisan permission:cache-reset`
- Ensure roles and permissions are seeded
- Check user has correct roles assigned
- Verify middleware configuration

## Rollback Plan

If deployment fails:

1. **Revert Code:**
   ```bash
   git revert HEAD
   git push origin main
   ```

2. **Revert Database (if needed):**
   ```bash
   php artisan migrate:rollback --step=1
   ```

3. **Clear Caches:**
   ```bash
   php artisan optimize:clear
   ```

## Maintenance Mode

To enable maintenance mode during updates:
```bash
php artisan down --secret="your-secret-token"
```

Access site during maintenance:
```
https://your-app-name.onrender.com/your-secret-token
```

Disable maintenance mode:
```bash
php artisan up
```

## Performance Optimization

### Enable Opcache
Add to PHP configuration or `.htaccess`:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
```

### Database Optimization
```bash
# Optimize database tables
php artisan db:optimize

# Clear query cache
php artisan cache:clear
```

### Asset Optimization
- Use CDN for static assets
- Enable browser caching
- Compress images before uploading

## Security Hardening

### After Deployment
1. Change default admin password
2. Remove any test users/data
3. Enable HTTPS (automatic on Render)
4. Configure CORS if needed
5. Set up regular database backups
6. Monitor application logs
7. Keep dependencies updated

### Regular Maintenance
- Update Laravel framework monthly
- Update Composer dependencies
- Update NPM dependencies
- Review security advisories
- Monitor error logs
- Test backup restoration

---

**Note:** This checklist is comprehensive but may need adjustments based on your specific application requirements.

