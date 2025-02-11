<?php
include 'conexion.php'; // Conexión a la base de datos

// Verificar el método de la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add':
            // Obtener los datos del producto
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $precio = floatval($_POST['precio'] ?? 0);
            $stock = intval($_POST['stock'] ?? 0);

            // Validar los datos
            if (empty($nombre) || empty($descripcion) || $precio <= 0 || $stock < 0) {
                echo json_encode(["success" => false, "message" => "Datos inválidos"]);
                exit();
            }

            // Preparar la consulta SQL
            $sql = "INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);

            if ($stmt === false) {
                echo json_encode(["success" => false, "message" => "Error en la consulta"]);
                exit();
            }

            // Enlazar los parámetros y ejecutar la consulta
            $stmt->bind_param('ssdi', $nombre, $descripcion, $precio, $stock);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Producto agregado con éxito"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al agregar producto"]);
            }

            // Cerrar la declaración y la conexión
            $stmt->close();
            $conexion->close();
            break;

        case 'update':
                $id = intval($_POST['id'] ?? 0);
                $nombre = trim($_POST['nombre'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                $precio = floatval($_POST['precio'] ?? 0);
                $stock = intval($_POST['stock'] ?? 0);
            
                // Validar los datos
                if ($id <= 0 || empty($nombre) || empty($descripcion) || $precio <= 0 || $stock < 0) {
                    echo json_encode(["success" => false, "message" => "Datos inválidos"]);
                    exit();
                }
            
                // Preparar la consulta
                $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=? WHERE id=?";
                $stmt = $conexion->prepare($sql);
                if (!$stmt) {
                    echo json_encode(["success" => false, "message" => "Error en la preparación de la consulta"]);
                    exit();
                }
            
                $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $id);
                if ($stmt->execute()) {
                    echo json_encode(["success" => true, "message" => "Producto actualizado"]);
                } else {
                    echo json_encode(["success" => false, "message" => "Error al actualizar: " . $stmt->error]);
                }
                $stmt->close();
                cerrarConexion($conexion);
                break;
            

        case 'delete':
            $id = intval($_POST['id'] ?? 0);

            // Validar el ID
            if ($id <= 0) {
                echo json_encode(["success" => false, "message" => "ID inválido"]);
                exit();
            }

            $sql = "DELETE FROM productos WHERE id=?";
            $params = [$id];
            $types = "i";

            if (ejecutarConsulta($conexion, $sql, $params, $types)) {
                echo json_encode(["success" => true, "message" => "Producto eliminado"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al eliminar"]);
            }
            break;

        default:
            echo json_encode(["success" => false, "message" => "Acción no válida"]);
    }

    // Cerrar conexión al final
    cerrarConexion($conexion);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'list') {
    if (isset($_GET['id'])) {
        // Búsqueda de un producto específico
        $id = intval($_GET['id']);
        $sql = "SELECT * FROM productos WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $producto = $result->fetch_assoc();
            echo json_encode([
                "success" => true,
                "producto" => $producto
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Producto no encontrado"
            ]);
        }
    } else {
        // Listar todos los productos
        $sql = "SELECT * FROM productos";
        $result = ejecutarConsulta($conexion, $sql);

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        echo json_encode($productos);
    }

    // Cerrar conexión al final
    cerrarConexion($conexion);
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}
?>