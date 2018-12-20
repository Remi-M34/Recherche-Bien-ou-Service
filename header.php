<?php
/**
 * Created by PhpStorm.
 * User: Remi MATTEI
 * Numéro étudiant: 21516143
 * Date: 20/10/2018
 * Time: 18:54
 */
?>

<header class="nav">

    <div class="nav-menu-container">
        <a href="index.php" class="logo_lien">
            <div id="nav-menu">

                <img src="images/logo.png" class="logo">
                <div class="titre">Recherche bien<br>ou service .fr</div>
            </div>
        </a>
        <div id="nav-menu-2">
            <a href="inscription.php" class="nav-link" id="header_lien_connexion">Connexion / Inscription</a>
            <a href="rechercher.php" class="nav-link">Rechercher</a>
            <?php
            if (isset($_SESSION['user'])) {
                echo "            <a href=\"profile.php\" class=\"nav-link inscription_necessaire\">Profil</a>
            <a href=\"proposer.php\" class=\"nav-link inscription_necessaire\">Proposer</a>
            <br>
            <a href=\"mes_propositions.php\" class=\"nav-link inscription_necessaire\">Mes propositions</a>
            <a href=\"mesemprunts.php\" class=\"nav-link inscription_necessaire\">Mes emprunts</a>";
            }
            ?>

        </div>

    </div>

    <div id="identification">


        <?php


        include("class/enum.php");
        include("class/type.php");
        include("class/notification.php");
        include("class/utilisateur.php");

        if (!isset($_SESSION['email'])) // si uid n'est pas dans la session
        {

            ?>

            <?php
        } else { // Membre connecté ou inscrit
            echo "    <div id=\"infosUid\">";
            echo "<script>document.getElementById('header_lien_connexion').style.display = 'none';</script>";

            if (isset($_SESSION['user'])) {
                $thisuser = unserialize($_SESSION['user']);
                echo "<div id='" . $thisuser->getId() . "' class='infosuidgauche'>";
                echo "Bienvenue " . $thisuser->getPrenom() . " " . $thisuser->getNom();
                echo "<br>";
                echo "Propositions : <a href='mes_propositions.php'>" . $thisuser->getPropositions() . "</a><br>";
                echo "Emprunts : <a href='mesemprunts.php'>" . $thisuser->getEmprunts() . "</a></div>";

                echo "<div class='infosuiddroite'>";
                echo $thisuser->getEmail();
                echo "<br>";
                echo "Solde : " . round($thisuser->getSolde(), 2) . " €<br>";
                echo "<br></div>";
            }
            echo "</div>";
            ?>
            <div id="deconnexion">
                <a href="controllers/disconnect.php">
                    <button type="submit" id="btnDeconnexion">Déconnexion</button>
                </a>
            </div>

            <?php

        }
        ?>

    </div>

</header>

<div id="bloc-de-notifications-non-persistentes"></div>
<div id="bloc-de-notifications"></div>
<div class="container-notif" id="container-notif">

</div>
<div class='warning' id='warning'></div>
<?php


echo "<div class='notification'>";


if (isset($_SESSION['email'])) {

    if (isset($_SESSION['connect'])) { // a été connecté
        $connect = new notification("Connexion réussie!", "notice_success");
        unset($_SESSION['connect']);

    } else if (isset($_SESSION['inscription'])) { // a été inscrit
        $connect = new notification("Inscription réussie!", "notice_success");
        unset($_SESSION['inscription']);
    }

    if (isset($_SESSION['profil-update'])) {
        unset($_SESSION['profil-update']);
    }


} else if (!isset($_SESSION['email'])) {
    if (isset($_SESSION['disconnect'])) // a été déconnecté
    {
        $connect = new notification("Déconnexion réussie", "notice_info");
        unset($_SESSION['disconnect']);
    } else if (isset($_SESSION['attempt'])) // a essayé de s'identifier avec un mauvais password
    {
        $connect = new notification("Mauvais mot de passe pour cet utilisateur", "notice_danger");
        unset($_SESSION['attempt']);
    }


}

echo "</div></div>";
unset($_SESSION['warning']);
unset($_SESSION['notification']);


?>

