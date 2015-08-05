<?php
/**
 * Created by PhpStorm.
 * User: Legna Filloy
 * Date: 28/06/14
 * Time: 16:35
 */

$date = new DateTime('07-10-2014 01:00 GMT-0430');
echo $date->format('d-m-Y H:i:s');
echo"       ";

$aux =$date->format('d-m-Y H:i:s');
//$inicioEvento = $inicioEvento.' GMT-0430';

$myvar = strtotime($aux) * 1000;
echo $myvar;

echo"  --->>  ";


$dateTime = new DateTime(); 

$dateTime->setTimeZone(new DateTimeZone('-0430')); 

echo $dateTime->format('d-m-Y H:i:s');

/*$inicioEvento = $inicioEvento.' GMT-0430';
$finEvento = $finEvento.' GMT-0430';

$aux1 = strtotime($inicioEvento) * 1000;
$aux2 =  strtotime($finEvento) * 1000;*/


?>