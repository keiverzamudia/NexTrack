<?php
// Incluir archivo de conexión a la base de datos
require_once 'db_connection.php'; // Asegúrate de que este archivo exista y funcione

// Verificar si la petición es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoActivo = isset($_POST['tipoActivo']) ? trim($_POST['tipoActivo']) : '';

    switch ($tipoActivo) {
        case 'PC':
            $marca = isset($_POST['marcaPC']) ? trim($_POST['marcaPC']) : '';
            $modelo = isset($_POST['modeloPC']) ? trim($_POST['modeloPC']) : '';
            $capacidad = isset($_POST['capacidadPC']) ? trim($_POST['capacidadPC']) : '';

            // Validar datos de PC (¡Crucial!)
            if (empty($marca) || empty($modelo)) {
                $response = ['success' => false, 'error' => 'Marca y modelo de PC son requeridos.'];
                break;
            }

            // Insertar en la tabla de PCs (ajusta el nombre de tu tabla)
            $sql = "INSERT INTO pc (marca, modelo, capacidad) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sss", $marca, $modelo, $capacidad);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'PC registrada correctamente.'];
                } else {
                    $response = ['success' => false, 'error' => 'Error al registrar PC: ' . $stmt->error];
                }
                $stmt->close();
            } else {
                $response = ['success' => false, 'error' => 'Error al preparar la consulta de PC: ' . $conn->error];
            }
            break;

        case 'Camara':
            $marca = isset($_POST['marcaCamara']) ? trim($_POST['marcaCamara']) : '';
            $modelo = isset($_POST['modeloCamara']) ? trim($_POST['modeloCamara']) : '';
            $capacidad = isset($_POST['capacidadCamara']) ? trim($_POST['capacidadCamara']) : '';

            // Validar datos de Cámara
            if (empty($marca) || empty($modelo)) {
                $response = ['success' => false, 'error' => 'Marca y modelo de Cámara son requeridos.'];
                break;
            }

            // Insertar en la tabla de Cámaras (ajusta el nombre de tu tabla)
            $sql = "INSERT INTO camaras (marca, modelo, capacidad) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sss", $marca, $modelo, $capacidad);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Cámara registrada correctamente.'];
                } else {
                    $response = ['success' => false, 'error' => 'Error al registrar Cámara: ' . $stmt->error];
                }
                $stmt->close();
            } else {
                $response = ['success' => false, 'error' => 'Error al preparar la consulta de Cámara: ' . $conn->error];
            }
            break;

        case 'Bateria':
            $marca = isset($_POST['marcaBateria']) ? trim($_POST['marcaBateria']) : '';
            $capacidadAmp = isset($_POST['capacidadBateria']) ? filter_var($_POST['capacidadBateria'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : '';

            // Validar datos de Batería
            if (empty($marca) || !is_numeric($capacidadAmp) || $capacidadAmp <= 0) {
                $response = ['success' => false, 'error' => 'Marca y capacidad de Batería son requeridos y la capacidad debe ser un número mayor que cero.'];
                break;
            }

            // Insertar en la tabla de Baterías (ajusta el nombre de tu tabla)
            $sql = "INSERT INTO baterias (marca, capacidad_amp) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sd", $marca, $capacidadAmp);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Batería registrada correctamente.'];
                } else {
                    $response = ['success' => false, 'error' => 'Error al registrar Batería: ' . $stmt->error];
                }
                $stmt->close();
            } else {
                $response = ['success' => false, 'error' => 'Error al preparar la consulta de Batería: ' . $conn->error];
            }
            break;

        case 'Monitor':
            $marca = isset($_POST['marcaMonitor']) ? trim($_POST['marcaMonitor']) : '';
            $modelo = isset($_POST['modeloMonitor']) ? trim($_POST['modeloMonitor']) : '';
            $tamano = isset($_POST['tamanoMonitor']) ? trim($_POST['tamanoMonitor']) : '';

            // Validar datos de Monitor
            if (empty($marca) || empty($modelo) || empty($tamano)) {
                $response = ['success' => false, 'error' => 'Marca, modelo y tamaño de Monitor son requeridos.'];
                break;
            }

            // Insertar en la tabla de Monitores (ajusta el nombre de tu tabla)
            $sql = "INSERT INTO monitores (marca, modelo, tamano) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sss", $marca, $modelo, $tamano);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Monitor registrado correctamente.'];
                } else {
                    $response = ['success' => false, 'error' => 'Error al registrar Monitor: ' . $stmt->error];
                }
                $stmt->close();
            } else {
                $response = ['success' => false, 'error' => 'Error al preparar la consulta de Monitor: ' . $conn->error];
            }
            break;

        default:
            $response = ['success' => false, 'error' => 'Tipo de activo no válido.'];
            break;
    }

    // Enviar la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);

} else {
    // Si la petición no es POST, devolver un error
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}

$conn->close();
?>