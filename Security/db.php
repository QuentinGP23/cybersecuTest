<?php
require_once __DIR__ . '/../config.php';

$servername = getenv('DB_SERVER') ?: 'localhost';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
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

$allowed_paths = ['article.php', 'connexion.php','inscription.php', 'admin.php', 'index.php', 'deconnexion.php',  'db.php', 'change-email.php', 'ajouter_article.php'];

$current_path = basename($_SERVER['SCRIPT_NAME']);
$current_params = $_GET;

if (!in_array($current_path, $allowed_paths) || !validateParameters($current_params, $current_path)) {
    die("URL non autorisée.");
}

function validateParameters($params, $path)
{
    if ($path == 'article.php' && (!isset($params['id']) || !is_numeric($params['id']))) {
        return false;
    }
    return true;
}
if (isset($_SESSION['id_utilisateur']) && in_array($current_path, ['connexion.php', 'inscription.php'])) {
    header('Location: index.php');
    exit();
}
if (!isset($_SESSION['id_utilisateur']) && !in_array($current_path, ['connexion.php', 'inscription.php'])) {
    die("URL non autorisée.");
    exit();
}
?>