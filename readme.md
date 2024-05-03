<p align="center"><a href="https://erp.shambarecords.com">SHAMBA ERP</a></p>

## Pre Requisite

#### env variables

APP_NAME="Shamba Equity"<br>
APP_ENV=local<br>
APP_KEY={Generate your key}<br>
APP_DEBUG=true<br>
APP_URL=http://127.0.0.1:8000<br>

LOG_CHANNEL=stack<br>

DB_CONNECTION=mysql<br>
DB_HOST=127.0.0.1<br>
DB_PORT={your port}<br>
DB_DATABASE={your database}<br>
DB_USERNAME={your username}<br>
DB_PASSWORD={your password}<br>

BROADCAST_DRIVER=log<br>
CACHE_DRIVER=file<br>
QUEUE_CONNECTION=sync<br>
SESSION_DRIVER=file<br>
SESSION_LIFETIME=120<br>

REDIS_HOST=127.0.0.1<br>
REDIS_PASSWORD=null<br>
REDIS_PORT=6379<br>

MAIL_MAILER=smtp<br>
MAIL_HOST=smtp.mailtrap.io<br>
MAIL_PORT=2525<br>
MAIL_USERNAME={username}<br>
MAIL_PASSWORD={password}<br>
MAIL_ENCRYPTION=tls<br>
MAIL_FROM_ADDRESS=support@shambarecords.com<br>
MAIL_FROM_NAME="${APP_NAME}"<br>

PASSPORT_CLIENT_ID="{passport keys}"<br>
PASSPORT_CLIENT_SECRET="{passport secret}"<br>

MAPS_API_KEY='{MAPS_API_KEY_FROM_GOOGLE}'<br>
MAPS_API=https://maps.googleapis.com/maps/api<br>
MAPS_LOCATION_API=https://maps.googleapis.com/maps/api/place<br>
MAPS_LOCATION='-1.3028618,36.7073079'<br>
MAPS_LOCATION_RADIUS=50000<br>

PDF_FORMAT=pdf<br>
TIMEZONE=Africa/Nairobi<br>
DEFAULT_PASSWORD=12345678<br>

## Project Setup

Follow these steps to setup the project

1. Install packages
   `composer install` (Assuming you have composer installed)
2. Run the migrations
   `php artisan migrate`
3. Run database seeds in the following order (MUST).<br>
   1. `php artisan db:seed --class=RolesNPermissionsSeeder`
   2. `php artisan db:seed --class=CooperativeSeeder`
   3. `php artisan db:seed --class=UserSeeder`
4. Start the server `php artisan serve`
5. Run the following endpoint to activate admin roles `127.0.0.1:8000/admin/roles`
6. Run the following command to create default cooperative admin `php artisan create:coopadmin`
7. Default super admin credentials `username: admin` and `password: 12345678`


#### API Setup Instructions (Passports)

- Run the following command to generate client ID and Secret `php artisan passport:install`
- Copy the Client ID and Secret to `PASSPORT_CLIENT_ID` and `PASSPORT_CLIENT_SECRET` respectively.

#### TODO: Monitoring and Alerting.

#### TODO: Containerize

#### TODO: CI/CD
