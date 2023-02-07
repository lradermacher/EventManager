## Event Manager
### Requirements
1. Install [PHP](https://www.php.net/downloads)
1. Install [Composer](https://getcomposer.org/download/)
1. Install [SymfonyCLI](https://symfony.com/download)
1. Install [Docker](https://www.docker.com/products/docker-desktop/)

### Setup
1. Install Packages
    - run `composer install`
1. Setup Database
    - run `docker-compose up`
    - run `php bin/console doctrine:migrations:migrate `
1. Start Application
    - run `symfony serve`

### Run Tests
1. Setup Test Database
    - run `php bin/console --env=test doctrine:database:create`
    - run `php bin/console --env=test doctrine:schema:create`
    - run `php ./vendor/bin/phpunit`