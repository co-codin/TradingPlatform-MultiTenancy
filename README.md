# Deploy Laravel

## Requirements
* Clone this repo
  ```
  git clone git@bitbucket.org:stoxtech/backend.git
  ```
* Enter project
  ```
  cd backend
  ```
* Copy Laravel environment file from .env.example to .env
  ```
  cp .env.example .env
  ```
* Enter laradock
  ```
  cd laradock
  ```
  
* Raise docker containers
  ```
   docker-compose up -d nginx workspace postgres redis
  ```

* Enter workspace container
  ```
   docker-compose exec workspace bash
  ```

* Install PHP dependencies and migrate DB
  ```
    composer install && php artisan key:generate && php artisan migrate && php artisan horizon:install
  ```
