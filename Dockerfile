FROM composer

LABEL version=v1.0.1
LABEL app=CyberShop

# Create the web directory to serve the app
RUN mkdir -p /var/www/html

WORKDIR /var/www/html/

# Copy all the files from the git repo to the container
COPY . .

# Create the php ini config file and install the extensions dependencies
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
RUN docker-php-ext-install pdo_mysql

# Update and install the laravel deps
RUN composer update && composer install --no-dev

# Publish the storage directory
RUN php artisan storage:link

# Install deps for the wait-for-mysql script
RUN apk add mariadb-client && apk cache clean
RUN mv wait-for-mysql.sh /

# Ensure that the MySQL container is started and launch migration
RUN echo "/wait-for-mysql.sh" > /init.sh 
# Serve the API
RUN echo "php artisan serve --host 0.0.0.0 --port 8000&" >> /init.sh
# Serve the app
RUN echo "php artisan serve --host 0.0.0.0 --port 80" >> /init.sh

RUN chmod +x /wait-for-mysql.sh
RUN chmod +x /init.sh

# Luanch it
CMD ["/init.sh"]
