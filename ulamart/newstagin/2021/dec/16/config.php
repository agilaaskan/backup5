<?php
 $servername = "localhost";

$username = "ulamart_speed";
$password = 'ruqxVCGFB?c]';
$dbname = "ulamart_speed";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}