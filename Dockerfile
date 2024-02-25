
# use PHP 8.2
FROM php:8.2-fpm

# Install common php extension dependencies
RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip

# Set the working directory
COPY . /var/www/app
WORKDIR /var/www/app

RUN chown -R www-data:www-data /var/www/app \
    && chmod -R 775 /var/www/app/storage


# install composer
COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

# copy composer.json to workdir & install dependencies
COPY composer.json ./
RUN composer install

# Set the default command to run php-fpm
CMD ["php-fpm"]


# # # Use a base image with the desired OS and package manager
# # FROM centos:8

# # FROM php:8.2-fpm as php

# # # Set environment variables
# # ENV PHP_OPCACHE_ENABLE=0
# # ENV PHP_OPCACHE_ENABLE_CLI=0
# # ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0
# # ENV PHP_OPCACHE_REVALIDATE_FREQ=0

# # # Install packages using yum
# # RUN yum -y install \
# #     cairo \
# #     ghostscript \
# #     libXinerama.x86_64 
# #     cups-libs \
# #     dbus-glib \
# #     libmemcached-devel \
# #     readline \
# #     readline-devel \
# #     libyaml-devel \
# #     libffi-devel \
# #     libSM \
# #     fontconfig \
# #     libXrender \
# #     libXext \
# #     xorg-x11-fonts-Type1 \
# #     xorg-x11-fonts-75dpi \
# #     freetype \
# #     libpng \
# #     zlib \
# #     libjpeg-turbo \
# #     libappindicator-gtk3 \
# #     poppler-utils

# # # Update Composer
# # RUN export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update 2.5.8

# # # Download and install LibreOffice
# # RUN if [ ! -f /tmp/LibreOffice_7.6.2_Linux_x86-64_rpm.tar.gz ]; then \
# #     wget https://download.documentfoundation.org/libreoffice/stable/7.6.2/rpm/x86_64/LibreOffice_7.6.2_Linux_x86-64_rpm.tar.gz -P /tmp; \
# #     fi

# # RUN if [ ! -d /tmp/LibreOffice_7.6.2.3_Linux_x86-64_rpm ]; then \
# #     tar -xvf /tmp/LibreOffice_7.6.2_Linux_x86-64_rpm.tar.gz -C /tmp; \
# #     fi

# # RUN if [ ! -d /opt/libreoffice7.6 ]; then \
# #     yum localinstall /tmp/LibreOffice_7.6.2.3_Linux_x86-64_rpm/RPMS/*.rpm -y; \
# #     fi

# # RUN if [ ! -f /usr/bin/soffice ]; then \
# #     ln -fs /opt/libreoffice7.6/program/soffice /usr/bin/soffice; \
# #     fi

# # # Download and install JDK 18
# # RUN if [ ! -f /tmp/jdk-18_linux-x64_bin.tar.gz ]; then \
# #     wget https://download.oracle.com/java/18/archive/jdk-18_linux-x64_bin.tar.gz -O /tmp/jdk-18_linux-x64_bin.tar.gz; \
# #     fi

# # RUN if [ ! -d /opt/jdk-18 ]; then \
# #     cd /opt && tar -xzf /tmp/jdk-18_linux-x64_bin.tar.gz && \
# #     alternatives --install /usr/bin/java java /opt/jdk-18/bin/java 1 && \
# #     alternatives --install /usr/bin/javac javac /opt/jdk-18/bin/javac 1 && \
# #     echo 'export JAVA_HOME="/opt/jdk-18"' >> /etc/profile.d/java.sh; \
# #     fi

# # # Download and install wkhtmltopdf
# # RUN if [ ! -f /tmp/wkhtmltox-0.12.6-1.centos7.x86_64.rpm ]; then \
# #     wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox-0.12.6-1.centos7.x86_64.rpm -O /tmp/wkhtmltox-0.12.6-1.centos7.x86_64.rpm; \
# #     fi

# # RUN if ! command -v wkhtmltopdf &> /dev/null; then \
# #     echo "wkhtmltopdf not found. Installing..."; \
# #     yum localinstall /tmp/wkhtmltox-0.12.6-1.centos7.x86_64.rpm -y; \
# #     fi

# # # Download and install Google Chrome
# # RUN if [ ! -f /tmp/google-chrome-stable_current_x86_64.rpm ]; then \
# #     wget https://dl.google.com/linux/direct/google-chrome-stable_current_x86_64.rpm -O /tmp/google-chrome-stable_current_x86_64.rpm; \
# #     fi

# # RUN if [ ! -f /tmp/google-chrome-stable_current_x86_64.rpm ]; then \
# #     yum localinstall /tmp/google-chrome-stable_current_x86_64.rpm -y; \
# #     fi

# # RUN if [ ! -f /usr/bin/chromium ]; then \
# #     ln -fs /usr/bin/google-chrome-stable /usr/bin/chromium; \
# #     fi

# # Set storage permissions
# # RUN sudo chmod -R 755 ./storage

# # # Run Laravel commands
# # RUN php artisan optimize && \
# #     php artisan config:clear && \
# #     php artisan view:clear && \
# #     php artisan l5-swagger:generate && \
# #     php artisan route:cache && \
# #     php artisan optimize && \
# #     php artisan view:cache && \
# #     php artisan migrate --force

# # # Create a systemd service for Laravel queue worker
# # RUN echo -e '[Unit]\nDescription=Laravel queue worker\n\n[Service]\nUser=nginx\nGroup=nginx\nRestart=always\nExecStart=/usr/bin/nohup /usr/bin/php /var/app/current/artisan queue:work --daemon\n\n[Install]\nWantedBy=multi-user.target' > /etc/systemd/system/laravel_worker.service

# # # Create a log tailing configuration
# # RUN echo -e '/var/app/current/storage/logs/laravel.log' > /opt/elasticbeanstalk/tasks/taillogs.d/laravel-logs.conf

# # # Set file permissions for the log configuration
# # RUN chown root:root /opt/elasticbeanstalk/tasks/taillogs.d/laravel-logs.conf && chmod 755 /opt/elasticbeanstalk/tasks/taillogs.d/laravel-logs.conf

# # # Start the Laravel queue worker service
# # CMD systemctl enable laravel_worker.service && systemctl start laravel_worker.service

# # Expose any necessary ports if applicable


# FROM surnet/alpine-wkhtmltopdf:3.16.2-0.12.6-full as wkhtmltopdf

# FROM php:8.2-fpm as php

# # Set environment variables
# ENV PHP_OPCACHE_ENABLE=0
# ENV PHP_OPCACHE_ENABLE_CLI=0
# ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0
# ENV PHP_OPCACHE_REVALIDATE_FREQ=0

# RUN apt-get update -y
# # Install necessary packages using apk
# RUN apt-get install -y \
#     libcairo2 libcairo2-dev \
#     ghostscript 
#     # imagemagick \
#     # libxinerama \
#     # cups-libs \
#     # dbus-glib \
#     # libmemcached \
#     # readline \
#     # readline-dev \
#     # libyaml-dev \
#     # libffi-dev \
#     # libsm \
#     # fontconfig \
#     # libxrender \
#     # libxext \
#     # xorg-fonts-type1 \
#     # xorg-fonts-75dpi \
#     # xorg-x11-fonts-75dpi \
#     # freetype \
#     # libpng \
#     # zlib \
#     # libjpeg-turbo \
#     # libappindicator \
#     # poppler-utils \
#     # apk add -Uuv \
#     # libressl-dev \
#     # libstdc++ \
#     # libx11 \
#     # libxrender \
#     # libxext \
#     # libssl1.1 \
#     # ca-certificates \
#     # fontconfig \
#     # freetype \
#     # ttf-droid \
#     # ttf-freefont \
#     # ttf-liberation \
#     # git bash supervisor freetype-dev libjpeg-turbo-dev \
#     # libzip-dev \
#     # libreoffice zlib1g-dev libpng-dev\
#     # libpq \
#     # zip \
#     # && docker-php-ext-install bcmath \
#     # gd \
#     # php-common \
#     # fpm \
#     # pdo \
#     # opcache \
#     # zip \
#     # phar \
#     # iconv \
#     # cli \
#     # curl \
#     # openssl \
#     # mbstring \
#     # tokenizer \
#     # fileinfo \
#     # json \
#     # xml \
#     # xmlwriter \
#     # simplexml \
#     # dom \
#     # pdo_mysql \
#     # pdo_sqlite \
#     # tokenizer
#     # bcmath bz2 calendar ctype curl dba dl_test dom enchant exif ffi fileinfo filter ftp gd gettext gmp hash iconv imap intl json ldap mbstring mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql pdo_sqlite pgsql phar posix pspell random readline reflection session shmop simplexml snmp soap sockets sodium spl standard sysvmsg sysvsem sysvshm tidy tokenizer xml xmlreader xmlwriter xsl zend_test zip
#     # pecl-redis



# # COPY php.ini /usr/local/etc/php/
# # COPY docker.conf /usr/local/etc/php-fpm.d/docker.conf
# # COPY .bashrc /root/

# RUN apt-get update \
#   && apt-get install -y build-essential zlib1g-dev default-mysql-client curl gnupg procps vim git unzip libzip-dev libpq-dev ghostscript \
#   && docker-php-ext-install zip pdo_mysql pdo_pgsql pgsql imagemagick libreoffice xmlwriter opcache tokenizer

# RUN apt-get install -y libicu-dev \
# && docker-php-ext-configure intl \
# && docker-php-ext-install intl

# # pcov
# RUN pecl install pcov && docker-php-ext-enable pcov

# COPY --from=wkhtmltopdf /bin/wkhtmltopdf /bin/libwkhtmltox.so /bin/

# # Clean up the image
# RUN rm -rf /var/cache/*


# WORKDIR /var/www

# COPY . .

# # Copy configuration files.
# COPY ./docker/php/php.ini /usr/local/etc/php/php-extra.ini
# # COPY ./docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
# # COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

# # Set working directory to /var/www.
# WORKDIR /var/www

# # Copy files from current folder to container current folder (set in workdir).
# COPY --chown=www-data:www-data . .

# # Create laravel caching folders.
# RUN mkdir -p /var/www/storage/framework
# RUN mkdir -p /var/www/storage/framework/cache
# RUN mkdir -p /var/www/storage/framework/testing
# RUN mkdir -p /var/www/storage/framework/sessions
# RUN mkdir -p /var/www/storage/framework/views

# # Fix files ownership.
# RUN chown -R www-data /var/www/storage
# RUN chown -R www-data /var/www/storage/framework
# RUN chown -R www-data /var/www/storage/framework/sessions

# # Set correct permission.
# RUN chmod -R 755 /var/www/storage
# RUN chmod -R 755 /var/www/storage/logs
# RUN chmod -R 755 /var/www/storage/framework
# RUN chmod -R 755 /var/www/storage/framework/sessions
# RUN chmod -R 755 /var/www/bootstrap

# # Adjust user permission & group
# # RUN usermod --uid 1000 www-data
# # RUN groupmod --gid 1001 www-data


ENV PORT=8000
ENTRYPOINT [ "docker/entrypoint.sh" ]

