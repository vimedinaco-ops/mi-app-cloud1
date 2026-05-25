<?php
// index.php – Aplicación PHP desplegada en Render.com
 
// Las variables de entorno se configuran en el panel de Render
$appNombre  = getenv('APP_NOMBRE') ?: 'Aplicación Web';
$serverHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
$fechaHora  = date('d/m/Y H:i:s');
 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($appNombre) ?></title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px;
               margin: 50px auto; text-align: center; }
        .card { border: 1px solid #ddd; padding: 2rem;
                border-radius: 8px; background: #f9f9f9; }
        .badge { background: #0A2463; color: white; padding: 4px 12px;
                 border-radius: 4px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="card">
        <h1>🚀 Deploy Exitoso</h1>
        <p>Aplicación: <strong><?= htmlspecialchars($appNombre) ?></strong></p>
        <p>Servidor: <span class="badge"><?= htmlspecialchars($serverHost) ?></span></p>
        <p>Fecha y hora del servidor: <strong><?= $fechaHora ?></strong></p>
        <p>PHP Version: <?= phpversion() ?></p>
    </div>
</body>
</html>
