<?php
session_start();

define('ENCRYPTION_KEY', 'Equipo1ñ');
define('ENCRYPTION_METHOD', 'aes-256-cbc');

function decrypt($data) {
    $key = hash('sha256', ENCRYPTION_KEY);
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, ENCRYPTION_METHOD, $key, 0, $iv);
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
    $submitted_username = $_POST['username'];
    $submitted_password = $_POST['password'];

    $sql = "SELECT id, fullname, username, password FROM usuarios";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $login_success = false;

    while ($row = $result->fetch_assoc()) {
        $decrypted_username = decrypt($row['username']);
        if ($submitted_username === $decrypted_username) {
            if (password_verify($submitted_password, $row['password'])) {
                $_SESSION['username'] = $decrypted_username;
                header("Location: encriptar.html");
                exit();
            } else {
                echo "Usuario o contraseña incorrectos.";
            }
            $login_success = true;
            break;
        }
    }

    if (!$login_success) {
        echo "Usuario o contraseña incorrectos.";
    }

    $stmt->close();
}

$conn->close();
?>
