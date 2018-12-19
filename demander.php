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
/**
 * Created by PhpStorm.
 * User: Remi
 * Date: 29/10/2018
 * Time: 22:40
 */




session_start();
include("header.php");
include("config.php");





?>

<div class="mes_propositions">


    <div class="mes_propositions_limiter">
        <div class="mes_propositions_container_tableau">
            <div class="mes_propositions_wrap_tableau">
                <div class="table100">
                    <table>
                        <thead>
                        <tr class="table100-head">
                            <th class="column1">Date</th>
                            <th class="colonne2">Order ID</th>
                            <th class="column3">Name</th>
                            <th class="column4">Price</th>
                            <th class="column5">Quantity</th>
                            <th class="column6">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="column1"><img src="images/user1.png" width="150px"></td>
                            <td class="colonne2"><input type="text"></td>
                            <td class="column3"><textarea rows="10"></textarea> </td>
                            <td class="column4"><select>
                                    <option value="one"></option>
                                    <option value="eeeeeeeeeee"></option>
                                </select></td>
                            <td class="column5">1</td>
                            <td class="column6">$999.00</td>
                        </tr>
                        <tr>
                            <td class="column1">2017-09-28 05:57</td>
                            <td class="colonne2">200397</td>
                            <td class="column3">Samsung S8 Black</td>
                            <td class="column4">$756.00</td>
                            <td class="column5">1</td>
                            <td class="column6">$756.00</td>
                        </tr>
                        <tr>
                            <td class="column1">2017-09-26 05:57</td>
                            <td class="colonne2">200396</td>
                            <td class="column3">Game Console Controller</td>
                            <td class="column4">$22.00</td>
                            <td class="column5">2</td>
                            <td class="column6">$44.00</td>
                        </tr>
                        <tr>
                            <td class="column1">2017-09-25 23:06</td>
                            <td class="colonne2">200392</td>
                            <td class="column3">USB 3.0 Cable</td>
                            <td class="column4">$10.00</td>
                            <td class="column5">3</td>
                            <td class="column6">$30.00</td>
                        </tr>
                        <tr>
                            <td class="column1">2017-09-24 05:57</td>
                            <td class="colonne2">200391</td>
                            <td class="column3">Smartwatch 4.0 LTE Wifi</td>
                            <td class="column4">$199.00</td>
                            <td class="column5">6</td>
                            <td class="column6">$1494.00</td>
                        </tr>
                        <tr>
                            <td class="column1">2017-09-23 05:57</td>
                            <td class="colonne2">200390</td>
                            <td class="column3">Camera C430W 4k</td>
                            <td class="column4">$699.00</td>
                            <td class="column5">1</td>
                            <td class="column6">$699.00</td>
                        </tr>
                        <tr>
                            <td class="column1">2017-09-22 05:57</td>
                            <td class="colonne2">200389</td>
                            <td class="column3">Macbook Pro Retina 2017</td>
                            <td class="column4">$2199.00</td>
                            <td class="column5">1</td>
                            <td class="column6">$2199.00</td>
                        </tr>
                        <tr>
                            <td class="column1">2017-09-21 05:57</td>
                            <td class="colonne2">200388</td>
                            <td class="column3">Game Console Controller</td>
                            <td class="column4">$999.00</td>
                            <td class="column5">1</td>
                            <td class="column6">$999.00</td>
                        </tr>
                        <tr>
                            <td class="column1">2017-09-19 05:57</td>
                            <td class="colonne2">200387</td>
                            <td class="column3">iPhone X 64Gb Grey</td>
                            <td class="column4">$999.00</td>
                            <td class="column5">1</td>
                            <td class="column6">$999.00</td>
                        </tr>
                        <tr>
                            <td class="column1">2017-09-18 05:57</td>
                            <td class="colonne2">200386</td>
                            <td class="column3">iPhone X 64Gb Grey</td>
                            <td class="column4">$999.00</td>
                            <td class="column5">1</td>
                            <td class="column6">$999.00</td>
                        </tr>
                        <tr>
                            <td class="column1">2017-09-22 05:57</td>
                            <td class="colonne2">200389</td>
                            <td class="column3">Macbook Pro Retina 2017</td>
                            <td class="column4">$2199.00</td>
                            <td class="column5">1</td>
                            <td class="column6">$2199.00</td>
                        </tr>
                        <tr>
                            <td class="column1">2017-09-21 05:57</td>
                            <td class="colonne2">200388</td>
                            <td class="column3">Game Console Controller</td>
                            <td class="column4">$999.00</td>
                            <td class="column5">1</td>
                            <td class="column6">$999.00</td>
                        </tr>
                        <tr>
                            <td class="column1">2017-09-19 05:57</td>
                            <td class="colonne2">200387</td>
                            <td class="column3">iPhone X 64Gb Grey</td>
                            <td class="column4">$999.00</td>
                            <td class="column5">1</td>
                            <td class="column6">$999.00</td>
                        </tr>
                        <tr>
                            <td class="column1">2017-09-18 05:57</td>
                            <td class="colonne2">200386</td>
                            <td class="column3">iPhone X 64Gb Grey</td>
                            <td class="column4">$999.00</td>
                            <td class="column5">1</td>
                            <td class="column6">$999.00</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">

    document.getElementsByClassName("column1").onclick =

</script>