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

$sql = "SELECT contenido FROM mapamental where id = '$id'";
$result = mysqli_query($conn, $sql);
$pila=[];
$fila = mysqli_fetch_array($result);
//echo $fila['contenido'];
$manage =  json_decode($fila['contenido'], true);

echo ('{"root":'.json_encode($manage["mindmap"] ["root"])).'}';
$conn->close();
?>