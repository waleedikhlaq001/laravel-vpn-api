# Installation Instructions

## Install Dependencies
1. composer install
1. npm install

## Configure
1. create a new .env file based on .env.example
    - Setup mySQL database credentials
    - Setup AppUrl
1. php artisan key:generate

## Populate DB & Seed
1. php artisan migrate:fresh --seed

## Run the development server
1. npm run dev
1. php artisan serve

## Translations
In order to create missing keys (defined in .vue files but not yet in .json translations), run following script:
Documentation: https://github.com/Spittal/vue-i18n-extract
1. npm run vue-i18n-extract