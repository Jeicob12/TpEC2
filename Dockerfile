FROM php:8.3-apache

RUN docker-php-ext-install pdo_mysql \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && echo "date.timezone=America/Argentina/Buenos_Aires" > /usr/local/etc/php/conf.d/timezone.ini

COPY index.php db.php registrar.php app_version.txt commit_message.txt /var/www/html/
COPY docker-entrypoint.sh /usr/local/bin/app-entrypoint.sh

RUN sed -i 's/\r$//' /usr/local/bin/app-entrypoint.sh \
    && chmod +x /usr/local/bin/app-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["app-entrypoint.sh"]
CMD ["apache2-foreground"]
