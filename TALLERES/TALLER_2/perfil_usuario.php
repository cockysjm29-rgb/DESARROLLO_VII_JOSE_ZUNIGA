<?php
$nombre_completo = "Jose Zuñiga";
$edad = 33;
$correo = "jose.zuniga1@utp.ac.pa";
$telefono = "+507 69100304";

// Definición de una constante
define("OCUPACION", "Coordinador de Tecnología");

// Creación de mensaje usando diferentes métodos de concatenación e impresión
$mensaje1 = "Hola, mi nombre es " . $nombre_completo . " y tengo " . $edad . " años.";
$mensaje2 = "Mi correo es $correo y actualmente trabajo como " . OCUPACION . ".";

echo $mensaje1 . "<br>";
print($mensaje2 . "<br>");
printf("Te dejo mis datos: %s, %d años, %s, %s<br>", $nombre_completo, $edad, $correo, OCUPACION);

?>