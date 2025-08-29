<?php
$calificacion = 100;

if ($calificacion >= 90) {
    echo "Tu calificación es A.<br>";
} elseif ($calificacion >= 80) {
    echo "Tu calificación es B.<br>";
} elseif ($calificacion >= 70) {
    echo "Tu calificación es C.<br>";
} elseif ($calificacion >= 60) {
    echo "Tu calificación es D.<br>";
} else {
    echo "Tu calificación es F.<br>";
}
echo "<br>";

$resultadoTernario = ($calificacion >= 60) ? "Aprobado" : "Reprobado";
echo "Resultado (ternario): $resultadoTernario<br><br>";

switch (true) {
    case ($calificacion >= 90):
        echo "Excelente trabajo.<br>";
        break;
    case ($calificacion >= 80):
        echo "Buen trabajo.<br>";
        break;
    case ($calificacion >= 70):
        echo "Trabajo aceptable.<br>";
        break;
    case ($calificacion >= 60):
        echo "Necesitas mejorar.<br>";
        break;
    default:
        echo "Debes esforzarte más.<br>";
}
echo "<br>";
?>