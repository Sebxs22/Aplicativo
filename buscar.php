<?php
// Incluir archivo de conexión
include 'conexion.php'; // Asegúrate que el archivo conexion.php esté configurado correctamente

// Verificar la conexión
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Verificar si se ha enviado el nombre del producto a través de la URL
$nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';

// Si se proporciona el nombre del producto
if (!empty($nombre)) {
    // Preparar la consulta SQL para buscar productos por nombre
    $sql = "SELECT * FROM productos WHERE nombre LIKE ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt === false) {
        echo "<p style='color:red;'>Error en la preparación de la consulta</p>";
        exit();
    }

    // Añadir los parámetros para la consulta preparada
    $likeNombre = "%" . $nombre . "%";
    $stmt->bind_param('s', $likeNombre);

    // Ejecutar la consulta
    $stmt->execute();
    // Obtener el resultado
    $result = $stmt->get_result();

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        echo "<h2>Productos encontrados:</h2>";
        echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        echo "<thead><tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Stock</th></tr></thead>";
        echo "<tbody>";

        // Recorrer los resultados y agregarlos a la tabla
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
            echo "<td>$" . htmlspecialchars($row['precio']) . "</td>";
            echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p style='color:red;'>Producto no encontrado.</p>";
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
} else {
    // Si no se ha proporcionado un nombre, mostrar todos los productos
    $sql = "SELECT * FROM productos"; // Consulta para obtener todos los productos
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Todos los productos:</h2>";
        echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        echo "<thead><tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Stock</th></tr></thead>";
        echo "<tbody>";

        // Recorrer los resultados y agregarlos a la tabla
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
            echo "<td>$" . htmlspecialchars($row['precio']) . "</td>";
            echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p style='color:red;'>Producto no encontrado.</p>";
    }
}

// Cerrar la conexión
$conexion->close();
?>
