<?php

interface Prestable {
    public function obtenerDetallesPrestamo(): String;
}

abstract class RecursoBiblioteca implements Prestable{
    public $id;
    public $titulo;
    public $autor;
    public $anioPublicacion;
    public $estado;
    public $fechaAdquisicion;
    public $tipo;

    public function __construct($datos) {
        foreach ($datos as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}

class Libro extends RecursoBiblioteca{
    private $isbn;

    public function obtenerDetallesPrestamo(): String {
        return "ISBN: " . $this->isbn;
    }
}

class Revista extends RecursoBiblioteca{
    private $numeroEdicion;
}

class DVD extends RecursoBiblioteca {
    private $duracion;
}
// Implementar las clases Libro, Revista y DVD aquí

class GestorBiblioteca {
    private $recursos = [];

    public function cargarRecursos() {
        $json = file_get_contents('biblioteca.json');
        $data = json_decode($json, true);
        
        foreach ($data as $recursoData) {
            $recurso = null;
            if ($recursoData['tipo'] == 'Libro'){
 
            }
            $recurso = new RecursoBiblioteca($recursoData);
            $this->recursos[] = $recurso;
        }
        
        return $this->recursos;
    }

    public function agregarRecurso($recurso) {
        
    }

    // Implementar los demás métodos aquí
}