# CSTEM Scholars Research Grant Funding System

## Getting Started

This project requires PHP 7.4, [Composer](https://getcomposer.org/), MariaDB or MySQL server, and Docker. The following
instructions also assume you will be using the Apache HTTP server, but other HTTP servers capable of running PHP,
including PHP's own built-in server will also work as long as the web root directory is configured to `src/public`
directory.

After cloning the repository, copy `src/config.sample.php` to `src/config.php`. The default project configuration is set up in developer
mode with no SMTP server to send emails. Adjust `src/config.php` as necessary. As this file may contain secrets such as
email passwords, this file was set to be ignored by Git and will not be committed.

## Development

The project can be futher developed while running in Docker containers. After setting up the config.php file, in the src directory run `docker-compose -f docker-compose.yml -f docker-compose.dev.yml build` then `docker-compose -f docker-compose.yml -f docker-compose.dev.yml up` to spin up the containers. After this step is complete you should be able to navigate to your localhost and see the home page of the website.

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
