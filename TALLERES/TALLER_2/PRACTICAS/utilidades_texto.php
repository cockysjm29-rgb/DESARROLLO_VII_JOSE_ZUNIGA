<?php
function contar_palabras($texto) {    
    $texto = trim($texto); // Eliminar espacios al inicio y al final    
    $palabras = explode(" ", $texto); // Separar el texto por espacios
    $contador = 0;

    // Recorrer con un bucle y contar solo las no vacías
    foreach ($palabras as $palabra) {
        if ($palabra !== "") {
            $contador++;
        }
    }

    return $contador;
}

function contar_vocales($texto) {
    // Convertir todo el texto a minúsculas
    $texto = strtolower($texto);

    $contador = 0;
    $vocales = ['a', 'e', 'i', 'o', 'u'];

    // Recorrer cada carácter de la cadena
    for ($i = 0; $i < strlen($texto); $i++) {
        if (in_array($texto[$i], $vocales)) {
            $contador++;
        }
    }

    return $contador;
}


function invertir_palabras($texto) {
    // Eliminar espacios al inicio y final
    $texto = trim($texto);

    // Separar las palabras en un arreglo
    $palabras = explode(" ", $texto);

    $resultado = "";

    // Recorrer desde el final hacia el inicio
    for ($i = count($palabras) - 1; $i >= 0; $i--) {
        if ($palabras[$i] !== "") {
            $resultado .= $palabras[$i] . " ";
        }
    }

    // Eliminar espacio extra al final
    return trim($resultado);
}
?>