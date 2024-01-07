<?php
session_start();
include 'db.php';

if (isset($_SESSION['id_utilisateur']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $conn->real_escape_string($_POST['titre']);
    $contenu = $conn->real_escape_string($_POST['contenu']);
    $id_utilisateur = $_SESSION['id_utilisateur'];

    $target_dir = "./img/"; 
    $chemin_image = $target_dir . basename($_FILES["image"]["name"]);

    $chemin_image = str_replace(' ', '_', $chemin_image);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $chemin_image)) {
        $stmt = $conn->prepare("INSERT INTO article (titre, contenu, chemin_image, id_utilisateur) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $titre, $contenu, $chemin_image, $id_utilisateur);

        if ($stmt->execute()) {
            header("Location: admin.php");
        } else {
            echo "Erreur : " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Erreur lors du téléchargement de l'image.";
    }
} else {
    echo "Vous devez être connecté pour ajouter un article.";
}

$conn->close();
?>
