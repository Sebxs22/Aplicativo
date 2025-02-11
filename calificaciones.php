<?php
// Incluir el archivo de conexión
include('conexion.php');

// Consulta para obtener las calificaciones
$sql = "SELECT * FROM calificacion";  // Cambia 'calificaciones' por el nombre correcto de tu tabla

// Ejecutar la consulta
$resultado = ejecutarConsulta($conexion, $sql);

// Mostrar la tabla con las calificaciones
if ($resultado->num_rows > 0) {
    echo "<center><table border='1'>
            <tr>
                <th>Nombre</th>
                <th>Producto</th>
                <th>Calificación</th>
                <th>Comentario</th>
                <th>Fecha</th>
            </tr>";

    // Recorrer los resultados y mostrar los datos
    while ($row = $resultado->fetch_assoc()) {
        // Verificar si la fecha está en formato Unix timestamp (milisegundos)
        $fecha = $row['fecha'];
        $fecha_formateada = '';

        if (is_numeric($fecha)) {
            // Si es un número (Unix timestamp en milisegundos)
            $fecha_formateada = date('Y-m-d H:i:s', $fecha / 1000);
        } else {
            // Si ya está en formato DATETIME (o similar), no se necesita dividir
            $fecha_formateada = $fecha;
        }

        echo "<tr>
                <td>" . $row['nombre'] . "</td>
                <td>" . $row['producto'] . "</td>
                <td>" . $row['calificacion'] . "</td>
                <td>" . $row['comentario'] . "</td>
                <td>" . $fecha_formateada . "</td>
              </tr>";
    }

    echo "</table><center>";
} else {
    echo "No hay calificaciones disponibles.";
}

// Cerrar la conexión
cerrarConexion($conexion);
?>
