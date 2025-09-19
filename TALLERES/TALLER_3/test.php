<?php
$inventario = [
    ["nombre" => "Laptop", "precio" => 999.99, "cantidad" => 10],
    ["nombre" => "Smartphone", "precio" => 499.5, "cantidad" => 15],
    ["nombre" => "Tableta", "precio" => 299.99, "cantidad" => 2],
    ["nombre" => "Auriculares", "precio" => 79.99, "cantidad" => 7]
];

// Ordenar por precio descendente
usort($inventario, function ($a, $b) {
    return $b['precio'] <=> $a['precio'];
});
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario</title>    
</head>
<body>
    <table>
        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
        </tr>
        <?php foreach ($inventario as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['nombre']) ?></td>
                <td>$<?= number_format($item['precio'], 2) ?></td>
                <td><?= $item['cantidad'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
