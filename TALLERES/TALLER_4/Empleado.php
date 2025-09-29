<?php
// Empleado.php
// Clase base que representa un empleado genérico.

class Empleado {
    // Propiedades privadas (encapsulación)
    private string $nombre;
    private string $idEmpleado;
    private float $salarioBase;

    // Constructor
    public function __construct(string $nombre, string $idEmpleado, float $salarioBase) {
        $this->nombre = $nombre;
        $this->idEmpleado = $idEmpleado;
        $this->salarioBase = $salarioBase;
    }

    // Getters
    public function getNombre(): string {
        return $this->nombre;
    }

    public function getIdEmpleado(): string {
        return $this->idEmpleado;
    }

    public function getSalarioBase(): float {
        return $this->salarioBase;
    }

    // Setters
    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setIdEmpleado(string $idEmpleado): void {
        $this->idEmpleado = $idEmpleado;
    }

    public function setSalarioBase(float $salarioBase): void {
        if ($salarioBase < 0) {
            throw new InvalidArgumentException("El salario no puede ser negativo.");
        }
        $this->salarioBase = $salarioBase;
    }

    // Método para obtener información legible del empleado
    public function obtenerInformacion(): string {
        return sprintf("Nombre: %s | ID: %s | Salario base: %.2f",
            $this->nombre, $this->idEmpleado, $this->salarioBase);
    }
}