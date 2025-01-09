# Imagem base com PHP e Apache
FROM php:8.3-apache

# Configuração de ambiente
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APACHE_DOCUMENT_ROOT=/var/www/html/web

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mbstring pdo pdo_pgsql opcache zip xml \
    && apt-get clean

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar Apache para o diretório Drupal
RUN a2enmod rewrite
# Configurar OPCache para produção
RUN echo "opcache.memory_consumption=128\n\
opcache.interned_strings_buffer=8\n\
opcache.max_accelerated_files=10000\n\
opcache.revalidate_freq=0\n\
opcache.enable_cli=1" > /usr/local/etc/php/conf.d/opcache.ini

# Copiar código fonte do projeto
WORKDIR /var/www/html
COPY . .

# Configuração de permissões para o Drupal
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
RUN mkdir -p /var/www/html/web/sites/default/files && \
    chmod -R 775 /var/www/html/web/sites/default/files && \
    chown -R www-data:www-data /var/www/html/web/sites/default/files

# Instalar dependências do Composer com otimizações para produção
RUN composer install --no-dev --optimize-autoloader

# Expor porta
EXPOSE 8080

COPY ./docker/apache/drupal.conf /etc/apache2/sites-available/000-default.conf
COPY ./docker/apache/ports.conf /etc/apache2/ports.conf

# Comando para iniciar o servidor
CMD ["bash", "-c", "chmod -R 775 /var/www/html/web/sites/default/files && chown -R www-data:www-data /var/www/html/web/sites/default/files; vendor/bin/drush config:set system.site uuid b72d5806-0979-414f-8365-150c94f75459; vendor/bin/drush config:import --partial -y & apache2-foreground"]
