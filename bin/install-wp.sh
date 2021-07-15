#!/usr/bin/env sh

# Determine site URL
url="wp.test"

if [ -n "$APP_DOMAIN" ]; then
  url=$APP_DOMAIN:
  
  if [ -n "$APP_PORT" ] && [ $APP_PORT != "80" ]; then
    url=$APP_DOMAIN:$APP_PORT
  fi
fi

# Install WordPress.
wp core install \
  --title="Project" \
  --admin_user="wordpress" \
  --admin_password="wordpress" \
  --admin_email="admin@example.com" \
  --url=$url \
  --skip-email

wp option set siteurl http://$url
wp option set home http://$url

# Update permalink structure.
wp option update permalink_structure "/%year%/%monthnum%/%postname%/" --skip-themes --skip-plugins

# Activate plugin.
wp plugin activate my-plugin
