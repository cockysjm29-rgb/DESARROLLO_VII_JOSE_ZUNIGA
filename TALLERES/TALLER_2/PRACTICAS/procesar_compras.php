<?php
// procesar_compras.php
include 'funciones_tienda.php';

// Lista de productos disponibles en la tienda
$productos = [
    'camisa' => 200,
    'pantalon' => 200,
    'zapatos' => 200,
    'calcetines' => 200,
    'gorra' => 201
];

// Carrito de compras del cliente
$carrito = [
    'camisa' => 1,
    'pantalon' => 1,
    'zapatos' => 1,
    'calcetines' => 1,
    'gorra' => 1
];

// Calcular subtotal
$subtotal = 0;
foreach ($carrito as $producto => $cantidad) {
    if ($cantidad > 0) {
        $subtotal += $productos[$producto] * $cantidad;
    }
}

// Calcular descuento, impuesto y total
$descuento = calcular_descuento($subtotal);
$impuesto = aplicar_impuesto($subtotal - $descuento);
$total = calcular_total($subtotal, $descuento, $impuesto);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Compra</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 60%; margin: 20px auto; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Resumen de la Compra</h2>
    <table>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario ($)</th>
            <th>Total ($)</th>
        </tr>
        <?php foreach ($carrito as $producto => $cantidad): ?>
            <?php if ($cantidad > 0): ?>
                <tr>
                    <td><?php echo ucfirst($producto); ?></td>
                    <td><?php echo $cantidad; ?></td>
                    <td><?php echo number_format($productos[$producto], 2); ?></td>
                    <td><?php echo number_format($productos[$producto] * $cantidad, 2); ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <tr>
            <td colspan="3" style="text-align: right;"><strong>Subtotal</strong></td>
            <td><?php echo number_format($subtotal, 2); ?></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right;"><strong>Descuento</strong></td>
            <td>-<?php echo number_format($descuento, 2); ?></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right;"><strong>Impuesto (7%)</strong></td>
            <td><?php echo number_format($impuesto, 2); ?></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right;"><strong>Total a Pagar</strong></td>
            <td><strong><?php echo number_format($total, 2); ?></strong></td>
        </tr>
    </table>
</body>
</html>