<?php
// On démarre une session
session_start();

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

    $menu_actif = ($menu['menu_actif'] == 0) ? 1 : 0;

    $sql = 'UPDATE `db_menu` SET `menu_actif`=:menu_actif WHERE `id_plat` = :id_plat;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètres
    $query->bindValue(':id_plat', $id_plat, PDO::PARAM_INT);
    $query->bindValue(':menu_actif', $menu_actif, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();

    header('Location: index_menu.php');
} else {
    $_SESSION['erreur'] = "URL invalide";
    header('Location: index_menu.php');
}
