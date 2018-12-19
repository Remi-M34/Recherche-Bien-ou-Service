<?php

//include("class/utilisateur.php");
require_once("controllers/config.php");
session_start();


/**
 * Created by PhpStorm.
 * User: Remi
 * Date: 15/11/2018
 * Time: 15:58
 */

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
    }
    else {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}

$sql = "";
$sql_cond = array();
$sql_array = [];
$type = "biens ";
$distance = 0;
$rayon = 0;
if (isset($_GET['type'])) {
    $type = $_GET['type'];
}


if (isset($_GET['lat']) && $_GET['lat'] != "") {
    $distance = "st_distance_sphere(Point(" . $_GET['lat'] . "," . $_GET['lng'] . "),Point(" . "lat,lng))";

    if (isset($_GET['rayon']) && isset($_GET['lat']) && $_GET['rayon'] != "partout") {
        // On multiplie le rayon par 1000 car st_distance_sphere retourne un résultat en mètre et non km
        $rayon = $_GET['rayon'] * 1000;
    }
}
$sql = "SELECT *, ".$distance." AS distance FROM {$type} WHERE 1  ";
if (isset($_GET['keywords']) && $_GET['keywords']!= "")
{
    $sql .= " AND MATCH (titre, description, mots) AGAINST ('" . $_GET['keywords'] . "')";
}

if ($rayon != 0)
{
    $sql .= " AND st_distance_sphere(Point(".$_GET['lat'].",".$_GET['lng']."),Point(lat,lng)) <=".$rayon . " ";
}
if (isset($_GET['categorie'])) {
    $sql .= " AND categorie = " . $_GET['categorie'];
}
//foreach ($sql_cond as $key => $value) {
//    $sql .= " AND " . $key . " = :" . $key;
//    $sql_array[":" . $key] = $value;
//}

$GLOBALS['items'] = $pdo->prepare($sql);
$GLOBALS['items']->execute($sql_array);
$_SESSION['requete'] = serialize($sql);
$_SESSION['sql_array'] = serialize($sql_array);

//echo $sql;
//echo print_r($sql_array);

//$thisuser = unserialize($_SESSION['user']);

$cat_all = $pdo->query("SELECT id, nom, path, id FROM categorie ORDER BY nom")->fetchAll(PDO::FETCH_UNIQUE);
?>


<!DOCTYPE html>
<html>
<head>

    <title>Rechercher</title>
    <link rel="stylesheet" type="text/css" href="css/booking.css" />
    <link rel="stylesheet" type="text/css" href="css/popbox.css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/pretty-checkbox.css">
    <link rel="stylesheet" type="text/css" href="css/daterangepicker.css" />

    <link rel="icon" href="favicon.ico" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js"></script>-->

    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/markerclusterer.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/moment.min.js"></script>
    <script type="text/javascript" src="js/daterangepicker.js"></script>
    <script type="text/javascript" src="js/popbox.js"></script>


    <script>
        if (GET("adresse") || GET("categorie"))
        window.location.href = "#trouver_map";

        var popbox =   new Popbox({
            blur:true,
        });


        function supp(event) {
            event.stopPropagation();

            var id = $(this).attr('id').split("_")[3];
            var type = $(this).attr('id').split("_")[2];
            var myid = $(this).attr('id').split("_")[4];

            var trid = "#trouver_" + type + "_" + id + "_" + myid;
            alert(trid);
            if (confirm("Supprimer ?")) {
                // Suppression de la ligne
                $(trid).fadeOut(650, function () {
                    $(trid).remove();
                    $.ajax({
                        type: "POST",
                        url: "controllers/mes_propositions_maj.php",
                        data: {id: id, 'type': type, update: "supp"},
                        success: function (result) {
                            notif("Supprimé!", "notice_success");
                        }
                    });
                });
            }
        }


        function notif(message, type){
            var CurrentDate = moment();


            var notifid = "notification_" + CurrentDate;
            document.getElementById('bloc-de-notifications-non-persistentes').style.display = 'block';
            document.getElementById('bloc-de-notifications-non-persistentes').innerHTML += "" +
                "<br><div class='notice-non-persistentes " + type + "' id=" + notifid + ">" + message + "</div>";

            // Suppression de la notification
            $("#" + notifid).fadeOut(4100, function () {
                var liste = document.getElementById('bloc-de-notifications-non-persistentes');
                var elements = liste.getElementsByTagName('div');
                var br = liste.getElementsByTagName('br');
                liste.removeChild(elements[0]);
                liste.removeChild(br[0]);
            })
        }

        function distance(lat1, lon1, lat2, lon2, unit = "K") {
            var radlat1 = Math.PI * lat1 / 180;
            var radlat2 = Math.PI * lat2 / 180;
            var radlon1 = Math.PI * lon1 / 180;
            var radlon2 = Math.PI * lon2 / 180;
            var theta = lon1 - lon2;
            var radtheta = Math.PI * theta / 180;
            var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
            dist = Math.acos(dist);
            dist = dist * 180 / Math.PI;
            dist = dist * 60 * 1.1515;
            if (unit == "K") {
                dist = dist * 1.609344
            }
            if (unit == "N") {
                dist = dist * 0.8684
            }
            return dist
        }

        function GET(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }

        var parts = window.location.search.substr(1).split("&");
        var $_GET = {};
        for (var i = 0; i < parts.length; i++) {
            var temp = parts[i].split("=");
            $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]).replace('+',' ');
        }


        /**
         * On affiche le menu déroulant personnalisé correspondant au champ cliqué par l'utilisateur, après avoir caché tous les autres
         */
        function afficherMenu() {
            $('div.recherche_avancee_plus').css("display", "none");
            $(this).children('div.recherche_avancee_plus').css("display", "inline-block");
        }


        function selectCat() {

            $("#recherche_avancee_block_choix_cat").text(this.textContent);
            var id = $(this).attr('id').split("_")[3];
            $("#recherche_avancee_select_cat").val(id);
        }

        function selectType()
        {
            $("#recherche_avancee_block_choix_cat").text("CATEGORIE");
            $("#recherche_avancee_select_cat").val("CATEGORIE");

        }


        function selectRayon() {
            $("#recherche_avancee_block_choix_rayon").text(this.textContent);
            $("#recherche_avancee_select_rayon").val(this.id.split("_")[1]);

        }


        function cacherMenu() {
            if (!$(event.target).closest('.recherche_avancee_block_choix').length) {
                setTimeout(function () {
                    $('div.recherche_avancee_plus').css("display", "none");
                }, 0);
            }
        }

        function initCat(){
            if (GET("type") && GET('type') === "services")
            {
                $(".recherche_avancee_services").css("display","inline-block");
                $(".recherche_avancee_biens").css("display","none");
            }
        }

        function reservation(){

            if ($("#reserver_cout_total").val() == "")
            {
                alert("Veuillez choisir une date!");
                return;
            }

            var type = $("#reservation_id").html().split(" ")[1].toLowerCase() + "s";
            var id = $("#reservation_id").html().split(" ")[3];
            var id_emprunte = $("#reservation_id_emprunte").val();
            var date_debut = $("#reserver_calendrier").val().split(" - ")[0];
            var date_fin = $("#reserver_calendrier").val().split(" - ")[1];
            var heure_debut = $("#heureDebut").val().substr(0,$("#heureDebut").val().length -3);
            var heure_fin = $("#heureFin").val().substr(0,$("#heureFin").val().length -3);
            var cout = $("#reserver_cout").val().split(" ")[0];
            var cout_total = $("#reserver_cout_total").val().split(" ")[0];

            // var blocObjet = "#trouver_"+type+"_"+id+"_"+id_emprunte;

            $.ajax({
                type: "POST",
                url: "controllers/reservation.php",
                data:
                    {
                        'type': type,
                        'id': id,
                        'id_emprunte': id_emprunte,
                        'date_debut': date_debut,
                        'date_fin': date_fin,
                        'heure_debut': heure_debut,
                        'heure_fin': heure_fin,
                        'cout': cout,
                        'cout_total': cout_total
                    }
            }).done(function (msg) {

                if (msg == "ok")
                {
                    // $(blocObjet).removeClass("trouver_selectionne");
                    // popbox.close('mypopbox1');
                    $("#reserver_cout_total").val("");
                    popbox.clear();
                    notif("Emprunt effectué avec succès !<br>", "notice_success");
                    $('*').click(false);

                    setTimeout(function(){

                        window.location.href = "https://recherchebienouservice.fr/mesemprunts.php";


                    },2000)

                }
                else
                {
                    notif("Erreur(s) : <br>" + msg, "notice_danger");
                }


            });


        }


        function reserver(){
            if (!($("#infosUid")[0])) {
                notif("Inscription nécessaire"  ,"notice_danger");
                return;
            }
            var id_emprunte = $(this).attr('id').split("_")[3];
            var id_emprunteur = $(this).attr('id').split("_")[4];


            if (id_emprunte == id_emprunteur) {
                notif("Impossible d'emprunter à vous-même!"  ,"notice_danger");
                return;
            }

            var popbox =   new Popbox({
                blur:true,
            });

            var id = $(this).attr('id').split("_")[2];

            var type = $(this).attr('id').split("_")[1];
            var typeAffichage = type.charAt(0).toUpperCase() + type.slice(1);
            var blocObjet = this;

            $(blocObjet).addClass("trouver_selectionne");

            $("#reservation_id").html("<h1> "+typeAffichage.substr(0,typeAffichage.length -1) + " n° " + id+" </h1>");
            $("#reservation_id_emprunte").val(id_emprunte);
            if (type == "biens")
            $("#heuresServices").css("display","none");
            else
                $("#heuresServices").css("display","inline-block");

            $.ajax({
                type: "POST",
                url: "controllers/reserver.php",
                data:
                    {
                    'type': type,
                    'id': id
                    }
            }).done(function (data) {
                if (data.msg == "ok") {
                    popbox.open("mypopbox1");

                        var datesInvalides = [];
                        datesInvalides = data.datesInvalides;

                        document.getElementById("reserver_dates").innerHTML =  '<input type="text" readonly="readonly"  name="daterange-reserver" value="Date" class="form-control" id="reserver_calendrier" />';

                        if (data.prix_neuf == null)
                        {
                            data['prix_neuf'] = 5
                        }
                    if (type == "biens") {
                        $("#reserver_prix_neuf").val(data.prix_neuf + " €");
                        $("#reserver_cout").val(Math.round((data.prix_neuf / 150) * 100) / 100 + " €");
                    }
                    else
                    {
                        $(".reserver_prix_neuf_bloc").css("display","none");
                        $(".reserver_cout_journalalier_infos").css("display","none");
                        $(".reserver_cout_journalier_label").html("Coût horaire");
                        $("#reserver_cout").val("12 €");
                    }
                    $("#reserver_deja_emprunte").val(data.emprunte+" fois");


                    var optionsServices = (type == "services");

                            $('input[name="daterange-reserver"]').daterangepicker({
                                // "singleDatePicker": optionsServices,

                                "timePicker": optionsServices,
                                "timePicker24Hour": optionsServices,
                                "timePickerIncrement": 60,
                                "showWeekNumbers": true,
                                "autoApply": true,
                                datesInvalides: datesInvalides,
                                "locale": {
                                    "format": "DD/MM/YYYY",
                                    "separator": " - ",
                                    "applyLabel": "Valider",
                                    "cancelLabel": "Annuler",
                                    "fromLabel": "De",
                                    "toLabel": "à",
                                    "customRangeLabel": "Custom",
                                    "weekLabel": "S",
                                    "daysOfWeek": [
                                        "Di",
                                        "Lu",
                                        "Ma",
                                        "Me",
                                        "Je",
                                        "Ve",
                                        "Sa"
                                    ],
                                    "monthNames": [
                                        "Janvier",
                                        "Février",
                                        "Mars",
                                        "Avril",
                                        "Mai",
                                        "Juin",
                                        "Juillet",
                                        "Août",
                                        "Septembre",
                                        "Octobre",
                                        "Novembre",
                                        "Décembre"
                                    ],
                                    "firstDay": 1
                                },
                                "alwaysShowCalendars": true,
                                "showCustomRangeLabel": true,
                                "startDate": moment(),
                                "endDate": moment(),
                                "minDate": moment()
                            }, function(start, end, label) {
                                // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');

                            });


                }
                else
                {notif("Erreur!" + data.erreur, "notice_warning");}

            });
        }

        $(document).ready(function () {

            $('.recherche_avancee_field').on('click', afficherMenu);
            $('.recherche_avancee_cat').on('click', selectCat);
            $('.recherche_avancee_rayon').on('click', selectRayon);
            $('.recherche_avancee_plus').on('click', cacherMenu);
            $('.recherche_avancee_type').on('click', selectType);
            $('#reservation_submit').on('click', reservation);
            $('.box').on('click', reserver);
            $('div:not(.recherche_avancee_block_choix)').on('click', cacherMenu);
            $('.trouver_supprimer').on('click', supp);
            // $('img').hover(function(){
            //     $(this).addClass("rechercher_image_zoom");
            //     $(this).removeClass("rechercher_image_zoom");
            // });
            // $('#geolocate').on('click', geolocateme);

            // $(".reserve").hover(function(){
            //     alert("ok");
            // },
            //     function(){
            //         alert("ok");
            //     }
            // );

            $('input[name="type"]').change(function () {
                if (this.id.includes("bien")) {
                    $('.recherche_avancee_biens').css("display", "inline-block");
                    $('.recherche_avancee_services').css("display", "none");
                }
                else {
                    $('.recherche_avancee_biens').css("display", "none");
                    $('.recherche_avancee_services').css("display", "inline-block");
                }
            })

        });




    </script>

</head>
<body>


<?php


include("header.php");


// Page inaccessible pour les invités
//if (!isset($_SESSION['user'])) {
//    header ('location: inscription.php');
////    exit();
//}


?>

<div class="trouver_bloc_recherche">


    <div class="row">
        <div class="col-md-8 col-md-offset-2  effect6 box3">
            <p>
                Recherchez un bien ou un service par mots-clés, catégorie, emplacement, rayon et date de disponibilité (à venir).
<br>
                Pour obtenir plus d'informations et pour emprunter un bien, cliquez dessus (pour les utilisateurs connectés uniquement)
                <br>Les dates futures barrées lors de la réservation représentent les jours d'indisponibilité du bien.
                <br>
                Les mots-clés sont recherchés dans les titres, les descriptions et les mots-clés.
            </p>

        </div>
    </div>

    <form name="formTrouver" id="rechercher_form">
        <div class="trouver_form_container">
            <div class="recherche_simple">
                <div class="rechercher_input">
                    <div class="rechercher_image_container">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 20 20">
                            <path d="M18.869 19.162l-5.943-6.484c1.339-1.401 2.075-3.233 2.075-5.178 0-2.003-0.78-3.887-2.197-5.303s-3.3-2.197-5.303-2.197-3.887 0.78-5.303 2.197-2.197 3.3-2.197 5.303 0.78 3.887 2.197 5.303 3.3 2.197 5.303 2.197c1.726 0 3.362-0.579 4.688-1.645l5.943 6.483c0.099 0.108 0.233 0.162 0.369 0.162 0.121 0 0.242-0.043 0.338-0.131 0.204-0.187 0.217-0.503 0.031-0.706zM1 7.5c0-3.584 2.916-6.5 6.5-6.5s6.5 2.916 6.5 6.5-2.916 6.5-6.5 6.5-6.5-2.916-6.5-6.5z"></path>
                        </svg>
                    </div>
                    <input id="search" type="text" name="keywords"/>
                    <div class="rechercher_resultats_nb">
                        <span id="rechercher_resultats_nb_nb"></span> résultats
                    </div>
                </div>
            </div>


            <div class="recherche_avancee">
                <span class="recherche_avancee_texte">Recherche avancée</span>
                <div class="recherche_avancee_ligne">
                    <div class="recherche_avancee_field recherche_avancee_type">
                        <span class="recherche_avancee_je_cherche recherche_avancee_span">Je recherche : </span>
                        <div class="pretty p-icon p-round">
                            <input type="radio" name="type" value="biens" id="recherche_avancee_biens_radio"/>
                            <div class="state p-primary">
                                <i class="icon mdi mdi-check"></i>
                                <label for="recherche_avancee_bien">Bien</label>
                            </div>
                        </div>

                        <div class="pretty p-icon p-round">
                            <input type="radio" name="type" value="services" id="recherche_avancee_services_radio"/>
                            <div class="state p-success">
                                <i class="icon mdi mdi-check"></i>
                                <label for="recherche_avancee_service">Service</label>
                            </div>
                        </div>
                    </div>


                    <div class="recherche_avancee_field recherche_avancee_categorie">
                        <select name="categorie" id="recherche_avancee_select_cat">
                            <option value="CATEGORIE" selected disabled></option>
                            <?php
                            foreach ($cat_all as $c) {
                                echo "<option value='" . $c['id'] . "'></option>";
                            }
                            ?>

                        </select>

                        <div class="recherche_avancee_block_choix" id="recherche_avancee_block_choix_cat">
                        </div>
                        <div class="recherche_avancee_plus" id="recherche_avancee_plus">
                            <ul class="recherche_avancee_biens">

                                <?php


                                foreach ($cat_all as $c) {
                                    if (strpos($c['path'], 'bien') !== false && $c['id']>2)
                                    echo "<li class='recherche_avancee_cat' id='recherche_avancee_cat_" . $c['id'] . "'>" . $c['nom'] . "</li>";
                                }
                                echo "</ul><ul class='recherche_avancee_services'>";
                                foreach ($cat_all as $c) {
                                    if (strpos($c['path'], 'service') !== false   && $c['id']>2)
                                        echo "<li class='recherche_avancee_cat' id='recherche_avancee_cat_" . $c['id'] . "'>" . $c['nom'] . "</li>";
                                }
                                echo "</ul>";
                                ?>


                        </div>
                    </div>
                    <div class="recherche_avancee_field">
                        <!--                            <div class="input-select">-->
<!--                        <select data-trigger="" name="choices-single-defaul">-->
<!--                            <option placeholder="" value="">SIZE</option>-->
<!--                            <option>SIZE</option>-->
<!--                            <option>SUBJECT B</option>-->
<!--                            <option>SUBJECT C</option>-->
<!--                        </select>-->
<!--                        <div class="recherche_avancee_block_choix">Accessoires</div>-->
                        <input type="text" name="daterange" value="Date" readonly="readonly" />

                        <script>$('input[name="daterange"]').daterangepicker({
                                "singleDatePicker": true,
                                "showWeekNumbers": true,
                                "autoApply": true,
                                ranges: {
                                    'Aujourd\'hui': [moment(), moment()],
                                    'Demain': [moment().add(1, 'days'), moment().add(1, 'days')],
                                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                                },
                                "locale": {
                                    "format": "DD/MM/YYYY",
                                    "separator": " - ",
                                    "applyLabel": "Apply",
                                    "cancelLabel": "Cancel",
                                    "fromLabel": "From",
                                    "toLabel": "To",
                                    "customRangeLabel": "Custom",
                                    "weekLabel": "W",
                                    "daysOfWeek": [
                                        "Di",
                                        "Lu",
                                        "Ma",
                                        "Me",
                                        "Je",
                                        "Ve",
                                        "Sa"
                                    ],
                                    "monthNames": [
                                        "Janvier",
                                        "Février",
                                        "Mars",
                                        "Avril",
                                        "Mai",
                                        "Juin",
                                        "Juillet",
                                        "Août",
                                        "Septembre",
                                        "Octobre",
                                        "Novembre",
                                        "Décembre"
                                    ],
                                    "firstDay": 1
                                },
                                "showCustomRangeLabel": false,
                                "startDate": moment(),
                                "endDate": moment(),
                                "minDate": moment()
                            }, function(start, end, label) {
                                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                            });
                        </script>




                    </div>
                </div>

<!--                <div class="recherche_avancee_ligne">-->
<!--                    <div class="recherche_avancee_field">-->
<!--                        <select data-trigger="" name="choices-single-defaul">-->
<!--                            <option placeholder="" value="">ACCESSORIES</option>-->
<!--                            <option>ACCESSORIES</option>-->
<!--                            <option>SUBJECT B</option>-->
<!--                            <option>SUBJECT C</option>-->
<!--                        </select>-->
<!---->
<!--                        <div class="recherche_avancee_block_choix">ACCESSOIRES</div>-->
<!--                        <div class="recherche_avancee_plus">-->
<!--                            <ul>-->
<!--                                <li>UN</li>-->
<!--                                <li>DEUX ICI !</li>-->
<!---->
<!--                            </ul>-->
<!---->
<!--                        </div>-->
<!---->
<!---->
<!--                    </div>-->
<!--                    <div class="recherche_avancee_field">-->
<!--                        <select data-trigger="" name="choices-single-defaul">-->
<!--                            <option placeholder="" value="">COLOR</option>-->
<!--                            <option>GREEN</option>-->
<!--                            <option>SUBJECT B</option>-->
<!--                            <option>SUBJECT C</option>-->
<!--                        </select>-->
<!---->
<!--                        <div class="recherche_avancee_block_choix">Accessoires</div>-->
<!---->
<!--                    </div>-->
<!--                    <div class="recherche_avancee_field">-->
<!--                        <select data-trigger="" name="choices-single-defaul">-->
<!--                            <option placeholder="" value="">SIZE</option>-->
<!--                            <option>SIZE</option>-->
<!--                            <option>SUBJECT B</option>-->
<!--                            <option>SUBJECT C</option>-->
<!--                        </select>-->
<!--                        <div class="recherche_avancee_block_choix">Accessoires</div>-->
<!---->
<!--                    </div>-->
<!--                </div>-->

                <input name="lat" id="recherche_avancee_lat">
                <input name="lng" id="recherche_avancee_lng">

                <div class="recherche_avancee_ligne">
                    <span class="recherche_avancee_span">Où ?</span>
                    <div class="recherche_avancee_field recherche_avancee_adresse">

                        <input type="text" name="adresse" id="recherche_avancee_adresse" onkeypress="return event.keyCode != 13;">

                    </div>
                    <img src="images/location2.png" id="geolocate">

<!--                    <span class="recherche_avancee_span">ou</span>-->
                    <div class="recherche_avancee_field">
                        <!--                            <div class="input-select">-->
                        <select name="rayon" id="recherche_avancee_select_rayon">
                            <option value="partout">Partout</option>
                            <option value="5">5 km</option>
                            <option value="30">30 km</option>
                            <option value="100">100 km</option>
                            <option value="300">< 300 km</option>
                        </select>

                        <div class="recherche_avancee_block_choix" id="recherche_avancee_block_choix_rayon">
                        </div>
                        <div class="recherche_avancee_plus">
                            <ul>
                                <li class="recherche_avancee_rayon"id="rayon_partout">Partout</li>
                                <li class="recherche_avancee_rayon" id="rayon_5">5 km</li>
                                <li class="recherche_avancee_rayon"id="rayon_30">30 km</li>
                                <li class="recherche_avancee_rayon"id="rayon_100">100 km</li>
                                <li class="recherche_avancee_rayon"id="rayon_300">300 km</li>

                            </ul>

                        </div>

                        <!--                            </div>-->
                    </div>

                </div>


                <div class="recherche_avancee_submit">
                    <div class="input-field">

                        <button class="btn-search" type="submit">Recherche
                        </button>
                        <div class="btn-delete" id="delete" onclick="window.location.href='rechercher.php'">Supprimer</div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <div class="trouver_map_container">
        <div id="trouver_map"></div>
        <div id="infowindow-content">
            <img src="images/geocode-71.png" width="16" height="16" id="place-icon">
            <span id="place-name" class="title"></span><br>
            <span id="place-address"></span>
        </div>
    </div>
    <div id="reset_state" style="font-weight: bold; cursor: pointer;">Réinitialiser le zoom</div>


</div>


<div id="trouver">



    <div class="trouver_categories effect8">
        <ul>
            <div class='trouver_li_categories'><a href='#'>Biens</a> </div>
            <?php
            $stmt = $pdo->prepare("SELECT count(*) as cpt FROM biens where categorie = ?");
            foreach ($cat_all as $c) {
                $stmt->execute([$c['id']]);
                $res = $stmt->fetch();

                if ($c['path'][0] === 'b' && isset($_GET['categorie']) && $_GET['categorie'] == $c['id'] )
                    echo "<li><a class='active' href='rechercher.php?type=biens&categorie=" . $c['id'] . "'><div class='rechercher_categorie_compte'>(" .$res['cpt'] .")</div>". $c['nom'] . "</a></li>";
                else if ($c['path'][0] === 'b' && $c['id']>2)
                    echo "<li><a href='rechercher.php?type=biens&categorie=" . $c['id'] . "'><div class='rechercher_categorie_compte'>(" .$res['cpt'] .")</div>". $c['nom'] . "</a></li>";
            }
            echo "<hr>
<div class='trouver_li_categories'><a href='#'>Services</a> </div>
";
            $stmt = $pdo->prepare("SELECT count(*) as cpt FROM services where categorie = ?");

            foreach ($cat_all as $c) {
                $stmt->execute([$c['id']]);
                $res = $stmt->fetch();

                if ($c['path'][0] === 's' &&  isset($_GET['categorie']) && $_GET['categorie'] == $c['id'])
                    echo "<li><a class='active' href='rechercher.php?type=services&categorie=" . $c['id'] . "'><div class='rechercher_categorie_compte'>(" .$res['cpt'] .")</div>". $c['nom'] . "</a></li>";
                else if ($c['path'][0] === 's' && $c['id']>2)
                    echo "<li><a href='rechercher.php?type=services&categorie=" . $c['id'] . "'><div class='rechercher_categorie_compte'>(" .$res['cpt'] .")</div>". $c['nom'] . "</a></li>";
            }


            ?>

        </ul>
    </div>


    <div data-popbox-id="mypopbox1" class="popbox">
          <div class="popbox_container">
<!--               	Popbox content 1-->

            <div id="popbox-contenu">

                    <div class="booking-form">
                        <form name="reservation">



                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input readonly="readonly" type="hidden" name="reservation_id_emprunte" val="" id="reservation_id_emprunte">
                                        <span class="form-label" id="reservation_id"></span>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6 reserver_prix_neuf_bloc">
                                    <div class="form-group">
                                        <span class="form-label reserver_prix_neuf_label">Prix neuf</span>
                                        <input id="reserver_prix_neuf" class="form-control" readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <span class="form-label">Déjà emprunté</span>
                                        <input id="reserver_deja_emprunte" class="form-control" readonly="readonly">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="font-weight-light">Vous pouvez faire cet emprunt sur un ou plusieurs jours</p>

                                    <div class="form-group">
                                        <span class="form-label">Dates d'emprunt</span>
                                        <div id="reserver_dates">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="heuresServices">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <span class="form-label">Heure début</span>
                                        <input readonly="readonly" name="heureDebut" val="" id="heureDebut" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <span class="form-label">Heure fin</span>
                                        <input readonly="readonly" name="heureFin" val="" id="heureFin" class="form-control">

                                    </div>
                                </div>
                            </div>

                            <div class="row reserver_cout_journalalier_infos">
                                <div class="col-sm-12">
                                <span class="form-label">Le coût journalier est: Prix neuf / 150</span>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="form-group">
                                            <span class="form-label reserver_cout_journalier_label">Coût journalier</span>
                                        <input id="reserver_cout" class="form-control" readonly="readonly">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <span class="form-label">Coût total</span>
                                        <input id="reserver_cout_total" class="form-control" readonly="readonly">

                                    </div>
                                </div>
                            </div>
                            <div class="form-btn">
                                <button class="submit-btn" id="reservation_submit">Réserver</button>
                            </div>
                        </form>
                    </div>

            </div>
<!--               	<button data-popbox-close="mypopbox1">Close</button>-->
              </div>
          </div>


    <?php

    foreach ($GLOBALS['items'] as $item) {
        $titre = $item['titre'];
        $cat = $cat_all[$item['categorie']]['nom'];

        $type = explode('/',$cat_all[$item['categorie']]['path'])[0];
        $user = new utilisateur($item['id_utilisateur'],$pdo);
        $pseudo = ucfirst($user->getPrenom()) . " " . ucfirst(substr($user->getNom(), 0, 1)) . ".";
        $avatar = "images/avatars/" . $user->getAvatar();
            $id = $user->getId();
        $iditem = $item['id'];
        $description = $item['description'];
        $adresse = $item['adresse'];
        $lat = $item['lat'];
        $lng = $item['lng'];
        $prix = $item['lng'];

        $src = "";
        if ($item['image'] == NULL) {
            if ($type == "biens")
                $src = "default_bien.png";
            else
                $src = "default-service2.png";
        }
        else
            $src = $item['id'] .".".$item['image'];

        $supp = " ";

        // Affichage du bouton de suppression si l'objet appartient à l'utilisateur ou si ce dernier est administrateur
        if (isset($_SESSION['user']) && ($thisuser->getId() === $id || $thisuser->getAdmin()))
        $supp = "<div class='trouver_supprimer' id='trouver_supprimer_" . $type . "_" . $iditem . "_" .$thisuser->getId()."'><td class='mes_propositions_services_colonne9 mp_supp' id='" . $item['id'] . "'>
<a class='trouver_supp' role='button' id='" . $iditem . "'>
<div class='mes_propositions_supp_helper'>
<img  src='images/trash.svg' class='trouver_btn_supp'>
</div> </a></td></div>";




        echo '    <div class="box effect8" id="trouver_' . $type . '_'.$iditem . '_' . $id . '_'.$thisuser->getId().'">

        <div class="trouver_container_image">
                        <span class="helper"></span>
<img src="' . $avatar . '">
            <br><a href="user.php?id=' . $id . '"> <div class="trouver_container_infos_pseudo" id ="trouver_container_infos_pseudo_' .$iditem . '"> ' . $pseudo . ' </div></a>

        </div>

        <div class="trouver_container_infos">
            <div class="trouver_container_infos_titre">' . $titre . '</div>

            <div class="trouver_container_infos_categorie">
              ' . $cat . '
            </div>
<br>
            <div class="trouver_container_infos_description">
                ' . $description . '
            </div>

            <div class="trouver_container_infos_adresse">
                ' . $adresse . '
            </div>

            <div class="trouver_container_infos_distance" id="bien_' . $item["id"] . '">
                /
            </div>

        </div>

        ' . $supp . '

        <div class="trouver_container_image_bien_service">
            <span class="helper"></span>
            <img src="uploads/'.$type.'/' . $src . '">

        </div>
        
        <script type="text/javascript">
        if (localStorage.getItem("lat") == null && localStorage.getItem("lng") == null)
        document.getElementById("bien_" + ' . $item['id'] . ').innerText = "?";
        else
        {                    var dist = distance(localStorage.getItem("lat"),localStorage.getItem("lng"),' . $lat . ',' . $lng . ');
if (dist >= 1)
        document.getElementById("bien_" + ' . $item['id'] . ').innerText = Math.round(10*dist)/10 + " km.";
        else
        document.getElementById("bien_" + ' . $item['id'] . ').innerText = 1000*Math.round(1000*dist)/1000 + " m.";}

</script>


    </div>';

        if (isset($_SESSION['user']) && $id === $thisuser->getId())
            echo '<script>
document.getElementById("trouver_container_infos_pseudo_' . $iditem . '").style.backgroundColor = "green";
</script>';

    }


    ?>

</div>


<script type="text/javascript">


    var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var labelIndex = 0;
    var resetGeo = false;


    function initMap() {

        var bounds = new google.maps.LatLngBounds();

        var map = new google.maps.Map(document.getElementById('trouver_map'), {
            center: {lat: 43.623118, lng: 3.870869},
            zoom: 10
        });

        // map.setZoom(12);

        var mc = new MarkerClusterer(map);
    mc.setMaxZoom(17);
    mc.setGridSize(33);


        var card = document.getElementById('pac-card');
        var input = document.getElementById('recherche_avancee_adresse');

        var infowindow = new google.maps.InfoWindow();
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);

        autocomplete.setFields(
            ['address_components', 'geometry', 'icon', 'name']);

        var infowindowContent = document.getElementById('infowindow-content');
        infowindow.setContent(infowindowContent);

        var markerHome = new google.maps.Marker({
            animation: google.maps.Animation.DROP,
            map: map,
            zoom: 11,
            anchorPoint: new google.maps.Point(0, -29),
            icon: {
                size: new google.maps.Size(40, 50),
                scaledSize: new google.maps.Size(40, 50),
                url: "images/markers/house-location.svg"
            }
        });


        // Ajout des markers. Positions légèrement modifiées pour éviter la superposition de markers sur la map
        <?php
        $items = $pdo->prepare($sql);
        $items->execute($sql_array);
        foreach ($items as $item) {
            $scale = 300000;
            $delta_lat = (mt_rand (0,100) - 50)/$scale;
            $delta_long = (mt_rand (0,100) - 50)/$scale;
            echo "var lat = " . ($item['lat']+$delta_lat) . ";";
            echo "var lng = " . ($item['lng']+$delta_long) . ";";
            echo "var pos = {lat: lat,lng: lng};";
            echo "var ad ='" . addslashes($item['adresse']) . "';";
            echo "var titre ='" . addslashes($item['titre']) . "';" ;
            if ($item['distance'] != 0)
                echo "titre +='" . "   (" . round($item['distance']/1000,2) ."km)';";
            echo 'ajouterMarker(pos,false,true,ad,titre,true,"images/markers/marker-editor.svg","'.$cat_all[$item["categorie"]]["nom"].'");';
        }
        echo "map.fitBounds(bounds);";

        ?>

        if (navigator.geolocation && GET('adresse') === null) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var geocoder = new google.maps.Geocoder;
                geocodeLatLng(geocoder, map, infowindow, position);
            }, function () {
                handleLocationError();
            });        }
        else if (GET("adresse") != null && GET('lat')) {
            latlng = {lat: parseFloat(GET('lat')), lng: parseFloat(GET('lng'))};
            markerHome.setPosition(latlng);
            // infowindow.open(map, markerHome);
            bounds.extend(latlng);

            if (map.getZoom() > 14)
                map.setZoom(10);
            infowindowContent.children['place-name'].textContent = 'Lieu de départ';
            infowindowContent.children['place-address'].textContent = GET('adresse');

            markerHome.addListener('click', function () {
                infowindowContent.children['place-name'].textContent = "Lieu de départ";
                infowindowContent.children['place-address'].textContent = GET('adresse');
                infowindow.open(map, this);
            });
        }
        else if (GET('adresse') === null)
        {
            handleLocationError();
        }

        // map.fitBounds(bounds);


        function handleLocationError() {
            document.getElementById('warning').innerHTML = "La géolocalisation n'a pas fonctionnée.";
        }


        function geocodeLatLng(geocoder, map, infowindow, position) {
            var latlng = {lat: parseFloat(position.coords.latitude), lng: parseFloat(position.coords.longitude)};
            geocoder.geocode({'location': latlng}, function (results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        var address;
                        if (GET('adresse') === null || resetGeo)
                        {address = results[0].formatted_address;
                        resetGeo = false;

                        }
                        else {
                            address = GET('adresse');
                        }
                        document.getElementById("recherche_avancee_adresse").value = address;
                        infowindowContent.children['place-address'].textContent = address;

                        markerHome.setPosition(latlng);
                        // infowindow.open(map, markerHome);
                        setLatLng();

                        infowindowContent.children['place-name'].textContent = 'Vous êtes ici';

                        bounds.extend(latlng);
                        map.fitBounds(bounds);


                        // if (map.getZoom() > 14)
                        //     map.setZoom(10);

                        markerHome.addListener('click', function () {
                            infowindowContent.children['place-name'].textContent = "Vous êtes ici";
                            infowindowContent.children['place-address'].textContent = address;
                            infowindow.open(map, this);
                        });

                    } else {
                        window.alert('Pas de résultats');
                    }
                } else {
                    window.alert('Geocoder a échoué : ' + status);
                }
            });
        }


        autocomplete.addListener('place_changed', function () {
            infowindow.close();
            markerHome.setVisible(false);

            var place = autocomplete.getPlace();

            if (!place.geometry) {
                window.alert("Pas de details disponibles pour: '" + place.name + "'");
                return;
            }
            bounds.extend(place.geometry.location);
            map.fitBounds(bounds);

            markerHome.setPosition(place.geometry.location);
            setLatLng();

            markerHome.setIcon({
                // anchor: new google.maps.Point(30, 30.26),
                size: new google.maps.Size(40, 50),
                scaledSize: new google.maps.Size(40, 50),
                url: "images/markers/house-location.svg"
            });

            markerHome.setVisible(true);

            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }

            infowindowContent.children['place-icon'].src = place.icon;
            infowindowContent.children['place-name'].textContent = "Lieu de départ";
            infowindowContent.children['place-address'].textContent = address;
            infowindow.open(map, markerHome);

            markerHome.addListener('click', function () {
                infowindowContent.children['place-name'].textContent = "Lieu de départ";
                infowindowContent.children['place-address'].textContent = address;
                infowindow.open(map, this);
            });
        });

        function setLatLng(){
            document.getElementById("recherche_avancee_lat").value = markerHome.position.lat();
            document.getElementById("recherche_avancee_lng").value = markerHome.position.lng();
            localStorage.setItem("lat",markerHome.position.lat());
            localStorage.setItem("lng",markerHome.position.lng());
        }

        var geoLocateBtn = document.getElementById('geolocate');
        geoLocateBtn.addEventListener('click', function() {

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    //Geocoder
                    var geocoder = new google.maps.Geocoder;
                    resetGeo = true;
                    geocodeLatLng(geocoder, map, infowindow, position);
                    map.fitBounds(bounds);


                }, function () {
                    handleLocationError();
                });
            }

            }, false);


        function ajouterMarker(pos, afficherFenetre, bounce, adresse, titre, afficherLabel, image, categorie) {
            var label = categorie[0];

            // if (afficherLabel) {
            //     label = labels[labelIndex++ % labels.length];
            // }

            marker = new google.maps.Marker({
                animation: google.maps.Animation.DROP,
                map: map,
                position: pos,
                label: {text: label, color: 'white'},
                anchorPoint: new google.maps.Point(0, -33),
                icon: {
                    // anchor: new google.maps.Point(30, 30.26),
                    size: new google.maps.Size(32, 42),
                    scaledSize: new google.maps.Size(32, 42),
                    url: image
                }
            });


            marker.setVisible(true);
            marker.addListener('click', function () {
                infowindowContent.children['place-name'].textContent = titre;
                infowindowContent.children['place-address'].textContent = adresse;
                infowindow.open(map, this);
            });
            bounds.extend(pos);




            // oms.addMarker(marker);
            mc.addMarker(marker);

        }

        $("#reset_state").click(function() {
            infowindow.close();
            map.fitBounds(bounds);
        })


    }

    initCat();
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBwQxOi8hCzeOA9TfWU9wOq7DEG26kyuHk&libraries=places&callback=initMap"
        async defer>
</script>

<?php

// Modification des valeurs par defaults dans les champs de recherche en fonction des valeurs GET définies

$script = "<script>";

    if (isset($_GET['categorie']) && $_GET['categorie'] != "CATEGORIE")
        $script .= '$("#recherche_avancee_block_choix_cat").text("'.$cat_all[$_GET["categorie"]]['nom'] .'");
$("#recherche_avancee_select_cat").val("'. $_GET['categorie'] .'");';
    else
        $script .= '$("#recherche_avancee_block_choix_cat").text("CATEGORIE");';

    if (isset($_GET['type']))
    {
        $script .= '$("#recherche_avancee_' . $_GET["type"] . '_radio").prop("checked", true);';
    }

    if (isset($_GET['keywords']) && $_GET['keywords']!= "")
    {
        $script .= '$("#search").val("'.$_GET["keywords"] .'");';
    }
    else
        $script .= '$("#search").attr("placeholder","Rechercher ...");';

    if (isset($_GET['adresse']))
    {
        $script .= '$("#recherche_avancee_adresse").val("'.$_GET["adresse"] .'");';
    }

    if (isset($_GET['lat']))
    {
        $script .= '$("#recherche_avancee_lat").val("'.$_GET["lat"] .'");';
        $script .= '$("#recherche_avancee_lng").val("'.$_GET["lng"] .'");';
    }

if (isset($_GET['rayon']) && $_GET['rayon'] != "partout")
{
    $script .= '$("#recherche_avancee_block_choix_rayon").text("'.$_GET["rayon"] .' km");
    $("#recherche_avancee_select_rayon").val("'. $_GET['rayon'] .'");';
}
else
    $script .= '$("#recherche_avancee_block_choix_rayon").text("Dans un rayon de...");';


$resultats = $items->rowCount();
    $script .= 'document.getElementById("rechercher_resultats_nb_nb").innerHTML = "'.$resultats.'";';

$script .= "</script>";

    echo $script;


?>


</body>
</html>

