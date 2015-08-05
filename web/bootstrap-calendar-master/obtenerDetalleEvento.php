<?php
/**
 * Created by PhpStorm.
 * User: Legna Filloy
 * Date: 28/06/14
 * Time: 16:35
 */
require_once "db.Class.php";
$db = db::getInstancia("localhost","ucab","ucab","ucab");

$idEvento = $_POST['idEvento'];

$resultBD = $db -> getQuery("SELECT * FROM evento WHERE id = $idEvento");

$e = array();
while($all = mysql_fetch_assoc($resultBD)){

	$dateTimeInicio = new DateTime(); 
	$dateTimeFin = new DateTime(); 
    $dateTimeInicio->setTimeZone(new DateTimeZone('-0430')); 
    $dateTimeFin->setTimeZone(new DateTimeZone('-0430')); 
    
    $inicio = $all['inicio'] / 1000;
    $fin = $all['fin'] / 1000;

    $dateTimeInicio->setTimestamp($inicio);
    $dateTimeFin->setTimestamp($fin);


    $e['id'] = $all['id'];
    $e['start'] = $dateTimeInicio->format('Y-m-d')."T".$dateTimeInicio->format('H:i:s');
    $e['end'] = $dateTimeFin->format('Y-m-d')."T".$dateTimeFin->format('H:i:s');;
    $e['title'] = $all['titulo'];
    $e['class'] = $all['tipo'];
    $e['detail'] = $all ['detalle'];

}

echo json_encode($e);


?>