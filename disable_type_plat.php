<?php
// On démarre une session
session_start();

// Est-ce que l'id existe et n'est pas vide dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    require_once('db_connect.php');

    // On nettoie l'id envoyé
    $id_type_plat = strip_tags($_GET['id']);

    $sql = 'SELECT * FROM `table_type_plat` WHERE `id_type_plat` = :id_type_plat;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':id_type_plat', $id_type_plat, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();

    // On récupère le produit
    $menu = $query->fetch();
    // On vérifie si le produit existe
    if (!$menu) {
        $_SESSION['erreur'] = "Cet id n'existe pas";
        header('Location: index_menu.php');
    }

    $type_plat_actif = ($menu['type_plat_actif'] == 0) ? 1 : 0;

    $sql = 'UPDATE `table_type_plat` SET `type_plat_actif`=:type_plat_actif WHERE `id_type_plat` = :id_type_plat;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètres
    $query->bindValue(':id_type_plat', $id_type_plat, PDO::PARAM_INT);
    $query->bindValue(':type_plat_actif', $type_plat_actif, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();

    header('Location: index_menu.php');
} else {
    $_SESSION['erreur'] = "URL invalide";
    header('Location: index_menu.php');
}
