<?php
// Configure session settings
ini_set('session.cookie_lifetime', 3600); // 1 hour
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

session_start();
$conn = new mysqli('localhost', 'root', '11391139Starr7', 'pet_health_tracker');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>