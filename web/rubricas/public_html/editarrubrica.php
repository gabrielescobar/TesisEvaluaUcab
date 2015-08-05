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
$rubrica = $_POST['rubrica'];
$titulo = $_POST['titulo'];
$id = $_POST['id'];
$total = $_POST['total'];
$response_array=[];


$result = mysqli_query($conn, "SELECT * FROM asignacion a,calificacion c,rubrica r WHERE a.rubrica_id =r.id AND a.id=c.asignacion_id");
if (mysqli_num_rows($result) > 0) {
    $response_array['status'] = 'error';
    $response_array['mess'] =  "La rubrica ya esta asociada a una calificación";
} else {
    $sql = "UPDATE rubrica SET contenido='$rubrica', titulo='$titulo', total='$total'  WHERE id='$id';";
    if ($conn->query($sql) === TRUE) {
        $response_array['status'] = 'success'; 
        $response_array['mess'] = "Su rubrica fue modificada exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
header('Content-type: application/json');
    echo json_encode($response_array);
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
    
