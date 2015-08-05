<?php
/**
 * Created by PhpStorm.
 * User: Legna Filloy
 * Date: 28/06/14
 * Time: 16:35
 */

$inicioEvento = $_POST['inicioEvento'];
$finEvento =  $_POST['finEvento'];

$datetime1 = new DateTime($inicioEvento);
$datetime2 = new DateTime($finEvento);

$interval = $datetime1->diff($datetime2);

$val=0;

  if( $interval->format('%R') == "+"){
    $val = 1;
  }

echo $val;


?>