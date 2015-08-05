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
$asignacion = $_POST['idasignacion'];


$result = mysqli_query($conn, "SELECT * FROM calificacion WHERE asignacion_id = '$asignacion' AND (estudianteSeccion_id='$estudiante' OR estudianteGrupo_id='$grupo')");
if (mysqli_num_rows($result) > 0) {

   echo "SI";
} else {
    echo "NO";
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