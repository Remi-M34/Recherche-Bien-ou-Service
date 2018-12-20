<?php
/**
 * Created by PhpStorm.
 * User: Remi MATTEI
 * Numéro étudiant: 21516143
 * Date: 24/10/2018
 * Time: 20:27
 */

session_start();
include("controllers/config.php");
include("header.php");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Proposition ajoutée</title>
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />

    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/themes/base/jquery-ui.css" rel="stylesheet"
          type="text/css"/>
    <script src="js/jquery-3.3.1.js"></script>
</head>
<body>
<div id="page proposer_ok_page">
    <div id="proposition_ok">

        <?php

        // Erreur si : formulaire non validé ou raffraichissement de la page
        if (!isset($_POST['submit']) || !isset($_SESSION['verif_proposer'])) {
            echo "        <div class=\"notif errorbox\">
            <h1>Erreur !</h1>
            <span class=\"alerticon\"><img src=\"images/error.png\" alt=\"erreurr\" /></span>
            <p>Il semblerait que le formulaire n'ait pas été correctement rempli ou vous avez tenté de raffraichir la page actuelle!</p>
            <button id='proposer_ok_back' onclick=\"history.go(-1);\">Retour </button>
        </div>";
            exit();
        }

        $image = 0;
        $ext = NULL;
        if (isset($_FILES['image']) && $_FILES["image"]["tmp_name"]) {
            $image = 1;
        }


        if (isset($_FILES['image']) && $_FILES["image"]["tmp_name"]) {
            $target_dir = "uploads/" . $_POST['type'] . "s/";

            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $stmt = $pdo->query("SELECT max(id)+1 as id from {$_POST['type']}s");
            $row = $stmt->fetch();

            if (!$stmt) {
                echo "\nPDO::errorInfo():\n";
                print_r($pdo->errorInfo());
            }

            $imageFileType = strtolower(pathinfo(basename($_FILES["image"]["name"]), PATHINFO_EXTENSION));
            $ext = strtolower(pathinfo(basename($_FILES["image"]["name"]), PATHINFO_EXTENSION));
            $target_file = $target_dir . $row[0] . "." . $imageFileType;
            $uploadOk = 1;
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $notiftype1 = new notification("Le fichier n'est pas une image", "notice_warning");
                $uploadOk = 0;
            }
            if (file_exists($target_file)) {
                unlink($target_file);
            }
// Check file size
            if ($_FILES["image"]["size"] > 5000000) {
                $notifsize = new notification("Le fichier est trop gros", "notice_warning");
                $uploadOk = 0;
            }
// Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" && $imageFileType != "PNG" ) {
                $notiftype = new notification("Images autorisées : jpeg png gif", "notice_warning");
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                $notiferr = new notification("L'image n'a pas pu être uploadée.<br>Vous pouvez en ajouter une depuis la liste de vos propositions", "notice_warning");
                $image = 0;
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
//                    echo $target_file;


                } else {
                    $notif = new notification("Erreur lors de l'upload de l'image", "notice_warning");
                    $image = 0;
                }
            }

        }
//echo $ext;
        if ($_POST['type'] == "bien") {
            $stmt = $pdo->prepare("INSERT INTO {$_POST['type']}s (id_utilisateur, titre, description, image, mots, categorie, lat, lng, prix_neuf, adresse) 
values (?, ?, ?, ?, ?, ?, ? ,?, ?, ?)");
            $stmt->execute([$_SESSION['id'], $_POST['titre'], $_POST['description'], $ext, $_POST['mots'], $_POST['select-categorie-biens'], $_POST['lat'], $_POST['lng'], $_POST['prix'], $_POST['adresse']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO {$_POST['type']}s (adresse, id_utilisateur, titre, description, image, mots, categorie, lat, lng, lun_d, lun_f, mar_d, mar_f, mer_d, mer_f, jeu_d, jeu_f, ven_d, ven_f, sam_d, sam_f, dim_d, dim_f) 
values (?, ?, ?, ?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ? ,?,?)");
            $stmt->execute([
                    $_POST['adresse'],
                    $_SESSION['id'],
                $_POST['titre'],
                $_POST['description'],
                $ext,
                $_POST['mots'],
                $_POST['select-categorie-services'],
                $_POST['lat'],
                $_POST['lng'],
                $_POST['ph1_d'],
                $_POST['ph1_f'],
                $_POST['ph2_d'],
                $_POST['ph2_f'],
                $_POST['ph3_d'],
                $_POST['ph3_f'],
                $_POST['ph4_d'],
                $_POST['ph4_f'],
                $_POST['ph5_d'],
                $_POST['ph5_f'],
                $_POST['ph6_d'],
                $_POST['ph6_f'],
                $_POST['ph7_d'],
                $_POST['ph7_f']
            ]);
        }

        if (!$stmt) {
            echo "\nPDO::errorInfo():\n";
            print_r($pdo->errorInfo());
        }

        $thisuser = unserialize($_SESSION['user']);
        $thisuser->setPropositions($thisuser->getPropositions()+1);
        $_SESSION['user'] = serialize($thisuser);

        // Afin d'éviter le rafraichissement de la page
        unset($_SESSION['verif_proposer']);

        ?>

        <div class="notif successbox">
            <h1><?php echo ucfirst($_POST['type']); ?> ajouté !</h1>
            <span class="alerticon"><img src="images/check.png" alt="valide"/></span>
            <p>Vous pouvez le modifier sur la page <a href="mes_propositions.php">Mes propositions</a></p>
        </div>

    </div>

</div>

