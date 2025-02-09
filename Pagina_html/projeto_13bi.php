<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Ordenando Numeros</title>
</head>
<body>
    <form method="GET">
        <h4> Digite um numero seguido de uma vírgula <h4>
        <input type="text" name="numero" placeholder="ex: 1,2,3,4,..."><br><br>
        <input type="submit" value="Ordenar">
    </form>
    </div>
    <?php
    if (isset($_GET['numero'])) { 
        $numeros = explode(',', $_GET['numero']); // separa os numeros por ","

        foreach ($numeros as $indice => $valor) {
            $numeros[$indice] = trim($valor); // remove espaços em branco
        }

        foreach ($numeros as $indice => $valor) {
            $numeros[$indice] = intval($valor); // transforma o valor que estrava em string em int 
        }
        //bubble sort
        $quantidade_elementos = count($numeros); // n recebe a quantidade de elementos que tem na array "numeros"
        for ($i = 0; $i < $quantidade_elementos-1; $i++) { // loop para controlar o elemento inicial
            for ($j = 0; $j < $quantidade_elementos-1; $j++) { // loop para controlar o elemento com o que o inicial será comparado
                if ($numeros[$j] < $numeros[$j+1]) { //veririfica se um elemento é menor do que o outro para realizar a troca
                    $temp = $numeros[$j];
                    $numeros[$j] = $numeros[$j+1];
                    $numeros[$j+1] = $temp;
                }
            }
        }
        echo "<h4>Números Ordenados:</h4>";
        foreach ($numeros as $numero) {
            echo $numero . " | ";
        }
    }
    ?>
</body>
</html>