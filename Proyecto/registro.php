<?php
define('ENCRYPTION_KEY', 'Equipo1ñ');
define('ENCRYPTION_METHOD', 'aes-256-cbc');

function encrypt($data) {
    $key = hash('sha256', ENCRYPTION_KEY);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(ENCRYPTION_METHOD));
    $encrypted = openssl_encrypt($data, ENCRYPTION_METHOD, $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

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

    $encrypted_username = encrypt($username);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (fullname, username, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $fullname, $encrypted_username, $hashed_password);

    if ($stmt->execute() === TRUE) {
        header("Location: Proyecto seguridad.html");
        exit(); 
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>