FROM php:8.2-apache

RUN docker-php-ext-install mysqli

COPY . /var/www/html/

# Permite que PHP lea las variables de entorno definidas en Render
RUN printf '%s\n' \
    'PassEnv DB_HOST' \
    'PassEnv DB_USER' \
    'PassEnv DB_PASSWORD' \
    'PassEnv DB_NAME' \
    'PassEnv DB_PORT' \
    'PassEnv DB_SSL' \
    'PassEnv DB_SSL_CA' \
    > /etc/apache2/conf-available/render-env.conf \
    && a2enconf render-env
