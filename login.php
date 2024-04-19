<?php
session_start();

$conn = new mysqli("localhost", "user", "root", "little_sun");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
