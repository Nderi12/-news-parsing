<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'news');

/* Attempt to connect to MySQL database */
$dataBaseConnection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($dataBaseConnection === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Global constant
define('ROOT_PATH', realpath(dirname(__FILE__)));

// Set the include path
define('PUBLIC_PATH', realpath(dirname(__FILE__) . 'public'));

// Set the base url for global access
define('BASE_URL', 'http://localhost/news/');
