<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <main class="connexion">
        <form action="connexion.php" method="post">
            <h1>Connexion</h1>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Se connecter</button>
            <a href="inscription.php">Pas encore de compte ? Inscrivez vous</a>
        </form>
    </main>
</body>
</html>
<?php
session_start();
require_once 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    if (!$email) {
        exit('Email invalide.');
    }
    $sql = "SELECT * FROM utilisateur WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            if ($_SESSION['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
        } else {
            echo "Identifiants incorrects";
        }
    } else {
        echo "Identifiants incorrects";
    }
    $stmt->close();
    $conn->close();
}
?>
