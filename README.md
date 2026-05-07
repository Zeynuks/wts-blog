# Project Deployment Guide

This project is built with Laravel 11, Orchid Platform, and utilizes Laravel Sail for a seamless Docker-based
development environment.

### Getting Started

#### 1. Prerequisites

Ensure you have Docker Desktop installed and running on your machine.

#### 2. Installation

Clone the repository and enter the project directory:

```Bash
git clone https://github.com/Zeynuks/wts-blog
cd wts-blog
```

#### 3. Environment Setup

Copy the example environment file. Important: Open .env and set your desired admin credentials.

```Bash
cp .env.example .env
```

#### 4. Composer Install

Run the following command to install dependencies via a temporary Docker container (if you don't have PHP/Composer
installed locally):

```Bash
docker run --rm \
-u "$(id -u):$(id -g)" \
-v "$(pwd):/var/www/html" \
-w /var/www/html \
laravelsail/php83-composer:latest \
composer install --ignore-platform-reqs
```

#### 5. Start Laravel Sail

Launch the Docker containers:

```Bash
./vendor/bin/sail up -d
```

#### 6. Application Key & Storage

Generate the app key and create a symbolic link for the storage:

```Bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan storage:link
```

#### 7. Database & Seeding

Run migrations and seed the database (this will create your Admin User from the .env settings):

```Bash
./vendor/bin/sail artisan migrate --seed
```

#### 8. API Documentation

Generate the Swagger/OpenAPI documentation:

```Bash
./vendor/bin/sail artisan l5-swagger:generate
```

### Useful Commands

Stop Sail:

```Bash 
./vendor/bin/sail stop 
```

Run Tests:

```Bash 
./vendor/bin/sail artisan test
```

Access Admin Panel: Open http://localhost/admin

View API Docs: Open http://localhost/api/documentation

Publish Assets (Orchid/Packages):

```Bash
./vendor/bin/sail artisan vendor:publish --all-assets --force
```

### Development Notes

PHP Version: 8.3

Database: MySQL

Tooling: Laravel Sail, Orchid Platform, L5-Swagger
