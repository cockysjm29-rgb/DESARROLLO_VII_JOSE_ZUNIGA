<?php
// Gerente.php
require_once 'Empleado.php';
require_once 'Evaluable.php';

/**
 * Clase Gerente - hereda de Empleado e implementa Evaluable
 */
class Gerente extends Empleado implements Evaluable {
    private string $departamento;
    private float $bonoAsignado = 0.0;

    public function __construct(string $nombre, string $idEmpleado, float $salarioBase, string $departamento) {
        parent::__construct($nombre, $idEmpleado, $salarioBase);
        $this->departamento = $departamento;
    }

    // Getter / Setter departamento
    public function getDepartamento(): string {
        return $this->departamento;
    }

    public function setDepartamento(string $departamento): void {
        $this->departamento = $departamento;
    }

    // Asigna un bono directo al gerente (puede usarse tras evaluación)
    public function asignarBono(float $monto): void {
        if ($monto < 0) {
            throw new InvalidArgumentException("Bono no puede ser negativo.");
        }
        $this->bonoAsignado += $monto;
    }

    public function getBonoAsignado(): float {
        return $this->bonoAsignado;
    }

    // Implementación de la evaluación
    public function evaluarDesempenio(): array {
        // Lógica simple de ejemplo: puntuación basada en salario y departamento
        // (Solo como demostración; en producción sería más elaborada.)
        $salario = $this->getSalarioBase();
        // Score base: entre 50 y 90 según salario
        $score = min(90, max(50, intval($salario / 1000 * 5) + 50));

        // Ajuste por departamento (ejemplo simplificado)
        $comentario = "Evaluación estándar para gerente del departamento {$this->departamento}.";
        $bono = 0.0;
        if (strtolower($this->departamento) === 'ventas') {
            $score += 5;
            $comentario .= " Buen desempeño en ventas detectado.";
            $bono = $salario * 0.10; // 10% del salario como ejemplo
        } elseif (strtolower($this->departamento) === 'operaciones') {
            $score += 3;
            $bono = $salario * 0.07;
        } else {
            $bono = $salario * 0.05;
        }

        // Normalizar score a max 100
        if ($score > 100) $score = 100;

        // No asignamos el bono automáticamente aquí, solo lo sugerimos
        return [
            'score' => $score,
            'comentario' => $comentario,
            'bono' => round($bono, 2)
        ];
    }

    // Información extendida
    public function obtenerInformacion(): string {
        return parent::obtenerInformacion() . " | Departamento: {$this->departamento} | Bono asignado: " . number_format($this->bonoAsignado, 2);
    }
}
