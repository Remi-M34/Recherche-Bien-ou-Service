<?php
/**
 * Created by PhpStorm.
 * User: Remi MATTEI
 * Numéro étudiant: 21516143
 * Date: 31/10/2018
 * Time: 02:43
 *
 *
 */

include("config.php");
$stmt = "";


if (isset($_POST['update']) && $_POST['update'] == "supp") {
    $sql = "DELETE FROM {$_POST['type']} WHERE id={$_POST['id']}";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
//    error_log($sql);
} else if (isset($_POST['update']) && $_POST['update'] == "maj") {

    if ($_POST['colonne'] == "titre") {
        $stmt = $pdo->prepare("UPDATE {$_POST['type']} SET {$_POST['colonne']} = ? WHERE id=?");
    } else if ($_POST['colonne'] == "description") {
        $stmt = $pdo->prepare("UPDATE {$_POST['type']} SET {$_POST['colonne']} = ? WHERE id=?");
    } else if ($_POST['colonne'] == "categorie") {
        $stmt = $pdo->prepare("UPDATE {$_POST['type']} SET {$_POST['colonne']} = ? WHERE id=?");
    } else if ($_POST['colonne'] == "mots") {
        $stmt = $pdo->prepare("UPDATE {$_POST['type']} SET {$_POST['colonne']} = ? WHERE id=?");
    } else if ($_POST['colonne'] == "prix_neuf") {
        $stmt = $pdo->prepare("UPDATE {$_POST['type']} SET {$_POST['colonne']} = ? WHERE id=?");
    }
    $stmt->execute([$_POST['valeur'], $_POST['id']]);

}


if (!$stmt) {
    echo "\nPDO::errorInfo():\n";
    error_log(print_r($pdo->errorInfo()));
} else
    echo "ok";
