<?php
/**
 * Created by PhpStorm.
 * User: Remi
 * Date: 20/10/2018
 * Time: 17:43
 */


session_start();
require_once('config.php');



session_unset();

    $_SESSION['disconnect'] = true;
$_SESSION['warning'] = true;
    header ('location: ../index.php');

?>
