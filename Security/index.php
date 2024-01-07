<?php
session_start();
require_once 'db.php';
$sql = "SELECT article.id_article, article.titre, article.contenu, article.datepubli, article.chemin_image, utilisateur.nom, utilisateur.prenom
        FROM article
        JOIN utilisateur ON article.id_utilisateur = utilisateur.id_utilisateur";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <main>
        <h1>Bienvenue sur la page d'accueil</h1>
        <form action="deconnexion.php" method="post">
            <button type="submit" name="logout">Déconnexion</button>
        </form>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="articleAdmin">
                    <img src="<?php echo htmlspecialchars($row['chemin_image']); ?>"
                        alt="<?php echo htmlspecialchars($row['titre']); ?>">
                    <h2>
                        <?php echo htmlspecialchars($row['titre']); ?>
                    </h2>
                    <p>Auteur :
                        <?php echo htmlspecialchars($row['prenom'] . ' ' . $row['nom']); ?>
                    </p>
                    <p>Publié le :
                        <?php echo htmlspecialchars($row['datepubli']); ?>
                    </p>
                    <a href="article.php?id=<?php echo htmlspecialchars($row['id_article']); ?>">Voir plus</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Aucun article trouvé.</p>
        <?php endif; ?>
        <?php
        $stmt->close();
        $conn->close();
        ?>
    </main>
</body>

</html>