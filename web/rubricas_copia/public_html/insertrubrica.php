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
$total = $_POST['total'];
$profesor= $_POST['profesor'];


$result = mysqli_query($conn, "SELECT * FROM rubrica WHERE titulo ='$titulo'");
if (mysqli_num_rows($result) > 0) {

    echo "Error, No puede crear una rubrica con el mismo nombre";
} else {
    $sql = "INSERT INTO rubrica (titulo, contenido,total,profesor_id)
           VALUES ('$titulo','$rubrica','$total','$profesor');";

    if ($conn->query($sql) === TRUE) {
        echo "Su rubrica fue creada exitosamente";
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