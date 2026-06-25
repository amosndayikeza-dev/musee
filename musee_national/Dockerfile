FROM php:8.2-apache

# 1. Installation des dépendances système nécessaires pour les extensions PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# 2. Activation du module rewrite d'Apache (indispensable pour les routes MVC)
RUN a2enmod rewrite

# 3. Installation de Composer pour charger tes dépendances (JWT, Dompdf, etc.)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Copie des fichiers du projet dans le dossier web d'Apache
COPY . /var/www/html/

# 5. Exécution de composer install pour installer tes paquets automatiquement
RUN composer install --no-interaction --optimize-autoloader

# 6. Configuration des permissions
RUN chown -R www-data:www-data /var/www/html