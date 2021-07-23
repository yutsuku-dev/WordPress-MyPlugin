#!/usr/bin/env sh

host="$1"
shift

printf 'Waiting for database '
while ! mysqladmin ping -h"$host" --silent; do
  printf '.'
  sleep 10
done
printf '\n\n'

>&2 printf 'Database is up\n'
exec "$@"
