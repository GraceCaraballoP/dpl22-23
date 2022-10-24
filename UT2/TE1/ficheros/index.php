<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora</title>
    <link rel="stylesheet" type="text/css" href="css/calculadora.css" />

</head>
<body>
    <?php
        function mostrarForm(){
            ?>
                <h1>Calculadora</h1>
                <form action="" method="post" enctype="multiplat/form-data">
                    <table>
                        <tr>
                            <td>
                                <label for="valor1">Valor 1:</label>
                            </td>
                            <td>
                                <input name="valor1" type="number">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="valor2">Valor 2:</label>
                            </td>
                            <td>
                                <input name="valor2" type="number">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="operacion">Operación:</label>
                            </td>
                            <td>
                                <select name="operacion" id="operacion">
                                    <option value="suma">Suma</option>
                                    <option value="resta">Resta</option>
                                    <option value="multiplicacion">Multiplicación</option>
                                    <option value="division">División</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <img src="img/calculadora.png" alt="Imagen de una calculadora básica">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button type="submit" name="btnEnviar">Realizar Operación</button>
                            </td>
                        </tr>
                    </table>
                </form>


            <?php
        }

        function operacion(string $operacion, int $valor1, int $valor2){
            if($operacion=="suma"){
                echo "La suma de $valor1 y $valor2 es: ".$valor1+$valor2;
            }elseif($operacion=="resta"){
                echo "La resta de $valor1 menos $valor2 es: ".$valor1-$valor2;
            }elseif($operacion=="multiplicacion"){
                echo "El producto de $valor1 por $valor2 es: ".$valor1*$valor2;
            }else{
                if($valor2==0){
                    echo "La division de $valor1 entre $valor2 no es posible.";
                }else{
                    echo "La division de $valor1 entre $valor2 es: ".$valor1/$valor2;
                }
            }
        }

        if(isset($_POST['operacion']) && isset($_POST['valor1']) && isset($_POST['valor2'])){
            $operacion=$_POST['operacion'];
            $valor1=$_POST['valor1'];
            $valor2=$_POST['valor2'];
            
            operacion($operacion,$valor1,$valor2);

        } else{
            mostrarForm();
        }
    ?>

    
</body>
</html>