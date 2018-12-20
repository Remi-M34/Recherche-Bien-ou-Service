<?php
/**
 * Created by PhpStorm.
 * User: Remi MATTEI
 * Numéro étudiant: 21516143
 * Date: 15/12/2018
 * Time: 01:44
 */


include("../class/utilisateur.php");
require_once("config.php");
session_start();
//error_log("id:" . $_POST['id'] . "--------- colonne : " . $_POST['colonne'] . "--------val:" . $_POST['valeur']);
$data = [];


$thisuser = unserialize($_SESSION['user']);

$sql;
$id_emprunteur = $thisuser->getId();
$id_emprunte = $_POST['id_emprunte'];
$date_debut = "STR_TO_DATE('" . $_POST['date_debut'] . " ". $_POST['heure_debut'] ."','%d/%m/%Y %H')";
$date_fin = "STR_TO_DATE('" . $_POST['date_fin'] . " ". $_POST['heure_fin'] ."','%d/%m/%Y %H')";
$id = $_POST['id'];
$cout = $_POST['cout'];
$cout_total = $_POST['cout_total'];

$sql = "INSERT INTO emprunt (id_emprunte, id_emprunteur, id_bien_service, cout, cout_total, date_debut, date_fin) values (?,?,?,?,?,{$date_debut},{$date_fin})";

try{

    $stmt = $pdo->prepare("SELECT ratioCheck({$id_emprunteur}) as ratio, maxEmpruntsCheck({$id_emprunteur}) as emprunts");
    $stmt->execute([]);
    $data = $stmt->fetch();
    if (!($data['ratio'] && $data['emprunts']) || $data['ratio'] == null || $data['emprunts'] == null) {
        if (!($data['ratio'])) {
            echo "<br>Ratio trop élevé";
        }
        if (!($data['emprunts'])) {
            echo "<br>Nombre d'emprunts maximum atteint";
        }
        exit(0);
    }

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_emprunte, $id_emprunteur, $id, $cout, $cout_total]);

//error_log($sql);
$thisuser = new utilisateur($id_emprunteur,$pdo);
$_SESSION['user'] = serialize($thisuser);


echo "ok";
}
catch(PDOException $e)
    {
        echo "erreur";
    }