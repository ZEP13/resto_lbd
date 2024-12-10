<?php
session_start();
require_once('db_connect.php');
if (!isset($_SESSION["id_user"])) {
    header("Location: login.php");
}

if ($_POST) {
    if (
        isset($_POST['reserv_nbr_pers']) && !empty($_POST['reserv_nbr_pers'])
        && isset($_POST['reserv_date']) && !empty($_POST['reserv_date'])
        && isset($_POST['reserv_heure']) && !empty($_POST['reserv_heure'])
        && isset($_POST['reserv_com'])
    ) {
        $reserv_nbr_pers = strip_tags($_POST['reserv_nbr_pers']);
        $reserv_date = strip_tags($_POST['reserv_date']);
        $reserv_heure = strip_tags($_POST['reserv_heure']);
        $reserv_com = strip_tags($_POST['reserv_com']);
        $user_id = $_SESSION['id_user'];


        $heure = strtotime($reserv_heure);
        $heure_min = strtotime("12:00");
        $heure_max = strtotime("14:00");
        $heure_min_2 = strtotime("19:00");
        $heure_max_2 = strtotime("23:00");

        if (!(($heure >= $heure_min && $heure <= $heure_max) || ($heure >= $heure_min_2 && $heure <= $heure_max_2))) {
            $_SESSION['erreur'] = "L'heure de réservation doit être entre 12h-14h ou 19h-23h.";
            header('Location: reservation.php');
            exit();
        }

        if ($heure >= $heure_min && $heure <= $heure_max) {
            // C'est le créneau de 12h00 à 14h00
            $sql_count = 'SELECT SUM(reserv_nbr_pers) AS total_reservations FROM db_resrv WHERE reserv_date = :reserv_date AND reserv_heure BETWEEN "12:00" AND "14:00"';
        } else {
            // C'est le créneau de 19h00 à 23h00
            $sql_count = 'SELECT SUM(reserv_nbr_pers) AS total_reservations FROM db_resrv WHERE reserv_date = :reserv_date AND reserv_heure BETWEEN "19:00" AND "23:00"';
        }

        $query_count = $db->prepare($sql_count);
        $query_count->bindValue(':reserv_date', $reserv_date, PDO::PARAM_STR);
        $query_count->execute();

        $result = $query_count->fetch(PDO::FETCH_ASSOC);
        $total_reservations = $result['total_reservations'] ?? 0; // S'il n'y a pas de réservations, total_reservations sera 0

        // Vérifier si la réservation dépasse la limite de 50 personnes
        if (($total_reservations + $reserv_nbr_pers) > 50) {
            $_SESSION['erreur'] = "Il n'y a pas assez de disponibilité pour " . $reserv_nbr_pers . " personnes à l'heure et à la date choisies.";
            header('Location: reservation.php');
            exit();
        }

        // Vérification du jour de la semaine (Jeudi, Vendredi, Samedi)
        $jour_semaine = date('l', strtotime($reserv_date));
        if (!in_array($jour_semaine, ['Thursday', 'Friday', 'Saturday'])) {
            $_SESSION['erreur'] = "La réservation doit être effectuée un Jeudi, Vendredi ou Samedi.";
            header('Location: reservation.php');
            exit();
        }

        $sql = 'INSERT INTO `db_resrv` (`reserv_nbr_pers`,`reserv_date`,`reserv_heure`,`reserv_com`,`user_id`) VALUE (:reserv_nbr_pers, :reserv_date, :reserv_heure, :reserv_com, :user_id)';

        $query = $db->prepare($sql);

        $query->bindValue(':reserv_nbr_pers', $reserv_nbr_pers, PDO::PARAM_INT);
        $query->bindValue(':reserv_date', $reserv_date, PDO::PARAM_STR);
        $query->bindValue(':reserv_heure', $reserv_heure, PDO::PARAM_STR);
        $query->bindValue(':reserv_com', $reserv_com, PDO::PARAM_STR);
        $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        if ($query->execute()) {
            $_SESSION['message'] = "Réservation réussie";
            header('Location: reservation.php');
            exit();
        } else {
            $_SESSION['erreur'] = "Une erreur est survenue, veuillez réessayer.";
            header('Location: reservation.php');
            exit();
        }
    } else {
        $_SESSION['erreur'] = "Le formulaire est incomplet.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <?php include 'header.php' ?>
    <div class="container mt-5 mb-5">
        <h2 class="text-center mb-4">Formulaire de réservation</h2>
        <form action="./reservation.php" method="post">
            <div class="row mb-3">
                <?php
                if (isset($_SESSION['erreur'])) {
                    echo '<div class="message error">' . $_SESSION['erreur'] . '</div>';
                    unset($_SESSION['erreur']);
                }
                if (isset($_SESSION['message'])) {
                    echo '<div class="message success">' . $_SESSION['message'] . '</div>';
                    unset($_SESSION['message']);
                }
                ?>
                <div class="col-md-6">
                    <label for="number" class="form-label">Nombres de Personnes</label>
                    <input type="number" class="form-control" id="number" name="reserv_nbr_pers" value="1" min="0" max="50" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="date" class="form-label">Date de réservation</label>
                    <input type="date" class="form-control" id="date" name="reserv_date" required>
                </div>
                <div class="col-md-6">
                    <label for="heure" class="form-label">Heure</label>
                    <input type="time" class="form-control" id="heure" name="reserv_heure" value="12:00" min="12:00" max="21:00" step="1800" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="commentaires" class="form-label">Commentaires</label>
                <textarea class="form-control" id="commentaires" name="reserv_com" rows="4"></textarea>
            </div>
            <div class="text-center">
                <button type="submit" name="submit" class="btn btn-primary">Réserver</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <script src="./index.js"></script>
    <?php include 'footer.php' ?>
</body>

</html>