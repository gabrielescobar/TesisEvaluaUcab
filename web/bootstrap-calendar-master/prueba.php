<?php

require_once "db.Class.php";
$db = db::getInstancia("localhost","ucab","ucab","ucab");

$id= '<script type="text/javascript">'
   /*.'var name = usuario + "=";'
   .'var ca = document.cookie.split(";");'
   . ' for(var i=0; i<ca.length; i++) {'
   . ' var c = ca[i];'
   . 'while (c.charAt(0)==" "){ c = c.substring(1);}'
     . 'if (c.indexOf(name) != -1) return c.substring(name.length,c.length);'
       . '}'*/
         . ' </script>';
setcookie("test","test");
print_r($_COOKIE);
          
    
echo $id;

//En la consulta hay q agregar el join de estudiante -> evento con la union de los eventos del maestro para ese portafolio
$eventos = $db -> getQuery("SELECT * from evento ");
$eventosDelEstudiante =  $db -> getQuery("SELECT * from evento where estudiante_id = 69 ");  /*Todos los eventos creados por el estudiante*/

$seccionesDelEstudiante = $db->getQuery("SELECT seccion_id from seccion_estudiante where estudiante_id = 69"); /*Secciones a las que pertenece el estudiante*/


while ($fila = mysql_fetch_array($seccionesDelEstudiante, MYSQL_ASSOC)){
 
   $eventosDeSeccionesDelEstudiante =  $db -> getQuery("SELECT * from evento where seccion_id =".$fila['seccion_id']); /*Eventos por cada seccion a la que 
   																													   pertenece el estudiante*/

    while($all = mysql_fetch_assoc($eventosDeSeccionesDelEstudiante)){
    $e = array();
    $e['id'] = $all['id'];
    $e['start'] = $all['inicio'];
    $e['end'] = $all['fin'];
    $e['title'] = $all['titulo'];
    $e['class'] = $all['tipo'];
    $e['detail'] = $all ['detalle'];
    $result[] = $e;
}
    

}


while($all = mysql_fetch_assoc($eventosDelEstudiante)){
    $e = array();
    $e['id'] = $all['id'];
    $e['start'] = $all['inicio'];
    $e['end'] = $all['fin'];
    $e['title'] = $all['titulo'];
    $e['class'] = $all['tipo'];
    $e['detail'] = $all ['detalle'];
    $result[] = $e;
}

echo json_encode(array('success' => 1, 'result' => $result));


?>