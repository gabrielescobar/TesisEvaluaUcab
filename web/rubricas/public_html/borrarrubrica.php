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

$result = mysqli_query($conn, "SELECT * FROM asignacion WHERE rubrica_id ='$id'");
if (mysqli_num_rows($result) > 0) {

    echo "<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Error! La rúbrica ya esta asociada a una asignación</div>";
} else {
    $sql = "DELETE FROM rubrica where id='$id';";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>La rúbrica fue eliminada exitosamente</div>";
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