<!DOCTYPE html>
<html>
<head>
    <title>Mes emprunts</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/themes/base/jquery-ui.css" rel="stylesheet"
          type="text/css"/>
    <script src="js/jquery-3.3.1.js"></script>

</head>
<body>

<?php
/**
 * Created by PhpStorm.
 * User: Remi MATTEI
 * Numéro étudiant: 21516143
 * Date: 16/12/2018
 * Time: 02:25
 */

session_start();
include("header.php");
include("controllers/config.php");



?>


<div class="mes_emprunts page">

    <table class="table table-hover table-fixed">

        <thead>
        <tr>
            <th>#</th>
            <th>Titre</th>
            <th>Proposé par</th>
            <th>Adresse</th>
            <th>Coût</th>
            <th>Coût total</th>
            <th>Date début</th>
            <th>Date fin</th>
        </tr>
        </thead>

        <tbody>
        <?php

        $emprunts_biens = $pdo->prepare("SELECT *, biens.adresse as adresse, emprunt.date_fin as date_fin FROM emprunt, biens, utilisateur WHERE id_bien_service = biens.id AND utilisateur.id = id_emprunte AND id_emprunteur = ? ORDER BY date DESC ");
        $emprunts_biens->execute([$thisuser->getId()]);
        $emprunts_services = $pdo->prepare("SELECT *, services.adresse as adresse, emprunt.date_fin as date_fin FROM emprunt, services, utilisateur WHERE id_bien_service = services.id AND utilisateur.id = id_emprunte AND id_emprunteur = ? ORDER BY date DESC ");
        $emprunts_services->execute([$thisuser->getId()]);

//        $biens = $pdo->query("SELECT id,titre, adresse FROM biens where emprunte>0")->fetchAll(PDO::FETCH_UNIQUE);
//        $services = $pdo->query("SELECT id,titre, adresse FROM services where emprunte>0")->fetchAll(PDO::FETCH_UNIQUE);
//
//        $html = "";
        $i = 1;
        $type;

        foreach ($emprunts_biens as $ligne)
        {
            echo "
        <tr>
            <td>" . $i++ . "</td>
            <td>" . $ligne['titre']. "</td>
                       <td>" . $ligne['prenom']. ' ' . $ligne['nom']. "</td>

           
           <td  id='adresse_" . $ligne['id'] . "'>                            <img class='mes_emprunts_map' src='https://maps.googleapis.com/maps/api/staticmap
?size=320x40&scale=2&center=" . $ligne['lat'] . "," . $ligne['lng'] . "&zoom=11
&path=fillcolor:0xAA000033%7Ccolor:0xFFFFFF00%7Cenc:}zswFtikbMjJzZ%7CRdPfZ}DxWvBjWpF~IvJnEvBrMvIvUpGtQpFhOQdKpz@bIx{A%7CPfYlvApz@bl@tcAdTpGpVwQtX}i@%7CGen@lCeAda@bjA%60q@v}@rfAbjA%7CEwBpbAd_@he@hDbu@uIzWcWtZoTdImTdIwu@tDaOXw_@fc@st@~VgQ%7C[uPzNtA%60LlEvHiYyLs^nPhCpG}SzCNwHpz@cEvXg@bWdG%60]lL~MdTmEnCwJ[iJhOae@nCm[%60Aq]qE_pAaNiyBuDurAuB}}Ay%60@%7CEKv_@?%7C[qGji@lAhYyH%60@Xiw@tBerAs@q]jHohAYkSmW?aNoaAbR}LnPqNtMtIbRyRuDef@eT_z@mW_Nm%7CB~j@zC~hAyUyJ_U{Z??cPvg@}s@sHsc@_z@cj@kp@YePoNyYyb@_iAyb@gBw^bOokArcA}GwJuzBre@i\tf@sZnd@oElb@hStW{]vv@??kz@~vAcj@zKa%60Atf@uQj_Aee@pU_UrcA
&key=AIzaSyBwQxOi8hCzeOA9TfWU9wOq7DEG26kyuHk&markers=color:blue' title='" . $ligne['adresse'] . "'></td>
           
           
           
            <td>" . $ligne['cout'] . " €</td>
            <td>" . $ligne['cout_total'] . " €</td>
            <td>" . $ligne['date_debut'] . "</td>
            <td>" . $ligne['date_fin'] . "</td>
        </tr>";
        }

        foreach ($emprunts_services as $ligne)
        {
            echo "
        <tr>
            <td>" . $i++ . "</td>
            <td>" . $ligne['titre']. "</td>
                       <td>" . $ligne['prenom']. ' ' . $ligne['nom']. "</td>

           
           <td  id='adresse_" . $ligne['id'] . "'>                            <img class='mes_emprunts_map' src='https://maps.googleapis.com/maps/api/staticmap
?size=320x40&scale=2&center=" . $ligne['lat'] . "," . $ligne['lng'] . "&zoom=11
&path=fillcolor:0xAA000033%7Ccolor:0xFFFFFF00%7Cenc:}zswFtikbMjJzZ%7CRdPfZ}DxWvBjWpF~IvJnEvBrMvIvUpGtQpFhOQdKpz@bIx{A%7CPfYlvApz@bl@tcAdTpGpVwQtX}i@%7CGen@lCeAda@bjA%60q@v}@rfAbjA%7CEwBpbAd_@he@hDbu@uIzWcWtZoTdImTdIwu@tDaOXw_@fc@st@~VgQ%7C[uPzNtA%60LlEvHiYyLs^nPhCpG}SzCNwHpz@cEvXg@bWdG%60]lL~MdTmEnCwJ[iJhOae@nCm[%60Aq]qE_pAaNiyBuDurAuB}}Ay%60@%7CEKv_@?%7C[qGji@lAhYyH%60@Xiw@tBerAs@q]jHohAYkSmW?aNoaAbR}LnPqNtMtIbRyRuDef@eT_z@mW_Nm%7CB~j@zC~hAyUyJ_U{Z??cPvg@}s@sHsc@_z@cj@kp@YePoNyYyb@_iAyb@gBw^bOokArcA}GwJuzBre@i\tf@sZnd@oElb@hStW{]vv@??kz@~vAcj@zKa%60Atf@uQj_Aee@pU_UrcA
&key=AIzaSyBwQxOi8hCzeOA9TfWU9wOq7DEG26kyuHk&markers=color:blue' title='" . $ligne['adresse'] . "'></td>
           
           
           
            <td>" . $ligne['cout'] . " €</td>
            <td>" . $ligne['cout_total'] . " €</td>

            <td>" . $ligne['date_debut'] . "</td>
            <td>" . $ligne['date_fin'] . "</td>
        </tr>";
        }


        ?>

        </tbody>

    </table>

    <footer>
        Site créé par Rémi Mattei
    </footer>
</div>




