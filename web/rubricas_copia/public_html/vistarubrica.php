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

$sql = "SELECT contenido FROM rubrica where id = '$id'";
$result = mysqli_query($conn, $sql);
$pila=[];
$fila = mysqli_fetch_array($result);
echo ($fila['contenido']);

//while ($fila = mysqli_fetch_array($result)) {
//   
//array_push($pila, $fila['Contenido']);
//}
//$prueba= json_encode($pila);
//printf($prueba);
$conn->close();
?>