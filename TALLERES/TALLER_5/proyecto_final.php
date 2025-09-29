<?php
declare(strict_types=1);

/**
 * proyecto_final.php
 * Sistema de Gestión de Estudiantes - TALLER_5
 * Implementa clases Estudiante y SistemaGestionEstudiantes con múltiples funcionalidades.
 * Ejecución: php proyecto_final.php
 */

// ---------------------------
// Clase Estudiante
// ---------------------------
class Estudiante
{
    private int $id;
    private string $nombre;
    private int $edad;
    private string $carrera;
    /** @var array<string,float> */
    private array $materias = [];
    /** @var string[] */
    private array $flags = [];

    public function __construct(int $id, string $nombre, int $edad, string $carrera, array $materias = [])
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->carrera = $carrera;

        // validar e inicializar materias (nombre => calificacion)
        foreach ($materias as $mat => $cal) {
            $this->agregarMateria((string)$mat, (float)$cal);
        }

        // calcular flags iniciales
        $this->actualizarFlags();
    }

    public function getId(): int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getEdad(): int { return $this->edad; }
    public function getCarrera(): string { return $this->carrera; }
    /** @return array<string,float> */
    public function getMaterias(): array { return $this->materias; }
    /** @return string[] */
    public function getFlags(): array { return $this->flags; }

    public function agregarMateria(string $materia, float $calificacion): void
    {
        // validación simple
        if ($calificacion < 0 || $calificacion > 100) {
            throw new \InvalidArgumentException("Calificación para $materia fuera de rango (0-100): $calificacion");
        }
        $this->materias[$materia] = $calificacion;
        $this->actualizarFlags();
    }

    public function obtenerPromedio(): float
    {
        if (empty($this->materias)) return 0.0;
        // uso de array_reduce (función de orden superior)
        $suma = array_reduce(array_values($this->materias), fn($carry, $item) => $carry + $item, 0.0);
        return round($suma / count($this->materias), 2);
    }

    /**
     * Devuelve un arreglo asociativo con todos los detalles del estudiante
     * @return array<string,mixed>
     */
    public function obtenerDetalles(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'edad' => $this->edad,
            'carrera' => $this->carrera,
            'materias' => $this->materias,
            'promedio' => $this->obtenerPromedio(),
            'flags' => $this->flags,
        ];
    }

    public function __toString(): string
    {
        $materias = empty($this->materias) ? 'Ninguna' : implode(', ', array_map(fn($k, $v) => "$k: $v", array_keys($this->materias), $this->materias));
        return sprintf("Estudiante #%d - %s | Edad: %d | Carrera: %s\nMaterias: %s\nPromedio: %.2f | Flags: %s\n",
            $this->id, $this->nombre, $this->edad, $this->carrera, $materias, $this->obtenerPromedio(), implode(', ', $this->flags)
        );
    }

    // Marca flags según reglas: promedio >= 90 => honor roll; promedio < 60 => en riesgo; materia reprobada (<60) => "tiene reprobadas"
    private function actualizarFlags(): void
    {
        $this->flags = [];
        $prom = $this->obtenerPromedio();
        if ($prom === 0.0 && empty($this->materias)) {
            // sin materias, no aplican flags
            return;
        }
        if ($prom >= 90.0) $this->flags[] = 'honor roll';
        if ($prom < 60.0) $this->flags[] = 'en riesgo académico';

        // buscar materias reprobadas
        $reprobadas = array_filter($this->materias, fn($cal) => $cal < 60.0);
        if (!empty($reprobadas)) $this->flags[] = 'tiene reprobadas';
    }
}

// ---------------------------
// Clase SistemaGestionEstudiantes
// ---------------------------
class SistemaGestionEstudiantes
{
    /** @var Estudiante[] */
    private array $estudiantes = [];
    /** @var Estudiante[] */
    private array $graduados = [];

    public function agregarEstudiante(Estudiante $estudiante): void
    {
        if (isset($this->estudiantes[$estudiante->getId()]) || isset($this->graduados[$estudiante->getId()])) {
            throw new \RuntimeException("Ya existe un estudiante con ID " . $estudiante->getId());
        }
        $this->estudiantes[$estudiante->getId()] = $estudiante;
    }

    public function obtenerEstudiante(int $id): ?Estudiante
    {
        return $this->estudiantes[$id] ?? null;
    }

    /** @return Estudiante[] */
    public function listarEstudiantes(): array
    {
        return array_values($this->estudiantes);
    }

    public function calcularPromedioGeneral(): float
    {
        $est = $this->listarEstudiantes();
        if (empty($est)) return 0.0;
        // uso de array_map + array_reduce
        $promedios = array_map(fn(Estudiante $e) => $e->obtenerPromedio(), $est);
        $suma = array_reduce($promedios, fn($carry, $item) => $carry + $item, 0.0);
        return round($suma / count($promedios), 2);
    }

    /** @return Estudiante[] */
    public function obtenerEstudiantesPorCarrera(string $carrera): array
    {
        // búsqueda insensible a mayúsculas
        return array_values(array_filter($this->estudiantes, fn(Estudiante $e) => strcasecmp($e->getCarrera(), $carrera) === 0));
    }

    public function obtenerMejorEstudiante(): ?Estudiante
    {
        $est = $this->listarEstudiantes();
        if (empty($est)) return null;
        usort($est, fn(Estudiante $a, Estudiante $b) => $b->obtenerPromedio() <=> $a->obtenerPromedio());
        return $est[0];
    }

    /**
     * Genera reporte de rendimiento por materia: promedio, max, min
     * @return array<string,array<string,float|int>> estructura: materia => ['promedio'=>..., 'max'=>..., 'min'=>...]
     */
    public function generarReporteRendimiento(): array
    {
        $mapMaterias = [];
        foreach ($this->estudiantes as $est) {
            foreach ($est->getMaterias() as $mat => $cal) {
                $mapMaterias[$mat][] = $cal;
            }
        }

        $reporte = [];
        foreach ($mapMaterias as $mat => $calificaciones) {
            $suma = array_reduce($calificaciones, fn($c, $i) => $c + $i, 0.0);
            $reporte[$mat] = [
                'promedio' => round($suma / count($calificaciones), 2),
                'max' => max($calificaciones),
                'min' => min($calificaciones),
                'cantidad' => count($calificaciones),
            ];
        }

        return $reporte;
    }

    public function graduarEstudiante(int $id): bool
    {
        if (!isset($this->estudiantes[$id])) return false;
        $this->graduados[$id] = $this->estudiantes[$id];
        unset($this->estudiantes[$id]);
        return true;
    }

    /** @return Estudiante[] */
    public function generarRanking(): array
    {
        $est = $this->listarEstudiantes();
        usort($est, fn(Estudiante $a, Estudiante $b) => $b->obtenerPromedio() <=> $a->obtenerPromedio());
        return $est;
    }

    /**
     * Búsqueda por nombre o carrera (parcial, insensible)
     * @return Estudiante[]
     */
    public function buscarEstudiantes(string $query): array
    {
        $q = mb_strtolower(trim($query));
        return array_values(array_filter($this->estudiantes, function(Estudiante $e) use ($q) {
            return (mb_stripos($e->getNombre(), $q) !== false) || (mb_stripos($e->getCarrera(), $q) !== false);
        }));
    }

    /**
     * Estadísticas por carrera
     * @return array<string,array<string,mixed>> carrera => ['cantidad'=>int,'promedio'=>float,'mejor'=>Estudiante|null]
     */
    public function estadisticasPorCarrera(): array
    {
        // agrupar por carrera
        $grupos = [];
        foreach ($this->estudiantes as $e) {
            $c = $e->getCarrera();
            $grupos[$c][] = $e;
        }

        $stats = [];
        foreach ($grupos as $carrera => $alumnos) {
            $cantidad = count($alumnos);
            $promedios = array_map(fn(Estudiante $es) => $es->obtenerPromedio(), $alumnos);
            $promedioGeneral = $cantidad ? round(array_reduce($promedios, fn($a,$b)=>$a+$b,0)/$cantidad,2) : 0.0;
            usort($alumnos, fn($a,$b)=> $b->obtenerPromedio() <=> $a->obtenerPromedio());
            $mejor = $alumnos[0] ?? null;
            $stats[$carrera] = ['cantidad' => $cantidad, 'promedio' => $promedioGeneral, 'mejor' => $mejor];
        }
        return $stats;
    }

    // Persistencia simple: guardar y cargar JSON
    public function guardarJSON(string $ruta): void
    {
        $data = [
            'estudiantes' => array_map(fn(Estudiante $e) => $e->obtenerDetalles(), $this->listarEstudiantes()),
            'graduados' => array_map(fn(Estudiante $e) => $e->obtenerDetalles(), array_values($this->graduados)),
        ];
        file_put_contents($ruta, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function cargarJSON(string $ruta): void
    {
        if (!file_exists($ruta)) throw new \RuntimeException("Archivo no encontrado: $ruta");
        $raw = json_decode(file_get_contents($ruta), true);
        if (!is_array($raw)) throw new \RuntimeException("JSON inválido en $ruta");

        // reiniciar
        $this->estudiantes = [];
        $this->graduados = [];

        foreach ($raw['estudiantes'] ?? [] as $d) {
            $e = new Estudiante((int)$d['id'], (string)$d['nombre'], (int)$d['edad'], (string)$d['carrera'], (array)$d['materias']);
            $this->estudiantes[$e->getId()] = $e;
        }
        foreach ($raw['graduados'] ?? [] as $d) {
            $e = new Estudiante((int)$d['id'], (string)$d['nombre'], (int)$d['edad'], (string)$d['carrera'], (array)$d['materias']);
            $this->graduados[$e->getId()] = $e;
        }
    }
}

// ---------------------------
// Sección de prueba / demo
// ---------------------------
function demo()
{
    echo "-- Sistema de Gestión de Estudiantes - Demo --\n\n";
    $sistema = new SistemaGestionEstudiantes();

    // Crear 10 estudiantes con materias variadas
    $estudiantesData = [
        [1, 'Ana Gómez', 20, 'Ingeniería', ['Matemáticas'=>95, 'Física'=>88, 'Programación'=>92]],
        [2, 'Luis Pérez', 22, 'Derecho', ['Constitucional'=>75, 'Derecho Civil'=>68]],
        [3, 'María Rodríguez', 19, 'Medicina', ['Anatomía'=>85, 'Bioquímica'=>91, 'Ética'=>78]],
        [4, 'Carlos Ruiz', 21, 'Ingeniería', ['Matemáticas'=>58, 'Física'=>62, 'Electrónica'=>70]],
        [5, 'Sofía Herrera', 23, 'Administración', ['Contabilidad'=>88, 'Marketing'=>92]],
        [6, 'Diego Morales', 20, 'Ingeniería', ['Matemáticas'=>100, 'Programación'=>98]],
        [7, 'Lucía López', 22, 'Derecho', ['Constitucional'=>55, 'Derecho Penal'=>60]],
        [8, 'Pablo Sánchez', 24, 'Administración', ['Contabilidad'=>45, 'Marketing'=>50]],
        [9, 'Elena Díaz', 21, 'Medicina', ['Anatomía'=>95, 'Bioquímica'=>89]],
        [10,'Mateo Fernández', 20, 'Ingeniería', ['Matemáticas'=>80, 'Programación'=>85, 'Física'=>78]],
    ];

    foreach ($estudiantesData as [$id,$nombre,$edad,$carrera,$materias]) {
        $e = new Estudiante($id, $nombre, $edad, $carrera, $materias);
        $sistema->agregarEstudiante($e);
    }

    // Mostrar todos los estudiantes
    echo "Listado de estudiantes:\n";
    foreach ($sistema->listarEstudiantes() as $est) {
        echo $est, "\n"; // usa __toString()
    }

    // Promedio general
    echo "Promedio general del sistema: " . $sistema->calcularPromedioGeneral() . "\n\n";

    // Obtener estudiantes por carrera
    $ing = $sistema->obtenerEstudiantesPorCarrera('Ingeniería');
    echo "Estudiantes en Ingeniería: " . count($ing) . "\n";
    foreach ($ing as $i) echo " - {$i->getNombre()} (Promedio: {$i->obtenerPromedio()})\n";
    echo "\n";

    // Mejor estudiante
    $mejor = $sistema->obtenerMejorEstudiante();
    echo "Mejor estudiante: " . ($mejor ? $mejor->getNombre() . ' (Promedio: ' . $mejor->obtenerPromedio() . ')' : 'N/A') . "\n\n";

    // Generar reporte por materia
    echo "Reporte de rendimiento por materia:\n";
    $reporte = $sistema->generarReporteRendimiento();
    foreach ($reporte as $mat => $info) {
        echo " - $mat => Promedio: {$info['promedio']}, Max: {$info['max']}, Min: {$info['min']}, Cantidad: {$info['cantidad']}\n";
    }
    echo "\n";

    // Generar ranking
    echo "Ranking (top 5):\n";
    $ranking = $sistema->generarRanking();
    $top = array_slice($ranking, 0, 5);
    $pos = 1;
    foreach ($top as $r) {
        echo "{$pos}. {$r->getNombre()} - Promedio: {$r->obtenerPromedio()}\n";
        $pos++;
    }
    echo "\n";

    // Búsqueda parcial
    echo "Búsqueda 'dere':\n";
    $busq = $sistema->buscarEstudiantes('dere');
    foreach ($busq as $b) echo " - {$b->getNombre()} ({$b->getCarrera()})\n";
    echo "\n";

    // Estadísticas por carrera
    echo "Estadísticas por carrera:\n";
    $stats = $sistema->estadisticasPorCarrera();
    foreach ($stats as $c => $info) {
        $mejorNombre = $info['mejor'] ? $info['mejor']->getNombre() : 'N/A';
        echo " - $c => Cantidad: {$info['cantidad']}, Promedio: {$info['promedio']}, Mejor: $mejorNombre\n";
    }
    echo "\n";

    // Graduar un estudiante
    echo "Graduando estudiante ID 2...\n";
    $sistema->graduarEstudiante(2);
    echo "Estudiantes restantes: " . count($sistema->listarEstudiantes()) . "\n\n";

    // Persistir a JSON
    $ruta = __DIR__ . DIRECTORY_SEPARATOR . 'estudiantes_dump.json';
    $sistema->guardarJSON($ruta);
    echo "Datos guardados en: $ruta\n";

    // Demostración carga JSON
    $sistema2 = new SistemaGestionEstudiantes();
    $sistema2->cargarJSON($ruta);
    echo "Sistema cargado desde JSON, estudiantes: " . count($sistema2->listarEstudiantes()) . "\n";

    echo "\n-- Fin Demo --\n";
}

// Ejecutar demo si se corre directamente
if (php_sapi_name() === 'cli') {
    demo();
}

?>