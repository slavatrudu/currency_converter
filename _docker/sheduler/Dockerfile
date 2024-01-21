FROM php:8.0
RUN apt-get update && apt-get -y install cron \
&& docker-php-ext-install mysqli pdo_mysql

COPY _docker/shedule-script /etc/cron.d/shedule-script

RUN chmod 0644 /etc/cron.d/shedule-script
RUN crontab /etc/cron.d/shedule-script
RUN touch /var/log/cron.log

CMD cron && tail -f /var/log/cron.log
