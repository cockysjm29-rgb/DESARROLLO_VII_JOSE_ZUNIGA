<?php
interface Prestable{
    public function obtenerDetallePrestamo():string;
}
abstract class RecursoBiblioteca implements Prestable {
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

// Implementar las clases Libro, Revista y DVD aquí

class GestorBiblioteca {
    private $recursos = [];

    public function cargarRecursos() {
        $this->recursos = [];
        $json = file_get_contents('biblioteca.json');
        $data = json_decode($json, true);
        
        foreach ($data as $recursoData) {
           $recurso = $this->obtenerInstancia($recursoData);
              if ($recurso !== null) {
                $this->recursos[] = $recurso;
              }
        }
        
        return $this->recursos;
        
    }

    // Implementar los demás métodos aquí
    public function agregarRecurso($recursoData) {
        $this->cargarRecursos();
        $recurso = $this->obtenerInstancia($recursoData);
        $recurso->id = $this->obtenerSiguienteId();
        
        $this->recursos[]= $recurso;
        $arrayRecursos = [];
        foreach($this->recursos as $r){
            $arrayRecursos[] = (array)$r;
        }
        file_put_contents('biblioteca.json', json_encode($arrayRecursos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function actualizarRecurso($recursoData){
        print_r($recursoData);
        $this-> cargarRecursos();
        $indice = 0;
        $recursoNuevo = $this-> obtenerInstancia($recursoData);
        foreach($this->recursos as $recursoActual){
            if($recursoActual-> id == $recursoNuevo->id){
                $this->recursos[$indice] = $recursoNuevo;
            }
            $indice++;
        }
         file_put_contents('biblioteca.json', json_encode($this->recursos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    }

    public function actualizarEstadoRecurso($id, $nuevoEstado){
        
        $this-> cargarRecursos();
        $indice = 0;
        //$recursoNuevo = $this-> obtenerInstancia($nuevoEstado);

        foreach($this->recursos as $recurso){
            if($recurso-> id == $id){
                $this->recursos[$indice]->estado = $nuevoEstado;
                break;
            }
            $indice++;
        }
         file_put_contents('biblioteca.json', json_encode($this->recursos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    }

    public function buscarRecursoPorId($id){
        $this-> cargarRecursos();
        foreach($this->recursos as $recurso){
            if($recurso -> id == $id){
                return $recurso;
            }
        }
    }

    public function buscarRecursoPorEstado($estado){
        $this -> cargarRecursos();
        $recursosValidos = [];
        foreach($this->recursos as $recurso){
            if($recurso->estado ==$estado){
                $recursosValidos[] = $recurso;

            }
        }
        //array_filter($this->recursos, function ($recurso) use ($estado))
        return $recursosValidos;
    }

    private function obtenerInstancia($recursoData){
        
            if($recursoData['tipo']=='Libro'){
                return new libro($recursoData);
            }elseif ($recursoData['tipo']=='Revista'){
                return new revista($recursoData);
            } 
            elseif ($recursoData['tipo']=='DVD'){
                return new dvd($recursoData);
            }
            
            return null;
           }
        
    

    private function obtenerSiguienteId(){
        $c = 0;
        foreach($this->recursos as $recurso){
            if ($recurso->id > $c){
                $c = $recurso->id;
            }
        }
        return $c + 1;
    }

    public function eliminarRecurso($id){
        $this->cargarRecursos();
        $recursos = [];
        foreach($this->recursos as $recurso){
            if($recurso-> id != $id){
                $recursos[] = $recurso;
            }
        }
        $this->recursos = $recursos;
        file_put_contents('biblioteca.json', json_encode($this->recursos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    
    public function listarRecursos($filtroEstado = "", $campoOrden = 'id', $direccionOrden = 'ASC') {
        $this->cargarRecursos();
        $recursosFiltrados = $this->recursos;

    // Filtrar por estado si se especifica
    if ($filtroEstado !== "") {
        $recursosFiltrados = array_filter($recursosFiltrados, function($recurso) use ($filtroEstado) {
            return $recurso->estado === $filtroEstado;
        });
    }

    // Ordenar por campo y dirección
    usort($recursosFiltrados, function($a, $b) use ($campoOrden, $direccionOrden) {
        if ($a->$campoOrden == $b->$campoOrden) return 0;
        if ($direccionOrden === 'ASC') {
            return ($a->$campoOrden < $b->$campoOrden) ? -1 : 1;
        } else {
            return ($a->$campoOrden > $b->$campoOrden) ? -1 : 1;
        }
    });

    return $recursosFiltrados;
}

}


class libro extends RecursoBiblioteca{
    public $isbn;
    public function obtenerDetallePrestamo(): string{
        return "ISBN:". $this->isbn;
    }
}

class revista extends RecursoBiblioteca{
    public $numeroEdicion;
    
    public function obtenerDetallePrestamo():string{
        return "Número de Edición:". $this->numeroEdicion;
    }
}
class dvd extends RecursoBiblioteca{
    public $duracion;

    public function obtenerDetallePrestamo():string{
        return "Duración:". $this->duracion;
    }
}