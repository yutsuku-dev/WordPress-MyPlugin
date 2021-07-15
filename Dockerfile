FROM wordpress:cli

ADD --chown=www-data:www-data bin/install-wp.sh /usr/local/bin/install-wp
RUN chown -R www-data:www-data /var/www/html/
