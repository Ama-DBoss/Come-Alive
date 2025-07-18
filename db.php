<?php
$conn = new mysqli("localhost", "root", "", "ranapp");
if ($conn->connect_error) die("DB connection failed: " . $conn->connect_error);
$conn->query("SET NAMES utf8mb4");
?>
