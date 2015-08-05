 
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
//$_POST['estudiante'].$_POST['grupo'].$_POST['asignacion']
$nota= ($_POST['data']);
$manage = array_sum ( (array) json_decode($_POST['data']));
$estudiante = $_POST['estudiante'];
$grupo = $_POST['grupo'];
$asignacion = $_POST['asignacion'];


  $sql = "UPDATE calificacion SET total='$manage',nota='$nota'  WHERE asignacion_id = '$asignacion' AND (estudianteSeccion_id='$estudiante' OR estudianteGrupo_id='$grupo')
           ;";
    if ($conn->query($sql) === TRUE) {
        echo "Se ha calificado satisfactoriamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }


//
//$sql = "INSERT INTO presentacion (grupoEstudiante, asignacion, tituloPresentacion,Contenido)
//VALUES ('$idalumno','$idasignacion','$titulo','$contenido');";
//
//if ($conn->query($sql) === TRUE) {
//    echo "New record created successfully";
//} else {
//    echo "Error: " . $sql . "<br>" . $conn->error;
//}

$conn->close();
?>
 
 
  
