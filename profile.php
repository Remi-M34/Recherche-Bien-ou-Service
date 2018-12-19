<!DOCTYPE html>
<html>
<head>

    <title>Profil</title>

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
 * User: Remi
 * Date: 24/10/2018
 * Time: 23:38
 */
session_start();

require_once("controllers/config.php");
//include("class/utilisateur.php");
include("header.php");




// Page inaccessible pour les invités
if (!isset($_SESSION['id'])) {
    header("Location: inscription.php");
    exit();
}

$utilisateur = new utilisateur($_SESSION['id'],$pdo);


if (isset($_POST['save']) || isset($_POST['supprimer-avatar'])) {
//    $_SESSION['profil-update'] = true;
//    $_SESSION['notification'] = true;
    $notif = new notification("Profil mis à jour correctement", "notice_success");
}


if (isset($_POST['save'])) {

    if ($utilisateur->checkEmail($_POST['email']) == "erreur")
    {
        $notif = new notification("L'adresse email n'a pas pu être mise à jour car elle est déjà utilisée par un autre utilisateur.", "notice_warning");
    }
    else
    {
        $utilisateur->setEmail($_POST['email']);

    }

    $utilisateur->setAdresse($_POST['adresse']);
    $utilisateur->setNom($_POST['nom']);
    $utilisateur->setPrenom($_POST['prenom']);
    $utilisateur->setId($_SESSION['id']);
    $_SESSION['email'] = $_POST['email'];


    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $utilisateur->setPassword(md5($_POST['password']));
        $utilisateur->updatePassword();
    }

    $utilisateur->updateUser();

}



if (isset($_FILES['avatar']) && $_FILES["avatar"]["tmp_name"]) {
    $target_dir = "images/avatars/";
    $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $target_file = $target_dir . basename($_SESSION['id']) . "." . $imageFileType;

// Vérifier si l'image est correcte
    $check = getimagesize($_FILES["avatar"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $notif = new notification("Le fichier n'est pas une image", "notice_warning");
        $uploadOk = 0;
    }

    // Vérifier si le fichier n'existe pas déjà. si oui, on le supprime pour placer la nouvelle photo de profil
    if (file_exists($target_file)) {
        unlink($target_file);
    }
    if ($_FILES["avatar"]["size"] > 5000000) {
        $notif = new notification("Image trop large (> 5Mo)", "notice_warning");
        $uploadOk = 0;
    }
// Certains formats uniquement
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        $notif = new notification("La photo de profil n'a pas pu être mise à jour.<br> Formats autorisés : png, jpeg, gif", "notice_warning");
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
//        echo "Erreur lors de l'upload";

    } else {
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            $utilisateur->setAvatar(basename($_SESSION['id']) . "." . $imageFileType);
            $utilisateur->updateUser();
            $notif = new notification("Photo de profil mise à jour correctement", "notice_success");

        } else {
            $notif = new notification("Erreur?", "notice_warning");
        }
    }

}

// On supprime l'avatar
if (isset($_POST['supprimer-avatar'])) {

    $utilisateur->setAvatar("default.png");
    $utilisateur->updateUser();


    if ($utilisateur->getAvatar() != "default.png") {
        $chemin = "images/avatars/" . $utilisateur->getAvatar();
        unlink($chemin);
    }
}


?>

<div class="page-profil page">


    <div id="form-edit-profile">
        <h1>Modifier le profil</h1>

        <div class="form-edit-profile-container">

            <form action="profile.php" method="post" id="form-profile" enctype="multipart/form-data">

                <label for="email">Adresse e-mail (identifiant)<input id="email" type="email" name="email" readonly="readonly"></label>
                <label for="password">Mot de passe<input type="password" name="password" id="password"></label>
                <label for="password2">Vérification mot de passe<input type="password" name="password2" id="password2"
                                                                       oninput="check(this)"></label>
                <hr>
                <label for="nom">Nom<input type="text" name="nom" id="nom"></label>
                <label for="prenom">Prénom<input type="text" name="prenom" id="prenom"></label>

                <label for="adresse">Adresse postale<input type="text" name="adresse" id="adresse" id="adresse"></label>

                <label for="telephone">Téléphone<input type="tel" name="telephone" id="telephone"></label>
                <hr>

                <label for="avatar">Modifier l'image de profil<input type="file" name="avatar"></label>
                <input type="submit" name="save" value="Sauvegarder les changements">


            </form>
        </div>
    </div>
    <div class="card">
        <h1>Avatar actuel</h1>

        <img src="images/avatars/<?php echo $utilisateur->getAvatar(); ?>" alt="avatar" style="width:100%">
        <form action="profile.php" method="post">
            <p>
                <button type="submit" name="supprimer-avatar">Supprimer</button>
        </form>
    </div>
</div>

<script>

    // Pour s'assurer que les mots de passe correspondent
    function check(input) {
        if (input.value != document.getElementById('password').value) {
            input.setCustomValidity('Les mots de passe ne correspondent pas !');
        } else {
            input.setCustomValidity('');
        }
    }


    document.getElementById("email").value = "<?php echo $utilisateur->getEmail(); ?>";
    document.getElementById("nom").value = "<?php echo $utilisateur->getNom(); ?>";
    document.getElementById("prenom").value = "<?php echo $utilisateur->getPrenom(); ?>";
    document.getElementById("adresse").value = "<?php echo $utilisateur->getAdresse(); ?>";
    document.getElementById("telephone").value = "<?php echo $utilisateur->getTelephone(); ?>";
    document.getElementById("password").value = "<?php echo $utilisateur->getTelephone(); ?>";
    document.getElementById("password2").value = "<?php echo $utilisateur->getTelephone(); ?>";


    var input = document.getElementById('adresse');
    var autocomplete = new google.maps.places.Autocomplete(input);
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace();
    })

</script>