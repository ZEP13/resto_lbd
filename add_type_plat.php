<?php
session_start();
if ($_POST) {
    if (
        isset($_POST['nom_type_plat']) && !empty($_POST['nom_type_plat'])
    ) {
        require_once('db_connect.php');

        $nom_type_plat = strip_tags($_POST['nom_type_plat']);

        $sql = 'INSERT INTO `table_type_plat` (`nom_type_plat`) VALUES (:nom_type_plat);';
        $query = $db->prepare($sql);
        $query->bindValue(':nom_type_plat', $nom_type_plat, PDO::PARAM_STR);

        $query->execute();

        $_SESSION['message'] = "Plat ajouté";
        require_once('close.php');
        header('Location: index_menu.php');
        exit();
    } else {
        $_SESSION['erreur'] = "Le formulaire est incomplet";
    }
}
?>
<!DOCTYPE html>
<html lang="fr-BE">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter type plat</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <section class="col-12">
        <?php
        if (!empty($_SESSION['erreur'])) {
            echo '<div class="alert alert-danger" role="alert">
                                ' . $_SESSION['erreur'] . '
                            </div>';
            $_SESSION['erreur'] = "";
        }
        ?>
        <h2>Ajouter un type de plat</h2>
        <form method="post">
            <div class="form-group">
                <label for="prix">Nom du type de plat</label>
                <input type="text" id="prix_plat" name="nom_type_plat" class="form-control">
            </div>
            <button class="btn btn-primary">Envoyer</button>
        </form>
    </section>
</body>

</html>