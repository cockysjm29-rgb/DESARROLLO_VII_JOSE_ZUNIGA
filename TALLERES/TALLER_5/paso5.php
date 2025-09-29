<?php
// 1. Crear un string JSON con datos de una tienda en línea
$jsonDatos = '
{
    "tienda": "ElectroTech",
    "productos": [
        {"id": 1, "nombre": "Laptop Gamer", "precio": 1200, "categorias": ["electrónica", "computadoras"]},
        {"id": 2, "nombre": "Smartphone 5G", "precio": 800, "categorias": ["electrónica", "celulares"]},
        {"id": 3, "nombre": "Auriculares Bluetooth", "precio": 150, "categorias": ["electrónica", "accesorios"]},
        {"id": 4, "nombre": "Smart TV 4K", "precio": 700, "categorias": ["electrónica", "televisores"]},
        {"id": 5, "nombre": "Tablet", "precio": 300, "categorias": ["electrónica", "computadoras"]}
    ],
    "clientes": [
        {"id": 101, "nombre": "Ana López", "email": "ana@example.com"},
        {"id": 102, "nombre": "Carlos Gómez", "email": "carlos@example.com"},
        {"id": 103, "nombre": "María Rodríguez", "email": "maria@example.com"}
    ]
}
';

// 2. Convertir el JSON a un arreglo asociativo de PHP
$tiendaData = json_decode($jsonDatos, true);

// 3. Función para imprimir los productos
function imprimirProductos($productos) {
    foreach ($productos as $producto) {
        //echo "{$producto['nombre']} - ${$producto['precio']} - Categorías: " . implode(", ", $producto['categorias']) . "\n";
          echo "{$producto['nombre']} - {$producto['precio']} - Categorías: " . implode(", ", $producto['categorias']) . "\n";
    }
}

echo "Productos de {$tiendaData['tienda']}:\n";
imprimirProductos($tiendaData['productos']);

// 4. Calcular el valor total del inventario
$valorTotal = array_reduce($tiendaData['productos'], function($total, $producto) {
    return $total + $producto['precio'];
}, 0);

echo "\nValor total del inventario: $$valorTotal\n";

// 5. Encontrar el producto más caro
$productoMasCaro = array_reduce($tiendaData['productos'], function($max, $producto) {
    return ($producto['precio'] > $max['precio']) ? $producto : $max;
}, $tiendaData['productos'][0]);

//echo "\nProducto más caro: {$productoMasCaro['nombre']} (${$productoMasCaro['precio']})\n";
  echo "\nProducto más caro: {$productoMasCaro['nombre']} ({$productoMasCaro['precio']})\n";


// 6. Filtrar productos por categoría
function filtrarPorCategoria($productos, $categoria) {
    return array_filter($productos, function($producto) use ($categoria) {
        return in_array($categoria, $producto['categorias']);
    });
}

$productosDeComputadoras = filtrarPorCategoria($tiendaData['productos'], "computadoras");
echo "\nProductos en la categoría 'computadoras':\n";
imprimirProductos($productosDeComputadoras);

// 7. Agregar un nuevo producto
$nuevoProducto = [
    "id" => 6,
    "nombre" => "Smartwatch",
    "precio" => 250,
    "categorias" => ["electrónica", "accesorios", "wearables"]
];
$tiendaData['productos'][] = $nuevoProducto;

// 8. Convertir el arreglo actualizado de vuelta a JSON
$jsonActualizado = json_encode($tiendaData, JSON_PRETTY_PRINT);
echo "\nDatos actualizados de la tienda (JSON):\n$jsonActualizado\n";

// TAREA: Implementa una función que genere un resumen de ventas
// Crea un arreglo de ventas (producto_id, cliente_id, cantidad, fecha)
// y genera un informe que muestre:
// - Total de ventas
// - Producto más vendido
// - Cliente que más ha comprado
// Tu código aquí
$ventas = [
    ["producto_id" => 1, "cliente_id" => 101, "cantidad" => 3, "fecha" => "2025-09-01"],
    ["producto_id" => 2, "cliente_id" => 102, "cantidad" => 1, "fecha" => "2025-09-02"],
    ["producto_id" => 1, "cliente_id" => 101, "cantidad" => 2, "fecha" => "2025-09-03"],
    ["producto_id" => 3, "cliente_id" => 103, "cantidad" => 5, "fecha" => "2025-09-04"],
    ["producto_id" => 2, "cliente_id" => 102, "cantidad" => 4, "fecha" => "2025-09-05"],
];

function generarResumenVentas($ventas) {
    $totalVentas = 0;
    $porProducto = [];
    $porCliente = [];

    foreach ($ventas as $venta) {
        $cantidad = $venta["cantidad"];
        $producto = $venta["producto_id"];
        $cliente  = $venta["cliente_id"];

        // Sumar al total
        $totalVentas += $cantidad;

        // Contar por producto
        if (!isset($porProducto[$producto])) {
            $porProducto[$producto] = 0;
        }
        $porProducto[$producto] += $cantidad;

        // Contar por cliente
        if (!isset($porCliente[$cliente])) {
            $porCliente[$cliente] = 0;
        }
        $porCliente[$cliente] += $cantidad;
    }

    // Producto más vendido
    $productoMasVendido = array_keys($porProducto, max($porProducto))[0];
    $cantidadProductoTop = max($porProducto);

    // Cliente que más compró
    $clienteTop = array_keys($porCliente, max($porCliente))[0];
    $cantidadClienteTop = max($porCliente);

    return [
        "total_ventas" => $totalVentas,
        "producto_mas_vendido" => $productoMasVendido,
        "cantidad_producto_top" => $cantidadProductoTop,
        "cliente_top" => $clienteTop,
        "cantidad_cliente_top" => $cantidadClienteTop
    ];
}
// Ejemplo de uso:
$resumen = generarResumenVentas($ventas);

echo "📊 Resumen de Ventas:\n";
echo "Total de ventas: " . $resumen["total_ventas"] . "\n";
echo "Producto más vendido: " . $resumen["producto_mas_vendido"] . " (Vendidos: " . $resumen["cantidad_producto_top"] . ")\n";
echo "Cliente que más compró: " . $resumen["cliente_top"] . " (Compró: " . $resumen["cantidad_cliente_top"] . ")\n";

?>