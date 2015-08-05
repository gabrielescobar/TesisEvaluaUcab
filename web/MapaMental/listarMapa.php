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
$idestudiante = $_POST['idestudiante'];

$sql = "SELECT * FROM mapamental WHERE estudiante_id='$idestudiante'";
$result = mysqli_query($conn, $sql);
$pila=[];


while ($fila = mysqli_fetch_array($result)) {
   
array_push($pila, $fila['contenido']);
}
$prueba= json_encode($pila);
printf($prueba);
$conn->close();
?>