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
$tituloEvento = $_POST['tituloEvento'];
$detalleEvento = $_POST['detalleEvento'];
$tipoEvento = $_POST['tipoEvento'];
$inicioEvento = $_POST['inicioEvento'];
$finEvento =  $_POST['finEvento'];

$inicioEvento = $inicioEvento.' GMT-0430';
$finEvento = $finEvento.' GMT-0430';

$aux1 = strtotime($inicioEvento) * 1000;
$aux2 =  strtotime($finEvento) * 1000;


$result = $db -> getQuery("UPDATE evento SET titulo='$tituloEvento',detalle='$detalleEvento',
inicio='$aux1',fin='$aux2',tipo='$tipoEvento' WHERE id = $idEvento");






?>