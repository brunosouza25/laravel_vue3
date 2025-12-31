FROM php:8.4-fpm-alpine

RUN apk update && apk add --no-cache \
    build-base \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    lcms2 \
    bash \
    vim \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    libexif \
    oniguruma-dev \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl gd \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN addgroup -g 1000 www && adduser -u 1000 -G www -s /bin/sh -D www

RUN mkdir -p /home/www/.composer/cache && chown -R www:www /home/www/.composer

ENV COMPOSER_CACHE_DIR=/home/www/.composer/cache
ENV PATH="/home/www/.composer/vendor/bin:${PATH}"

WORKDIR /var/www

COPY --chown=www:www . /var/www

RUN chmod -R 775 /var/www/app

USER www

RUN composer global require laravel/installer

EXPOSE 9000

CMD ["php-fpm"]
