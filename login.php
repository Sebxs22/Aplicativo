<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Faltan datos"]);
        exit();
    }

    // Consulta para obtener nombre, contraseña y nivel del usuario
    $stmt = $conexion->prepare("SELECT nombre, password, nivel FROM usuarios WHERE nombre = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $user = $resultado->fetch_assoc();

        echo json_encode([
            "success" => true,
            "message" => "Credenciales correctas",
            "nivel" => $user['nivel']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Usuario o contraseña incorrectos"]);
    }

    $stmt->close();
    cerrarConexion($conexion);
}
?>
