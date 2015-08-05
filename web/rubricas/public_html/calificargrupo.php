 
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
$tipo=$_POST['tipo'];

if ($tipo== '1'){
$consulta="SELECT estudiante_id,seccion_id FROM estudianteseccion WHERE id='$estudiante'";
$result = mysqli_query($conn, $consulta);
$fila = mysqli_fetch_array($result);
$est=$fila['estudiante_id'];
$secc=$fila['seccion_id'];

$consulta2="SELECT grupo_id FROM estudiantegrupo WHERE estudiante_id='$est' AND seccion_id='$secc'";
$result2 = mysqli_query($conn, $consulta2);
$fila2 = mysqli_fetch_array($result2);
$grup=$fila2['grupo_id'];

$consulta3 = "SELECT estudiante_id FROM estudiantegrupo WHERE grupo_id='$grup'";
$result3 = mysqli_query($conn, $consulta3);
$error=false;

while ($fila3 = mysqli_fetch_array($result3)) {
   $nom=$fila['estudiante_id'];
       $sql = "INSERT INTO calificacion (asignacion_id,estudianteGrupo_id, estudianteSeccion_id, nota,total)
           VALUES ($asignacion,$grupo,$estudiante,'$nota','$manage');";

    if ($conn->query($sql) === TRUE) {
        $error=false;
    } else {
        $error=true;
        break;
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
echo "Se ha calificado satisfactoriamente";

}
else{
     $sql = "INSERT INTO calificacion (asignacion_id,estudianteGrupo_id, estudianteSeccion_id, nota,total)
           VALUES ($asignacion,$grupo,$estudiante,'$nota',$manage);";

    if ($conn->query($sql) === TRUE) {
        echo "Se ha calificado satisfactoriamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


$conn->close();
?>
 
 
  
