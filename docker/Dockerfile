FROM --platform=linux/arm64 php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install Node.js 22
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash -
RUN apt-get install -y nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy only necessary files first
COPY composer.* ./
RUN composer install --no-scripts --no-autoloader

COPY package*.json ./
RUN npm install
RUN npm rebuild esbuild

COPY .. .

RUN composer dump-autoload --optimize
RUN npm run build

EXPOSE 9000
