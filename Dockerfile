FROM php:8.2-cli

# Install required PHP extensions and system packages
RUN apt-get update && apt-get install -y \
    git curl zip unzip libsqlite3-dev sqlite3 libonig-dev libzip-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . /app

# Install Laravel dependencies
RUN composer install && php artisan key:generate

# Expose the port Laravel will use
EXPOSE 8000

# Run Laravel's built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]