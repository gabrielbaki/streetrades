<?php
// Create connection
$sqlConnect = mysqli_connect('localhost', 'root', '', 'location_commerce_db', '8080','');
  mysqli_select_db($sqlConnect, 'location_commerce_db');
// Check connection
if (!$sqlConnect) {
  die("Connection failed: " . $sqlConnect->connect_error);
}
?>
