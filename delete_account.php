<?php
session_start();
require_once './db_connect.php';

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    // Préparer et exécuter la requête pour supprimer l'utilisateur de la base de données
    $sql = 'DELETE FROM db_users WHERE id_user = :id_user';
    $query = $db->prepare($sql);
    $query->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $query->execute();

    // Détruire la session et rediriger vers la page de connexion
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
