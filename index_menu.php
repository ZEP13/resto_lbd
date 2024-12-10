<?php
// On démarre une session
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}

// On inclut la connexion à la base
require_once('db_connect.php');

$sql = 'SELECT * FROM  `db_menu`JOIN table_type_plat ON db_menu.type_plat = table_type_plat.id_type_plat';
$query = $db->prepare($sql);
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = 'SELECT * FROM  `table_type_plat`';
$query = $db->prepare($sql);
$query->execute();
$types = $query->fetchAll(PDO::FETCH_ASSOC);

require_once('close.php');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des plats</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <main class="container m-5">
        <div class="row">
            <section class="col-12 mb-5">
                <?php
                if (!empty($_SESSION['erreur'])) {
                    echo '<div class="alert alert-danger" role="alert">
                                ' . $_SESSION['erreur'] . '
                            </div>';
                    $_SESSION['erreur'] = "";
                }
                ?>
                <?php
                if (!empty($_SESSION['message'])) {
                    echo '<div class="alert alert-success" role="alert">
                                ' . $_SESSION['message'] . '
                            </div>';
                    $_SESSION['message'] = "";
                }
                ?>
                <h1>Liste des plats</h1>
                <table class="table">
                    <thead>
                        <th>id_plat</th>
                        <th>type</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>desc</th>
                        <th>photo</th>
                        <th>Actif</th>
                    </thead>
                    <tbody data-bs-spy="scroll" data-bs-target="#navbar-example3" data-bs-smooth-scroll="true" class="scrollspy-example-2" tabindex="0">
                        <?php
                        // On boucle sur la variable result
                        foreach ($result as $menu) {
                        ?>
                            <tr>
                                <td><?= $menu['id_plat'] ?></td>
                                <td><?= $menu['nom_type_plat'] ?></td>
                                <td><?= $menu['nom_plat'] ?></td>
                                <td><?= $menu['prix_plat'] ?> €</td>
                                <td><?= $menu['desc_plat'] ?></td>
                                <td><?= $menu['photo_plat'] ?></td>
                                <td><?= $menu['menu_actif'] ?></td>
                                <td><a href="disable.php?id=<?= $menu['id_plat'] ?>">A/D</a> <a href="details.php?id=<?= $menu['id_plat'] ?>">Voir</a> <a href="edit.php?id=<?= $menu['id_plat'] ?>">Modifier</a> <a href="delete.php?id=<?= $menu['id_plat'] ?>">Supprimer</a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <a href="add.php" class="btn btn-primary">Ajouter un plat</a><br>
            </section>
            <section class="col-12 mb-5">
                <?php
                if (!empty($_SESSION['erreur'])) {
                    echo '<div class="alert alert-danger" role="alert">
                                ' . $_SESSION['erreur'] . '
                            </div>';
                    $_SESSION['erreur'] = "";
                }
                ?>
                <?php
                if (!empty($_SESSION['message'])) {
                    echo '<div class="alert alert-success" role="alert">
                                ' . $_SESSION['message'] . '
                            </div>';
                    $_SESSION['message'] = "";
                }
                ?>
                <h1>Liste des type de plats</h1>
                <table class="table">
                    <thead>
                        <th>id_plat</th>
                        <th>nom type</th>
                        <th>Actif</th>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($types as $type) {
                        ?>
                            <tr>
                                <td><?= $type['id_type_plat'] ?></td>
                                <td><?= $type['nom_type_plat'] ?></td>
                                <td><?= $type['type_plat_actif'] ?></td>
                                <td><a href="disable_type_plat.php?id=<?= $type['id_type_plat'] ?>">A/D</a></td>

                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <a href="add_type_plat.php" class="btn btn-primary">Ajouter un type de plat</a><br>
            </section>
        </div>
        <a href="adminuser.php" class="btn btn-primary mr-3">Voir mes utilisateurs</a>
        <a href="menu.php" class="btn btn-primary mr-3">Voir mon menu</a>
        <a href="index.php" class="btn btn-primary">Page aceuille</a>
    </main>
</body>

</html>