<?php

// Create connection
$con=mysqli_connect("localhost","root","","boltracker");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

mysqli_query($con,"INSERT INTO scripts (bol_name, script_name)
VALUES (" . $_GET['bol_name'] . ", " . $_GET['script_name']);

mysqli_close($con);

?>