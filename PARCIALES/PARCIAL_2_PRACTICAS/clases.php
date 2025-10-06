<?php
// Archivo: clases.php

interface Detalle {
    public function obtenerDetallesEspecificos(): string;
}

class Tarea implements Detalle {
    public $id;
    public $titulo;
    public $descripcion;
    public $estado;
    public $prioridad;
    public $fechaCreacion;
    public $tipo;

    public function __construct($datos) {
        foreach ($datos as $key => $value) {
             if (property_exists($this, $key)) {
             $this->$key = $value;
    }
  }
}

    // Implementar estos getters
    // public function getEstado() { }
    // public function getPrioridad() { }

     public function obtenerDetallesEspecificos(): string {
        return "Detalles no especificados para tipo genérico de tarea.";
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getPrioridad() {
        return $this->prioridad;
    }
}

    class TareaDesarrollo extends Tarea {
    public string $lenguajeProgramacion;

    public function obtenerDetallesEspecificos(): string {
        return "Lenguaje de programación: " . $this->lenguajeProgramacion;
    }
}

    class TareaDiseno extends Tarea {
    public string $herramientaDiseno;

    public function obtenerDetallesEspecificos(): string {
        return "Herramienta de diseño: " . $this->herramientaDiseno;
    }
}

  class TareaTesting extends Tarea {
    public string $tipoTest;

    public function obtenerDetallesEspecificos(): string {
        return "Tipo de test: " . $this->tipoTest;
    }
}

class GestorTareas {
    private $tareas = [];
    private $archivo = 'tareas.json';

    public function cargarTareas() {
        if (!file_exists($this->archivo)) {
            return [];
        }

        $json = file_get_contents($this->archivo);
        $data = json_decode($json, true);
        foreach ($data as $tareaData) {
            $tipo = strtolower($tareaData['tipo'] ?? '');

            switch ($tipo) {
                case 'desarrollo':
                    $tarea = new TareaDesarrollo($tareaData);
                    break;
                case 'diseño':
                case 'diseno':
                    $tarea = new TareaDiseno($tareaData);
                    break;
                case 'testing':
                    $tarea = new TareaTesting($tareaData);
                    break;
                default:
                    $tarea = new Tarea($tareaData);
            }

            $this->tareas[] = $tarea;
        }
        return $this->tareas;
    }

    private function guardarTareas() {
        $json = json_encode($this->tareas, JSON_PRETTY_PRINT);
        file_put_contents($this->archivo, $json);
    }

    public function agregarTarea($tarea) {
        $tarea->id = $this->generarNuevoId();
        $tarea->fechaCreacion = date('Y-m-d');
        $this->tareas[] = $tarea;
        $this->guardarTareas();
    }

    public function eliminarTarea($id) {
        $this->tareas = array_filter($this->tareas, fn($t) => $t->id != $id);
        $this->guardarTareas();
    }

    public function actualizarTarea($tareaActualizada) {
        foreach ($this->tareas as &$tarea) {
            if ($tarea->id == $tareaActualizada->id) {
                $tarea = $tareaActualizada;
                break;
            }
        }
        $this->guardarTareas();
    }

    public function actualizarEstadoTarea($id, $nuevoEstado) {
        foreach ($this->tareas as &$tarea) {
            if ($tarea->id == $id) {
                $tarea->estado = $nuevoEstado;
                break;
            }
        }
        $this->guardarTareas();
    }

    public function buscarTareasPorEstado($estado) {
        return array_filter($this->tareas, fn($t) => $t->estado == $estado);
    }

    public function listarTareas($filtroEstado = '') {
        if ($filtroEstado) {
            return $this->buscarTareasPorEstado($filtroEstado);
        }
        return $this->tareas;
    }

    private function generarNuevoId() {
        $ids = array_map(fn($t) => $t->id, $this->tareas);
        return empty($ids) ? 1 : max($ids) + 1;
    }
}

// Implementar:
// 1. La interfaz Detalle
// 2. Modificar la clase Tarea para implementar la interfaz Detalle
// 3. Las clases TareaDesarrollo, TareaDiseno y TareaTesting que hereden de Tarea