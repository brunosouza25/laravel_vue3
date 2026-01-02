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
 && docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl gd bcmath \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Criar usuário com mesmo UID do host
ARG USER_ID=1000
ARG GROUP_ID=1000

RUN addgroup -g ${GROUP_ID} www && adduser -u ${USER_ID} -G www -s /bin/sh -D www

RUN mkdir -p /home/www/.composer/cache && chown -R www:www /home/www/.composer

ENV COMPOSER_CACHE_DIR=/home/www/.composer/cache
ENV PATH="/home/www/.composer/vendor/bin:${PATH}"

WORKDIR /var/www


USER www

RUN composer global require laravel/installer

EXPOSE 9000

CMD ["php-fpm"]
