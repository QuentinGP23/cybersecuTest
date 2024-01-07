<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <main class="connexion">
        <form action="inscription.php" method="post">
            <h1>Inscription</h1>
            <label for="email">Email</label>
            <input type="email" name="email" id="email">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom">
            <label for="prenom">Prenom</label>
            <input type="text" name="prenom" id="prenom">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password">
            <button type="submit">S'inscrire</button>
            <a href="connexion.php">Déjà inscrit ? Se connecter</a>
        </form>
    </main>
</body>

</html>
<?php
session_start();
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $nom = $conn->real_escape_string($_POST['nom']);
    $prenom = $conn->real_escape_string($_POST['prenom']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "INSERT INTO utilisateur (email, nom, prenom, password, role) VALUES (?, ?, ?, ?, 'user')";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        exit("Erreur lors de la préparation de la requête, vérifiez les logs pour plus de détails.");
    }
    $stmt->bind_param("ssss", $email, $nom, $prenom, $password);
    if ($stmt->execute()) {
        header("Location: connexion.php");
        exit();
    } else {
        exit("Erreur lors de l'exécution de la requête, vérifiez les logs pour plus de détails.");
    }
    $stmt->close();
}
$conn->close();
?>