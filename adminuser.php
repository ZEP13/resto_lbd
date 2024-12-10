<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}
require_once 'db_connect.php';

$sql = 'SELECT * FROM db_users';
$query = $db->prepare($sql);
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);

require_once 'close.php';
?>
<!DOCTYPE html>
<html lang="fr-BE">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users admin</title>
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
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                    </tr>
                    <?php
                    foreach ($result as $user) {
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($user['user_nom']) ?></td>
                            <td><?= htmlspecialchars($user['user_prenom']) ?></td>
                            <td><?= htmlspecialchars($user['user_mail']) ?></td>
                            <td><?= htmlspecialchars($user['user_phone']) ?></td>
                            <td>
                                <?php
                                if ($user['id_user'] !== $_SESSION['id_user']) {
                                ?>
                                    <a href="deleteuser.php?id=<?= $user['id_user'] ?>">Supprimer</a>
                            </td>
                        </tr>
                <?php
                                }
                            }
                ?>
                </table>
                <a href="index_menu.php" class="btn btn-primary"> Retour Edit Menu </a><br>
            </section>
        </div>
    </main>
</body>

</html>