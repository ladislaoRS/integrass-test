FROM php:7.4-fpm

ENV APP_DIR /var/www/app
ENV APP_USER www-data

ENV NGINX_PATH=/etc/nginx

RUN mkdir -p $APP_DIR

# Set working directory
WORKDIR $APP_DIR

# Install dependencies
RUN apt-get update && apt-get install -y \
    libonig-dev \
    zlib1g-dev \
    libzip-dev \
    build-essential \
    default-mysql-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    nginx \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd opcache

RUN rm $NGINX_PATH/sites-enabled/*

COPY etc/nginx $NGINX_PATH
COPY etc/php/* /usr/local/etc/php/
COPY etc/php-fpm.d/* /usr/local/etc/php-fpm.d/
COPY etc/supervisord/* /etc/supervisor/conf.d/

RUN ln -s $NGINX_PATH/sites-available/dev-api.conf $NGINX_PATH/sites-enabled/dev-api.conf && \
    nginx -t

COPY database ./database

COPY composer.lock composer.json ./

RUN composer install

# Copy existing application directory contents
COPY . .

RUN pecl install -f xdebug && \
echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini

RUN php artisan package:discover --ansi \
    && chown -R $APP_USER:$APP_USER storage bootstrap/cache

# Expose port 80 and start php-fpm server
EXPOSE 80

ENTRYPOINT ["./docker-entrypoint.sh"]

# CMD ["php-fpm"]
CMD [ "/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf" ]

