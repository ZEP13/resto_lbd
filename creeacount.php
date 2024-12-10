<?php
session_start();
require_once "./db_connect.php";
if (isset($_SESSION["id_user"])) {
    header("Location: profil.php");
}
if ($_POST) {
    if (
        isset($_POST['user_nom']) && !empty($_POST['user_nom'])
        && isset($_POST['user_prenom']) && !empty($_POST['user_prenom'])
        && isset($_POST['user_mail']) && !empty($_POST['user_mail'])
        && isset($_POST['user_phone']) && !empty($_POST['user_phone'])
        && isset($_POST['user_password']) && !empty($_POST['user_password'])
        && isset($_POST['confirm_password']) && !empty($_POST['confirm_password'])
    ) {
        $user_nom = strip_tags($_POST['user_nom']);
        $user_prenom = strip_tags($_POST['user_prenom']);
        $user_mail = strip_tags($_POST['user_mail']);
        $user_phone = strip_tags($_POST['user_phone']);
        $user_password = strip_tags($_POST['user_password']);
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        $pattern_phone_belgium = '/^(?:\+32|0)[1-9](\d{8})$/';

        $sql_check_email = 'SELECT COUNT(*) FROM `db_users` WHERE `user_mail` = :user_mail';
        $query_check_email = $db->prepare($sql_check_email);
        $query_check_email->bindValue(':user_mail', $user_mail, PDO::PARAM_STR);
        $query_check_email->execute();
        $email_count = $query_check_email->fetchColumn();

        if (!preg_match($pattern, $user_password)) {
            $_SESSION['erreur'] = "Les mots de passe doit contenir au minium un nbre, un carac special,une majuscule,taille = 8.";
        } elseif (!preg_match($pattern_phone_belgium, $user_phone)) {
            $_SESSION['erreur'] = "Le numéro de téléphone est invalide. Il doit commencer par +32 ou 0 et comporter 9 chiffres.";
        } elseif ($_POST['user_password'] !== $_POST['confirm_password']) {
            $_SESSION['erreur'] = "Les mots de passe ne correspondent pas.";
        } elseif (!filter_var($user_mail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erreur'] = "Le mail est incorrect.";
        } elseif ($email_count > 0) {
            $_SESSION['erreur'] = "Cet e-mail est déjà utilisé. Veuillez en choisir un autre.";
            header('Location: creeacount.php');
            exit();
        } else {

            $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

            $sql = 'INSERT INTO `db_users` (`user_nom`, `user_prenom`, `user_mail`, `user_password`,`user_phone`) VALUES (:user_nom, :user_prenom, :user_mail, :user_password, :user_phone);';

            $query = $db->prepare($sql);

            $query->bindValue(':user_nom', $user_nom, PDO::PARAM_STR);
            $query->bindValue(':user_prenom', $user_prenom, PDO::PARAM_STR);
            $query->bindValue(':user_mail', $user_mail, PDO::PARAM_STR);
            $query->bindValue(':user_phone', $user_phone, PDO::PARAM_STR);
            $query->bindValue(':user_password', $hashed_password, PDO::PARAM_STR);


            if ($query->execute()) {
                $_SESSION['message'] = "Compte créé";
                header('Location: login.php');
                exit();
            } else {
                $_SESSION['erreur'] = "Une erreur est survenue, veuillez réessayer.";
                header('Location: creeacount.php');
                exit();
            }
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
    <link rel="stylesheet" href="style_createacount.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <?php include 'header.php' ?>
    <form action="./creeacount.php" method="post">
        <div class="boxlog">
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
                    <label for="">Nom utilisateur</label><br />
                    <input type="text" id="username" name="user_nom" required />
                </div>
                <div class="userprenom">
                    <label for="">Prenom utilisateur</label><br />
                    <input type="text" id="userpre" name="user_prenom" required />
                </div>
                <div class="usermail">
                    <label for="">Adresse mail</label><br />
                    <input type="email" id="mail" name="user_mail" required />
                </div>
                <div class="userphone">
                    <label for="">Telephone utilisateur</label><br />
                    <input type="tel" id="user" name="user_phone" required />
                </div>
                <div class="password">
                    <label for="">Mot de passe</label><br />
                    <input type="password" id="mdp" name="user_password" required />
                </div>
                <div class="passwordconfirm">
                    <label for="">Confirmer Mot de passe</label><br />
                    <input type="password" id="mdpconfirm" name="confirm_password" required />
                </div>
                <div class="showPassword">
                    <label for="showPassword">Afficher le mot de passe</label>
                    <input type="checkbox" id="showPassword" onclick="togglePassword()" />
                </div>
            </div>
            <div class="submit">
                <input type="submit" class="btnsubmit" name="submit" />
            </div>
            <p>Vous avec deja un compte ?<a href="./login.php"> Connectez vous !</a></p>
        </div>
    </form>
    <?php include 'footer.php' ?>
    <script src="./index.js"></script>
</body>

</html>