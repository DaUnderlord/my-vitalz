#!/bin/bash

# Laravel scheduler loop for Railway
# You only need this if you decide to run a separate scheduler service.

set -e

while true
do
  echo "Running the scheduler..."
  php artisan schedule:run --verbose --no-interaction &
  sleep 60
done
