<?php
// index.php
// Demostración de uso del sistema de gestión de empleados en HTML

require_once 'Empresa.php';

// Crear empresa
$miEmpresa = new Empresa();

// Crear empleados
$gerente = new Gerente("Ana Pérez", "G-001", 4500.00, "Ventas");
$dev1 = new Desarrollador("Carlos Ruiz", "D-101", 2500.00, "PHP", "Senior");
$dev2 = new Desarrollador("María Gómez", "D-102", 1500.00, "JavaScript", "Junior");
$empleadoGenerico = new Empleado("Roberto Solis", "E-900", 1200.00);

// Agregar empleados
$miEmpresa->agregarEmpleado($gerente);
$miEmpresa->agregarEmpleado($dev1);
$miEmpresa->agregarEmpleado($dev2);
$miEmpresa->agregarEmpleado($empleadoGenerico);

// Evaluaciones
$resultadoEvaluaciones = $miEmpresa->realizarEvaluaciones();

// HTML de salida
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Gestión de Empleados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 20px;
        }
        h1, h2 {
            color: #333;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #bbb;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background: #2c3e50;
            color: white;
        }
        .advertencia {
            color: #b71c1c;
            font-weight: bold;
        }
        .resaltado {
            background: #ecf0f1;
        }
    </style>
</head>
<body>

    <h1>📋 Sistema de Gestión de Empleados</h1>

    <div class="card">
        <h2>Lista de Empleados</h2>
        <table>
            <tr>
                <th>Nombre</th>
                <th>ID</th>
                <th>Salario Base</th>
                <th>Detalles</th>
            </tr>
            <?php foreach ($miEmpresa->listarEmpleados() as $info): ?>
                <?php
                // Separar la información para mostrarla más ordenada
                $partes = explode("|", $info);
                ?>
                <tr>
                    <?php foreach ($partes as $p): ?>
                        <td><?= trim($p) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="card">
        <h2>💰 Nómina</h2>
        <p><strong>Total después de evaluaciones:</strong> 
            <?= number_format($miEmpresa->calcularNominaTotal(), 2) ?>
        </p>
    </div>

    <div class="card">
        <h2>📊 Evaluaciones de Desempeño</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Score</th>
                <th>Comentario</th>
                <th>Bono sugerido</th>
            </tr>
            <?php foreach ($resultadoEvaluaciones['resultados'] as $res): ?>
                <tr>
                    <td><?= $res['id'] ?></td>
                    <td><?= $res['nombre'] ?></td>
                    <td><?= $res['tipo'] ?></td>
                    <td><?= $res['evaluacion']['score'] ?></td>
                    <td><?= $res['evaluacion']['comentario'] ?></td>
                    <td><?= number_format($res['evaluacion']['bono'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php if (!empty($resultadoEvaluaciones['advertencias'])): ?>
    <div class="card">
        <h2>⚠ Advertencias</h2>
        <ul>
            <?php foreach ($resultadoEvaluaciones['advertencias'] as $adv): ?>
                <li class="advertencia"><?= $adv ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

</body>
</html>
