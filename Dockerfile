FROM php:8.4-apache

# Instala extensões necessárias
RUN apt-get update && apt-get install -y \
libpq-dev \
&& docker-php-ext-install pdo pdo_pgsql

RUN apt-get update && apt-get install -y \
    less \
    nano \
    vim \
    procps

# Ativa mod_rewrite
RUN a2enmod rewrite

# Define DocumentRoot correto (exemplo usando "public")
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN mkdir -p /var/store/logs \
    && chown -R www-data:www-data /var/store/logs \
    && chmod -R 775 /var/store/logs


# Copia composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Instala dependências (opcional no desenvolvimento)
# RUN composer install

USER www-data

EXPOSE 80
