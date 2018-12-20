<!DOCTYPE html>
<html>
<head>

    <title>Utilisateur</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/themes/base/jquery-ui.css" rel="stylesheet"
          type="text/css"/>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBwQxOi8hCzeOA9TfWU9wOq7DEG26kyuHk&libraries=places&language=gb"></script>

    <script src="js/jquery-3.3.1.js"></script>
</head>
<body>

<?php
/**
 * Created by PhpStorm.
 * User: Remi MATTEI
 * Numéro étudiant: 21516143
 * Date: 05/12/2018
 * Time: 03:50
 */



session_start();

require_once("controllers/config.php");
include("header.php");

$sql;
$utilisateur;



if (isset($_GET['id'])) {
    $sql = "SELECT * FROM utilisateur WHERE id=?";

    $utilisateur = new utilisateur($_GET['id'],$pdo);

    $pseudo = ucfirst($utilisateur->getPrenom()) . " " . ucfirst(substr($utilisateur->getNom(), 0, 1)) . ".";

    if (!$utilisateur->getExiste())
    {
        $notice = new notification("Utilisateur non trouvé !","notice_danger");
        exit();
    }


}
else
{
    exit();
}


?>


<div class="user_page">
<div class="user_page_left">
    <h1 class="user_page_nom">
        <?php
        echo $pseudo;
        ?>
    </h1>

    <div class="user_page_avatar_container">
    <img class="user_page_avatar" src="images/avatars/<?php echo $utilisateur->getAvatar(); ?>">
</div></div>

    <div class="user_page_right">

        Inscription : <?php
        echo $utilisateur->getDate();
        ?>
<br>
        Biens et services mis à disposition : <?php
        echo $utilisateur->getPropositions();
        ?>

    </div>
</div>


