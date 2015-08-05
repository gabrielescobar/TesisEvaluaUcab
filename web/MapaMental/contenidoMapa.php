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
$titulo=$_POST['titulo'];
$sql = "SELECT Contenido FROM presentacion where tituloMapamental='$titulo'";

$result = mysqli_query($conn, $sql);
$fila=mysqli_fetch_array($result); 
   

$prueba= $fila['Contenido'];
printf($prueba);
$conn->close();
?>