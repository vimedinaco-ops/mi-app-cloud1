<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Contacto</title>

    <style>

        body{
            font-family: Arial;
            background:#f4f4f4;
            margin:0;
            padding:0;
        }

        .container{
            width:400px;
            margin:50px auto;
            background:white;
            padding:30px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        h1{
            text-align:center;
            color:#0A2463;
        }

        input, textarea{

            width:100%;
            padding:10px;
            margin-top:10px;
            margin-bottom:20px;

            border:1px solid #ccc;
            border-radius:5px;
        }

        button{

            width:100%;
            padding:12px;

            background:#0A2463;
            color:white;

            border:none;
            border-radius:5px;

            cursor:pointer;
            font-size:16px;
        }

        button:hover{
            background:#133c9b;
        }

        .volver{
            text-align:center;
            margin-top:20px;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Formulario de Contacto</h1>

    <form>

        <label>Nombre</label>

        <input type="text" placeholder="Ingrese su nombre">

        <label>Correo</label>

        <input type="email" placeholder="Ingrese su correo">

        <label>Mensaje</label>

        <textarea rows="5" placeholder="Escriba su mensaje"></textarea>

        <button type="submit">
            Enviar
        </button>

    </form>

    <div class="volver">

        <a href="index.php">
            Volver al inicio
        </a>

    </div>

</div>

</body>
</html>