<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed.');
    }

    if (isset($_SESSION['id_utilisateur']) && isset($_POST['email'])) {
        $new_email = $_POST['email'];
        $user_id = $_SESSION['id_utilisateur'];

        $stmt = $conn->prepare("UPDATE utilisateur SET email = ? WHERE id_utilisateur = ?");
        $stmt->bind_param("si", $new_email, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Email changé avec succès!";
        } else {
            echo "Erreur ou aucun changement effectué.";
        }
        $stmt->close();
    } else {
        echo "Vous devez être connecté pour changer votre email.";
    }
    $conn->close();
} else {
}
?>
