## Installation

1. Clone the repository.
2. Run `docker compose up -d` to start the containers.
3. Run `docker compose exec -it php-fpm composer install` to install dependencies.
4. Run `docker compose exec -it php-fpm composer run-script post-root-package-install` to install a project.
5. Run `docker compose exec -it php-fpm composer run-script post-create-project-cmd` to initialize a project.
6. Run `docker compose exec -it php-fpm php artisan migrate:fresh --seed` to run migrations.
7. Run `docker compose exec -it php-fpm php artisan test` to run tests

### Documentation
 - Postman collection in folder `docs`
