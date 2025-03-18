<?php
$config = parse_ini_file('/var/www/private/db-config.ini');

if (!$config) {
    die("Error: Could not read database configuration file.");
}

// Create connection using credentials from db-config.ini
$conn = new mysqli(
    $config['servername'],
    $config['username'],
    $config['password'],
    $config['dbname']
);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
