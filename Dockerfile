# Stage 1 get the base image
FROM php:8.2-apache as php-8.2

WORKDIR /var/www/html

# get rid of apache's warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# make sure we're connecting to the apt repos via https
#RUN sed -i 's|http://|https://|g' /etc/apt/sources.list

# update all of repo info and all of the installed packages from the php image
RUN apt-get -y --fix-missing update \
    && apt-get -y upgrade

# Install the required packages
RUN apt-get -y --no-install-recommends install \
    git \
    curl \
    libcurl4-openssl-dev \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    libzip-dev

# clear the apt cache
RUN rm -rf /var/lib/apt/lists/*

# Install the needed php extensions
RUN docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install -j$(nproc) intl
RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install -j$(nproc) curl
RUN docker-php-ext-install -j$(nproc) mbstring
RUN docker-php-ext-install -j$(nproc) mysqli

# Install/enable apache modules
RUN a2enmod rewrite ssl headers

# copy over the application
ADD docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh
COPY . /var/www/html
RUN rm /var/www/html/docker-entrypoint.sh

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN git config --global --add safe.directory '*'

# Install the application
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader

# Run startup script
EXPOSE 80
EXPOSE 443
ENTRYPOINT ["/docker-entrypoint.sh"]
