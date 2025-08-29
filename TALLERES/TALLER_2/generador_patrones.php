<?php
echo "<h2>Ejercicio Práctico</h2>";

// Bucle for creando patrón
echo "Patrón de Triángulo Rectángulo con bucle for:<br>";
for ($i = 1; $i <= 5; $i++) {    
    for ($j = 1; $j <= $i; $j++) {
        echo "*";
    }
    echo "<br>";
}
echo "<br><br>";

// Bucle while con números impares
$j = 0;
echo "Números impares entre 1 y 20:<br>";
while ($j < 20) {
    $j++;
    if ($j % 2 == 0) {
        continue;
    }
    echo "$j ";
}
echo "<br><br>";

// Contador regresivo
echo "Contador regresivo de 10 a 1, saltando el 5:<br>";
$contador = 10;
do {
    if ($contador != 5) {
        echo $contador . "<br>";
    }
    $contador--;
} while ($contador >= 1);
?>