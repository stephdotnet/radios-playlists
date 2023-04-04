#!/bin/bash

# Pull latest changes from the git repository
git fetch && git pull

# Run composer install in the webserver container of the docker-compose file
docker-compose -f ../../../docker-compose.yml run --rm -w /var/www/html/radios webserver composer install

# Run migrations in the webserver container of the docker-compose file
docker-compose -f ../../../docker-compose.yml run --rm -w /var/www/html/radios webserver php artisan migrate --force
