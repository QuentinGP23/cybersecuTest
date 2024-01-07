<?php
$servername = "localhost";
$username = "admin";
$password = "test";
$dbname = "secu";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
?>
