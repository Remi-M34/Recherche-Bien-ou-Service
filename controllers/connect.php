<?php
/**
 * Created by PhpStorm.
 * User: Remi
 * Date: 20/10/2018
 * Time: 17:40
 */


include ("../class/utilisateur.php");
session_start();
require_once('config.php');

$user;
if(!isset($_POST['username']) && !isset($_POST['password']))
{
    header('Location: ../index.php');
    Exit;
}
else
{
    $_SESSION['warning'] = true;

    $username =$_POST['username'];
    $password =md5($_POST['password']);

//    $sql = "SELECT login, id FROM utilisateur WHERE login = ? and password = ?";
//    $stmt = $pdo->prepare($sql);
//    $stmt->execute([$username, $password]);

    $user = new utilisateur($username,$pdo);

    //si l'utilisateur est trouvé dans la BDD
    if ($user->getExiste() == true && $user->getPassword() == $password) {
        $_SESSION['email'] = $username;
        $_SESSION['id'] = $user->getId();
        $_SESSION['connect'] = true;

    }
    else // Utilisateur et mdp non trouvés
    {
        if ($user->getExiste() == true) // password non valide
        {
            $_SESSION['attempt'] = true;
            header('location:../inscription.php');
            exit();
        }
        else //inscription du membre
        {
            $user->setByEmail($username,$password);

            $_SESSION['email'] = $username;
            $_SESSION['id'] = $user->getId();
            $_SESSION['inscription'] = true;
//            header('location:../index.php');
//            exit();

        }


    }

    $_SESSION['user'] = serialize($user);
    header('location:../index.php');
    exit();


}
?>
