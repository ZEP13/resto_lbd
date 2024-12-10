<?php
session_start();
require_once 'db_connect.php';

// On récupère tous les types de plats depuis la base de données
$sql_types = 'SELECT * FROM `table_type_plat` WHERE `type_plat_actif` != 0';
$query_types = $db->prepare($sql_types);
$query_types->execute();
$types = $query_types->fetchAll(PDO::FETCH_ASSOC);

// On récupère tous les plats depuis la base de données
$sql_plats = 'SELECT * FROM `db_menu` WHERE `menu_actif` != 0';
$query_plats = $db->prepare($sql_plats);
$query_plats->execute();
$plats = $query_plats->fetchAll(PDO::FETCH_ASSOC);

require_once "close.php";
?>

<!DOCTYPE html>
<html lang="fr-BE">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./stylemenu.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <title>MENU</title>
</head>

<body>
  <?php include 'header.php' ?>
  <div class="containermenu">
    <div class="titlemenu">
      <h1>MENU</h1>
    </div>
    <div class="boxmenu">

      <?php
      // Affichage des types de plats
      foreach ($types as $type) {
        // Récupérer le nom du type de plat
        $typeName = htmlspecialchars($type['nom_type_plat']);
        $typeId = $type['id_type_plat'];

        // Filtrer les plats pour ce type
        $platsParType = array_filter($plats, function ($plat) use ($typeId) {
          return $plat['type_plat'] == $typeId;
        });

        // Si des plats existent pour ce type, on les affiche
        if (!empty($platsParType)) {
      ?>
          <div class="<?= strtolower($typeName) ?>">
            <h2><?= $typeName ?></h2>
            <hr>
            <div class="container<?= strtolower($typeName) ?> boxtype">
              <table>
                <?php
                // Affichage des plats pour ce type
                foreach ($platsParType as $menu) {
                ?>
                  <tr>
                    <td>
                      <div class="platname"><?= htmlspecialchars($menu['nom_plat']) ?></div>
                    </td>
                    <td>
                      <div class="platdescr">~ <?= htmlspecialchars($menu['desc_plat']) ?> ~</div>
                    </td>
                    <td>
                      <div class="platprix"><?= htmlspecialchars($menu['prix_plat']) ?></div> €
                    </td>
                  </tr>
                <?php
                }
                ?>
              </table>
            </div>
          </div>
      <?php
        }
      }
      ?>

    </div>
  </div>
  <?php include 'footer.php' ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>