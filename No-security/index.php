<?php
session_start();

include 'db.php';

$sql = "SELECT article.id_article, article.titre, article.contenu, article.datepubli, article.chemin_image, utilisateur.nom, utilisateur.prenom
        FROM article
        JOIN utilisateur ON article.id_utilisateur = utilisateur.id_utilisateur";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
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

                    <img src="<?php echo $row['chemin_image']; ?>" alt="Image de l'article">
                    <div>
                        <?php echo $row['titre']; ?>
                    </div>
                    <p>Auteur :
                        <?php echo $row['prenom'] . ' ' . $row['nom']; ?>
                    </p>
                    <a href="article.php?id=<?php echo $row['id_article']; ?>">
                        <p>voir plus</p>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Aucun article trouvé.</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </main>
</body>

</html>