<?php
/**
 * Created by PhpStorm.
 * User: Remi MATTEI
 * Numéro étudiant: 21516143
 * Date: 15/11/2018
 * Time: 15:58
 */

require_once("controllers/config.php");
session_start();




$sql = "SELECT * FROM biens ORDER BY date_debut DESC LIMIT 10";

$GLOBALS['items'] = $pdo->prepare($sql);
$GLOBALS['items']->execute($sql_array);
$_SESSION['requete'] = serialize($sql);
$_SESSION['sql_array'] = serialize($sql_array);


$cat_all = $pdo->query("SELECT id, nom, path, id FROM categorie ORDER BY nom")->fetchAll(PDO::FETCH_UNIQUE);
?>


<!DOCTYPE html>
<html>
<head>

    <title>Accueil</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/pretty-checkbox.css">
    <link rel="stylesheet" type="text/css" href="css/daterangepicker.css"/>

    <link rel="icon" href="favicon.ico"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/markerclusterer.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/moment.min.js"></script>
    <script type="text/javascript" src="js/daterangepicker.js"></script>

    <script>


        $(document).ready(function () {

            $('.recherche_avancee_field').on('click', afficherMenu);
            $('.recherche_avancee_cat').on('click', selectCat);
            $('.recherche_avancee_rayon').on('click', selectRayon);
            $('.recherche_avancee_plus').on('click', cacherMenu);
            $('.recherche_avancee_type').on('click', selectType);

            $('div:not(.recherche_avancee_block_choix)').on('click', cacherMenu);

            $('input[name="type"]').change(function () {
                if (this.id.includes("bien")) {
                    $('.recherche_avancee_biens').css("display", "inline-block");
                    $('.recherche_avancee_services').css("display", "none");
                } else {
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
?>

<div class="trouver_bloc_recherche rechercher_index">


    <div class="row">
        <div class="col-md-8 col-md-offset-2  effect6 box3">
            <p>
                Bienvenue sur <span>Recherche Bien ou Service.fr</span>!
                <br>Ce site propose des outils permettant l'emprunt et la mise à disposition de biens et services dans
                une zone géographique définie.
                <br>
            <hr>
            <?php
            if (isset($_SESSION['user'])) {
                echo "Chaque nouvel utilisateur dispose d'un solde de 100€ permettant l'emprunt de biens ou services.
                    <br>Pour le moment, le solde peut passer en négatif sans avoir d'incidence sur les emprunts futurs.
                    <br>Il y a en revanche deux restrictions interdisant les emprunts si l'utilisateur a trop demandé et pas assez proposé, ou si il a simplement effectué un trop grand nombre d'emprunts.";
            } else
                echo " Seuls les membres connectés ont accès à toutes les fonctionnalités, parmi lesquels :
                <ul>
                <li>
                    Mise à disposition d'un matériel ou service
                </li>
                <li>
                    Pages répertoriant ses propres emprunts passés, ses mises à dispositions avec possibilité de les éditer
                </li>
                <li>
                    Edition du profil, accès au solde actuel.
                </li>
            </ul>";

            ?>

            </p>

        </div>
    </div>

    <form name="formTrouver" action="rechercher.php">
        <div class="trouver_form_container">
            <div class="recherche_simple">
                <div class="rechercher_input">
                    <div class="rechercher_image_container">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 20 20">
                            <path d="M18.869 19.162l-5.943-6.484c1.339-1.401 2.075-3.233 2.075-5.178 0-2.003-0.78-3.887-2.197-5.303s-3.3-2.197-5.303-2.197-3.887 0.78-5.303 2.197-2.197 3.3-2.197 5.303 0.78 3.887 2.197 5.303 3.3 2.197 5.303 2.197c1.726 0 3.362-0.579 4.688-1.645l5.943 6.483c0.099 0.108 0.233 0.162 0.369 0.162 0.121 0 0.242-0.043 0.338-0.131 0.204-0.187 0.217-0.503 0.031-0.706zM1 7.5c0-3.584 2.916-6.5 6.5-6.5s6.5 2.916 6.5 6.5-2.916 6.5-6.5 6.5-6.5-2.916-6.5-6.5z"></path>
                        </svg>
                    </div>
                    <input id="search" type="text" name="keywords" placeholder="Rechercher..."/>
                    <!--                    <div class="rechercher_resultats_nb">-->
                    <!--                        <span id="rechercher_resultats_nb_nb"></span> résultats-->
                    <!--                    </div>-->
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

                        <div class="recherche_avancee_block_choix" id="recherche_avancee_block_choix_cat">CATEGORIE
                        </div>
                        <div class="recherche_avancee_plus" id="recherche_avancee_plus">
                            <ul class="recherche_avancee_biens">

                                <?php


                                foreach ($cat_all as $c) {
                                    if (strpos($c['path'], 'bien') !== false && $c['id'] > 2)
                                        echo "<li class='recherche_avancee_cat' id='recherche_avancee_cat_" . $c['id'] . "'>" . $c['nom'] . "</li>";
                                }
                                echo "</ul><ul class='recherche_avancee_services'>";
                                foreach ($cat_all as $c) {
                                    if (strpos($c['path'], 'service') !== false && $c['id'] > 2)
                                        echo "<li class='recherche_avancee_cat' id='recherche_avancee_cat_" . $c['id'] . "'>" . $c['nom'] . "</li>";
                                }
                                echo "</ul>";
                                ?>


                        </div>
                    </div>
                    <div class="recherche_avancee_field">
                        <input type="text" name="daterange" value="Date" readonly="readonly"/>

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
                            }, function (start, end, label) {
                            //     console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                            // });
                        </script>


                    </div>
                </div>

                <input name="lat" id="recherche_avancee_lat">
                <input name="lng" id="recherche_avancee_lng">

                <div class="recherche_avancee_ligne">
                    <span class="recherche_avancee_span">Où ?</span>
                    <div class="recherche_avancee_field recherche_avancee_adresse">

                        <input type="text" name="adresse" id="recherche_avancee_adresse"
                               onkeypress="return event.keyCode != 13;">

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

                        <div class="recherche_avancee_block_choix" id="recherche_avancee_block_choix_rayon">Dans un
                            rayon de
                        </div>
                        <div class="recherche_avancee_plus">
                            <ul>
                                <li class="recherche_avancee_rayon" id="rayon_partout">Partout</li>
                                <li class="recherche_avancee_rayon" id="rayon_5">5 km</li>
                                <li class="recherche_avancee_rayon" id="rayon_30">30 km</li>
                                <li class="recherche_avancee_rayon" id="rayon_100">100 km</li>
                                <li class="recherche_avancee_rayon" id="rayon_300">300 km</li>
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="recherche_avancee_submit">
                    <div class="input-field">
                        <button class="btn-search" type="submit">Recherche
                        </button>
                        <div class="btn-delete" id="delete" onclick="window.location.href='rechercher.php'">Supprimer
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <div class="row">
        <div class="col-md-12 accueil_derniers_biens_ajoutés box4">
            Derniers biens ajoutés
        </div>
    </div>


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
            $delta_lat = (mt_rand(0, 100) - 50) / $scale;
            $delta_long = (mt_rand(0, 100) - 50) / $scale;
            echo "var lat = " . ($item['lat'] + $delta_lat) . ";";
            echo "var lng = " . ($item['lng'] + $delta_long) . ";";
            echo "var pos = {lat: lat,lng: lng};";
            echo "var ad ='" . $item['adresse'] . "';";
            echo "var titre ='" . $item['titre'] . "';";
            if ($item['distance'] != 0)
                echo "titre +='" . "   (" . round($item['distance'] / 1000, 2) . "km)';";
            echo 'ajouterMarker(pos,false,true,ad,titre,true,"images/markers/marker-editor.svg","' . $cat_all[$item["categorie"]]["nom"] . '");';
        }
        echo "map.fitBounds(bounds);";

        ?>

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var geocoder = new google.maps.Geocoder;
                geocodeLatLng(geocoder, map, infowindow, position);
            }, function () {
                handleLocationError();
            });
        } else {
            handleLocationError();
        }


        function handleLocationError() {
            document.getElementById('warning').innerHTML = "La géolocalisation n'a pas fonctionnée.";
        }


        function geocodeLatLng(geocoder, map, infowindow, position) {
            var latlng = {lat: parseFloat(position.coords.latitude), lng: parseFloat(position.coords.longitude)};
            geocoder.geocode({'location': latlng}, function (results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        var address;
                        if (GET('adresse') === null || resetGeo) {
                            address = results[0].formatted_address;
                            resetGeo = false;

                        } else {
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

        function setLatLng() {
            document.getElementById("recherche_avancee_lat").value = markerHome.position.lat();
            document.getElementById("recherche_avancee_lng").value = markerHome.position.lng();
            localStorage.setItem("lat", markerHome.position.lat());
            localStorage.setItem("lng", markerHome.position.lng());
        }

        var geoLocateBtn = document.getElementById('geolocate');
        geoLocateBtn.addEventListener('click', function () {

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

            mc.addMarker(marker);
        }

        $("#reset_state").click(function () {
            infowindow.close();
            map.fitBounds(bounds);
        })
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
        $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]).replace('+', ' ');
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

    function selectType() {
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

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBwQxOi8hCzeOA9TfWU9wOq7DEG26kyuHk&libraries=places&callback=initMap"
        async defer>
</script>


</body>
</html>
