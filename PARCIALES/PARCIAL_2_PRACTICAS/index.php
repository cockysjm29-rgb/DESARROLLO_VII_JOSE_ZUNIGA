<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'clases.php';
$estadosLegibles = [
    'pendiente' => 'Pendiente',
    'en_progreso' => 'En Progreso',
    'completada' => 'Completada'
];

$prioridadesLegibles = [
    1 => 'Alta',
    2 => 'Media alta',
    3 => 'Media',
    4 => 'Media baja',
    5 => 'Baja'
];

// Obtener la acción del query string, 'list' por defecto
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Variable para almacenar la tarea en edición
$tareaEnEdicion = null;

// Variables para ordenamiento y filtrado
$sortField = isset($_GET['field']) ? $_GET['field'] : 'id';
$sortDirection = isset($_GET['direction']) ? $_GET['direction'] : 'ASC';
$filterEstado = isset($_GET['filterEstado']) ? $_GET['filterEstado'] : '';

$tareas = null;
$gestorTareas = new GestorTareas();
$tareas = $gestorTareas->cargarTareas();

$accionesValidas = ['add', 'edit', 'delete', 'status', 'filter', 'list', 'sort'];
if (!in_array($action, $accionesValidas)) {
    $action = 'list';  // acción por defecto segura
    $mensaje = "Acción no reconocida, mostrando lista por defecto.";
}
// Procesar la acción
switch ($action) {
    case 'add':
        $datos = $_GET;
        $datos['estado'] = 'pendiente';
        $tipo = strtolower($datos['tipo']);

        switch ($tipo) {
            case 'desarrollo':
                $tarea = new TareaDesarrollo($datos);
                break;
            case 'diseno':
                $tarea = new TareaDiseno($datos);
                break;
            case 'testing':
                $tarea = new TareaTesting($datos);
                break;
            default:
                $tarea = new Tarea($datos);
        }

        $gestorTareas->agregarTarea($tarea);
        $mensaje = "Tarea agregada correctamente.";
        $tareas = $gestorTareas->listarTareas();
        break;

    case 'edit':
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $id = $_GET['id'];
            foreach ($tareas as $tarea) {
                if ($tarea->id == $id) {
                    $tareaEnEdicion = $tarea;
                    break;
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['titulo'])) {
            // Envío de formulario de edición
            $datos = $_GET;
            $tipo = strtolower($datos['tipo']);

            switch ($tipo) {
                case 'desarrollo':
                    $tarea = new TareaDesarrollo($datos);
                    break;
                case 'diseno':
                    $tarea = new TareaDiseno($datos);
                    break;
                case 'testing':
                    $tarea = new TareaTesting($datos);
                    break;
                default:
                    $tarea = new Tarea($datos);
            }

            $gestorTareas->actualizarTarea($tarea);
            $mensaje = "Tarea actualizada correctamente.";
            $tareas = $gestorTareas->listarTareas();
        }
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            $gestorTareas->eliminarTarea($_GET['id']);
            $mensaje = "Tarea eliminada correctamente.";
            $tareas = $gestorTareas->listarTareas();
        }
        break;

    case 'status':
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            $gestorTareas->actualizarEstadoTarea($_GET['id'], $_GET['estado']);
            $mensaje = "Estado de la tarea actualizado.";
            $tareas = $gestorTareas->listarTareas();
        }
        break;

    case 'filter':
        $tareas = $gestorTareas->listarTareas($filterEstado);
        break;

    case 'sort':
        $tareas = $gestorTareas->listarTareas($filterEstado);
        usort($tareas, function($a, $b) use ($sortField, $sortDirection) {
            return $sortDirection === 'ASC' ? $a->$sortField <=> $b->$sortField : $b->$sortField <=> $a->$sortField;
        });
        break;

    case 'list':
    default:
        $tareas = $gestorTareas->listarTareas();
        break;
}

// Cargar las tareas si aún no se han cargado
if ($tareas === null) {
    $gestorTareas = new GestorTareas();
    $tareas = $gestorTareas->cargarTareas();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Gestor de Tareas</h1>
        
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para agregar/editar tarea -->
        <form action="index.php" method="GET" class="row g-3 mb-4 align-items-end">
            <input type="hidden" name="action" value="<?php echo $tareaEnEdicion ? 'edit' : 'add'; ?>">
            <?php if ($tareaEnEdicion): ?>
                <input type="hidden" name="id" value="<?php echo $tareaEnEdicion->id; ?>">
            <?php endif; ?>
            
            <div class="col">
                <input type="text" class="form-control" name="titulo" placeholder="Título" required
                       value="<?php echo $tareaEnEdicion ? $tareaEnEdicion->titulo : ''; ?>">
            </div>
            <div class="col">
                <input type="text" class="form-control" name="descripcion" placeholder="Descripción" required
                       value="<?php echo $tareaEnEdicion ? $tareaEnEdicion->descripcion : ''; ?>">
            </div>
            <div class="col">
                <select class="form-select" name="prioridad" required>
                    <option value="">Prioridad</option>
                    <?php foreach ($prioridadesLegibles as $valor => $texto): ?>
                         <?php
                         $selected = ($tareaEnEdicion && intval($tareaEnEdicion->prioridad) === intval($valor)) ? 'selected' : '';
                         ?>
                         <option value="<?php echo $valor; ?>" <?php echo $selected; ?>>
                             <?php echo $texto; ?>
                         </option>
                     <?php endforeach; ?>
                 </select>
            </div>

    <div class="col">
        <select class="form-select" name="tipo" required id="tipoTarea">
            <option value="">Tipo de Tarea</option>
            <option value="desarrollo" <?php echo ($tareaEnEdicion && $tareaEnEdicion->tipo == 'desarrollo') ? 'selected' : ''; ?>>Desarrollo</option>
            <option value="diseno" <?php echo ($tareaEnEdicion && $tareaEnEdicion->tipo == 'diseno') ? 'selected' : ''; ?>>Diseño</option>
            <option value="testing" <?php echo ($tareaEnEdicion && $tareaEnEdicion->tipo == 'testing') ? 'selected' : ''; ?>>Testing</option>
        </select>
    </div>
    
    <!-- Campos específicos -->
    <div class="col" id="campoEspecifico">
        <!-- Desarrollo -->
        <input type="text" class="form-control" id="campoDesarrollo" name="lenguajeProgramacion"
               placeholder="Lenguaje de Programación"
               style="display:none;"
               value="<?php echo ($tareaEnEdicion instanceof TareaDesarrollo ? $tareaEnEdicion->lenguajeProgramacion : ''); ?>">

        <!-- Diseño -->
        <input type="text" class="form-control" id="campoDiseno" name="herramientaDiseno"
               placeholder="Herramienta de Diseño"
               style="display:none;"
               value="<?php echo ($tareaEnEdicion instanceof TareaDiseno ? $tareaEnEdicion->herramientaDiseno : ''); ?>">

        <!-- Testing -->
        <select class="form-select" id="campoTesting" name="tipoTest" style="display:none;">
            <option value="">Tipo de Test</option>
            <option value="unitario" <?php echo ($tareaEnEdicion instanceof TareaTesting && $tareaEnEdicion->tipoTest == 'unitario') ? 'selected' : ''; ?>>Unitario</option>
            <option value="integracion" <?php echo ($tareaEnEdicion instanceof TareaTesting && $tareaEnEdicion->tipoTest == 'integracion') ? 'selected' : ''; ?>>Integración</option>
            <option value="e2e" <?php echo ($tareaEnEdicion instanceof TareaTesting && $tareaEnEdicion->tipoTest == 'e2e') ? 'selected' : ''; ?>>E2E</option>
        </select>
    </div>
    
    <div class="col">
        <button type="submit" class="btn btn-primary">
            <?php echo $tareaEnEdicion ? 'Actualizar Tarea' : 'Agregar Tarea'; ?>
        </button>
    </div>
</form>

        <!-- Filtro por estado -->
        <form action="index.php" method="GET" class="row g-3 mb-4 align-items-end">
            <input type="hidden" name="action" value="filter">
            <div class="col-auto">
                <select name="filterEstado" class="form-select">
                    <option value="">Todos los estados</option>
                    <?php foreach ($estadosLegibles as $valor => $texto): ?>
                        <option value="<?php echo $valor; ?>" <?php echo $filterEstado == $valor ? 'selected' : ''; ?>>
                            <?php echo $texto; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </form>

        <!-- Tabla de tareas -->
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><a href="index.php?action=sort&field=id&direction=<?php echo $sortField == 'id' && $sortDirection == 'ASC' ? 'DESC' : 'ASC'; ?>">ID <?php echo $sortField == 'id' ? ($sortDirection == 'ASC' ? '▲' : '▼') : ''; ?></a></th>
                    <th><a href="index.php?action=sort&field=titulo&direction=<?php echo $sortField == 'titulo' && $sortDirection == 'ASC' ? 'DESC' : 'ASC'; ?>">Título <?php echo $sortField == 'titulo' ? ($sortDirection == 'ASC' ? '▲' : '▼') : ''; ?></a></th>
                    <th><a href="index.php?action=sort&field=descripcion&direction=<?php echo $sortField == 'descripcion' && $sortDirection == 'ASC' ? 'DESC' : 'ASC'; ?>">Descripción <?php echo $sortField == 'descripcion' ? ($sortDirection == 'ASC' ? '▲' : '▼') : ''; ?></a></th>
                    <th><a href="index.php?action=sort&field=estado&direction=<?php echo $sortField == 'estado' && $sortDirection == 'ASC' ? 'DESC' : 'ASC'; ?>">Estado <?php echo $sortField == 'estado' ? ($sortDirection == 'ASC' ? '▲' : '▼') : ''; ?></a></th>
                    <th><a href="index.php?action=sort&field=prioridad&direction=<?php echo $sortField == 'prioridad' && $sortDirection == 'ASC' ? 'DESC' : 'ASC'; ?>">Prioridad <?php echo $sortField == 'prioridad' ? ($sortDirection == 'ASC' ? '▲' : '▼') : ''; ?></a></th>
                    <th><a href="index.php?action=sort&field=tipo&direction=<?php echo $sortField == 'tipo' && $sortDirection == 'ASC' ? 'DESC' : 'ASC'; ?>">Tipo <?php echo $sortField == 'tipo' ? ($sortDirection == 'ASC' ? '▲' : '▼') : ''; ?></a></th>
                    <th><a href="index.php?action=sort&field=fechaCreacion&direction=<?php echo $sortField == 'fechaCreacion' && $sortDirection == 'ASC' ? 'DESC' : 'ASC'; ?>">Fecha Creación <?php echo $sortField == 'fechaCreacion' ? ($sortDirection == 'ASC' ? '▲' : '▼') : ''; ?></a></th>
                    <th>Detalles Específicos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tareas as $tarea): ?>
                    <tr>
                        <td><?php echo $tarea->id; ?></td>
                        <td><?php echo $tarea->titulo; ?></td>
                        <td><?php echo $tarea->descripcion; ?></td>
                        <td><?php echo $estadosLegibles[$tarea->estado] ?? $tarea->estado; ?></td>
                        <td><?php echo $prioridadesLegibles[$tarea->prioridad] ?? $tarea->prioridad; ?></td>
                        <td><?php echo $tarea->tipo; ?></td>
                        <td><?php echo $tarea->fechaCreacion; ?></td>
                        <td><?php echo $tarea->obtenerDetallesEspecificos(); ?></td>                        
                        <td>
                            <a href='index.php?action=edit&id=<?php echo $tarea->id; ?>' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i></a>
                            <a href='index.php?action=delete&id=<?php echo $tarea->id; ?>' class='btn btn-sm btn-danger' onclick="return confirm('¿Está seguro de que desea eliminar esta tarea?');"><i class='fas fa-trash'></i></a>
                            <div class='btn-group'>
                                <button type='button' class='btn btn-sm btn-secondary dropdown-toggle' data-bs-toggle='dropdown'>
                                    Estado
                                </button>
                                <ul class='dropdown-menu'>
                                    <li><a class='dropdown-item' href='index.php?action=status&id=<?php echo $tarea->id; ?>&estado=pendiente'>Pendiente</a></li>
                                    <li><a class='dropdown-item' href='index.php?action=status&id=<?php echo $tarea->id; ?>&estado=en_progreso'>En Progreso</a></li>
                                    <li><a class='dropdown-item' href='index.php?action=status&id=<?php echo $tarea->id; ?>&estado=completada'>Completada</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('tipoTarea').addEventListener('change', function() {
        const campoEspecifico = document.getElementById('campoEspecifico');
        const campoDesarrollo = document.getElementById('campoDesarrollo');
        const campoDiseno = document.getElementById('campoDiseno');
        const campoTesting = document.getElementById('campoTesting');
        
        campoEspecifico.style.display = 'none';
        campoDesarrollo.style.display = 'none';
        campoDiseno.style.display = 'none';
        campoTesting.style.display = 'none';
        
        switch(this.value) {
            case 'desarrollo':
                campoEspecifico.style.display = 'block';
                campoDesarrollo.style.display = 'block';
                break;
            case 'diseno':
                campoEspecifico.style.display = 'block';
                campoDiseno.style.display = 'block';
                break;
            case 'testing':
                campoEspecifico.style.display = 'block';
                campoTesting.style.display = 'block';
                break;
        }
    });
    </script>
</body>
</html>
