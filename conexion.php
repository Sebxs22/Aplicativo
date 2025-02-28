<?php
// Nueva configuración de conexión a Railway
$hostname = 'roundhouse.proxy.rlwy.net';
$database = 'railway';
$username = 'root';
$password = 'HxyORDtpVzlsCTvQBKwoVURyRryHgXgV';
$port = 48320;

try {
    // Crear conexión
    $conexion = new mysqli($hostname, $username, $password, $database, $port);

    // Verificar conexión
    if ($conexion->connect_errno) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }

    // Establecer charset a utf8
    $conexion->set_charset("utf8");

    // Opcional: mensaje de éxito (comentar en producción)
    // echo "Conexión exitosa a la base de datos";

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// Función para cerrar la conexión de manera segura
function cerrarConexion($conexion) {
    if ($conexion) {
        $conexion->close();
    }
}

// Función para ejecutar consultas de manera segura
function ejecutarConsulta($conexion, $sql) {
    try {
        $resultado = $conexion->query($sql);
        if ($resultado === false) {
            throw new Exception("Error en la consulta: " . $conexion->error);
        }
        return $resultado;
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
