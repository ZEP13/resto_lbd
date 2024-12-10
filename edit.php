<?php
// On démarre une session
session_start();

if ($_POST) {
    if (
        isset($_POST['id_plat']) && !empty($_POST['id_plat'])
        && isset($_POST['type_plat']) && !empty($_POST['type_plat'])
        && isset($_POST['nom_plat']) && !empty($_POST['nom_plat'])
        && isset($_POST['desc_plat']) && !empty($_POST['desc_plat'])
        && isset($_POST['prix_plat']) && !empty($_POST['prix_plat'])
    ) {
        // On inclut la connexion à la base
        require_once('db_connect.php');

        // On nettoie les données envoyées
        $desc_plat = strip_tags($_POST['desc_plat']);
        $type_plat = strip_tags($_POST['type_plat']);
        $prix_plat = strip_tags($_POST['prix_plat']);
        $nom_plat = strip_tags($_POST['nom_plat']);
        $id_plat = strip_tags($_POST['id_plat']);

        $sql = "UPDATE `db_menu` SET `type_plat`=:type_plat, `prix_plat`=:prix_plat, `nom_plat`=:nom_plat, `desc_plat` =:desc_plat WHERE `id_plat`=:id_plat;";

        $query = $db->prepare($sql);
        $query->bindValue(':id_plat', $id_plat, PDO::PARAM_STR);
        $query->bindValue(':desc_plat', $desc_plat, PDO::PARAM_STR);
        $query->bindValue(':type_plat', $type_plat, PDO::PARAM_STR);
        $query->bindValue(':prix_plat', $prix_plat, PDO::PARAM_STR);
        $query->bindValue(':nom_plat', $nom_plat, PDO::PARAM_STR);

        $query->execute();

        $_SESSION['message'] = "Plat modifié";
        require_once('close.php');

        header('Location: index_menu.php');
    } else {
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
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
        header('Location: index_menu.php');
    }
} else {
    $_SESSION['erreur'] = "URL invalide";
    header('Location: index_menu.php');
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un plat</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <?php
                if (!empty($_SESSION['erreur'])) {
                    echo '<div class="alert alert-danger" role="alert">
                                ' . $_SESSION['erreur'] . '
                            </div>';
                    $_SESSION['erreur'] = "";
                }
                ?>
                <h1>Modifier un plat</h1>
                <form method="post">
                    <div class="form-group">
                        <label for="nom_plat">Nom du plat</label>
                        <input type="text" id="nom_plat" name="nom_plat" class="form-control" value="<?= $menu['nom_plat'] ?>">
                    </div>

                    <div class="form-group">
                        <label for="prix_plat">Prix du plat</label>
                        <input type="text" id="prix_plat" name="prix_plat" class="form-control" value="<?= $menu['prix_plat'] ?>">
                    </div>

                    <div class="form-group">
                        <label for="desc_plat">Description</label>
                        <input type="text" id="desc_plat" name="desc_plat" class="form-control" value="<?= $menu['desc_plat'] ?>">
                    </div>

                    <div class="form-group">
                        <label for="type_plat">Type de plat</label>
                        <select class="custom-select" name="type_plat" id="type_plat">
                            <?php

                            require_once('db_connect.php');
                            $sql = "SELECT * FROM `table_type_plat`";
                            $query = $db->prepare($sql);
                            $query->execute();

                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($row['id_type_plat'] == $menu['type_plat']) ? 'selected' : '';
                                echo '<option value="' . $row['id_type_plat'] . '" ' . $selected . '>' . $row['nom_type_plat'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <input type="hidden" value="<?= $menu['id_plat'] ?>" name="id_plat">
                    <button class="btn btn-primary">Envoyer</button>
                </form>
            </section>
        </div>
    </main>
</body>

</html>