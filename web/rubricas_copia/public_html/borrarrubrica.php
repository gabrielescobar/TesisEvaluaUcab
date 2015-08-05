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
$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM asignacion WHERE rubrica_id ='$id'");
if (mysqli_num_rows($result) > 0) {

    header("Location: http://evaluaucab.edu/app_dev.php/profesor/rubricas?val=0");
     die();
} else {
    $sql = "DELETE FROM rubrica where id='$id';";

    if ($conn->query($sql) === TRUE) {
       header("Location: http://evaluaucab.edu/app_dev.php/profesor/rubricas?val=1");
        die();
        
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