<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once "db.Class.php";
$db = db::getInstancia("localhost", "ucab", "ucab", "ucab");
$id = $_POST['id']; //idEvento 
$val = $_POST['val'];  //Estudiante (0) Profesor(1)

$evento = $db->getQuery("SELECT * from evento where id =" . $id);

$var =0;
    while ($all = mysql_fetch_assoc($evento)) {
     
        if($val === '0'){
            
            if( $all['seccion_id'] == null){ // si es null lo puedo borrar, retorno 1 
              $var = 1;  
           } 
            
        } else if ($val === '1'){
            
            if(($all['examenPortafolio_id'] == null) && ($all['asignacion_id'] == null) && ($all['estudiante_id'] == null)){
                //quiere decir q es un evento creado directamente por el profesor,entonces si se puede borrar y modificar
           $var = 1;
            }else if (($all['examenPortafolio_id'] != null) || ($all['asignacion_id'] != null)) {
                //quiere decir q es un evento automatico del sistema por una asignacion o un examen,entonces no se puede borrar
                $var =0;
            }
            
            
        }
        
       
    }
    
    echo $var;

?>
