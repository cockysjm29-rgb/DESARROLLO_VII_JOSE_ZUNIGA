<?php
function obtenerLibros() {
    return [
        [
            'titulo' => 'El Quijote',
            'autor' => 'Miguel de Cervantes',
            'anio_publicacion' => 1605,
            'genero' => 'Novela',
            'descripcion' => 'La historia del ingenioso hidalgo Don Quijote de la Mancha.'
        ],
        [
            'titulo' => 'Cien años de soledad',
            'autor' => 'Gabriel García Márquez',
            'anio_publicacion' => 1967,
            'genero' => 'Realismo mágico',
            'descripcion' => 'La historia de la familia Buendía en el pueblo ficticio de Macondo.'
        ],
        [
            'titulo' => 'La Odisea',
            'autor' => 'Homero',
            'anio_publicacion' => -800,
            'genero' => 'Épica',
            'descripcion' => 'El viaje de Odiseo de regreso a Ítaca tras la guerra de Troya.'
        ],
        [
            'titulo' => '1984',
            'autor' => 'George Orwell',
            'anio_publicacion' => 1949,
            'genero' => 'Distopía',
            'descripcion' => 'Una crítica al totalitarismo en un mundo gobernado por el Gran Hermano.'
        ],
        [
            'titulo' => 'Orgullo y prejuicio',
            'autor' => 'Jane Austen',
            'anio_publicacion' => 1813,
            'genero' => 'Romántica',
            'descripcion' => 'La historia de Elizabeth Bennet y sus enredos amorosos en la Inglaterra georgiana.'
        ]
    ];
}

function mostrarDetallesLibro($libro) {
    return "
    <div class='libro'>
        <h3>{$libro['titulo']}</h3>
        <p><strong>Autor:</strong> {$libro['autor']}</p>
        <p><strong>Año de publicación:</strong> {$libro['anio_publicacion']}</p>
        <p><strong>Género:</strong> {$libro['genero']}</p>
        <p>{$libro['descripcion']}</p>
    </div>
    ";
}