<?php
    require_once("./config.php");
    function conex():array{
        $conex = pg_connect("host=".HOST." dbname=".DB_NAME." user=".DB_USER." password=".DB_PASS);
        $peticion = pg_fetch_all(pg_query($conex, "SELECT * FROM places"));

        pg_close($conex);

        return $peticion;
    }
?>