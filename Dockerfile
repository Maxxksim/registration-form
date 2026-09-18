FROM php:8.5-cli

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction

COPY . .
RUN composer dump-autoload --optimize

COPY startserver.sh /usr/local/bin/startserver.sh
RUN sed -i 's/\r$//' /usr/local/bin/startserver.sh && chmod +x /usr/local/bin/startserver.sh

EXPOSE 8000
ENTRYPOINT ["/usr/local/bin/startserver.sh"]
