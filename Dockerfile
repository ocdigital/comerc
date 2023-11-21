# Use the official PHP image with Apache
FROM php:8.1-apache

# Enable mod_rewrite for Laravel
RUN a2enmod rewrite

# Install necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install the GD extension
RUN apt-get update && \
    apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd

# Install additional dependencies
RUN apt-get update && \
    apt-get install -y \
        zip \
        unzip \
        git \
    && rm -rf /var/lib/apt/lists/*

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Configure Apache environment variables
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy application files to the container
COPY . /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel dependencies
RUN composer install

# Copy the environment file
COPY .env.example .env

# Generate the application key
RUN php artisan key:generate

# Set Apache user as the owner of Laravel files
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Command to start Apache
CMD ["apache2-foreground"]
