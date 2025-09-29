<?php
// Evaluable.php
// Interfaz para empleados que pueden ser evaluados

interface Evaluable {
    /**
     * Realiza la evaluación de desempeño del empleado.
     * Debe devolver un array con al menos:
     *  - 'score' (int o float)
     *  - 'comentario' (string)
     *  - 'bono' (float) opcional: monto de bono sugerido
     *
     * @return array
     */
    public function evaluarDesempenio(): array;
}