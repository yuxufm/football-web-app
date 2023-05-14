# Football Web App - Symfony 6 Project

This is a README file for running the Symfony 6 project on your local machine. Please follow the instructions below to set up and run the project.

## Prerequisites

Before running the Symfony 6 project, make sure you have the following software installed:
- PHP (version 8.1 or higher) or see this tech specs from this url: 
- Composer (dependency manager for PHP)
- MySQL (mariadb version 10.4.2)

## Installation

To install the Symfony 6 project and its dependencies, follow these steps:
1. Clone the project repository from GitHub: `git clone https://github.com/yuxufm/football-web-app.git`
2. Navigate to the project directory
3. Install the required PHP dependencies using Composer: `composer install`

## ENV Setup

Update the database connection configuration in the `.env` file with your database credentials.
1. Duplicate this file ./.env to ./.env.local
2. Change this code line on .env.local file
`DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=mariadb-10.5.8"` to this `DATABASE_URL="mysql://root@127.0.0.1:3306/football_app?serverVersion=mariadb-10.4.28&charset=utf8mb4"`
or you can change based on your database and connection values.

## Database Setup

Run the following command to create the necessary database tables and schema
1. Create a new database by running this cli on terminal: `php bin/console doctrine:database:create`
2. Run migrations: `php bin/console doctrine:migrations:migrate`

## Running Project

1. `symfony server:start`
2. go to this url: https://127.0.0.1:8000/


## User Interface of App:
![Screenshot 2023-05-14 at 07 05 28](https://github.com/yuxufm/football-web-app/assets/20906289/bb504fdd-0b95-4f17-9f83-0d20cd415978)

Thank you.
