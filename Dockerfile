FROM php:8.0-fpm

ARG user
ARG uid

RUN apt update
RUN apt install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libxml2-dev \
    libxslt1-dev \
    libzip-dev \
    libonig-dev \
    graphviz \
    curl

RUN docker-php-ext-install gd zip pdo_mysql mbstring exif pcntl bcmath soap xml xsl
RUN apt clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.2.14 /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www/app

COPY . .
RUN composer install

USER $user
