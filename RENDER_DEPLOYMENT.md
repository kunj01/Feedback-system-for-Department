# Render Deployment Guide

This guide will help you deploy the Laravel application to Render with MySQL database.

## Prerequisites

1. A Render account (sign up at https://render.com)
2. This Laravel application repository pushed to GitHub/GitLab
3. Composer and Node.js installed locally for testing

## Step 1: Prepare Your Repository

Ensure all changes are committed and pushed to your GitHub/GitLab repository:

```bash
git add .
git commit -m "Prepare for Render deployment"
git push origin main
```

## Step 2: Create MySQL Database on Render

1. Go to your Render Dashboard
2. Click **New** → **PostgreSQL** or use an external MySQL provider
3. For MySQL, you have two options:
   - Use a managed MySQL provider (PlanetScale, AWS RDS, etc.) and connect via environment variables
   - Use Render's PostgreSQL and migrate to it (requires code changes)
   
**Recommended:** Use an external MySQL database like PlanetScale (free tier available) or Railway

### Using PlanetScale (Recommended)

1. Sign up at https://planetscale.com
2. Create a new database
3. Get the connection string
4. Use the connection details for Render environment variables

## Step 3: Create Web Service on Render

1. Go to Render Dashboard
2. Click **New** → **Web Service**
3. Connect your GitHub/GitLab repository
4. Configure the service:

### Build & Deploy Settings

- **Name:** `your-app-name`
- **Region:** Choose closest to your users
- **Branch:** `main` (or your default branch)
- **Root Directory:** Leave empty (or specify if monorepo)
- **Runtime:** `PHP`
- **Build Command:**
  ```bash
  composer install --no-dev --optimize-autoloader && npm install && npm run build && php artisan config:cache && php artisan route:cache && php artisan view:cache
  ```
- **Start Command:**
  ```bash
  php artisan migrate --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=10000
  ```

### Environment Variables

Add the following environment variables in Render Dashboard:

```bash
# Application
APP_NAME="Your App Name"
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com

# Database (MySQL)
DB_CONNECTION=mysql
DB_HOST=your-mysql-host.com
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=.onrender.com
SESSION_SAME_SITE=lax
CACHE_STORE=database
CACHE_PREFIX=

# Queue
QUEUE_CONNECTION=database

# Logging
LOG_CHANNEL=stderr
LOG_LEVEL=error

# Sanctum
SANCTUM_STATEFUL_DOMAINS=your-app-name.onrender.com

# Mail (configure as needed)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourapp.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Important:** Generate a new APP_KEY:
```bash
php artisan key:generate --show
```
Copy the output and use it for the `APP_KEY` environment variable in Render.

## Step 4: Configure Health Check

In Render Dashboard under **Health Check Path**, set:
```
/
```

## Step 5: Deploy

1. Click **Create Web Service**
2. Render will automatically deploy your application
3. Monitor the logs for any errors
4. Once deployed, visit your app URL: `https://your-app-name.onrender.com`

## Step 6: Initial Setup After Deployment

After first successful deployment, you need to create the admin user and setup roles:

### Option 1: Using Render Shell
1. Go to your Web Service in Render Dashboard
2. Click **Shell** tab
3. Run:
```bash
php artisan db:seed --class=RoleSeeder
php artisan tinker
```
Then create admin user:
```php
$user = new App\Models\User();
$user->name = 'Admin User';
$user->email = 'admin@example.com';
$user->password = bcrypt('your-secure-password');
$user->save();
$user->assignRole('admin');
```

### Option 2: Create a seeder and run via command
Create a deployment command that seeds initial data.

## Step 7: Storage Configuration

For production, consider using cloud storage (S3, Cloudinary, etc.) instead of local storage:

1. Update `.env` on Render:
```bash
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
```

2. Ensure `flysystem-aws-s3-v3` is installed (it's likely already included with Laravel)

## Troubleshooting

### Build Fails
- Check PHP version compatibility (requires PHP 8.2+)
- Verify all dependencies in `composer.json` are correct
- Check build logs for specific errors

### Database Connection Issues
- Verify database credentials are correct
- Ensure database host is accessible from Render
- Check if database allows connections from Render IPs

### 500 Errors After Deployment
- Check logs: `php artisan log:tail` or view logs in Render Dashboard
- Verify APP_KEY is set correctly
- Ensure migrations ran successfully
- Check file permissions

### Vite Assets Not Loading
- Ensure `npm run build` completed successfully
- Check APP_URL is set correctly
- Verify `public/build` directory exists and has assets

### Session Issues
- Ensure `sessions` table exists (run migrations)
- Verify SESSION_DOMAIN and SANCTUM_STATEFUL_DOMAINS are set correctly
- Check SESSION_SECURE_COOKIE is `true` for HTTPS

## Performance Optimization

For better performance on Render:

1. **Enable Opcache** - Add to build command:
   ```bash
   echo "opcache.enable=1" >> /etc/php.ini
   ```

2. **Use Redis for Cache & Sessions** (Render paid plan):
   - Create Redis instance on Render
   - Update `.env`:
     ```bash
     CACHE_STORE=redis
     SESSION_DRIVER=redis
     REDIS_HOST=your-redis-host
     REDIS_PASSWORD=your-redis-password
     ```

3. **Asset Optimization**:
   - Vite already minifies and optimizes assets during build
   - Consider using a CDN for static assets

## Maintenance Mode

To enable maintenance mode:
```bash
php artisan down --secret="your-secret-token"
```

Access during maintenance:
```
https://your-app-name.onrender.com/your-secret-token
```

To disable maintenance mode:
```bash
php artisan up
```

## Backup Strategy

### Database Backups
- Set up regular backups for your MySQL database
- For PlanetScale, backups are automatic
- For other providers, configure scheduled backups

### Application Files
- Use S3 or similar cloud storage for uploaded files
- Keep repository backed up on GitHub/GitLab

## Continuous Deployment

Render automatically deploys when you push to your connected branch:

```bash
git add .
git commit -m "Update feature"
git push origin main
```

Render will automatically:
1. Pull latest code
2. Run build command
3. Run start command
4. Deploy new version with zero downtime

## Security Checklist

- ✅ APP_ENV is set to `production`
- ✅ APP_DEBUG is set to `false`
- ✅ APP_KEY is generated and set
- ✅ Database credentials are secure
- ✅ SESSION_SECURE_COOKIE is `true`
- ✅ HTTPS is enabled (automatic on Render)
- ✅ CORS is configured if needed
- ✅ Rate limiting is enabled
- ✅ File upload validation is in place

## Cost Considerations

- Render free tier spins down after inactivity (cold starts)
- Consider paid tier for production apps that need 24/7 uptime
- External database providers may have separate costs
- Monitor usage and costs regularly

## Support

- Render Docs: https://render.com/docs
- Laravel Docs: https://laravel.com/docs
- Community Support: Laravel Forums, Render Community

---

**Last Updated:** January 2026
