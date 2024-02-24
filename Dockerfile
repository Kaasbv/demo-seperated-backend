
FROM php:7.4-apache

# Copy the current directory contents into the container at /app
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Install mysqli
RUN docker-php-ext-install mysqli

# Expose port 80
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]