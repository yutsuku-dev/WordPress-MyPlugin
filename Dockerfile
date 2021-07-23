FROM wordpress:cli

ADD --chown=www-data:www-data bin/install-wp.sh /usr/local/bin/install-wp
ADD --chown=www-data:www-data bin/wait-for-db.sh /usr/local/bin/wait-for-db
RUN chmod 755 /usr/local/bin/install-wp
RUN chmod 755 /usr/local/bin/wait-for-db
RUN chown -R www-data:www-data /var/www/html/

COPY --from=composer /usr/bin/composer /usr/bin/composer
