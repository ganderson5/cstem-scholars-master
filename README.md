# CSTEM Scholars Research Grant Funding System

## Getting Started

This project requires PHP 7.4, [Composer](https://getcomposer.org/), and MariaDB or MySQL server. The following 
instructions also assume you will be using the Apache HTTP server, but other HTTP servers capable of running PHP, 
including PHP's own built-in server will also work as long as the web root directory is configured to `src/public` 
directory. The easiest way to set up PHP and MariaDB on Windows is using 
[XAMPP](https://www.apachefriends.org/index.html).

Use a database client like the MySQL command-line client, DataGrip, HeidiSQL, or XAMPP's preinstalled 
[phpMyAdmin](http://localhost/phpmyadmin) to import the database schema in `setup.sql` file. This will create a 
new database called "researchGrant" with all tables needed for the project.

You will need to edit your Apache server configuration file to serve files from the `src/public` directory. To 
accomplish this, find and edit the following two lines in the Apache configuration: 

    DocumentRoot "path/to/src/public"
    <Directory "path/to/src/public">

In XAMPP, you will need to edit both the `httpd.conf` and `httpd-ssl.conf` files.

Alternatively, you can symlink the existing web root directory to `src/public` directory and leave Apache configuration 
as is. On Windows, this can be accomplished by running 
`mklink /D "path/to/xampp/htdocs" "path/to/cstem-scholars/src/public"` in Administrator command prompt.

Finally, copy `src/config.sample.php` to `src/config.php`. The default project configuration is set up in developer 
mode with no SMTP server to send emails. Adjust `src/config.php` as necessary. As this file may contain secrets such as 
email passwords, this file was set to be ignored by Git and will not be committed.

## Deployment

We use [Docker](https://www.docker.com/get-started) for server deployments. With the Docker service up and running, 
simply edit `docker/config.php` as needed and spin up the containers by running `docker-compose up` inside the root 
directory of the project. You may also want to change the password for your database account in the `docker-compose.yml` 
file. Make sure you change the corresponding `DB_USERNAME` and `DB_PASSWORD` config constants in `docker/config.php`. 
While developing the project, you may need to rebuild the container images using the `docker-compose build` command.

## Coding Style

This project follows [PSR-12](https://www.php-fig.org/psr/psr-12/) coding style. If you're using PHPStorm, consider 
setting your code style preference to [PSR12](https://blog.jetbrains.com/phpstorm/2019/11/phpstorm-2019-3-release/#psr) 
and using the automatic code formatting feature.

The most widely used naming convention for CSS classes and IDs appears to be `kebab-case` and this is the convention we 
are sticking to.

## File naming Conventions

- PHP class files must be named `PascalCase.php` and should be located in `src/classes` directory or `src/models` if 
  it's a model class.
- All other PHP files must be named `snake_case.php`.
- PHP pages and other publicly accessible files must be located in `src/public` directory.
