# parsy

<div align="center">
  <h3 align="center">Parsy</h3>

  <p align="center">
    A small application that parses jobs listing HTML pages and saves them into a database.
  </p>
</div>

## Built With

* [Docker](https://www.docker.com/)
* [Composer](https://getcomposer.org/)
* [Bootstrap](https://getbootstrap.com)
* [jQuery](https://jquery.com)
* [DataTables](https://datatables.net/)

## Getting Started

Follow the steps below to get starting with the app.

### Prerequisites

* build and start the Docker containers
  ```sh
  docker-compose up -d
  ```

* run the application installer via Docker - creates the database and tables
  ```sh
  docker-compose exec parsy_php_apache php command/install.php
  ```

* run the parser via Docker - you can pass a second argument for the filename. If you don't the default "data.html" value will be used
  ```sh
  docker-compose exec parsy_php_apache php command/parse.php data.html
  ```

* run the unit tests via Docker
  ```sh
  docker-compose exec parsy_php_apache php vendor/bin/phpunit tests
  ```