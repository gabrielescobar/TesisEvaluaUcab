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

$estudiante = $_POST['estudiante'];
$grupo = $_POST['grupo'];
$asignacion = $_POST['asignacion'];

$sql = "SELECT nota FROM calificacion WHERE asignacion_id = '$asignacion' AND (estudianteSeccion_id='$estudiante' OR estudianteGrupo_id='$grupo')";
$result = mysqli_query($conn, $sql);
$pila=[];
$fila = mysqli_fetch_array($result);
echo ($fila['nota']);

//while ($fila = mysqli_fetch_array($result)) {
//   
//array_push($pila, $fila['Contenido']);
//}
//$prueba= json_encode($pila);
//printf($prueba);
$conn->close();

?>