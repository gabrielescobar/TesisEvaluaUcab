<?php
$servername = "localhost";
$username = "ucab";
$password = "ucab";
$dbname = "ucab";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$estudiante = $_POST['seccion'];
$grupo = $_POST['grupo'];
$alumno = $_POST['idestudiante'];
$idasignacion = $_POST['idasignacion'];

$sql = "SELECT contenido,estudiante_id FROM mapamental where asignacion_id = $idasignacion AND (estudianteGrupo_id IN (select id from estudiantegrupo where grupo_id =$grupo ) OR estudianteSeccion_id =$estudiante)";
$result = mysqli_query($conn, $sql);
$pila=[];
$fila = mysqli_fetch_array($result);
//echo $fila['contenido'];
$manage =  json_decode($fila['contenido'], true);

echo ('{"root":'.json_encode($manage["mindmap"] ["root"])).'}';
$conn->close();
?>