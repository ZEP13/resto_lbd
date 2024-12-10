<?php
session_start();
require_once 'db_connect.php';
if (isset($_SESSION["id_user"])) {
    header("Location: profil.php");
}
if ($_POST) {
    if (
        isset($_POST['user_mail']) && !empty($_POST['user_mail'])
        && isset($_POST['user_password']) && !empty($_POST['user_password'])
    ) {

        $user_mail = htmlspecialchars($_POST['user_mail']);
        $user_password = htmlspecialchars($_POST['user_password']);
        $pseudoadmin = 'admin@lol.be';
        $mdpadmin = '1234//adminLOL';

        $sql_check_email = 'SELECT * FROM `db_users` WHERE `user_mail` = :user_mail';
        $query_check_email = $db->prepare($sql_check_email);
        $query_check_email->bindValue(':user_mail', $user_mail, PDO::PARAM_STR);
        $query_check_email->execute();

        $user = $query_check_email->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION['erreur'] = "Cet utilisateur n'existe pas. Veuillez créer un compte.";
            header('Location: login.php');
            exit();
        }
        if (!password_verify($user_password, $user['user_password'])) {
            $_SESSION['erreur'] = "Le mot de passe est incorrect.";
            header('Location: login.php');
            exit();
        }
        if ($user_mail == $pseudoadmin) {
            $_SESSION["user"] = "admin";
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['user_mail'] = $user['user_mail'];
            header("Location: index_menu.php");
            exit();
        }

        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['user_mail'] = $user['user_mail'];
        header('Location: profil.php');
        exit();
    } else {
        $_SESSION['erreur'] = "Le formulaire est incomplet.";
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style_createacount.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Login</title>
</head>

<body>
    <?php include 'header.php' ?>

    <form action="./login.php" method="post">
        <legend>Connectez vous afin de reserver une table</legend>
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
        <div class="log">
            <div class="username">
                <label for="user">Email utilisateur</label><br />
                <input type="email" id="user" name="user_mail" required />
            </div>
            <div class="password">
                <label for="mdp">Mot de passe</label><br />
                <input type="password" id="mdp" name="user_password" required />
            </div>
            <div class="login">
                <a href="./creeacount.php"> Créer un compte</a>
            </div>
        </div>
        <div class="connect">
            <input type="submit" name="submit" value="Se connecter" class="btnconnect" />
        </div>
    </form>
    <?php include 'footer.php' ?>
</body>

</html>