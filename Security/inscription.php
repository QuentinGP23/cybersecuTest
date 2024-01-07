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
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valider les entrées
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    if (!$email || !$nom || !$prenom) {
        exit('Les données fournies ne sont pas valides.');
    }
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $sql = "INSERT INTO utilisateur (email, nom, prenom, password, role) VALUES (?, ?, ?, ?, 'user')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $email, $nom, $prenom, $passwordHash);
        $stmt->execute();
        header("Location: connexion.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        error_log($e->getMessage());
        exit('Un problème est survenu. Veuillez réessayer plus tard.');
    } finally {
        $stmt->close();
        $conn->close();
    }
}
?>