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
$id = $_POST['id'];

$result = mysqli_query($conn, "SELECT * FROM asignacion WHERE lista_id ='$id'");
if (mysqli_num_rows($result) > 0) {

} else {
    $sql = "DELETE FROM rubrica where id='$id';";

    if ($conn->query($sql) === TRUE) {

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