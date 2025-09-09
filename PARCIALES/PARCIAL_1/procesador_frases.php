<?php
include 'operaciones_cadenas.php';

$frases = [
    "Por la tarde iremos al lugar acordado",
    "Al que entendedor pocas palabras",
    "La distancia del sol equivale a caminar la tierra de mar a mar durante 3 años sin parar"
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesador de Frases</title>
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #444;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Análisis de Frases</h2>
    <table>
        <tr>
            <th>Frase Original</th>
            <th>Veces que aparece cada palabra</th>
            <th>Cambiando primeras letras a mayúsculas</th>            
        </tr>
        <?php
        
         foreach ($frases as $frase) {
            echo "<tr>";
            echo "<td>$frase</td>";
            echo "<td>" . contar_palabras_repetidas($frase) . "</td>";
            echo "<td>" . capitalizar_palabras($frase) . "</td>";            
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>