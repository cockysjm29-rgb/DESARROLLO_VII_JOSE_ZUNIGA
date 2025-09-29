<?php
// Desarrollador.php
require_once 'Empleado.php';
require_once 'Evaluable.php';

/**
 * Clase Desarrollador - hereda de Empleado e implementa Evaluable
 */
class Desarrollador extends Empleado implements Evaluable {
    private string $lenguajePrincipal;
    private string $nivel; // e.g., "Junior", "Mid", "Senior"

    public function __construct(string $nombre, string $idEmpleado, float $salarioBase, string $lenguajePrincipal, string $nivel) {
        parent::__construct($nombre, $idEmpleado, $salarioBase);
        $this->lenguajePrincipal = $lenguajePrincipal;
        $this->nivel = $nivel;
    }

    // Getters / Setters
    public function getLenguajePrincipal(): string {
        return $this->lenguajePrincipal;
    }

    public function setLenguajePrincipal(string $lenguajePrincipal): void {
        $this->lenguajePrincipal = $lenguajePrincipal;
    }

    public function getNivel(): string {
        return $this->nivel;
    }

    public function setNivel(string $nivel): void {
        $this->nivel = $nivel;
    }

    // Implementación de la evaluación específica para Desarrollador
    public function evaluarDesempenio(): array {
        // Evaluación basada en nivel
        $nivelLower = strtolower($this->nivel);
        switch ($nivelLower) {
            case 'senior':
                $score = 90;
                $comentario = "Desarrollador senior con alto rendimiento esperado.";
                $bono = $this->getSalarioBase() * 0.12; // 12%
                break;
            case 'mid':
            case 'intermedio':
                $score = 75;
                $comentario = "Buen desempeño. Puede mejorar en arquitectura y revisión de código.";
                $bono = $this->getSalarioBase() * 0.08; // 8%
                break;
            case 'junior':
            default:
                $score = 60;
                $comentario = "Nivel junior. Recomendado plan de capacitación y mentoreo.";
                $bono = $this->getSalarioBase() * 0.04; // 4%
                break;
        }

        // Ajuste por lenguaje (ejemplo: lenguajes críticos pueden añadir puntos)
        $lang = strtolower($this->lenguajePrincipal);
        if (in_array($lang, ['php','java','python','javascript'])) {
            $comentario .= " Competencias en {$this->lenguajePrincipal} valoradas positivamente.";
            $score += 3;
        }

        if ($score > 100) $score = 100;

        return [
            'score' => $score,
            'comentario' => $comentario,
            'bono' => round($bono, 2)
        ];
    }

    public function obtenerInformacion(): string {
        return parent::obtenerInformacion() . " | Lenguaje: {$this->lenguajePrincipal} | Nivel: {$this->nivel}";
    }
}
