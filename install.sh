#!/bin/sh
composer install
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail php artisan storage:link
./vendor/bin/sail php artisan migrate:fresh --seed
./vendor/bin/sail php artisan key:generate
./vendor/bin/sail php artisan horizon
