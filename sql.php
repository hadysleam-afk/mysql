<?php
$connect = mysqli_connect("localhost:3307", "root", "", "sql");
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
