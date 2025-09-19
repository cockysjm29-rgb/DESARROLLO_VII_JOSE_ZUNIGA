<?php
// Archivo: analisis_text.php

// Incluir funciones de utilidades_texto.php
include 'utilidades_texto.php';

// Definir un array con 3 frases
$frases = [
    "Hola este es un ejemplo de texto",
    "Programar en PHP es divertido y útil",
    "La distancia del sol equivale a caminar la tierra de mar a mar durante 3 años sin parar"
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Análisis de Texto</title>
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
            <th>Número de Palabras</th>
            <th>Número de Vocales</th>
            <th>Frase Invertida</th>
        </tr>
        <?php
        // Procesar cada frase con las funciones
         foreach ($frases as $frase) {
            echo "<tr>";
            echo "<td>$frase</td>";
            echo "<td>" . contar_palabras($frase) . "</td>";
            echo "<td>" . contar_vocales($frase) . "</td>";
            echo "<td>" . invertir_palabras($frase) . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>