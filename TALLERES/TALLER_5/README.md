# Sistema de Gestión de Estudiantes

## Descripción
Este proyecto es un **Sistema de Gestión de Estudiantes** desarrollado en PHP que permite administrar información de estudiantes, calcular promedios, generar reportes y estadísticas, y graduar estudiantes. Incluye una versión interactiva para **navegador web**.

El sistema se implementa con **programación orientada a objetos** y hace uso avanzado de **arreglos asociativos y multidimensionales**, así como funciones de orden superior como `array_map`, `array_filter` y `array_reduce`.

## Estructura de Archivos
- `proyecto_final.php`: Implementación de las clases `Estudiante` y `SistemaGestionEstudiantes` para línea de comandos.
- `proyecto_final_web.php`: Versión adaptada para navegador con interfaz HTML, tablas, búsqueda y botones de acción.
- `README.md`: Documentación del proyecto.
- `estudiantes_dump.json`: Archivo generado opcionalmente para persistir datos.

## Clases Principales

### Estudiante
Atributos:
- `id` (int)
- `nombre` (string)
- `edad` (int)
- `carrera` (string)
- `materias` (array asociativo: nombre => calificación)
- `flags` (array de strings: honor roll, en riesgo académico, tiene reprobadas)

Métodos:
- `__construct()` - Inicializa un estudiante con sus materias.
- `agregarMateria($materia, $calificacion)` - Añade materia y calificación.
- `obtenerPromedio()` - Calcula promedio de calificaciones.
- `obtenerDetalles()` - Retorna arreglo con toda la información del estudiante.
- `__toString()` - Representación textual para impresión.

### SistemaGestionEstudiantes
Atributos:
- `estudiantes` (array de objetos `Estudiante`)
- `graduados` (array de estudiantes graduados)

Métodos:
- `agregarEstudiante($estudiante)`
- `obtenerEstudiante($id)`
- `listarEstudiantes()`
- `calcularPromedioGeneral()`
- `obtenerEstudiantesPorCarrera($carrera)`
- `obtenerMejorEstudiante()`
- `generarReporteRendimiento()`
- `graduarEstudiante($id)`
- `generarRanking()`
- `buscarEstudiantes($query)` - búsqueda parcial insensible a mayúsculas.
- `estadisticasPorCarrera()`
- `guardarJSON($ruta)` y `cargarJSON($ruta)` - persistencia de datos.

## Funcionalidades
- Crear y administrar estudiantes con múltiples materias.
- Cálculo de promedios individuales y generales.
- Flags automáticos: honor roll, en riesgo académico, materias reprobadas.
- Reporte de rendimiento por materia (promedio, máximo, mínimo, cantidad).
- Ranking de estudiantes según promedio.
- Estadísticas por carrera.
- Graduar estudiantes y mantener registro de graduados.
- Búsqueda parcial por nombre o carrera.
- Persistencia de datos en formato JSON.
- Interfaz web interactiva con tablas y formularios.

## Instrucciones de Uso

### Versión CLI
1. Ejecutar desde línea de comandos:
```bash
php proyecto_final.php
```
2. La demo crea 10 estudiantes y muestra resultados en la consola.

### Versión Web
1. Colocar `proyecto_final_web.php` y `proyecto_final.php` en el mismo directorio accesible por el servidor web.
2. Acceder mediante navegador a `proyecto_final_web.php`.
3. Usar la interfaz para:
   - Ver listado de estudiantes.
   - Buscar estudiantes por nombre o carrera.
   - Graduar estudiantes usando el botón correspondiente.
   - Visualizar reportes, ranking y estadísticas.

## Requerimientos
- PHP 7.4 o superior.
- Servidor web (opcional para versión web: Apache, Nginx o PHP Built-in Server).

## Retos Adicionales Implementados
- Persistencia simple usando JSON.
- Interfaz web completa con HTML y CSS.
- Validación básica de calificaciones (0-100).

## Autor
Jose Zuñiga

