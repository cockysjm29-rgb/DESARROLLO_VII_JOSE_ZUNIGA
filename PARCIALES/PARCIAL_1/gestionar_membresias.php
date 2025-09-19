<?php
include 'funciones_gimnasio.php';

$membresias = [
    'Premium' => 15,
    'Basica' => 2,
    'VIP' => 30,
    'Familiar' => 8,
    'Corporativa' => 18
];

$cliente = [
    'Juan Perez' => 18,
    'Ana Garcia' => 8,
    'Carlos Lopez' => 30,
    'Maria Rodriguez' => 2,
    'Luis Martinez' => 15
];


$subtotal = 0;
foreach ($cliente as $membresias => $cantidad) {
    if ($cantidad > 0) {
        $subtotal += $membresias[$producto] * $cantidad;
    }
}


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
        <?php foreach ($cliente as $producto => $cantidad): ?>
            <?php if ($cantidad > 0): ?>
                <tr>
                    <td><?php echo ucfirst($producto); ?></td>
                    <td><?php echo $cantidad; ?></td>
                    <td><?php echo number_format($membresias[$producto], 2); ?></td>
                    <td><?php echo number_format($membresias[$producto] * $cantidad, 2); ?></td>
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