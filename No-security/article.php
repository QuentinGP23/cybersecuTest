<?php
session_start();
include 'db.php';
if (isset($_POST['submit_comment']) && isset($_SESSION['id_utilisateur']) && isset($_GET['id'])) {
    $commentaire = $conn->real_escape_string($_POST['commentaire']);
    $id_utilisateur = $_SESSION['id_utilisateur'];
    $id_article = $_GET['id'];
    $stmt = $conn->prepare("INSERT INTO commentaire (contenu, id_utilisateur, id_article, dateComm) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sii", $commentaire, $id_utilisateur, $id_article);
    $stmt->execute();
    $stmt->close();
    header("Location: article.php?id=" . $id_article);
    exit();
}
if (isset($_GET['id'])) {
    $id_article = $_GET['id'];
    $sql = "SELECT article.*, utilisateur.nom, utilisateur.prenom 
            FROM article
            JOIN utilisateur ON article.id_utilisateur = utilisateur.id_utilisateur 
            WHERE id_article = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_article);
    $stmt->execute();
    $result = $stmt->get_result();
    $article = $result->fetch_assoc();
    $sql_comments = "SELECT commentaire.*, utilisateur.nom, utilisateur.prenom 
                     FROM commentaire 
                     JOIN utilisateur ON commentaire.id_utilisateur = utilisateur.id_utilisateur 
                     WHERE id_article = ?";
    $stmt_comments = $conn->prepare($sql_comments);
    $stmt_comments->bind_param("i", $id_article);
    $stmt_comments->execute();
    $comments = $stmt_comments->get_result();

    $stmt->close();
    $stmt_comments->close();
}
?>
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="admin.php" class="button">Retour à l'administration</a>
<?php else: ?>
    <a href="index.php" class="button">Retour à l'accueil</a>
<?php endif; ?>
<!DOCTYPE html>
<html lang="fr">

<body>
    <main class="article">
        <h1>
            <?php echo $article['titre']; ?>
        </h1>
        <img src="<?php echo $article['chemin_image']; ?>" alt="Image de l'article">
        <p>
            <?php echo $article['contenu']; ?>
        </p>
        <p>Auteur :
            <?php echo $article['prenom'] . ' ' . $article['nom']; ?>
        </p>
        <form action="article.php?id=<?php echo $id_article; ?>" method="post">
            <textarea name="commentaire" required placeholder="Votre commentaire"></textarea>
            <button type="submit" name="submit_comment">Poster le commentaire</button>
        </form>

        <?php if ($comments->num_rows > 0): ?>
            <?php while ($comment = $comments->fetch_assoc()): ?>
                <div class="commentaire">
                    <p>
                        <?php echo $comment['contenu']; ?>
                    </p>
                    <p>Posté par :
                        <?php echo $comment['prenom'] . ' ' . $comment['nom']; ?>
                    </p>
                    <p>Le :
                        <?php echo $comment['dateComm']; ?>
                    </p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Pas de commentaire.</p>
        <?php endif; ?>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="admin.php" class="button">Retour à l'administration</a>
        <?php else: ?>
            <a href="index.php" class="button">Retour à l'accueil</a>
        <?php endif; ?>
        <?php $conn->close(); ?>
    </main>
</body>

</html>

</html>