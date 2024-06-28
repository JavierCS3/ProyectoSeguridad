<?php
session_start();

$servername = "SG-Cinepolis-equipo-1-8696-mysql-master.servers.mongodirector.com";
$username = "sgroot";
$password = "CZHEl@zTU5Bnpg5K";
$dbname = "seguridad"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, fullname, username, password FROM usuarios WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: bienvenido.php");
        } else {
            echo "Usuario o contraseña incorrectos.";
        }
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}

$conn->close();
?>
