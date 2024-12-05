<?php
// Connection details
$host = "localhost";
$user = "root"; // Default XAMPP username
$pass = "";     // Default XAMPP password is empty
$database = "student_management_system";

// Creating connection
$connection = new mysqli($host, $user, $pass, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
} else {
    echo "Connected successfully!";
}
?>