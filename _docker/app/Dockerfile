FROM php:8.0
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp/.composer
WORKDIR /srv/app
COPY .. /srv/app
RUN apt-get update && apt-get install -y unzip git default-mysql-client npm \
&& curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.6.6 \
&& docker-php-ext-install mysqli pdo_mysql \
&& composer install \
&& npm install \
&& npm run prod
COPY _docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
EXPOSE 8081
