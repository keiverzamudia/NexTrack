<?php
// Incluir el header
require_once("app/View/Component/Header.php");
?>

<!-- Contenido principal -->
<div class="container-fluid">
    <!-- Encabezado de página -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Activos</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#registrarModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Registrar Nuevo Activo
        </button>
    </div>

    <!-- Mensajes de alerta -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['mensaje']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Tabla de activos -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Características</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activos as $activo): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($activo['codigo']); ?></td>
                                <td><?php echo htmlspecialchars($activo['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($activo['tipo']); ?></td>
                                <td><?php echo htmlspecialchars($activo['caracteristicas']); ?></td>
                                <td>
                                    <?php
                                    $estadoClass = '';
                                    switch ($activo['estado']) {
                                        case 'disponible':
                                            $estadoClass = 'success';
                                            break;
                                        case 'asignado':
                                            $estadoClass = 'primary';
                                            break;
                                        case 'mantenimiento':
                                            $estadoClass = 'warning';
                                            break;
                                    }
                                    ?>
                                    <span class="badge badge-<?php echo $estadoClass; ?>">
                                        <?php echo ucfirst(htmlspecialchars($activo['estado'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modificarModal<?php echo $activo['id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php if ($activo['estado'] === 'disponible'): ?>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#asignarModal<?php echo $activo['id']; ?>">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#eliminarModal<?php echo $activo['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Modificar -->
                            <div class="modal fade" id="modificarModal<?php echo $activo['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modificarModalLabel<?php echo $activo['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modificarModalLabel<?php echo $activo['id']; ?>">Modificar Activo</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="index.php?url=gestionActivo&action=modificar" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $activo['id']; ?>">
                                                <div class="form-group">
                                                    <label for="codigo">Código</label>
                                                    <input type="text" class="form-control" id="codigo" name="codigo" value="<?php echo htmlspecialchars($activo['codigo']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nombre">Nombre</label>
                                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($activo['nombre']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tipo">Tipo</label>
                                                    <input type="text" class="form-control" id="tipo" name="tipo" value="<?php echo htmlspecialchars($activo['tipo']); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="caracteristicas">Características</label>
                                                    <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3" required><?php echo htmlspecialchars($activo['caracteristicas']); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="estado">Estado</label>
                                                    <select class="form-control" id="estado" name="estado" required>
                                                        <option value="disponible" <?php echo $activo['estado'] === 'disponible' ? 'selected' : ''; ?>>Disponible</option>
                                                        <option value="asignado" <?php echo $activo['estado'] === 'asignado' ? 'selected' : ''; ?>>Asignado</option>
                                                        <option value="mantenimiento" <?php echo $activo['estado'] === 'mantenimiento' ? 'selected' : ''; ?>>Mantenimiento</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Asignar -->
                            <div class="modal fade" id="asignarModal<?php echo $activo['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="asignarModalLabel<?php echo $activo['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="asignarModalLabel<?php echo $activo['id']; ?>">Asignar Activo</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="index.php?url=gestionActivo&action=asignar" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $activo['id']; ?>">
                                                <div class="form-group">
                                                    <label for="usuario_id">Seleccionar Usuario</label>
                                                    <select class="form-control" id="usuario_id" name="usuario_id" required>
                                                        <?php foreach ($usuarios as $usuario): ?>
                                                            <option value="<?php echo $usuario['id']; ?>">
                                                                <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Asignar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Eliminar -->
                            <div class="modal fade" id="eliminarModal<?php echo $activo['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="eliminarModalLabel<?php echo $activo['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="eliminarModalLabel<?php echo $activo['id']; ?>">Confirmar Eliminación</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Está seguro que desea eliminar el activo "<?php echo htmlspecialchars($activo['nombre']); ?>"?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <a href="index.php?url=gestionActivo&action=eliminar&id=<?php echo $activo['id']; ?>" class="btn btn-danger">Eliminar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Registrar -->
<div class="modal fade" id="registrarModal" tabindex="-1" role="dialog" aria-labelledby="registrarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registrarModalLabel">Registrar Nuevo Activo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="index.php?url=gestionActivo&action=registrar" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="codigo">Código</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" required>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <input type="text" class="form-control" id="tipo" name="tipo" required>
                    </div>
                    <div class="form-group">
                        <label for="caracteristicas">Características</label>
                        <textarea class="form-control" id="caracteristicas" name="caracteristicas" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Incluir el footer
require_once("app/View/Component/Footer.php");
?> 