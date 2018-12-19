<?php
/**
 * Created by PhpStorm.
 * User: Remi
 * Date: 20/10/2018
 * Time: 17:37
 */



if (!isset($pdo))
static $pdo = null;
try {
    if (is_null($pdo)) {
        $pdo = new PDO('mysql:host=mysql-5.nextwab.com;dbname=PL_7783_db;charset=UTF8', "PL7783_admin", "HlinPass");
        $pdo->setAttribute(PDO::ATTR_PERSISTENT,true);
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        $stmt = $pdo->prepare("UPDATE stats SET nombre = nombre + 1 WHERE stat LIKE ?");
        $stmt->execute(['connexions_ouvertes']);

    }
    else
        echo "ooo";


}
catch (Exception $e)
{
    die('<br />Erreur : ' . $e->getMessage());
}






?>
