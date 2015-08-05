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



$result = mysqli_query($conn, "SELECT * FROM rubrica WHERE titulo ='$titulo'");


    
    $sql = "UPDATE rubrica SET contenido='$rubrica', titulo='$titulo', total='$total'  WHERE id='$id'
           ;";

    if ($conn->query($sql) === TRUE) {
        echo "Su rubrica fue modificada exitosamente";
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