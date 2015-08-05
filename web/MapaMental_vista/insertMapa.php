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
$idalumno = $_POST['idalumno'];
$idasignacion = $_POST['idasignacion'];
$titulo = $_POST['titulo'];
$contenido = $_POST['contenido'];


$result = mysqli_query($conn, "SELECT * FROM mapamental WHERE grupoEstudiante ='$idalumno' AND asignacion ='$idasignacion' AND tituloMapamental ='$titulo'");
if (mysqli_num_rows($result) > 0) {

    $sql = "UPDATE mapamental SET Contenido='$contenido' WHERE grupoEstudiante ='$idalumno'
            AND asignacion ='$idasignacion' AND tituloMapamental ='$titulo'
           ;";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $sql = "INSERT INTO mapamental (grupoEstudiante, asignacion, tituloMapamental,Contenido)
           VALUES ('$idalumno','$idasignacion','$titulo','$contenido');";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
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