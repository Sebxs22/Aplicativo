<?php
// Incluir el archivo de conexión
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener los parámetros enviados por la URL
    $nombre = trim($_GET['nombre'] ?? '');
    $producto = trim($_GET['producto'] ?? '');
    $calificacion = trim($_GET['calificacion'] ?? '');
    $comentario = trim($_GET['comentario'] ?? '');
    $fecha = trim($_GET['fecha'] ?? '');

    // Verificar si falta algún dato
    if (empty($nombre) || empty($producto) || empty($calificacion) || empty($comentario) || empty($fecha)) {
        echo json_encode(["success" => false, "message" => "Datos incompletos"]);
        exit();
    }

    // Verificar que la conexión está establecida
    if (!isset($conexion) || $conexion->connect_error) {
        echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos"]);
        exit();
    }

    // Consulta preparada para insertar los datos
    $stmt = $conexion->prepare("INSERT INTO calificacion (nombre, producto, calificacion, comentario, fecha) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $nombre, $producto, $calificacion, $comentario, $fecha);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Calificación guardada correctamente"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error al guardar la calificación: " . $stmt->error
        ]);
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    cerrarConexion($conexion);
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}
?>
