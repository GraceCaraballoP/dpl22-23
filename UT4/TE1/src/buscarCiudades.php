<?php
    require_once("conexion.php");
    $lista=conex();

    function buscarCiudades($ciudades):array{
        
        $listaVisitadas=[];
        $listaPorVisitar=[];
        
        foreach($ciudades as $ciudad){
            if($ciudad['visited']=='t'){
                array_push($listaVisitadas,$ciudad['name']);
            }else{
                array_push($listaPorVisitar,$ciudad['name']);
            }
        }
        return array($listaVisitadas,$listaPorVisitar);
    }

    function listarCiudades($ciudades){
        for($i=0;$i<count($ciudades);$i++){
            echo "<li>",$ciudades[$i],"</li>\n";
        }
    }
    
    buscarCiudades($lista);
?>