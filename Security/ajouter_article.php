<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['id_utilisateur']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
    $contenu = filter_var($_POST['contenu'], FILTER_SANITIZE_STRING);
    $id_utilisateur = $_SESSION['id_utilisateur'];
    $target_dir = "./img/";
    $originalFilename = basename($_FILES["image"]["name"]);
    $fileExtension = strtolower(pathinfo($originalFilename, PATHINFO_EXTENSION));
    $chemin_image = $target_dir . uniqid() . '.' . $fileExtension;
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        die("Le fichier n'est pas une image.");
    }
    if ($_FILES["image"]["size"] > 500000) {
        die("Désolé, votre fichier est trop volumineux.");
    }
    if(!in_array($fileExtension, ['jpg', 'png', 'jpeg', 'gif'])) {
        die("Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
    }
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
