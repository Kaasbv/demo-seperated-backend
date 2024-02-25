
FROM php:8.0-apache

# Copy the current directory contents into the container at /app

RUN mkdir -p /var/www/html/api

COPY . /var/www/html/api

# Set the working directory
WORKDIR /var/www/html/api

# Install mysqli
RUN docker-php-ext-install mysqli

# Create a file
RUN echo "api" > /var/www/html/index.html

# Expose port 80
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]