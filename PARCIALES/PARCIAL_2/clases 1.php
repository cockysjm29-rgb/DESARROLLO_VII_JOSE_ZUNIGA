<?php

interface Inventariable {
    public function obtenerInformacionInventario(): string;
}

class Producto implements Inventariable {
    public $id;
    public $nombre;
    public $descripcion;
    public $estado;
    public $stock;
    public $fechaIngreso;
    public $categoria;

     
    public function __construct($datos) {
        foreach ($datos as $clave => $valor) {
            if (property_exists($this, $clave)) {
                $this->$clave = $valor;
            }
        }
    }

    public function obtenerInformacionInventario(): string {
        return "Producto: {$this->nombre} - Stock: {$this->stock}";
    }
}

 
class ProductoElectronico extends Producto {
    public $garantiaMeses;
    public function __construct($datos) {
        parent::__construct($datos);
        if (isset($datos['garantiaMeses'])) {
            $this->garantiaMeses = $datos['garantiaMeses'];
        }
    }

    public function obtenerInformacionInventario(): string {
        return "Producto Electrónico: {$this->nombre} - Stock: {$this->stock} - Garantía: {$this->garantiaMeses} meses";
    }
}


class ProductoAlimento extends Producto {
    public $fechaVencimiento;

    public function __construct($datos) {
        parent::__construct($datos);
        if (isset($datos['fechaVencimiento'])) {
            $this->fechaVencimiento = $datos['fechaVencimiento'];
        }
    }

    public function obtenerInformacionInventario(): string {
        return "Producto Alimento: {$this->nombre} - Stock: {$this->stock} - Vence: {$this->fechaVencimiento}";
    }
}
class ProductoRopa extends Producto {
    public $talla;

    public function __construct($datos) {
        parent::__construct($datos);
        if (isset($datos['talla'])) {
            $this->talla = $datos['talla'];
        }
    }

   
    public function obtenerInformacionInventario(): string {
        return "Producto Ropa: {$this->nombre} - Stock: {$this->stock} - Talla: {$this->talla}";
    }
}


class GestorInventario {
    private $items = [];
    private $rutaArchivo = 'productos.json';

    public function obtenerTodos() {
        if (empty($this->items)) {
            $this->cargarDesdeArchivo();
        }
        return $this->items;
    }

    private function cargarDesdeArchivo() {
        if (!file_exists($this->rutaArchivo)) {
            return;
        }
        
        $jsonContenido = file_get_contents($this->rutaArchivo);
        $arrayDatos = json_decode($jsonContenido, true);
        
        if ($arrayDatos === null) {
            return;
        }
        
        foreach ($arrayDatos as $datos) {
            switch ($datos['categoria']) {
                case 'electronico':
                    $this->items[] = new ProductoElectronico($datos);
                    break;
                case 'alimento':
                    $this->items[] = new ProductoAlimento($datos);
                    break;
                case 'ropa':
                    $this->items[] = new ProductoRopa($datos);
                    break;
                default:
                    $this->items[] = new Producto($datos);
                    break;
            }
        }
    }


    private function persistirEnArchivo() {
        $arrayParaGuardar = array_map(function($item) {
            return get_object_vars($item);
        }, $this->items);
        
        file_put_contents(
            $this->rutaArchivo, 
            json_encode($arrayParaGuardar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }


    public function obtenerMaximoId() {
        $this->obtenerTodos();
        
        if (empty($this->items)) {
            return 0;
        }
        
        $ids = array_map(function($item) {
            return $item->id;
        }, $this->items);
        
        return max($ids);
    }

    public function agregar($nuevoProducto) {
        $this->obtenerTodos();
        $nuevoProducto->id = $this->obtenerMaximoId() + 1;
        $nuevoProducto->fechaIngreso = date('Y-m-d');
        $this->items[] = $nuevoProducto;
        $this->persistirEnArchivo();
    }

    public function eliminar($idProducto) {
        $this->obtenerTodos();
        
        foreach ($this->items as $indice => $item) {
            if ($item->id == $idProducto) {
                unset($this->items[$indice]);
                $this->items = array_values($this->items);
                $this->persistirEnArchivo();
                return true;
            }
        }
        return false;
    }

    public function actualizar($productoActualizado) {
        $this->obtenerTodos();
        
        foreach ($this->items as $indice => $item) {
            if ($item->id == $productoActualizado->id) {
                $this->items[$indice] = $productoActualizado;
                
                $this->persistirEnArchivo();
                return true;
            }
        }
        return false;
    }

    public function filtrarPorEstado($estadoBuscado) {
        $this->obtenerTodos();
        
        if (empty($estadoBuscado)) {
            return $this->items;
        }
        
        return array_filter($this->items, function($item) use ($estadoBuscado) {
            return $item->estado === $estadoBuscado;
        });
    }

    public function obtenerPorId($idBuscado) {
        $this->obtenerTodos();
        
        foreach ($this->items as $item) {
            if ($item->id == $idBuscado) {
                return $item;
            }
        }
        return null;
    }
}
