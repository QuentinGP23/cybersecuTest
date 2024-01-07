<?php
session_start();
include 'db.php';

if (isset($_SESSION['id_utilisateur']) && isset($_POST['email'])) {
    $new_email = $_POST['email'];
    $user_id = $_SESSION['id_utilisateur'];

    $sql = "UPDATE utilisateur SET email = ? WHERE id_utilisateur = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_email, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Email changé avec succès!";
    } else {
        echo "Erreur ou aucun changement effectué.";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "Vous devez être connecté pour changer votre email.";
}
?>
