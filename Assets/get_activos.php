<?php
require_once 'db_connection.php';

// Obtener el término de búsqueda
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Preparar la consulta SQL
$sql = "SELECT codigo, nombre, estatus, asignado FROM activos";
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " WHERE codigo LIKE '%$search%' OR nombre LIKE '%$search%'";
}

$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

if ($result->num_rows === 0) {
    echo "<tr><td colspan='4' class='text-center'>No se encontraron registros</td></tr>";
} else {
    while($row = $result->fetch_assoc()) {
        // Determinar la clase de color según el estatus
        $statusClass = '';
        switch($row['estatus']) {
            case 'Disponible':
                $statusClass = 'text-success';
                break;
            case 'Asignado':
                $statusClass = 'text-primary';
                break;
            case 'En Mantenimiento':
                $statusClass = 'text-warning';
                break;
        }

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['codigo']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
        echo "<td class='" . $statusClass . "'>" . htmlspecialchars($row['estatus']) . "</td>";
        echo "<td>" . htmlspecialchars($row['asignado']) . "</td>";
        echo "<td>
                <button class='btn btn-primary btn-sm asignar-btn' 
                        data-codigo='" . htmlspecialchars($row['codigo']) . "'
                        data-nombre='" . htmlspecialchars($row['nombre']) . "'
                        " . ($row['estatus'] != 'Disponible' ? 'disabled' : '') . "
                        data-asignado='" . htmlspecialchars($row['asignado']) ."
                        '>
                    <i class='fas fa-user-plus'></i> Asignar
                </button>
              </td>";
        echo "</tr>";
    }
}

$conn->close();
?>