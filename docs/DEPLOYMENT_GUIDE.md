# Deployment Guide
## Training & Placement Tracking System

**Version:** 1.0  
**Last Updated:** December 9, 2025  
**Target Environment:** Production Server (Linux)

---

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Server Requirements](#server-requirements)
3. [Initial Server Setup](#initial-server-setup)
4. [Application Deployment](#application-deployment)
5. [Web Server Configuration](#web-server-configuration)
6. [SSL/HTTPS Setup](#ssl-https-setup)
7. [Database Configuration](#database-configuration)
8. [File Permissions](#file-permissions)
9. [Performance Optimization](#performance-optimization)
10. [Monitoring & Logging](#monitoring--logging)
11. [Backup Configuration](#backup-configuration)
12. [Troubleshooting](#troubleshooting)

---

## Prerequisites

### Required Software
- **Ubuntu 20.04/22.04 LTS** or similar Linux distribution
- **PHP 8.2** or higher
- **MySQL 8.0** or higher
- **Nginx** or **Apache 2.4**
- **Composer 2.8**
- **Node.js 18+** and **npm**
- **Git**
- **SSL Certificate** (Let's Encrypt recommended)

### Required PHP Extensions
```bash
php8.2-cli
php8.2-fpm
php8.2-mysql
php8.2-mbstring
php8.2-xml
php8.2-curl
php8.2-zip
php8.2-gd
php8.2-bcmath
php8.2-intl
php8.2-opcache
```

---

## Server Requirements

### Minimum Specifications
- **CPU:** 2 cores
- **RAM:** 4 GB
- **Storage:** 40 GB SSD
- **Bandwidth:** 100 Mbps

### Recommended Specifications
- **CPU:** 4 cores
- **RAM:** 8 GB
- **Storage:** 100 GB SSD
- **Bandwidth:** 1 Gbps

---

## Initial Server Setup

### 1. Update System Packages
```bash
sudo apt update && sudo apt upgrade -y
```

### 2. Install Required Software
```bash
# Install PHP 8.2 and extensions
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath \
    php8.2-intl php8.2-opcache

# Install MySQL
sudo apt install -y mysql-server

# Install Nginx
sudo apt install -y nginx

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js and npm
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install Git
sudo apt install -y git
```

### 3. Configure Firewall
```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

---

## Application Deployment

### 1. Create Application Directory
```bash
sudo mkdir -p /var/www/training-placement
sudo chown -R $USER:$USER /var/www/training-placement
cd /var/www/training-placement
```

### 2. Clone Repository
```bash
# If using Git
git clone https://github.com/your-username/training-placement.git .

# Or upload files manually via SFTP/SCP
```

### 3. Install Dependencies
```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node.js dependencies and build assets
npm install
npm run build
```

### 4. Environment Configuration
```bash
# Copy production environment file
cp .env.production.example .env

# Edit .env file with production values
nano .env

# Generate application key
php artisan key:generate
```

### 5. Run Database Migrations
```bash
# Run migrations
php artisan migrate --force

# Run seeders (for initial data only)
php artisan db:seed --force
```

### 6. Create Storage Symlink
```bash
php artisan storage:link
```

### 7. Optimize Application
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload -o
```

---

## Web Server Configuration

### Nginx Configuration

Create file: `/etc/nginx/sites-available/training-placement`

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com www.your-domain.com;
    
    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name your-domain.com www.your-domain.com;

    root /var/www/training-placement/public;
    index index.php index.html;

    # SSL Configuration (Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # Logging
    access_log /var/log/nginx/training-placement-access.log;
    error_log /var/log/nginx/training-placement-error.log;

    # Increase upload size
    client_max_body_size 20M;

    # Main location
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM configuration
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Deny access to hidden files
    location ~ /\. {
        deny all;
    }

    # Cache static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable the site:
```bash
sudo ln -s /etc/nginx/sites-available/training-placement /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### Apache Configuration (Alternative)

Create file: `/etc/apache2/sites-available/training-placement.conf`

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    Redirect permanent / https://your-domain.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    DocumentRoot /var/www/training-placement/public

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/your-domain.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/your-domain.com/privkey.pem

    <Directory /var/www/training-placement/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/training-placement-error.log
    CustomLog ${APACHE_LOG_DIR}/training-placement-access.log combined
</VirtualHost>
```

Enable required modules and site:
```bash
sudo a2enmod rewrite ssl headers
sudo a2ensite training-placement
sudo systemctl restart apache2
```

---

## SSL/HTTPS Setup

### Using Let's Encrypt (Recommended)

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtain SSL certificate (for Nginx)
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# For Apache
# sudo certbot --apache -d your-domain.com -d www.your-domain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

### Manual SSL Certificate

If using a purchased SSL certificate:
1. Upload certificate files to `/etc/ssl/certs/`
2. Upload private key to `/etc/ssl/private/`
3. Update Nginx/Apache configuration with correct paths

---

## Database Configuration

### 1. Secure MySQL Installation
```bash
sudo mysql_secure_installation
```

### 2. Create Database and User
```bash
sudo mysql -u root -p
```

```sql
-- Create database
CREATE DATABASE training_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user with strong password
CREATE USER 'production_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';

-- Grant privileges
GRANT ALL PRIVILEGES ON training_laravel.* TO 'production_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

-- Exit
EXIT;
```

### 3. Update .env File
```bash
nano /var/www/training-placement/.env
```

Update database credentials:
```
DB_DATABASE=training_laravel
DB_USERNAME=production_user
DB_PASSWORD=STRONG_PASSWORD_HERE
```

---

## File Permissions

### Set Correct Ownership
```bash
cd /var/www/training-placement

# Set ownership
sudo chown -R www-data:www-data .

# Set directory permissions
sudo find . -type d -exec chmod 755 {} \;

# Set file permissions
sudo find . -type f -exec chmod 644 {} \;

# Make storage and bootstrap/cache writable
sudo chmod -R 775 storage bootstrap/cache
```

---

## Performance Optimization

### 1. Enable PHP OPcache

Edit `/etc/php/8.2/fpm/php.ini`:
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.validate_timestamps=0
opcache.revalidate_freq=0
opcache.save_comments=1
```

Restart PHP-FPM:
```bash
sudo systemctl restart php8.2-fpm
```

### 2. Configure MySQL for Performance

Edit `/etc/mysql/mysql.conf.d/mysqld.cnf`:
```ini
[mysqld]
innodb_buffer_pool_size=1G
innodb_log_file_size=256M
innodb_flush_method=O_DIRECT
innodb_flush_log_at_trx_commit=2
max_connections=200
query_cache_size=0
query_cache_type=0
```

Restart MySQL:
```bash
sudo systemctl restart mysql
```

### 3. Laravel Optimization Commands
```bash
cd /var/www/training-placement

# Cache everything
php artisan optimize

# Verify caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Monitoring & Logging

### 1. Configure Laravel Logging

Update `.env`:
```
LOG_CHANNEL=daily
LOG_LEVEL=error
```

### 2. Set Up Log Rotation

Create `/etc/logrotate.d/training-placement`:
```
/var/www/training-placement/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

### 3. Error Monitoring (Optional - Sentry)

Install Sentry package:
```bash
composer require sentry/sentry-laravel
```

Configure in `.env`:
```
SENTRY_LARAVEL_DSN=your-sentry-dsn
```

---

## Backup Configuration

### 1. Database Backup Script

Create `/usr/local/bin/backup-database.sh`:
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/training-placement"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="training_laravel"
DB_USER="production_user"
DB_PASS="YOUR_PASSWORD"

mkdir -p $BACKUP_DIR

# Create backup
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_backup_$DATE.sql.gz

# Keep only last 7 days
find $BACKUP_DIR -name "db_backup_*.sql.gz" -mtime +7 -delete

echo "Backup completed: db_backup_$DATE.sql.gz"
```

Make executable:
```bash
sudo chmod +x /usr/local/bin/backup-database.sh
```

### 2. Schedule Daily Backups

Add to crontab:
```bash
sudo crontab -e
```

Add line:
```
0 2 * * * /usr/local/bin/backup-database.sh >> /var/log/backup.log 2>&1
```

---

## Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check Nginx/Apache logs
tail -f /var/log/nginx/training-placement-error.log

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 2. Permission Denied Errors
```bash
# Fix storage permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### 3. Database Connection Failed
```bash
# Test MySQL connection
mysql -u production_user -p training_laravel

# Verify .env database credentials
cat .env | grep DB_
```

#### 4. File Upload Issues
```bash
# Check PHP upload limits
php -i | grep upload_max_filesize
php -i | grep post_max_size

# Update if needed in /etc/php/8.2/fpm/php.ini
upload_max_filesize = 20M
post_max_size = 20M

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

---

## Post-Deployment Checklist

- [ ] Application accessible via HTTPS
- [ ] HTTP redirects to HTTPS
- [ ] SSL certificate valid and auto-renewing
- [ ] Admin login working
- [ ] All user roles can log in
- [ ] File uploads working
- [ ] File downloads working
- [ ] Database backups scheduled
- [ ] Log rotation configured
- [ ] Error monitoring active (if using Sentry)
- [ ] All pages load without errors
- [ ] Security headers enabled
- [ ] Rate limiting configured (optional)
- [ ] Email notifications working (if configured)

---

## Support & Maintenance

### Regular Maintenance Tasks

**Daily:**
- Monitor error logs
- Check disk space
- Verify backups completed

**Weekly:**
- Review access logs for suspicious activity
- Update system packages
- Test backup restoration

**Monthly:**
- Security audit
- Performance review
- Database optimization

### Update Procedure

```bash
cd /var/www/training-placement

# Backup database first
/usr/local/bin/backup-database.sh

# Put in maintenance mode
php artisan down

# Pull latest changes
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Run migrations
php artisan migrate --force

# Clear caches
php artisan optimize

# Bring back online
php artisan up
```

---

**Document Version:** 1.0  
**Last Updated:** December 9, 2025  
**Maintained By:** Development Team
