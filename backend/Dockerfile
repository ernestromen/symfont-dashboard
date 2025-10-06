FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    curl \
    && docker-php-ext-install intl pdo pdo_mysql opcache zip


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

WORKDIR /app

COPY . .

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
