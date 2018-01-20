Hacker News bootcamp
========================
A simple clone of Hacker News.

## Quick Start

To get started, clone the repository and CD into the directory

    git clone https://github.com/OthmaneHF/HackerNewsBootcamp.git
    cd bootcamp

### Database configuration
Next, create a new database that the app will use and configure connection settings for your database by following the steps below

- Open `app/config/parameters.yml`
- Update the database connection settings (`database_host`,`database_name`,`database_user`,`database_password`)
- Save

### Installation
- Run  `composer install`
- Run  `bin/console doctrine:migrations:migrate`


