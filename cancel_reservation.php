<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id_reservation'])) {
    $id_reservation = $_GET['id_reservation'];
    require_once 'db_connect.php';

    // Vérification que la réservation appartient à l'utilisateur connecté
    $sql = 'SELECT * FROM db_resrv WHERE id_reservation = :id_reservation AND user_id = :id_user';
    $query = $db->prepare($sql);
    $query->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
    $query->bindParam(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);
    $query->execute();
    $reservation = $query->fetch(PDO::FETCH_ASSOC);

    if ($reservation) {
        // Convertir la date et l'heure de la réservation en timestamp
        $reserv_date_time = strtotime($reservation['reserv_date'] . ' ' . $reservation['reserv_heure']);
        $current_time = time(); // Heure actuelle

        // Vérifier si la réservation est dans le futur
        if ($reserv_date_time > $current_time) {
            // La réservation est dans le futur, on peut la supprimer
            $delete_sql = 'DELETE FROM db_resrv WHERE id_reservation = :id_reservation';
            $delete_query = $db->prepare($delete_sql);
            $delete_query->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
            $delete_query->execute();

            $_SESSION['message'] = "Réservation annulée avec succès.";
        } else {
            $_SESSION['erreur'] = "Vous ne pouvez pas annuler une réservation déjà passée.";
        }
    } else {
        $_SESSION['erreur'] = "Réservation non trouvée.";
    }

    header("Location: profil.php");
    exit();
}
