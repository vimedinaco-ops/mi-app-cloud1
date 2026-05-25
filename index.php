<?php

require_once 'config/database.php';

$pdo = getDBConnection();

$mensaje = '';

/* ======================================
   VALIDACIONES + CREATE
====================================== */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {

    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $carrera = trim($_POST['carrera']);

    if(strlen($nombre) < 3 || strlen($nombre) > 100){

        $mensaje = "El nombre debe tener entre 3 y 100 caracteres.";

    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $mensaje = "Email inválido.";

    } else {

        $stmt = $pdo->prepare(
            "INSERT INTO estudiantes(nombre,email,carrera)
             VALUES(:n,:e,:c)"
        );

        $stmt->execute([

            ':n' => htmlspecialchars($nombre),
            ':e' => filter_var($email, FILTER_SANITIZE_EMAIL),
            ':c' => htmlspecialchars($carrera)

        ]);

        header("Location: /");
        exit;
    }
}

/* ======================================
   DELETE
====================================== */

if(isset($_GET['delete'])){

    $stmt = $pdo->prepare(
        "DELETE FROM estudiantes WHERE id=:id"
    );

    $stmt->execute([
        ':id' => (int)$_GET['delete']
    ]);

    header("Location: /");
    exit;
}

/* ======================================
   UPDATE
====================================== */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {

    $id = (int)$_POST['id'];

    $stmt = $pdo->prepare(
        "UPDATE estudiantes
         SET nombre=:n, carrera=:c
         WHERE id=:id"
    );

    $stmt->execute([

        ':n' => htmlspecialchars($_POST['nombre']),
        ':c' => htmlspecialchars($_POST['carrera']),
        ':id' => $id

    ]);

    header("Location: /");
    exit;
}

/* ======================================
   BUSQUEDA
====================================== */

$buscar = $_GET['buscar'] ?? '';

/* ======================================
   PAGINACION
====================================== */

$porPagina = 5;

$pagina = isset($_GET['pagina'])
    ? (int)$_GET['pagina']
    : 1;

$offset = ($pagina - 1) * $porPagina;

/* ======================================
   READ
====================================== */

$sql = "
SELECT *
FROM estudiantes
WHERE nombre ILIKE :buscar
OR carrera ILIKE :buscar
ORDER BY creado_en DESC
LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':buscar', "%{$buscar}%");
$stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

$estudiantes = $stmt->fetchAll();

/* ======================================
   TOTAL REGISTROS
====================================== */

$totalQuery = $pdo->prepare(
"
SELECT COUNT(*) FROM estudiantes
WHERE nombre ILIKE :buscar
OR carrera ILIKE :buscar
"
);

$totalQuery->execute([
    ':buscar' => "%{$buscar}%"
]);

$totalRegistros = $totalQuery->fetchColumn();

$totalPaginas = ceil($totalRegistros / $porPagina);

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<title>Sistema CRUD</title>

<style>

body{
    font-family:Arial;
    max-width:1000px;
    margin:30px auto;
}

input{
    padding:8px;
    margin:5px;
}

button{
    padding:8px 15px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

table,th,td{
    border:1px solid #ccc;
}

th,td{
    padding:10px;
}

.error{
    color:red;
}

</style>

</head>

<body>

<h1>🎓 Gestión de Estudiantes</h1>

<?php if($mensaje): ?>

<p class="error"><?= $mensaje ?></p>

<?php endif; ?>

<!-- FORMULARIO -->

<h2>Registrar Estudiante</h2>

<form method="POST">

<input
name="nombre"
placeholder="Nombre"
required>

<input
name="email"
type="email"
placeholder="Email"
required>

<input
name="carrera"
placeholder="Carrera"
required>

<button type="submit" name="guardar">
Guardar
</button>

</form>

<!-- BUSQUEDA -->

<h2>Buscar</h2>

<form method="GET">

<input
name="buscar"
placeholder="Buscar estudiante"
value="<?= htmlspecialchars($buscar) ?>">

<button type="submit">
Buscar
</button>

</form>

<!-- TABLA -->

<h2>
Lista de Estudiantes
(<?= $totalRegistros ?>)
</h2>

<table>

<tr>

<th>ID</th>
<th>Nombre</th>
<th>Email</th>
<th>Carrera</th>
<th>Acciones</th>

</tr>

<?php foreach($estudiantes as $e): ?>

<tr>

<td><?= $e['id'] ?></td>

<td><?= htmlspecialchars($e['nombre']) ?></td>

<td><?= htmlspecialchars($e['email']) ?></td>

<td><?= htmlspecialchars($e['carrera']) ?></td>

<td>

<!-- ELIMINAR -->

<a href="?delete=<?= $e['id'] ?>"
onclick="return confirm('¿Eliminar?')">

Eliminar

</a>

|

<!-- EDITAR -->

<form method="POST" style="display:inline;">

<input
type="hidden"
name="id"
value="<?= $e['id'] ?>">

<input
name="nombre"
value="<?= htmlspecialchars($e['nombre']) ?>">

<input
name="carrera"
value="<?= htmlspecialchars($e['carrera']) ?>">

<button type="submit" name="actualizar">

Actualizar

</button>

</form>

</td>

</tr>

<?php endforeach; ?>

</table>

<!-- PAGINACION -->

<div style="margin-top:20px;">

<?php if($pagina > 1): ?>

<a href="?pagina=<?= $pagina - 1 ?>&buscar=<?= urlencode($buscar) ?>">

⬅ Anterior

</a>

<?php endif; ?>

|

<?php if($pagina < $totalPaginas): ?>

<a href="?pagina=<?= $pagina + 1 ?>&buscar=<?= urlencode($buscar) ?>">

Siguiente ➡

</a>

<?php endif; ?>

</div>

</body>
</html>