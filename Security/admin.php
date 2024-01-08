<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$sql = "SELECT article.id_article, article.titre, article.contenu, article.datepubli, article.chemin_image, utilisateur.nom, utilisateur.prenom 
        FROM article 
        JOIN utilisateur ON article.id_utilisateur = utilisateur.id_utilisateur";
$result = $conn->query($sql);

$sql_articles = "SELECT id_article, titre FROM article";
$result_articles = $conn->query($sql_articles);

// Supprimer un article
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supprimer'])) {
    // Vérifier le token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Erreur de validation CSRF.');
    }

    $id_article = filter_var($_POST['id_article'], FILTER_SANITIZE_NUMBER_INT);

    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("DELETE FROM commentaire WHERE id_article = ?");
        $stmt->bind_param("i", $id_article);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM article WHERE id_article = ?");
        $stmt->bind_param("i", $id_article);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        header("Location: admin.php");
        exit();
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        throw $exception;
    }
}
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
        <h1>Bienvenue sur la page admin</h1>
        <form action="deconnexion.php" method="post">
            <button type="submit" name="logout">Déconnexion</button>
        </form>
        <form action="ajouter_article.php" method="post" enctype="multipart/form-data">
            <h2>Ajouter un nouvel article</h2>
            <input type="text" name="titre" placeholder="Titre de l'article" required>
            <textarea name="contenu" placeholder="Contenu de l'article" required></textarea>
            <input type="file" name="image" required>
            <button type="submit" name="ajouter">Ajouter l'article</button>
        </form>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="articleAdmin">
                    <img src="<?php echo htmlspecialchars($row['chemin_image']); ?>" alt="Image de l'article">
                    <h2>
                        <?php echo htmlspecialchars($row['titre']); ?>
                    </h2>
                    <p>Auteur :
                        <?php echo htmlspecialchars($row['prenom'] . ' ' . $row['nom']); ?>
                    </p>
                    <a href="article.php?id=<?php echo htmlspecialchars($row['id_article']); ?>">Voir plus</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Aucun article trouvé.</p>
        <?php endif; ?>
        <form action="admin.php" method="post">
            <h2>Supprimer un article</h2>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <select name="id_article" required>
                <option value="">Sélectionnez un article</option>
                <?php if ($result_articles && $result_articles->num_rows > 0): ?>
                    <?php while ($article = $result_articles->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($article['id_article']); ?>">
                            <?php echo htmlspecialchars($article['titre']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
            <button type="submit" name="supprimer">Supprimer l'article</button>
        </form>
    </main>
    <?php $conn->close(); ?>
</body>

</html>