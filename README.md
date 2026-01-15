## Project Requirements
```
PHP 8.3 and up
```
## Project Setup

## Setup .env
Make sure to configure database
```
cp .env.example .env
```

```sh
php artisan migrate
php artisan serve (if no vhost)
php artisan db:seed UserSeeder (inital users)
```
