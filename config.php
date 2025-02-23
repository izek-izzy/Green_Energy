<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'isaac');
define('DB_PASS', 'Ib0977313696');
define('DB_NAME', 'green');

function connectDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>
