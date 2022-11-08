# Deploy Laravel

## Prerequirements
* Clone this repo
  ```
  git clone git@bitbucket.org:stoxtech/backend.git
  ```
* Enter into project
  ```
  cd backend
  ```
* Copy Laravel environment file from .env.example to .env
  ```
  cp .env.example .env
  ```
* Edit DB configuration
  ```
    DB_CONNECTION=pgsql
    DB_HOST=pgsql
    DB_PORT=5432
    DB_DATABASE=postgres
    DB_USERNAME=postgres
    DB_PASSWORD=root
  ```
* Install Sail 
  ```
  composer require laravel/sail --dev
  ```

* Raise docker containers
  ```
  ./vendor/bin/sail up -d
  ```

* Install PHP dependencies
  ```
  ./vendor/bin/sail composer install
  ```
