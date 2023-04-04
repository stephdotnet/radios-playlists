#!/bin/bash

# Pull latest changes from the git repository
git fetch && git pull

# Run composer install in the webserver container of the docker-compose file
docker-compose run --rm webserver composer install --no-dev

# Run migrations in the webserver container of the docker-compose file
docker-compose run --rm webserver php artisan migrate --force
