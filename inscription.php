<?php
/**
 * Created by PhpStorm.
 * User: Remi MATTEI
 * Numéro étudiant: 21516143
 * Date: 26/11/2018
 * Time: 03:01
 */

session_start();
require_once("controllers/config.php");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion / Inscription</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/plages_horaires.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css'>
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/themes/base/jquery-ui.css" rel="stylesheet"
          type="text/css"/>
    <script src="../js/jquery-3.3.1.js"></script>


</head>
<body>

<?php

//include("class/utilisateur.php");
include("header.php");

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

?>

<div class="login-page-page">
<div class="login-page">
    <div class="login-page-form">

        <form class="login-page-login-form" method="post" action="controllers/connect.php">
            <input type="text"  name="username" placeholder="adresse email" minlength=3 />
            <input type="password"  name="password" placeholder="mot de passe" minlength=3 />
            <button>S'inscrire / S'identifier</button>
            <p class="message">Inscrivez-vous ou identifiez-vous via ce formulaire.</p>
        </form>
    </div>
</div>
</div>
<script type="text/javascript">
    $('.message a').click(function(){
        $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
    });

    document.forms[0].addEventListener('submit', function (evt) {

        if ($('[name="username"]').val().length < 3 ||$('[name="password"]').val().length < 3)
            // Si l'utilisateur n'a pas sélectionné une adresse correcte, erreur
        {
            evt.preventDefault();
            document.getElementById('warning').innerHTML = "<br>Erreur! Valeurs inférieures à 3";


        } else {

        }


    }, false);


</script>
</body></html>