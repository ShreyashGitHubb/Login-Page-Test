<?php

$host = "localhost";  // Database host
$user = "root";       // Database username
$pass = "shreyash@123"; // Database password (leave blank for XAMPP default)
$db = "auth";         // Database name

// Create a new mysqli instance and check for connection errors
$conn = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "Connected successfully"; // Optional: Confirm successful connection
}
?>
