<?php
/**
 * Created by PhpStorm.
 * User: Legna Filloy
 * Date: 28/06/14
 * Time: 16:35
 */

require_once "db.Class.php";
$db = db::getInstancia("localhost","ucab","ucab","ucab");

$tituloEvento = $_POST['tituloEvento'];
$detalleEvento = $_POST['detalleEvento'];
$tipoEvento = $_POST['tipoEvento'];
$inicioEvento = $_POST['inicioEvento'];
$finEvento =  $_POST['finEvento'];
$visible = 1;
$id =$_POST['id'];
$val =$_POST['val'];

$inicioEvento = $inicioEvento.' GMT-0430';
$finEvento = $finEvento.' GMT-0430';

$aux1 = strtotime($inicioEvento) * 1000;
$aux2 =  strtotime($finEvento) * 1000;

/*se puede insertar como estudiante o como seccion */
if ($val == 0){ //es estudiante
$result = $db -> getQuery("INSERT INTO evento (titulo,detalle,inicio,fin,tipo,estudiante_id,visible)
                            VALUES ('$tituloEvento','$detalleEvento','$aux1','$aux2','$tipoEvento','$id','$visible' )");

}else{ // es profesor
    $result = $db -> getQuery("INSERT INTO evento (titulo,detalle,inicio,fin,tipo,seccion_id,visible)
                            VALUES ('$tituloEvento','$detalleEvento','$aux1','$aux2','$tipoEvento','$id','$visible' )");

}




?>