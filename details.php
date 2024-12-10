<?php
// On démarre une session
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}
// Est-ce que l'id existe et n'est pas vide dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    require_once('db_connect.php');

    // On nettoie l'id envoyé
    $id_plat = strip_tags($_GET['id']);

    $sql = 'SELECT * FROM `db_menu` WHERE `id_plat` = :id_plat;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':id_plat', $id_plat, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();

    // On récupère le produit
    $menu = $query->fetch();

    // On vérifie si le produit existe
    if (!$menu) {
        $_SESSION['erreur'] = "Cet id n'existe pas";
        header('Location: index_detail.php');
    }
} else {
    $_SESSION['erreur'] = "URL invalide";
    header('Location: index_detail.php');
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du plat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <h1>Détails du plat <?= $menu['nom_plat'] ?></h1>
                <p>ID : <?= $menu['id_plat'] ?></p>
                <p>Description : <?= $menu['desc_plat'] ?></p>
                <p>Prix : <?= $menu['prix_plat'] ?> €</p>
                <p>Photo : <?= $menu['photo_plat'] ?></p>
                <p>Disponible : <?= $menu['menu_actif'] ?></p>
                <p><a href="index_menu.php">Retour</a> <a href="edit.php?id=<?= $menu['id_plat'] ?>">Modifier</a></p>
            </section>
        </div>
    </main>
</body>

</html>