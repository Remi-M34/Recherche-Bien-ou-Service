<!DOCTYPE html>
<html>
<head>
    <title>Proposer</title>
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


<?php
/**
 * Created by PhpStorm.
 * User: Remi
 * Date: 29/10/2018
 * Time: 22:40
 */


session_start();
include("header.php");
include("controllers/config.php");



$biens = $pdo->prepare("
SELECT *, DATE_FORMAT(date_debut,'%d/%m/%Y') AS date
FROM biens 
WHERE id_utilisateur = ?");
$biens->execute([$_SESSION['id']]);

$services = $pdo->prepare("
SELECT *, DATE_FORMAT(date_debut,'%d/%m/%Y') AS date
FROM services 
WHERE id_utilisateur = ?");
$services->execute([$_SESSION['id']]);

$categorie = $pdo->query("SELECT id, nom FROM categorie WHERE path LIKE 'biens%'")->fetchAll(PDO::FETCH_KEY_PAIR);
$categorie_services = $pdo->query("SELECT id, nom FROM categorie WHERE path LIKE 'services%'")->fetchAll(PDO::FETCH_KEY_PAIR);


?>

<script type="text/javascript">
    $(document).ready(function () {

        $('.mp_titre').on('click', changerTitre);
        $('.mp_des').on('click', changerDescription);
        $('.mp_cat').on('click', changerCategorieBiens);
        $('.mp_mots').on('click', changerMots);
        $('.mp_prix').on('click', changerPrix);
        $('.mp_supp').on('click', supp);
        $('.mp_type').on('click', changerType);

        // Si l'utilisateur appuie sur Entrée, on valide la nouvelle valeur
        $('.mp_titre_input').keydown(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                var id = $(this).attr('id').split("_")[3];
                var val = $(this).val();
            var type = $(this).attr('id').split("_")[0];
                maj("titre", val, id, type);
            }
        });


        $('.mp_des_input').keydown(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                var id = $(this).attr('id').split("_")[3];
                var val = $(this).val();
                var type = $(this).attr('id').split("_")[0];

                maj("description", val, id, type);
            }
        });


        $('.mp_cat_select').change(function () {
            var id = $(this).attr('id').split("_")[5];
            var type = $(this).attr('id').split("_")[0];

            var valid = "#" + type + "_mes_propositions_select_cat_" + id + " option:selected";
            var val = $(valid).html();
            var val2 = $(this).val();
            var type = $(this).attr('id').split("_")[0];

            maj("categorie", val2, id,type);
            document.getElementById(type + "_cat_span_" + id).innerHTML = val;

        });


        $('.mp_mots_input').keydown(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                var id = $(this).attr('id').split("_")[3];
                var val = $(this).val();

                var type = $(this).attr('id').split("_")[0];

                maj("mots", val, id, type);
            }
        });


        $('.mp_prix_input').keydown(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                var id = $(this).attr('id').split("_")[3];
                var val = $(this).val();
                var type = $(this).attr('id').split("_")[0];
                maj("prix_neuf", val, id,type);
            }
        });


    });

</script>


<div class="mes_propositions page">


    <div class="row">
        <div class="col-md-6 col-md-offset-3 mes_propositions_infos effect6 box2">
            <p>Ce tableau répertorie la liste de toutes vos propositions de biens et services.

                Pour en modifier un, cliquez sur un champ, éditez-le puis validez avec entrée.</p>

        </div>
    </div>

    <div class="mes_propositions_choix_container">
        <input type="radio" id="radio1" name="radios" value="biens" class="mes_propositions_choix" checked>
        <label for="radio1" class="mp_type" id="mp_choix_biens">Biens</label>
        <input type="radio" id="radio2" name="radios" value="services" class="mes_propositions_choix">
        <label for="radio2" class="mp_type" id="mp_choix_services">Services</label></div>


    <div class="mes_propositions_limiter">
        <div class="mes_propositions_container_tableau" id="test" value="tt">
            <div class="mes_propositions_wrap_tableau">
                <div class="mes_propositions_tableau">
                    <table id="mp_table_biens">
                        <thead>
                        <tr class="table100-head">
                            <th class="mp_image_th c">Image</th>
                            <th class="mp_titre_th">Titre</th>
                            <th class="mp_description_th l">Description</th>
                            <th class="mp_categorie_th">Catégorie</th>
                            <th class="mp_mots_th">Mots clés</th>
                            <th class="mp_prix_th c">Prix neuf</th>
                            <th class="mp_lieu_th c" >Lieu</th>
                            <th class="mp_date_th c">Date</th>
                            <th class="mp_supp_th">Supp</th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php

                        foreach ($biens as $row) {

//                            $cat = $pdo->query("SELECT nom FROM categorie WHERE id=" . $row['categorie'])->fetch();
                            echo "<tr id='biens_tr_" . $row['id'] . "'><td class='mes_propositions_biens_colonne1 mp_image'>";
                            $id = $row['id'];
                            $im = $row['image'];
                            if ($row['image'] == NULL) {
                                $id = "default_bien";
                                $im = "png";
                            }
                            echo "<img class='mes_propositions_image mp_image' src='uploads/biens/{$id}.{$im}'></td>";

                            echo "<td class='mes_propositions_biens_colonne2 mp_titre' id='" . $row['id'] . "'>
                            <span id='biens_titre_span_" . $row['id'] . "'>" . $row["titre"] . "</span>
                            <input class='biens_titre_input mp_titre_input' value='" . $row["titre"] . "'  onfocus='this.value = this.value;' id='biens_titre_input_" . $row['id'] . "'></td>";

//                            echo  "<td class='mes_propositions_biens_colonne3'>" . $row["description"] . "</td>";

                            echo "<td class='mes_propositions_biens_colonne3 mp_des' id='" . $row['id'] . "'>
                            <span id='biens_des_span_" . $row['id'] . "'>" . $row["description"] . "</span>
                            <textarea class='biens_des_input mp_des_input'  rows='5' value='" . $row["description"] . "'  id='biens_des_input_" . $row['id'] . "'>" . $row['description'] . "</textarea></td>";


                            echo "<td class='mes_propositions_biens_colonne4 mp_cat' id='" . $row['id'] . "'>
                            <span id='biens_cat_span_" . $row['id'] . "'>" . $categorie[$row['categorie']] . "</span>
                <select id='biens_mes_propositions_select_cat_" . $row['id'] . "' name='categorie' class='biens_cat_select mp_cat_select'>";


//<!--                    $sql = "SELECT nom, path, id FROM categorie where path like 'biens%'";-->
                            foreach ($categorie as $key => $value) {
                                if ($key == $row['categorie']) {
                                    echo "<option value='{$key}' selected>" . $value . "</option>";
                                } else {
                                    echo "<option value='{$key}'>" . $value . "</option>";
                                }
                            }
                            echo "</select>";


                            echo "<td class='mes_propositions_biens_colonne5 mp_mots' id='" . $row['id'] . "'>
                            <span id='biens_mots_span_" . $row['id'] . "'>" . $row["mots"] . "</span>
                            <input class='biens_mots_input mp_mots_input' value='" . $row["mots"] . "'  onfocus='this.value = this.value;' id='biens_mots_input_" . $row['id'] . "'></td>";


                            echo "<td class='mes_propositions_biens_colonne6 mp_prix' id='" . $row['id'] . "'>
                            <span id='biens_prix_span_" . $row['id'] . "'>" . $row["prix_neuf"] . " €</span>
                            <input min='0' type='number' class='biens_prix_input mp_prix_input' value='" . $row["prix_neuf"] . "'  onfocus='this.value = this.value;' id='biens_prix_input_" . $row['id'] . "'></td>";


                            echo "<td class='mes_propositions_biens_colonne7' id='adresse_" . $row['id'] . "'>                            <img class='mes_propositions_map' src='https://maps.googleapis.com/maps/api/staticmap
?size=220x75&scale=2&center=" . $row['lat'] . "," . $row['lng'] . "&zoom=11
&path=fillcolor:0xAA000033%7Ccolor:0xFFFFFF00%7Cenc:}zswFtikbMjJzZ%7CRdPfZ}DxWvBjWpF~IvJnEvBrMvIvUpGtQpFhOQdKpz@bIx{A%7CPfYlvApz@bl@tcAdTpGpVwQtX}i@%7CGen@lCeAda@bjA%60q@v}@rfAbjA%7CEwBpbAd_@he@hDbu@uIzWcWtZoTdImTdIwu@tDaOXw_@fc@st@~VgQ%7C[uPzNtA%60LlEvHiYyLs^nPhCpG}SzCNwHpz@cEvXg@bWdG%60]lL~MdTmEnCwJ[iJhOae@nCm[%60Aq]qE_pAaNiyBuDurAuB}}Ay%60@%7CEKv_@?%7C[qGji@lAhYyH%60@Xiw@tBerAs@q]jHohAYkSmW?aNoaAbR}LnPqNtMtIbRyRuDef@eT_z@mW_Nm%7CB~j@zC~hAyUyJ_U{Z??cPvg@}s@sHsc@_z@cj@kp@YePoNyYyb@_iAyb@gBw^bOokArcA}GwJuzBre@i\tf@sZnd@oElb@hStW{]vv@??kz@~vAcj@zKa%60Atf@uQj_Aee@pU_UrcA
&key=AIzaSyBwQxOi8hCzeOA9TfWU9wOq7DEG26kyuHk&markers=color:blue' title='" . $row['adresse'] . "'></td>";


                            echo "<td class='mes_propositions_biens_colonne8 mp_date'>" . $row['date'] . "</td>";

                            echo "<td class='mes_propositions_biens_colonne9 mp_supp' id='" . $row['id'] . "'>
                            <a class='mes_propositions_supp' role='button' id='" . $row['id'] . "'>
                            <div class='mes_propositions_supp_helper'>
                            <img  src='images/trash.svg' class='mes_propositions_btn_supp'>
	                         </div> </a></td>";
                        }

                        ?>

                        </tbody></table>



                    <table id="mp_table_services">
                        <thead>
                        <tr class="table100-head">
                            <th class="mp_image_th c">Image</th>
                            <th class="mp_titre_th">Titre</th>
                            <th class="mp_description_th l">Description</th>
                            <th class="mp_categorie_th">Catégorie</th>
                            <th class="mp_mots_th">Mots clés</th>
                            <th class="mp_ph_th c">Plages Horaires</th>
                            <th class="mp_lieu_th c" >Lieu</th>
                            <th class="mp_date_th c">Date</th>
                            <th class="mp_supp_th">Supp</th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php



                        foreach ($services as $row) {

//                            $cat = $pdo->query("SELECT nom FROM categorie WHERE id=" . $row['categorie'])->fetch();
                            echo "<tr id='services_tr_" . $row['id'] . "'><td class='mes_propositions_services_colonne1 mp_image'>";
                            $id = $row['id'];
                            $im = $row['image'];
                            if ($row['image'] == NULL) {
                                $id = "default-service2";
                                $im = "png";
                            }
                            echo "<img class='mes_propositions_image' src='uploads/services/{$id}.{$im}'></td>";

                            echo "<td class='mes_propositions_services_colonne2 mp_titre' id='" . $row['id'] . "'>
                            <span id='services_titre_span_" . $row['id'] . "'>" . $row["titre"] . "</span>
                            <input class='services_titre_input mp_titre_input' value='" . $row["titre"] . "'  onfocus='this.value = this.value;' id='services_titre_input_" . $row['id'] . "'>
                            </td>";

//                            echo  "<td class='mes_propositions_biens_colonne3'>" . $row["description"] . "</td>";

                            echo "<td class='mes_propositions_services_colonne3 mp_des' id='" . $row['id'] . "'>
                            <span id='services_des_span_" . $row['id'] . "'>" . $row["description"] . "</span>
                            <textarea class='services_des_input mp_des_input'  rows='5' value='" . $row["description"] . "'  id='services_des_input_" . $row['id'] . "'>" . $row['description'] . "</textarea></td>";


                            echo "<td class='mes_propositions_services_colonne4 mp_cat' id='" . $row['id'] . "'>
                            <span id='services_cat_span_" . $row['id'] . "'>" . $categorie_services[$row['categorie']] . "</span>
                <select id='services_mes_propositions_select_cat_" . $row['id'] . "' name='categorie' class='services_cat_select mp_cat_select'>";






//<!--                    $sql = "SELECT nom, path, id FROM categorie where path like 'biens%'";-->
                            foreach ($categorie_services as $key => $value) {
                                if ($key == $row['categorie']) {
                                    echo "<option value='{$key}' selected>" . $value . "</option>";
                                } else {
                                    echo "<option value='{$key}'>" . $value . "</option>";
                                }
                            }
                            echo "</select>";


                            echo "<td class='mes_propositions_services_colonne5 mp_mots' id='" . $row['id'] . "'>
                            <span id='services_mots_span_" . $row['id'] . "'>" . $row["mots"] . "</span>
                            <input class='services_mots_input mp_mots_input' value='" . $row["mots"] . "'  onfocus='this.value = this.value;' id='services_mots_input_" . $row['id'] . "'></td>";


                            echo "<td class='mes_propositions_services_colonne6 mp_ph' id='" . $row['id'] . "'>
                            <span id='services_prix_span_" . $row['id'] . "'>A venir</span>

                            </td>";

//                            <input type='number' class='services_prix_input' value='" . $row["lun_d"] . "'  onfocus='this.value = this.value;' id='services_prix_input_" . $row['id'] . "'>



                            echo "<td class='mes_propositions_biens_colonne7 mp_map' id='adresse_" . $row['id'] . "'>                            <img class='mes_propositions_map' src='https://maps.googleapis.com/maps/api/staticmap
?size=220x75&scale=2&center=" . $row['lat'] . "," . $row['lng'] . "&zoom=11
&path=fillcolor:0xAA000033%7Ccolor:0xFFFFFF00%7Cenc:}zswFtikbMjJzZ%7CRdPfZ}DxWvBjWpF~IvJnEvBrMvIvUpGtQpFhOQdKpz@bIx{A%7CPfYlvApz@bl@tcAdTpGpVwQtX}i@%7CGen@lCeAda@bjA%60q@v}@rfAbjA%7CEwBpbAd_@he@hDbu@uIzWcWtZoTdImTdIwu@tDaOXw_@fc@st@~VgQ%7C[uPzNtA%60LlEvHiYyLs^nPhCpG}SzCNwHpz@cEvXg@bWdG%60]lL~MdTmEnCwJ[iJhOae@nCm[%60Aq]qE_pAaNiyBuDurAuB}}Ay%60@%7CEKv_@?%7C[qGji@lAhYyH%60@Xiw@tBerAs@q]jHohAYkSmW?aNoaAbR}LnPqNtMtIbRyRuDef@eT_z@mW_Nm%7CB~j@zC~hAyUyJ_U{Z??cPvg@}s@sHsc@_z@cj@kp@YePoNyYyb@_iAyb@gBw^bOokArcA}GwJuzBre@i\tf@sZnd@oElb@hStW{]vv@??kz@~vAcj@zKa%60Atf@uQj_Aee@pU_UrcA
&key=AIzaSyBwQxOi8hCzeOA9TfWU9wOq7DEG26kyuHk&markers=color:blue' title='" . $row['adresse'] . "'></td>";


                            echo "<td class='mes_propositions_services_colonne8 mp_date'>" . $row['date'] . "</td>";

                            echo "<td class='mes_propositions_services_colonne9 mp_supp' id='" . $row['id'] . "'>
                            <a class='mes_propositions_supp' role='button' id='" . $row['id'] . "'>
                            <div class='mes_propositions_supp_helper'>
                            <img  src='images/trash.svg' class='mes_propositions_btn_supp'>
	                         </div> </a></td>";
                        }


                        ?>

                        </tbody></table>



                </div>
            </div>
        </div>
    </div>




    <footer>
        Site créé par Rémi Mattei
    </footer>

</div>


<script type="text/javascript">
    estVide("biens");
    estVide("services");

    // document.getElementsByClassName("column2").addEventListener("click", GetAddress);


    function changerTitre() {
        var id = $(this).attr('id');
        var type = $(this).attr('class').split("_")[2];
        var inputtitre = type +"_titre_input_" + id;
        var spantitre = type +"_titre_span_" + id;
        document.getElementById(inputtitre).style.display = "inline-block";
        document.getElementById(spantitre).style.display = "none";
        document.getElementById(inputtitre).focus();
        document.getElementById(inputtitre).select();
    }


    function changerDescription() {
        var id = $(this).attr('id');
        var type = $(this).attr('class').split("_")[2];

        var inputdes = type +"_des_input_" + id;
        var spandes = type +"_des_span_" + id;
        document.getElementById(inputdes).style.display = "inline-block";
        document.getElementById(spandes).style.display = "none";
        document.getElementById(inputdes).focus();
        // document.getElementById(inputdes).value = document.getElementById(inputdes).val();
        document.getElementById(inputdes).select();
    }


    function changerCategorieBiens() {
        var id = $(this).attr('id');
        var type = $(this).attr('class').split("_")[2];

        var inputcat = type +"_mes_propositions_select_cat_" + id;
        var spancat = type + "_cat_span_" + id;
        document.getElementById(inputcat).style.display = "inline-block";
        document.getElementById(spancat).style.display = "none";
        document.getElementById(inputcat).focus();
        // document.getElementById(inputdes).value = document.getElementById(inputdes).val();
        // document.getElementById(inputcat).select();
    }


    function changerMots() {
        var id = $(this).attr('id');
        var type = $(this).attr('class').split("_")[2];

        var inputtitre = type + "_mots_input_" + id;
        var spantitre = type + "_mots_span_" + id;
        document.getElementById(inputtitre).style.display = "inline-block";
        document.getElementById(spantitre).style.display = "none";
        document.getElementById(inputtitre).focus();
        document.getElementById(inputtitre).select();
    }


    function changerPrix() {
        var id = $(this).attr('id');
        var type = $(this).attr('class').split("_")[2];

        var inputtitre = type+"_prix_input_" + id;
        var spantitre = type+ "_prix_span_" + id;
        document.getElementById(inputtitre).style.display = "inline-block";
        document.getElementById(spantitre).style.display = "none";
        document.getElementById(inputtitre).focus();
        document.getElementById(inputtitre).select();
    }

    function changerType(){
        var id = $(this).attr('id');
        if (id == "mp_choix_biens")
        {
            document.getElementById("mp_table_biens").style.display = "block";
            document.getElementById("mp_table_services").style.display = "none";

        }
        else if (id == "mp_choix_services")
        {
            document.getElementById("mp_table_biens").style.display = "none";
            document.getElementById("mp_table_services").style.display = "block";
        }
    }


    function supp() {
        var id = $(this).attr('id');
        var type = $(this).attr('class').split("_")[2];
        var trid = "#" + type + "_tr_" + id;
        if (confirm("Supprimer ?")) {
            // Suppression de la ligne
            $(trid).fadeOut(650, function () {
                $(trid).remove();
                estVide(type);


                $.ajax({
                    type: "POST",
                    url: "controllers/mes_propositions_maj.php",
                    data: {id: id, 'type': type, update: "supp"},
                    success: function (result) {
                        notif("Supprimé!", id, "notice_success");
                    }
                });
            });
        }
    }


    function notif(message, id, type){

        var notifid = "notification_" + id;
        document.getElementById('bloc-de-notifications-non-persistentes').style.display = 'block';
        document.getElementById('bloc-de-notifications-non-persistentes').innerHTML += "" +
            "<br><div class='notice-non-persistentes " + type + "' id=" + notifid + ">" + message + "</div>";

        // Suppression de la notification
        $("#" + notifid).fadeOut(3100, function () {
            var liste = document.getElementById('bloc-de-notifications-non-persistentes');
            var elements = liste.getElementsByTagName('div');
            var br = liste.getElementsByTagName('br');
            liste.removeChild(elements[0]);
            liste.removeChild(br[0]);
        })
    }


    function maj(colonne, val, id, type) {
        $.ajax({
            type: "POST",
            url: "controllers/mes_propositions_maj.php",
            data: {'colonne': colonne, 'valeur': val, 'id': id, update: "maj", 'type': type}
        }).done(function (msg) {
            if (msg == "ok") {
                if (colonne == "titre") {
                    document.getElementById(type +"_titre_input_" + id).style.display = "none";
                    document.getElementById(type +"_titre_span_" + id).style.display = "block";
                    document.getElementById(type +"_titre_span_" + id).innerHTML = val;
                }
                else if (colonne == "description") {
                    document.getElementById(type +"_des_input_" + id).style.display = "none";
                    document.getElementById(type +"_des_span_" + id).style.display = "block";
                    document.getElementById(type +"_des_span_" + id).innerHTML = val;
                }
                else if (colonne == "categorie") {
                    document.getElementById(type +"_mes_propositions_select_cat_" + id).style.display = "none";
                    document.getElementById(type +"_cat_span_" + id).style.display = "block";
                }
                else if (colonne == "mots") {
                    document.getElementById(type +"_mots_input_" + id).style.display = "none";
                    document.getElementById(type +"_mots_span_" + id).style.display = "block";
                    document.getElementById(type +"_mots_span_" + id).innerHTML = val;
                }
                else if (colonne == "prix_neuf") {
                    document.getElementById(type+"_prix_input_" + id).style.display = "none";
                    document.getElementById(type+"_prix_span_" + id).style.display = "block";
                    document.getElementById(type+"_prix_span_" + id).innerHTML = val + " €";
                }
                notif("Mis à jour!", id, "notice_success");
            }
            else
                notif("Erreur!", id, "notice_warning");
        });
    }


    function estVide(type){

        var cmt = document.getElementById('mp_table_' + type).getElementsByTagName('tr');
        var cmt2 = cmt.length;
        if (cmt2 == 1)
        {
            document.getElementById("mp_table_" + type).textContent = "Rien à afficher";
        }

    }


    function GetAddress(lat, lng, id) {
        // alert(this.innerHTML);
        // var lat = parseFloat(document.getElementById("txtLatitude").value);
        // var lng = parseFloat(document.getElementById("txtLongitude").value);
        var latlng = new google.maps.LatLng(lat, lng);
        var geocoder = geocoder = new google.maps.Geocoder();
        geocoder.geocode({'latLng': latlng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    // echo results[1].formatted_address);
                    document.getElementById(id).innerHTML = results[0].formatted_address;
                }
            }
        });
    };

</script>


<script type="text/javascript">


    // var map;
    // function initMap() {
    //     // map = new google.maps.Map(document.getElementById('map'), {
    //     //     center: {lat: -34.397, lng: 150.644},
    //     //     zoom: 4,
    //     //     disableDefaultUI: false,
    //     //     scrollwheel: false
    //     // });
    // }
    //
    //
    //
    // var geocoder;
    //
    // function initialize() {
    //     geocoder = new google.maps.Geocoder();
    // }
    //
    // function codeLatLng(lat, lng) {
    //     var latlng = new google.maps.LatLng(lat, lng);
    //     geocoder.geocode({
    //         'latLng': latlng
    //     }, function (results, status) {
    //         if (status === google.maps.GeocoderStatus.OK) {
    //             if (results[1]) {
    //                 console.log(results[1]);
    //             } else {
    //                 alert('No results found');
    //             }
    //         } else {
    //             alert('Geocoder failed due to: ' + status);
    //         }
    //     });
    // }
    //
    // google.maps.event.addDomListener(window, 'load', initialize);
    //


    // function initMap() {
    //     var map = new google.maps.Map(document.getElementById('map'), {
    //         zoom: 8,
    //         center: {lat: 40.731, lng: -73.997}
    //     });
    //     var geocoder = new google.maps.Geocoder;
    //     var infowindow = new google.maps.InfoWindow;
    //
    //     document.getElementById('column1').addEventListener('click', function() {
    //         geocodeLatLng(geocoder, map, infowindow);
    //     });
    // }
    //
    // function geocodeLatLng(geocoder, map, infowindow) {
    //     var input = document.getElementById('latlng').value;
    //     var latlngStr = input.split(',', 2);
    //     var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
    //     geocoder.geocode({'location': latlng}, function(results, status) {
    //         if (status === 'OK') {
    //             if (results[0]) {
    //                 // map.setZoom(11);
    //                 // var marker = new google.maps.Marker({
    //                 //     position: latlng,
    //                 //     map: map
    //                 // });
    //                 // infowindow.setContent(results[0].formatted_address);
    //                 // infowindow.open(map, marker);
    //                 alert(results[0].formatted_adress);
    //             } else {
    //                 window.alert('No results found');
    //             }
    //         } else {
    //             window.alert('Geocoder failed due to: ' + status);
    //         }
    //     });
    // }


    // document.getElementsByClassName("column1").onclick =

</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBwQxOi8hCzeOA9TfWU9wOq7DEG26kyuHk&libraries=places"
        async defer></script>
