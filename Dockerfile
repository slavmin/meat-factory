FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    libzip-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

#RUN apt-get update && apt-get install -y \
#    libfreetype6-dev \
#    libjpeg62-turbo-dev \
#    libpng-dev \
#    apt-get clean && \
#    rm -rf /var/lib/apt/lists/*

#RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - && \
#    apt-get install -y nodejs

RUN docker-php-ext-install \
      xml \
      pgsql \
      pdo_pgsql \
      mbstring \
      pcntl \
      bcmath \
      zip

#RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
#    docker-php-ext-install \
#    exif \
#    gd \

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
EXPOSE 9000
