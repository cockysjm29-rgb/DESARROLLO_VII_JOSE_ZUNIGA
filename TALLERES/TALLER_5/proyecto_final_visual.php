<?php
declare(strict_types=1);

/**
 * proyecto_final_web.php
 * Sistema de Gestión de Estudiantes - Versión Web Interactiva
 */
require_once 'proyecto_final.php';

// Inicializar sistema y estudiantes
$sistema = new SistemaGestionEstudiantes();
$estudiantesData = [
    [1, 'Ana Gómez', 20, 'Ingeniería', ['Matemáticas'=>95, 'Física'=>88, 'Programación'=>92]],
    [2, 'Luis Pérez', 22, 'Derecho', ['Constitucional'=>75, 'Derecho Civil'=>68]],
    [3, 'María Rodríguez', 19, 'Medicina', ['Anatomía'=>85, 'Bioquímica'=>91, 'Ética'=>78]],
    [4, 'Carlos Ruiz', 21, 'Ingeniería', ['Matemáticas'=>58, 'Física'=>62, 'Electrónica'=>70]],
    [5, 'Sofía Herrera', 23, 'Administración', ['Contabilidad'=>88, 'Marketing'=>92]],
    [6, 'Diego Morales', 20, 'Ingeniería', ['Matemáticas'=>100, 'Programación'=>98]],
    [7, 'Lucía López', 22, 'Derecho', ['Constitucional'=>55, 'Derecho Penal'=>60]],
    [8, 'Pablo Sánchez', 24, 'Administración', ['Contabilidad'=>45, 'Marketing'=>50]],
    [9, 'Elena Díaz', 21, 'Medicina', ['Anatomía'=>95, 'Bioquímica'=>89]],
    [10,'Mateo Fernández', 20, 'Ingeniería', ['Matemáticas'=>80, 'Programación'=>85, 'Física'=>78]],
];
foreach ($estudiantesData as [$id,$nombre,$edad,$carrera,$materias]) {
    $sistema->agregarEstudiante(new Estudiante($id, $nombre, $edad, $carrera, $materias));
}

// Manejo de acciones desde formulario web
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['graduar_id'])) {
        $sistema->graduarEstudiante((int)$_POST['graduar_id']);
    }
    if (isset($_POST['buscar'])) {
        $busqueda = trim($_POST['buscar']);
        $estudiantesListado = $sistema->buscarEstudiantes($busqueda);
    } else {
        $estudiantesListado = $sistema->listarEstudiantes();
    }
} else {
    $estudiantesListado = $sistema->listarEstudiantes();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Gestión de Estudiantes</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .honor { background-color: #d4edda; } /* verde */
        .riesgo { background-color: #f8d7da; } /* rojo */
        form { margin-bottom: 20px; }
        input[type=text] { padding: 5px; width: 200px; }
        input[type=submit] { padding: 5px 10px; }
    </style>
</head>
<body>

<h1>Sistema de Gestión de Estudiantes</h1>

<form method="post">
    <label>Buscar estudiante por nombre o carrera: </label>
    <input type="text" name="buscar" placeholder="Ej: Ingeniería" value="<?= htmlspecialchars($_POST['buscar'] ?? '') ?>">
    <input type="submit" value="Buscar">
</form>

<h2>Listado de Estudiantes (<?= count($estudiantesListado) ?>)</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Edad</th>
        <th>Carrera</th>
        <th>Materias</th>
        <th>Promedio</th>
        <th>Flags</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($estudiantesListado as $est): 
        $clase = in_array('honor roll', $est->getFlags()) ? 'honor' : (in_array('en riesgo académico', $est->getFlags()) ? 'riesgo' : '');
    ?>
    <tr class="<?= $clase ?>">
        <td><?= $est->getId() ?></td>
        <td><?= htmlspecialchars($est->getNombre()) ?></td>
        <td><?= $est->getEdad() ?></td>
        <td><?= htmlspecialchars($est->getCarrera()) ?></td>
        <td>
            <ul>
                <?php foreach ($est->getMaterias() as $mat => $cal): ?>
                    <li><?= htmlspecialchars($mat) ?>: <?= $cal ?></li>
                <?php endforeach; ?>
            </ul>
        </td>
        <td><?= $est->obtenerPromedio() ?></td>
        <td><?= implode(', ', $est->getFlags()) ?></td>
        <td>
            <form method="post" style="display:inline">
                <input type="hidden" name="graduar_id" value="<?= $est->getId() ?>">
                <input type="submit" value="Graduar">
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Promedio General del Sistema: <?= $sistema->calcularPromedioGeneral() ?></h2>

<h2>Reporte de Rendimiento por Materia</h2>
<table>
    <tr>
        <th>Materia</th>
        <th>Promedio</th>
        <th>Max</th>
        <th>Min</th>
        <th>Cantidad</th>
    </tr>
    <?php foreach ($sistema->generarReporteRendimiento() as $mat => $info): ?>
    <tr>
        <td><?= htmlspecialchars($mat) ?></td>
        <td><?= $info['promedio'] ?></td>
        <td><?= $info['max'] ?></td>
        <td><?= $info['min'] ?></td>
        <td><?= $info['cantidad'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Ranking de Estudiantes</h2>
<ol>
    <?php foreach ($sistema->generarRanking() as $r): ?>
        <li><?= htmlspecialchars($r->getNombre()) ?> - Promedio: <?= $r->obtenerPromedio() ?></li>
    <?php endforeach; ?>
</ol>

<h2>Estadísticas por Carrera</h2>
<table>
    <tr>
        <th>Carrera</th>
        <th>Cantidad</th>
        <th>Promedio General</th>
        <th>Mejor Estudiante</th>
    </tr>
    <?php foreach ($sistema->estadisticasPorCarrera() as $carrera => $info): ?>
    <tr>
        <td><?= htmlspecialchars($carrera) ?></td>
        <td><?= $info['cantidad'] ?></td>
        <td><?= $info['promedio'] ?></td>
        <td><?= $info['mejor'] ? htmlspecialchars($info['mejor']->getNombre()) : 'N/A' ?></td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>