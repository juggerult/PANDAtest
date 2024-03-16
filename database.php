<?php

$servername = "mysql.zzz.com.ua";
$username = "kovalIvan";
$password = "Vanya2710277999";
$dbname = "kovalivan";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



?>