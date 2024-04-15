FROM composer

# Create the web dir
RUN mkdir -p /var/www/html

# Set the working directory to /var/www/html
WORKDIR /var/www/html/

# Copy all the files from the git repo to the container
COPY . .

# Create the php ini config file
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
# Install required extensions for laravel
RUN docker-php-ext-install pdo_mysql

# Update and install the laravel deps
RUN composer update
RUN composer install --no-dev

# Publish the storage directory
RUN php artisan storage:link

# Create an init script that runs the migration and launch the
# http server
RUN echo "php artisan migrate" > /init.sh
RUN echo "php artisan serve --host 0.0.0.0 --port 8000&" >> /init.sh
RUN echo "php artisan serve --host 0.0.0.0 --port 80" >> /init.sh
RUN chmod +x /init.sh

# Luanch it
CMD ["/init.sh"]
