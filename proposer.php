<!DOCTYPE html>
<html>
<head>
    <title>Proposer</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/plages_horaires.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />

    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css'>

    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/themes/base/jquery-ui.css" rel="stylesheet"
          type="text/css"/>
    <script src="js/jquery-3.3.1.js"></script>
    <!--    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>-->
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
</head>
<body>


<?php
session_start();
include("header.php");
require_once("controllers/config.php");

if (!isset($_SESSION['id'])) {
    header("Location: inscription.php");
    exit();
}


?>


<div class="proposer_map_form_container" id="page">
    <div class="formulaire_proposer">
        <h1>Ajout d'une proposition<span>Ajoutez un bien ou un service!</span></h1>
        <form name="form" method="post" action="proposer_ok.php" onkeypress="return event.keyCode != 13;"
              enctype="multipart/form-data">
            <!--         Pour eviter que le formulaire soit envoyé quand l'utilisateur appuie sur entrée-->

            <div class="section"><span>1</span>Type de bien</div>
            <div class="formulaire_proposer_rubrique" id="un">


                <label for="bien">Vous proposez :</label>
                <fieldset>

                    <div class="proposer_type">
                        <label for="bien"><input type="radio" name="type" value="bien" id="bien" required="required">
                            Un bien</label>
                        <label for="service"><input type="radio" name="type" value="service" id="service">
                            Un service</label>
                    </div>
                </fieldset>


            </div>
            <div class="section" id="localisation-texte"><span>2</span>Localisation</div>

            <input id="lat-hidden" name="lat" type="hidden">
            <input id="lng-hidden" name="lng" type="hidden">


            <div class="formulaire_proposer_rubrique" id="deux">
                <label for="pac-input">Adresse<input id="pac-input" required="required" type="text"
                                                     name="adresse"/></label>
            </div>


            <div class="section"><span>3</span>Catégorie, description...</div>
            <div class="formulaire_proposer_rubrique" id="trois">

                <label>Sélectionnez une catégorie                <span id="choisir"><br><br>Vous devez d'abord préciser le type de proposition !</span>
                </label>
                <select id="select-categorie-biens" name="select-categorie-biens">
                    <optgroup label="Biens">
                    <?php

                    $sql = "SELECT nom, path, id FROM categorie where path like 'biens%' AND id > 2";
                    foreach ($pdo->query($sql) as $ligne) {
                        if ($ligne)
                        echo "<option value='{$ligne['id']}'>{$ligne['nom']}</option>";
                    }
                    ?>
                </select>
                <select id="select-categorie-services"name="select-categorie-services">
                    <optgroup label="Services">
                    <?php
                    $sql = "SELECT nom, id, path FROM categorie where path like 'services%' AND ID > 2";
                    foreach ($pdo->query($sql) as $ligne) {
                        echo "<option value='{$ligne['id']}'>{$ligne['nom']}</option>";
                    }
                    ?>
                </select>

                <br><br>















                <label>Donnez un titre à votre proposition<br>
                    <input type="texte" name="titre" required="required" maxlength="30"></label>

                <label>... et une description !<br>
                    <textarea  id="proposer-description" class="text" minlength="0" rows="7"
                              name="description" maxlength="125"
                              placeholder="Vous pouvez rédiger une description"></textarea></label>
                <label>Mots clés<br>
                    <input type="texte" name="mots"></label>



                <div id="proposer_prix_neuf" >
                    <label>Prix neuf de l'objet<br>
                        <input type="number" id="prix" name="prix" min="0" ></label></div>


                <br>

                <div id="proposer_plages_horaires">
                    <label>Définissez les jours et horaires auxquels vous proposez votre service</label>


                    <div>
                        Tous les jours <strong> De </strong><strong id="amount" class="amount"></strong> <span
                                id="proposer_afficher_jours">Plus...</span>

                    </div>
                    <div id="slider" class="slider"></div>

                    <input type="hidden" value="08:00" id="ph_d"/>
                    <input type='hidden' value="17:00" id="ph_f"/>


                    <div id="proposer_plages_horaires_jours">
                        <div>
                            <span class="proposer_jour">Lundi</span><span
                                    class="proposer_horaires"><strong>De </strong><strong id="amount1"
                                                                                          class="amount1"></strong></span>
                        </div>
                        <div id="slider1"></div>

                        <input type="hidden" value="08:00" id="ph1_d" name="ph1_d"/>
                        <input type='hidden' value="17:00" id="ph1_f" name="ph1_f"/>

                        <div>
                            <span class="proposer_jour"> Mardi</span><span
                                    class="proposer_horaires"><strong>De </strong><strong id="amount2"
                                                                                          class="amount2"></strong></span>
                        </div>
                        <div id="slider2"></div>

                        <input type="hidden" value="08:00" id="ph2_d" name="ph2_d"/>
                        <input type='hidden' value="17:00" id="ph2_f" name="ph2_f"/>


                        <div>
                            <span class="proposer_jour"> Mercredi</span><span class="proposer_horaires"><strong>De </strong><strong
                                        id="amount3" class="amount3"></strong></span>
                        </div>
                        <div id="slider3"></div>

                        <input type="hidden" value="08:00" id="ph3_d"  name="ph3_d"/>
                        <input type='hidden' value="17:00" id="ph3_f"  name="ph3_f"/>


                        <div>
                            <span class="proposer_jour"> Jeudi</span><span
                                    class="proposer_horaires"><strong>De </strong><strong id="amount4"
                                                                                          class="amount4"></strong></span>
                        </div>
                        <div id="slider4"></div>

                        <input type="hidden" value="08:00" id="ph4_d" name="ph4_d"/>
                        <input type='hidden' value="17:00" id="ph4_f" name="ph4_f"/>


                        <div>
                            <span class="proposer_jour"> Vendredi</span><span
                                    class="proposer_horaires"> <strong>De </strong><strong id="amount5"
                                                                                           class="amount5"></strong></span>
                        </div>
                        <div id="slider5"></div>

                        <input type="hidden" value="08:00" id="ph5_d" name="ph5_d"/>
                        <input type='hidden' value="17:00" id="ph5_f" name="ph5_f"/>


                        <div>
                            <span class="proposer_jour"> Samedi</span> <span
                                    class="proposer_horaires"><strong>De </strong><strong id="amount6"
                                                                                          class="amount6"></strong></span>
                        </div>
                        <div id="slider6"></div>

                        <input type="hidden" value="08:00" id="ph6_d" name="ph6_d"/>
                        <input type='hidden' value="17:00" id="ph6_f" name="ph6_f"/>


                        <div>
                            <span class="proposer_jour">Dimanche</span><span
                                    class="proposer_horaires"><strong>De </strong><strong id="amount7"
                                                                                          class="amount7"></strong></span>
                        </div>
                        <div id="slider7"></div>

                        <input type="hidden" value="08:00" id="ph7_d" name="ph7_d"/>
                        <input type='hidden' value="17:00" id="ph7_f" name="ph7_f"/>
                    </div>
                </div>













            </div>




            <div class="section"><span>4</span>Image</div>
            <div class="formulaire_proposer_rubrique" id="quatre">
                Vous pouvez également ajouter une image (jpeg, png)<br><br>
                <input class="inputfile" type="file" id="image" name="image" accept="image/png, image/jpeg, image/jpg"/>
                <label class="labelinputfile" for="image"></label>
            </div>


            <div class="button-section">
                <input type="submit" name="submit"/>
            </div>
        </form>
    </div>


    <script>

        $("#proposer_afficher_jours").on("click", function (e) {
            var block = document.getElementById("proposer_plages_horaires_jours");
            if (block.style.display == "block") {
                block.style.display = "none";
                document.getElementById("proposer_afficher_jours").innerHTML = "Plus...";
            }
            else {
                block.style.display = "block";
                document.getElementById("proposer_afficher_jours").innerHTML = "Moins...";
            }


        });


        $('input[name="type"]').on("click", function (e) {
            document.getElementById("choisir").style.display = "none";
            if (document.getElementById('service').checked) {
                document.getElementById("localisation-texte").innerHTML = "<span>2</span>Localisation du Service";
                document.getElementById("select-categorie-biens").style.display = "none";
                document.getElementById("select-categorie-services").style.display = "inline-block";
                document.getElementById("select-categorie-biens").attributes["required"] = "";
                document.getElementById("select-categorie-services").attributes["required"] = "required";

                document.getElementById("proposer_prix_neuf").style.display = "none";
                document.getElementById("proposer_plages_horaires").style.display = "inline-block";
                // document.getElementById("prix").attributes["required"] = "";

            } else if (document.getElementById('bien').checked) {
                document.getElementById("localisation-texte").innerHTML = "<span>2</span>Localisation du Bien";
                document.getElementById("select-categorie-services").style.display = "none";
                document.getElementById("select-categorie-biens").style.display = "inline-block";
                document.getElementById("proposer_prix_neuf").style.display = "inline-block";
                document.getElementById("proposer_plages_horaires").style.display = "none";
                // document.getElementById("prix").attributes["required"] = "required";
                document.getElementById("select-categorie-biens").attributes["required"] = "required";
                document.getElementById("select-categorie-services").attributes["required"] = "";


            }

            document.getElementById("deux").style.display = "block";
        })

    </script>


    <div class="map-container">
        <h1>Localisation</h1>
        <div id="map"></div>
        <div id="infowindow-content">
            <img src="images/geocode-71.png" width="16" height="16" id="place-icon">
            <span id="place-name" class="title"></span><br>
            <span id="place-address"></span>
        </div>
    </div>
</div>
<script type="text/javascript">
    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

    var adress = "";

    function initMap() {


        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 43.623118, lng: 3.870869},
            zoom: 9
        });
        var card = document.getElementById('pac-card');
        var input = document.getElementById('pac-input');
        // var types = document.getElementById('type-selector');
        // var strictBounds = document.getElementById('strict-bounds-selector');

        var infowindow = new google.maps.InfoWindow();


        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };


                // infowindow.setPosition(pos);
                // infowindow.setContent('Location found.');
                // infowindow.open(map);
                map.setCenter(pos);
                marker = new google.maps.Marker({
                    map: map,
                    anchorPoint: new google.maps.Point(0, -29)
                });
                marker.setPosition(pos);
                marker.setVisible(true);

                //Geocoder
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


        var marker = "";


        function geocodeLatLng(geocoder, map, infowindow, position) {
            // var input = document.getElementById('pac-input').value;
            // var latlngStr = input.split(',', 2);
            var latlng = {lat: parseFloat(position.coords.latitude), lng: parseFloat(position.coords.longitude)};
            geocoder.geocode({'location': latlng}, function (results, status) {
                if (status === 'OK') {
                    if (results[0]) {

                        document.getElementById("pac-input").value = results[0].formatted_address;
                        infowindowContent.children['place-name'].textContent = results[0].formatted_address;
                        map.setZoom(15);

                        infowindow.open(map, marker);

                        document.getElementById("lat-hidden").value = position.coords.latitude.toString();
                        document.getElementById("lng-hidden").value = position.coords.longitude.toString();

                    } else {
                        window.alert('No results found');
                    }
                } else {
                    window.alert('Geocoder failed due to: ' + status);
                }
            });
        }


        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);

        autocomplete.setFields(
            ['address_components', 'geometry', 'icon', 'name']);

        var infowindowContent = document.getElementById('infowindow-content');
        infowindow.setContent(infowindowContent);
        marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29)
        });


        autocomplete.addListener('place_changed', function () {
            infowindow.close();
            marker.setVisible(false);

            var place = autocomplete.getPlace();


            if (!place.geometry) {
                window.alert("Pas de details disponibles pour: '" + place.name + "'");
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
            document.getElementById("lat-hidden").value = autocomplete.getPlace().geometry.location.lat();
            document.getElementById("lng-hidden").value = autocomplete.getPlace().geometry.location.lng();


            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }

            infowindowContent.children['place-icon'].src = place.icon;
            infowindowContent.children['place-name'].textContent = place.name;
            infowindowContent.children['place-address'].textContent = address;
            infowindow.open(map, marker);


        });

    }


    // Vérification du formulaire avant envoi
    /**
     *
     */
    document.forms[0].addEventListener('submit', function (evt) {
        var file = document.getElementById('image').files[0];
        var pos = document.forms["form"]["lat"].value;

        document.getElementById('warning').innerHTML = "";

        if (pos == "") // Si l'utilisateur n'a pas sélectionné une adresse correcte, erreur
        {
            evt.preventDefault();
            document.getElementById('warning').innerHTML += "<br>L'adresse doit être correcte et proposée par Google";
            window.scrollTo(0, 0);
        }
        else if (file && file.size > 5000000) { // si l'utilisateur tente d'uploaded une image supérieur à 5Mo
            evt.preventDefault();
            document.getElementById('warning').innerHTML += "<br>Fichier trop gros";
            window.scrollTo(0, 0);
        } else {
            // On valide le formulaire et attribue une valeure en session afin d'éviter le raffraichissement de la page proposer_ok et l'ajout multiple de biens/services dans la BDD
            <?php
            $_SESSION['verif_proposer'] = true;
            ?>

        }


    }, false);


</script>


<script src="js/plages_horaires.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBwQxOi8hCzeOA9TfWU9wOq7DEG26kyuHk&libraries=places&callback=initMap"
        async defer></script>


</body>
</html>


