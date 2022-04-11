# parsy

<div align="center">
  <h3 align="center">Wristwatch Store</h3>

  <p align="center">
    A small application for parsing HTML data into jobs.
  </p>
</div>

## Built With

* [Composer](https://getcomposer.org/)
* [Bootstrap](https://getbootstrap.com)
* [jQuery](https://jquery.com)
* [DataTables](https://datatables.net/)

## Getting Started

Follow the steps below to get starting with the app.

### Prerequisites

* composer :: run the following command in your terminal
  ```sh
  composer install
  ```

* run the application installer - creates the database and tables
  ```sh
  php command/install.php
  ```

* run the parser - you can pass a second argument for the filename. If you don't the default "data.html" value will be used
  ```sh
  php command/parse.php data.html
  ```