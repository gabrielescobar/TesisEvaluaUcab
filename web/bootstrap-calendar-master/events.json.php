<?php
require_once "db.Class.php";
$db = db::getInstancia("localhost", "ucab", "ucab", "ucab");
$id = $_GET['id']; //Se comporta como idEstudiante o idSeccion
$val = $_GET['val'];  //variable isProfesor q identifica si es estudiante(0) o profesor(1)
/* Es mejor q para el profesor los calendarios se manejan independientes, es decir, un calendario por seccion */
//En la consulta hay q agregar el join de estudiante -> evento con la union de los eventos del maestro para ese portafolio
/* Agregar el if de si es profesor o alumno, si es alumno se deja como esta con estas dos instrucciones
 * q estan abajo. Si es profesor se coloca un else y nada mas se hace un select * de los eventos de esa seccion, reutilizando
 * el mismo parametro id, ya q va a variar su contenido dependiendo de quien haga la llamada inicial de iframe 
 */
if ($val == 0) { //Si es estudiante
    $eventosDelEstudiante = $db->getQuery("SELECT * from evento where estudiante_id =" . $id);  /* Todos los eventos creados por el estudiante */
    $seccionesDelEstudiante = $db->getQuery("SELECT seccion_id from estudianteseccion where estudiante_id =" . $id); /* Secciones a las que pertenece el estudiante  */
    while ($fila = mysql_fetch_array($seccionesDelEstudiante, MYSQL_ASSOC)) {
        $eventosDeSeccionesDelEstudiante = $db->getQuery("SELECT * from evento where evento.visible = 1 and seccion_id =" . $fila['seccion_id']); /* Eventos por cada seccion a la que 
          pertenece el estudiante */
        while ($all = mysql_fetch_assoc($eventosDeSeccionesDelEstudiante)) {
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
    while ($all = mysql_fetch_assoc($eventosDelEstudiante)) {
        $e = array();
        $e['id'] = $all['id'];
        $e['start'] = $all['inicio'];
        $e['end'] = $all['fin'];
        $e['title'] = $all['titulo'];
        $e['class'] = $all['tipo'];
        $e['detail'] = $all ['detalle'];
        $result[] = $e;
    }
} else { //Si es profesor
    $eventosSeccion = $db->getQuery("SELECT * from evento where seccion_id =" . $id);
    while ($all = mysql_fetch_assoc($eventosSeccion)) {
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
echo json_encode(array('success' => 1, 'result' => $result));
?>