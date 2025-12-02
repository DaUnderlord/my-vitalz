#!/bin/bash

# Exit the script if any command fails
set -e

# Run database migrations in production mode
php artisan migrate --force

# Clear any cached state
php artisan optimize:clear

# Cache the various components of the Laravel application
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
