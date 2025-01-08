# Electronic Medical Record System

[![code style: prettier](https://img.shields.io/badge/code_style-prettier-ff69b4.svg?style=flat-square)](https://github.com/prettier/prettier)

This is a web-based Electronic Medical Record systems application which complies with Indonesia's FHIR Implementation (SATUSEHAT).

API Documentation can be accessed through [Postman](https://documenter.getpostman.com/view/29785588/2s9Yynm4LB)

## Available Features

-   [x] User Registration
-   [x] Practitioner Onboarding
-   [x] Patient Onboarding
-   [x] Rawat Jalan Jilid 1
-   [x] Rawat Inap Jilid 1
-   [x] IGD Jilid 1

## Prerequisites

This project is built using:

-   PHP 8.2
-   Laravel 10
-   MySQL
-   Vue.js 3 with Inertia.js
-   Tailwind CSS 3

## Installation

1. Clone the project
2. Run these commands:
    ```sh
    # install Laravel dependencies
    composer install

    # install Vue dependencies
    npm install

    # Create env file from example
    cp .env.example .env
    ```
3. Change these values in `.env`:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=rme
    DB_USERNAME=root
    DB_PASSWORD=

    MAIL_MAILER=smtp
    MAIL_HOST=mailpit
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"
    MAIL_ADMIN=hello@example.com
    ```

4. Use [SATUSEHAT Postman](https://www.postman.com/satusehat/workspace/satusehat-public/overview) to create Organization and Location resource. Paste the resource data in `storage/onboarding-resource`.
   
5. Change these values in `.env`, get the latest values through [SATUSEHAT Developer Portal](https://satusehat.kemkes.go.id/platform)
    ```
    auth_url=https://api-satusehat-dev.dto.kemkes.go.id/oauth2/v1
    base_url=https://api-satusehat-dev.dto.kemkes.go.id/fhir-r4/v1
    consent_url=https://api-satusehat-dev.dto.kemkes.go.id/consent/v1
    kfa_v1_url=https://api-satusehat-dev.dto.kemkes.go.id/kfa
    kfa_v2_url=https://api-satusehat-dev.dto.kemkes.go.id/kfa-v2
    client_id=your_client_id
    client_secret=your_client_secret
    organization_id=your_organization_id
    location_id=your_location_id
    ```
6. Run these commands:
    ```sh
    # Generate app key
    php artisan key:generate

    # Migrate and seed database
    php artisan migrate --seed

    # (Optional) seed database with dummies if needed
    php artisan db:seed DummyDataSeeder

    # (Optional) seed database with example data if needed
    php artisan db:seed IdFhirResourceSeeder

    # Build the front-end
    npm run build
    ```
7. Serve the app using web servers or local server with `php artisan serve`
8. Run Task Scheduler. Please refer to [Laravel's documentation](https://laravel.com/docs/10.x/scheduling#running-the-scheduler)

## Contributors

-   [@itsLeonB](https://github.com/itsLeonB) - Back-end
-   [@mandorzqy](https://github.com/mandorzqy) - Front-end
-   [@salmahatta](https://github.com/salmahatta) - UI/UX
   
## License

This project is licensed under the [Creative Commons Attribution-NonCommercial 4.0 International License](https://creativecommons.org/licenses/by-nc/4.0/).

You are free to:
- Share: Copy and redistribute the material in any medium or format.
- Adapt: Remix, transform, and build upon the material.

**Under the following terms**:
- **Attribution**: You must give appropriate credit, provide a link to the license, and indicate if changes were made.
- **NonCommercial**: You may not use the material for commercial purposes.

See the full license text in the `LICENSE` file.
![License: CC BY-NC 4.0](https://img.shields.io/badge/License-CC--BY--NC%204.0-lightgrey.svg)
