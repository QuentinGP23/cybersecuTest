<?php
require_once __DIR__ . '/../config.php';

$servername = getenv('DB_SERVER') ?: 'localhost';
$username = getenv('DB_USERNAME') ?: 'admin';
$password = getenv('DB_PASSWORD') ?: 'test';
$dbname = getenv('DB_NAME') ?: 'secu';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Échec de la connexion : " . $conn->connect_error);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Un problème est survenu. Veuillez réessayer plus tard.');
}
?>