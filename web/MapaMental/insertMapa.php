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
$estudiante = $_POST['estudiante'];
$grupo = $_POST['grupo'];
$idasignacion = $_POST['idasignacion'];
$titulo = $_POST['titulo'];
$contenido = $_POST['contenido'];
$idmapa = $_POST['idmapa'];
$nombretil= json_decode($contenido,true);
$nombre=$nombretil["mindmap"]["root"]["text"]["caption"];
$aqui=$nombretil["mindmap"]["root"]["text"]["caption"];
$result = mysqli_query($conn, "SELECT id FROM mapamental WHERE estudiante_id='$idalumno' AND nombre='$aqui'");
if (mysqli_num_rows($result) > 0) {
$fila=mysqli_fetch_array($result); 
$prueba= $fila['id'];
    $sql = "UPDATE mapamental SET Contenido='$contenido',nombre='$nombre',tituloMapamental ='$titulo',asignacion_id =$idasignacion,estudianteGrupo_id=$grupo,estudianteSeccion_id =$estudiante
            WHERE id='$prueba'
           ;";

    if ($conn->query($sql) === TRUE) {
        echo "Mapa Actualizado Exitosamente";
    } else {
        echo "Error, Ya existe un mapa para esta asignación";
    }
} else {
    $sql = "INSERT INTO mapamental (estudianteSeccion_id,estudianteGrupo_id,estudiante_id, asignacion_id, tituloMapamental,Contenido,nombre)
           VALUES ($estudiante,$grupo,$idalumno,$idasignacion,'$titulo','$contenido','$nombre');";

    if ($conn->query($sql) === TRUE) {
        echo "Mapa Creado Exitosamente";
    } else {
         echo $estudiante." ".$grupo." ".$idalumno." ".$idasignacion." ".$titulo." ".$contenido." ".$nombre;
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