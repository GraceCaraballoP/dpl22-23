<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciudades</title>
    <style>
        body{
            text-align:center;
        }
    </style>
</head>
<body>
    <h1>My Travel Bucket List</h1>
    <h2>Places I'd Like to visit</h2>
    <ul>
    <?php //false
        require_once("buscarCiudades.php");
        $ciudades=buscarciudades($lista);
        listarCiudades($ciudades[1]);
    ?>
    </ul>
    <h2>Places I've Already Been To</h2>
    <ul>
    <?php //true
        listarCiudades($ciudades[0]);
    ?>
    </ul>
</body>
</html>