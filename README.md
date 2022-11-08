Deployment

1. Local environment
 - composer install
 - cp .env.example .env
 - настроить перемен в .env

2. Docker environment
    - ccomposer require laravel/sail --dev
    - php artisan sail:install
      ./vendor/bin/sail up -d
