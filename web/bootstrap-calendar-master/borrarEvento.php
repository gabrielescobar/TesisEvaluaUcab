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

$result = $db -> getQuery("DELETE FROM evento WHERE id = $idEvento");



?>