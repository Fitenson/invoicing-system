🧾 Welcome to My Invoicing System

📌 Project Overview

PHP Version: 8.2

Framework: Laravel 10

Frontend: Blade Views

This project is an invoicing system built using Laravel, leveraging Blade templates for frontend rendering. It includes features for managing users, projects and invoices.


🚀 Setup Guide
Follow the steps below to set up and run the project locally:

✅ Prerequisites
- Ensure your machine is using PHP 8.2


📥 Clone the Repository
- git clone https://github.com/Fitenson/invoicing-system.git
- cd invoicing-system


📦 Install Dependencies
- composer install

🗃️ Run Migrations
- php artisan migrate

🌱 Seed the Database
Populate the database with the available seeders:
- php artisan db:seed --class=UserSeeder
- php artisan db:seed --class=ProjectSeeder


🖨️ PDF Generation with Laravel Snappy
This project uses Laravel Snappy for generating PDF documents. Follow the steps below to install and configure it properly.

🛠️ Requirements
Laravel Snappy is a wrapper for wkhtmltopdf, so you need to install it first.

1) Download wkhtmltopdf
- If you using Windows, go to: https://wkhtmltopdf.org/downloads.html and install the stable version of wkhtmltopdf

2) Publish the configuration file:
- php artisan vendor:publish --provider="Barryvdh\Snappy\ServiceProvider"

3)  Configure config/snappy.php
Update the paths for the binary in config/snappy.php if needed:

'pdf' => [
    'enabled' => true,
    'binary'  => env('WKHTML_PDF_BINARY', '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf"'),
    'timeout' => false,
    'options' => [],
    'env'     => [],
],

4) Clear and Optimize Configuration Cache
- Before running the project, make sure to clear and optimize the configuration:
- php artisan config:clear
- php artisan optimize


🖥️ Serve the Project
- Start the local development server:
- php artisan serve


How to use the Invoicing System
1) Register your name and email on the registration page
2) After registration, go to the login page and enter your name and password
3) After logging in, you can manage projects, clients, and invoices created in the system
