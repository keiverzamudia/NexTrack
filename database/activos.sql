-- Crear la tabla de activos (equipos electrónicos)
CREATE TABLE IF NOT EXISTS activos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('PC', 'Monitor', 'Impresora', 'Router', 'Switch') NOT NULL,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    departamento ENUM('ITV', 'PAL', 'MER') NOT NULL,
    estado ENUM('disponible', 'asignado', 'mantenimiento') DEFAULT 'disponible',
    usuario_id INT,
    fecha_asignacion DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Crear tabla para mantener el último número usado por departamento
CREATE TABLE IF NOT EXISTS codigos_activos (
    departamento ENUM('ITV', 'PAL', 'MER') PRIMARY KEY,
    ultimo_numero INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar valores iniciales para cada departamento
INSERT INTO codigos_activos (departamento, ultimo_numero) VALUES 
('ITV', 0),
('PAL', 0),
('MER', 0);

-- Crear trigger para generar el código automáticamente
DELIMITER //
CREATE TRIGGER before_activo_insert 
BEFORE INSERT ON activos
FOR EACH ROW
BEGIN
    DECLARE nuevo_numero INT;
    
    -- Obtener y actualizar el último número para el departamento
    UPDATE codigos_activos 
    SET ultimo_numero = ultimo_numero + 1 
    WHERE departamento = NEW.departamento;
    
    -- Obtener el nuevo número
    SELECT ultimo_numero INTO nuevo_numero 
    FROM codigos_activos 
    WHERE departamento = NEW.departamento;
    
    -- Generar el código con el formato: DEPTO + número
    SET NEW.codigo = CONCAT(NEW.departamento, LPAD(nuevo_numero, 4, '0'));
END//
DELIMITER ;

-- Crear índices para mejorar el rendimiento
CREATE INDEX idx_activos_codigo ON activos(codigo);
CREATE INDEX idx_activos_estado ON activos(estado);
CREATE INDEX idx_activos_usuario ON activos(usuario_id);
CREATE INDEX idx_activos_departamento ON activos(departamento); 