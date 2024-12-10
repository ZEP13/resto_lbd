<?php
session_start();
if (!isset($_SESSION["id_user"])) {
    header("Location: login.php");
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    require_once './db_connect.php';


    $sql = 'SELECT * FROM db_users WHERE `id_user` = :id_user';
    $query = $db->prepare($sql);
    $query->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);


    $sql = 'SELECT*FROM db_resrv WHERE `user_id` = :id_user';
    $query = $db->prepare($sql);
    $query->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $query->execute();
    $reserv = $query->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: login.php");
    exit();
}
require_once "close.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styleprofil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Profil</title>
</head>

<body>
    <?php include 'header.php' ?>
    <div class="titleprofil">
        <h1>Profil de <?= htmlspecialchars($user['user_nom']); ?></h1>
    </div>
    <section>
        <div class="datauser">
            <div class="donperso">
                <h3>Données personnelles</h3>
            </div>
            <div class="tabledataperso">
                <table>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                    </tr>
                    <?php
                    if ($user) {
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($user['user_nom']) ?></td>
                            <td><?= htmlspecialchars($user['user_prenom']) ?></td>
                            <td><?= htmlspecialchars($user['user_mail']) ?></td>
                            <td><?= htmlspecialchars($user['user_phone']) ?></td>
                        </tr>
                    <?php
                    } else {
                        echo '<tr><td colspan="4">Aucune donnée disponible</td></tr>';
                    }
                    ?>
                </table>
            </div><br>
        </div>
    </section>
    <section>
        <div class="datareserv">
            <div class="oldreserv">
                <h3>Mes réservations</h3>
            </div>
            <div class="tablecienreserv">
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Nombres de Personnes</th>
                        <th>Commentaire</th>
                    </tr>
                    <?php
                    if ($reserv) {
                        foreach ($reserv as $reserv) {
                    ?>
                            <tr>
                                <td>
                                    <div class="reserv_date"><?= htmlspecialchars($reserv['reserv_date']) ?></div>
                                </td>
                                <td>
                                    <div class="reserv_nbr_pers"><?= htmlspecialchars($reserv['reserv_nbr_pers']) ?></div>
                                </td>
                                <td>
                                    <div class="reserv_com"><?= htmlspecialchars($reserv['reserv_com']) ?></div>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="4">Aucune ancinne reservation</td></tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
    </section>
    <div class="link">
        <a href="delete_account.php">Supprimer mon compte</a>
    </div>
    <div class="link">
        <a href="deconnect.php">Se deconnecter</a>
    </div>
    <?php include 'footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>