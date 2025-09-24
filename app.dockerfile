# Use the official PHP image
FROM php:8.2-apache

# Copy app files to the container
COPY . /var/www/html/

# Expose the default Apache port
EXPOSE 80
