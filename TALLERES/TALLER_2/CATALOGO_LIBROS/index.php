<?php
// Incluimos funciones y plantillas
require_once "includes/funciones.php";
include "includes/header.php";

// Obtener los libros
$libros = obtenerLibros();

// Ordenar los libros por título
usort($libros, function($a, $b) {
    return strcmp($a['titulo'], $b['titulo']);
});
?>

<div class="contenedor">
    <h2>Catálogo de Libros</h2>
    <?php
        foreach ($libros as $libro) {
            echo mostrarDetallesLibro($libro);
        }
    ?>
</div>

<?php include "includes/footer.php"; 
?>