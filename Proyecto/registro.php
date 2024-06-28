<?php
$servername = "SG-Cinepolis-equipo-1-8696-mysql-master.servers.mongodirector.com";
$username = "sgroot";
$password = "CZHEl@zTU5Bnpg5K";
$dbname = "seguridad"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (fullname, username, password) VALUES ('$fullname', '$username', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>