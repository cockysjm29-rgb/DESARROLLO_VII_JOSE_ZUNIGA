<?php
function contar_palabras_repetidas($texto) {    
    $texto = trim($texto); 
    $palabras = explode(" ", $texto); 
    $contador = 0;

foreach ($palabras as $palabra) {
        if ($palabra === $texto) {
            $contador++;
        }
    }
    return $contador;
}

function capitalizar_palabras($texto) {
    $texto = strtolower($texto);
    $contador = 0;
    //$vocales = ['a', 'e', 'i', 'o', 'u']; 

    for ($i = 0; $i < strlen($texto); $i++) {
        if (in_array($texto[$i], $vocales)) {
            $contador++;
        }
    }

    return $contador;
}
?>
