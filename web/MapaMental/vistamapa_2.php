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
$idasignacion = $_POST['idasignacion'];

$sql = "SELECT contenido FROM mapamental where asignacion_id =$idasignacion AND (estudianteGrupo_id=$grupo OR estudianteSeccion_id =$estudiante)";


$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0){
$pila=[];
$fila = mysqli_fetch_array($result);
//echo $fila['contenido'];
$manage =  json_decode($fila['contenido'], true);

echo ('{"root":'.json_encode($manage["mindmap"] ["root"])).'}';
}
else {
    echo ('{"root": {"id": "1e348414-4b48-419e-b00e-5e07f443fcb5","parentId": null,"text": {"caption": "Idea Principal","font": {"style": "normal","weight": "bold","decoration": "none","size": 20,"color": "#000000"}},"offset": {"x": 0,"y": 0},"foldChildren": false,"branchColor": "#000000","children": []}}');
}
$conn->close();
?>