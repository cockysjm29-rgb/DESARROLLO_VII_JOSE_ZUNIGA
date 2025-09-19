<?php

function calcularAreaRectangulo($base, $altura) {
    return $base * $altura;
}

$base = 8;
$altura = 12;

$area = calcularAreaRectangulo($base, $altura);
echo "La base del rectángulo es: " . $base . "<br>";
echo "La altura del rectángulo es: " . $altura . "<br>";
echo "El área del rectángulo es: " . $area . "<br>";

echo "Numeros pares del 2 al 50:<br>";
for ($i = 2; $i <= 50; $i += 2) {
    echo $i . "<br>";
}
?>