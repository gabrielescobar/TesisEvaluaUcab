 
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
$nota= json_encode($_POST['data']);
$manage = array_sum ( (array)($_POST['data']));
$estudiante = $_POST['estudiante'];
$grupo = $_POST['grupo'];
$asignacion = $_POST['asignacion'];


    $sql = "INSERT INTO calificacion (asignacion_id,estudianteGrupo_id, estudianteSeccion_id, nota,total)
           VALUES ('$asignacion','$grupo','$estudiante','$nota',$manage);";

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
 
 
  
