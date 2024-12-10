<?php
// On démarre une session
session_start();

// Est-ce que l'id existe et n'est pas vide dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    require_once('db_connect.php');

    // On nettoie l'id envoyé
    $id_user = strip_tags($_GET['id']);

    $sql = 'SELECT * FROM `db_users` WHERE `id_user` = :id_user;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':id_user', $id_user, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();

    $menu = $query->fetch();

    if (!$menu) {
        $_SESSION['erreur'] = "Cet id n'existe pas";
        header('Location: index_menu.php');
        die();
    }

    $sql = 'DELETE FROM `db_users` WHERE `id_user` = :id_user;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':id_user', $id_user, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();
    $_SESSION['message'] = "Utilisateur supprimé";
    header('Location: index_menu.php');
} else {
    $_SESSION['erreur'] = "URL invalide";
    header('Location: index_menu.php');
}
