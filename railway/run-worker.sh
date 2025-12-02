#!/bin/bash

# Simple queue worker for Railway
# You only need this if you decide to run a separate worker service.

php artisan queue:work
