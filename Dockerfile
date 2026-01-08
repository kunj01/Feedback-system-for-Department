FROM php:8.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl nodejs npm \
    libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Enable Apache rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 🔥 BUILD FRONTEND (THIS FIXES VITE ERROR)
RUN npm install
RUN npm run build

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache public

# Apache config
COPY apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
CMD ["apache2-foreground"]

