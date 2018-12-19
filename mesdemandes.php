<!DOCTYPE html>
<html>
<head>
    <title>Proposer</title>
    <link rel="stylesheet" href="../public_html/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/themes/base/jquery-ui.css" rel="stylesheet"
          type="text/css"/>
    <script src="js/jquery-3.3.1.js"></script>

</head>
<body>


<?php
session_start();
include("header.php");


?>

<div class="proposer_map_form_container" id="page">
    <div class="formulaire_proposer">
        <h1>Ajout d'une proposition<span>Ajoutez un bien ou un service!</span></h1>
        <form name="form" method="post" action="proposer_ok.php" onkeypress="return event.keyCode != 13;"
              enctype="multipart/form-data">
            <!--         Pour eviter que le formulaire soit envoyé quand l'utilisateur fait entrée-->

            <div class="section"><span>1</span>Type de bien</div>
            <div class="formulaire_proposer_rubrique" id="un">
                Vous proposez : <br><br>
                <label for="bien"><input type="radio" name="type" value="bien" id="bien" required="required"> Un
                    bien</label>
                <label for="service"> <input type="radio" name="type" value="service" id="service"> Un service</label>
            </div>
            <div class="section" id="localisation-texte"><span>2</span>Localisation</div>

            <input id="lat-hidden" name="lat" type="hidden">
            <input id="lng-hidden" name="lng" type="hidden">


            <div class="formulaire_proposer_rubrique" id="deux">
                <label for="pac-input">Adresse<input id="pac-input" required="required" type="text"
                                                     name="field1"/></label>
            </div>

            <div class="section"><span>3</span>Catégorie</div>


            <div class="formulaire_proposer_rubrique" id="trois">
                <span id="choisir">Veuillez sélectionner une catégorie</span>

                <select id="select-categorie-biens" name="select-categorie-biens">
                    <?php
                    require_once("config.php");

                    $sql = "SELECT nom, path, id FROM categorie where path like 'biens%'";
                    foreach ($pdo->query($sql) as $ligne) {
                        echo "<option value='{$ligne['id']}'>{$ligne['nom']}</option>";
                    }
                    ?>
                </select>
                <select id="select-categorie-services">
                    <?php
                    $sql = "SELECT nom, id, path FROM categorie where path like 'services%'";
                    foreach ($pdo->query($sql) as $ligne) {
                        echo "<option value='{$ligne['id']}'>{$ligne['nom']}</option>";
                    }
                    ?>
                </select>

            </div>


            <div class="section"><span>4</span>Titre, description</div>
            <div class="formulaire_proposer_rubrique" id="cinq">

                <label>Donnez un titre à votre proposition<br>
                    <input type="texte" name="titre" required="required"></label>

                <label>... et une description !<br>
                    <textarea required="required" id="proposer-description" class="text" minlength="15" rows="7"
                              name="description"
                              placeholder="Vous devez rédiger une description"></textarea></label><br>

                <label>Mots clés<br>
                    <input type="texte" name="mots"></label>


            </div>


            <div class="section"><span>5</span>Image</div>
            <div class="formulaire_proposer_rubrique" id="quatre">
                Vous pouvez également ajouter une image (jpeg, png)<br><br>
                <input class="inputfile" type="file" id="image" name="image" accept="image/png, image/jpeg, image/jpg"/>
                <label class="labelinputfile" for="image"></label>

            </div>


            <div class="button-section">
                <input type="submit" name="submit"/>
                <span class="privacy-policy">
     <input id="valider_proposition" type="checkbox" name="field7">You agree to our Terms and Policy.
     </span>
            </div>
        </form>
    </div>


    <script>
        $('input[name="type"]').on("click", function (e) {
            document.getElementById("choisir").style.display = "none";
            if (document.getElementById('service').checked) {
                document.getElementById("localisation-texte").innerHTML = "<span>2</span>Localisation du Service";
                document.getElementById("select-categorie-biens").style.display = "none";
                document.getElementById("select-categorie-services").style.display = "inline-block";
            } else if (document.getElementById('bien').checked) {
                document.getElementById("localisation-texte").innerHTML = "<span>2</span>Localisation du Bien";
                document.getElementById("select-categorie-services").style.display = "none";
                document.getElementById("select-categorie-biens").style.display = "inline-block";
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

                        //  marker = new google.maps.Marker({
                        //     position: latlng,
                        //     map: map
                        // });
                        // infowindow.setContent(results[0].formatted_address);
                        // infowindow.open(map, marker);
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

        // Bind the map's bounds (viewport) property to the autocomplete object,
        // so that the autocomplete requests use the current map bounds for the
        // bounds option in the request.
        autocomplete.bindTo('bounds', map);

        // Set the data fields to return when the user selects a place.
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
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
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


    document.forms[0].addEventListener('submit', function (evt) {
        var file = document.getElementById('image').files[0];
        var pos = document.forms["form"]["lat"].value;

        document.getElementById('warning').innerHTML = "";

        if (pos == "") // Si l'utilisateur n'a pas sélectionné une adresse correcte
        {
            evt.preventDefault();
            document.getElementById('warning').innerHTML += "<br>L'adresse doit être correcte et proposée par Google";
            window.scrollTo(0, 0);
        }
        else if (file && file.size > 5000000) { // l'utilisateur tente d'uploaded une image supérieur à 5Mo
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


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBwQxOi8hCzeOA9TfWU9wOq7DEG26kyuHk&libraries=places&callback=initMap"
        async defer></script>


</body>
</html>


