# Use official PHP image with Apache
FROM php:8.1-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        git \
        curl \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . . /var/www/html

# Expose port 80
EXPOSE 80



