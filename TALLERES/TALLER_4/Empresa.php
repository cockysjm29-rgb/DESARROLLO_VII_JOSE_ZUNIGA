<?php
// Empresa.php
require_once 'Empleado.php';
require_once 'Evaluable.php';
require_once 'Gerente.php';
require_once 'Desarrollador.php';

/**
 * Clase Empresa - administra empleados de diferentes tipos
 */
class Empresa {
    /**
     * @var Empleado[] $empleados
     */
    private array $empleados = [];

    // Agregar un empleado (cualquier instancia de Empleado o subclase)
    public function agregarEmpleado(Empleado $empleado): void {
        // Evitar IDs duplicados
        foreach ($this->empleados as $e) {
            if ($e->getIdEmpleado() === $empleado->getIdEmpleado()) {
                throw new InvalidArgumentException("Ya existe un empleado con ID {$empleado->getIdEmpleado()}.");
            }
        }
        $this->empleados[] = $empleado;
    }

    // Listar todos los empleados (devuelve array con información)
    public function listarEmpleados(): array {
        $listado = [];
        foreach ($this->empleados as $empleado) {
            // Si la clase define su propio obtenerInformacion, lo usamos; si no, usamos el de Empleado
            if (method_exists($empleado, 'obtenerInformacion')) {
                $listado[] = $empleado->obtenerInformacion();
            } else {
                $listado[] = $empleado->getNombre() . " | ID: " . $empleado->getIdEmpleado();
            }
        }
        return $listado;
    }

    // Calcular la nómina total (salarios base + bonos asignados si existen en subclases)
    public function calcularNominaTotal(): float {
        $total = 0.0;
        foreach ($this->empleados as $empleado) {
            $total += $empleado->getSalarioBase();
            // Si el empleado es Gerente, sumar su bono asignado
            if ($empleado instanceof Gerente) {
                $total += $empleado->getBonoAsignado();
            }
            // Si más adelante otras clases tienen bonos, se pueden detectar aquí también.
        }
        return $total;
    }

    /**
     * Realiza las evaluaciones de desempeño en todos los empleados que implementen Evaluable.
     * Devuelve un array con resultados y advertencias para los no-evaluables.
     *
     * @return array ['resultados' => [...], 'advertencias' => [...]]
     */
    public function realizarEvaluaciones(): array {
        $resultados = [];
        $advertencias = [];

        foreach ($this->empleados as $empleado) {
            if ($empleado instanceof Evaluable) {
                try {
                    $evaluacion = $empleado->evaluarDesempenio();
                    // Si la evaluación sugiere un 'bono' y el empleado es Gerente, podemos asignarlo opcionalmente.
                    if (isset($evaluacion['bono']) && is_numeric($evaluacion['bono']) && $empleado instanceof Gerente) {
                        // Asignamos el bono sugerido automáticamente como ejemplo de flujo.
                        $empleado->asignarBono(floatval($evaluacion['bono']));
                    }
                    $resultados[] = [
                        'id' => $empleado->getIdEmpleado(),
                        'nombre' => $empleado->getNombre(),
                        'tipo' => get_class($empleado),
                        'evaluacion' => $evaluacion
                    ];
                } catch (Throwable $t) {
                    $advertencias[] = "Error evaluando {$empleado->getNombre()} (ID {$empleado->getIdEmpleado()}): " . $t->getMessage();
                }
            } else {
                // Manejo de caso de intento de evaluar un empleado no-evaluable: no se ejecuta, se deja advertencia.
                $advertencias[] = "Empleado {$empleado->getNombre()} (ID {$empleado->getIdEmpleado()}) no implementa Evaluable y no fue evaluado.";
            }
        }

        return ['resultados' => $resultados, 'advertencias' => $advertencias];
    }
}