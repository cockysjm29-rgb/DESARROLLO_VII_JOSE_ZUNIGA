<?php
echo "<h2>Inventario Actual</h2>";

// Leer inventario desde JSON
function leerInventario($archivo) {
    $contenido = file_get_contents($archivo);
    return json_decode($contenido, true);
}

// Ordenar el inventario alfabeticamente por nombre
function ordenarInventario($inventario) {
    usort($inventario, function ($a, $b) {
          return $b['precio'] <=> $a['precio'];        
    });
    return $inventario;
}

// Mostrar resumen del inventario
function mostrarResumen($inventario) {    
    foreach ($inventario as $producto) {
        echo "Producto: {$producto['nombre']} | Precio: \${$producto['precio']} | Cantidad: {$producto['cantidad']}\n";
    }
    echo "\n";
}

// Calcular el valor total del inventario
function valorTotal($inventario) {
    $total = array_sum(array_map(function ($p) {
        return $p['precio'] * $p['cantidad'];
    }, $inventario));
    return $total;
}

// Generar informe de productos con stock bajo
function productosStockBajo($inventario, $limite = 5) {
    $bajoStock = array_filter($inventario, function ($p) use ($limite) {
        return $p['cantidad'] < $limite;
    });
    return $bajoStock;
}

// Script principal

$archivo = "inventario.json";

$inventario = leerInventario($archivo);

$inventarioOrdenado = ordenarInventario($inventario);

mostrarResumen($inventarioOrdenado);

$total = valorTotal($inventarioOrdenado);
echo "<p><br>Valor total del inventario: \$$total\n\n</br></p>";

echo "Productos con Stock Bajo (menos de 5):\n";
$bajoStock = productosStockBajo($inventarioOrdenado);
if (empty($bajoStock)) {
    echo "No hay productos con stock bajo.\n</br>";
} else {
    foreach ($bajoStock as $producto) {
        echo "{$producto['nombre']} ({$producto['cantidad']}\n";
    }
}
echo "\n";
?>


 
