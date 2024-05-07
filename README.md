# Electronic Medical Record System

[![code style: prettier](https://img.shields.io/badge/code_style-prettier-ff69b4.svg?style=flat-square)](https://github.com/prettier/prettier)

This is a web-based Electronic Medical Record systems application which complies with Indonesia's FHIR Implementation (SATUSEHAT).

API Documentation can be accessed through [Postman](https://documenter.getpostman.com/view/29785588/2s9Yynm4LB)

## Available Features

- [x] User Registration
- [x] Practitioner Onboarding
- [x] Patient Onboarding
- [x] Rawat Jalan Jilid 1
- [x] Rawat Inap Jilid 1
- [x] IGD Jilid 1

## Prerequisites

This project is built using:

- PHP 8.2
- Laravel 10
- MongoDB
- Vue.js 3 with Inertia.js
- Tailwind CSS 3

## Installation

1. Clone the project
2. Run `composer install`
3. Run `npm install`
4. Run `cp .env.example .env`
5. Change these values in `.env`:

   ```
   DB_CONNECTION=mongodb
   DB_DATABASE=rme
   DB_URI=mongodb://localhost:27017

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

6. Change these values in `.env`, get the latest values through `https://satusehat.kemkes.go.id/platform`
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
7. Run `php artisan key:generate`
8. Migrate and seed database using: `php artisan migrate --seed`
9. (Optional) If dummy data is needed, run `php artisan db:seed DummyDataSeeder`
10. (Optional) If example SATUSEHAT data is needed, run `php artisan db:seed IdFhirResourceSeeder`
11. Run `npm run build`
12. Serve the app using web servers or local server with `php artisan serve`
13. Run Task Scheduler. Please refer to [Laravel's documentation](https://laravel.com/docs/10.x/scheduling#running-the-scheduler)

## Contributors

- [@itsLeonB](https://github.com/itsLeonB) - Back-end
- [@mandorzqy](https://github.com/mandorzqy) - Front-end
- [@salmahatta](https://github.com/salmahatta) - UI/UX
