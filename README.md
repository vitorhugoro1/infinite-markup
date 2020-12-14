# Infinite Markup

Create an application to manually upload the given XMLs and have an option
to asynchronously process them. The results of the processed data must be logged. Make the
processed information available via rest APIs.

# How To Run

- Copy environment:
    `cp .env.example .env`
- Composer install:
    `composer install`
- Build Docker image and put on daemon state:
    `vendor/bin/sail up --build -d`
- Migrate database:
    `vendor/bin/sail artisan migrate`
- Run tests:
    `vendor/bin/sail artisan test`
