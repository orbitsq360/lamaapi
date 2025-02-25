# Use an official PHP image from Docker Hub
FROM php:8.0-apache

# Install required PHP extensions and tools
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite for clean URLs (if required)
RUN a2enmod rewrite

# Copy the current directory contents into the /var/www/html directory
COPY . /var/www/html/

# Expose port 80 to be able to access the app through HTTP
EXPOSE 80
