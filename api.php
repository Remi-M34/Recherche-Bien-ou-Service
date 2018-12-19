<?php
/**
 * Created by PhpStorm.
 * User: Remi
 * Date: 22/10/2018
 * Time: 01:16
 */


 $terms = $_GET['data'];

 $data = file_get_contents("https://maps.googleapis.com/maps/api/place/autocomplete/json?input=".$terms."&types=geocode&key=AIzaSyB9FN_d6hYm8FSbk3WkSHxAUWVOclm9Tww");


 $arr = array();
 $i=0;
 foreach(json_decode($data)->predictions as $item){
     $arr[$i] = array(
         'id' => $i,
         'text' => $item->description
     );
     $i++;
 }

 echo json_encode($arr);

?>