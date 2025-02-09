<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">   
    <title>Projeto 1°bimestre n°4</title>
</head>
<style>
    .verde{
        color:#006400;
        background-color: #00FF00;
    }
    .vermelho {
        color:#FA8072;
        background-color: #800000;  
    }
    .amarelo {
        color:#ADFF2F;
        background-color: #DAA520;  
    }
    .container{
        text-align: center;
    }
    .titulo{
        background: blue;
        text: white;
    }

    table {
        border-collapse: collapse;
        width: 50%;
        margin: auto;
    }
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: blue;
        color: white;
    }
</style>
<body>
    <div class="container my-5">
        <form action="projeto_14bi.php" method="GET">
            <input type="text" name="numero" placeholder="Digite um número">
            <br><br>
            <input type="submit" name="enviar dado">
        </form>
    </div>
    <!-- PHP -->

<?php
    if(isset($_GET['numero'])){
        $numero = $_GET['numero'];
        $primo = 0;
        $parimpar = 0;
        $divisores = 0;
        $verifica = 0;
        $perfeito = 0;
        $numeros_perfeitos = array();

        if ($numero%2==0) {
            $parimpar = 1;
        }

        for ($cont1 = 1;$cont1 < $numero;$cont1++) {
            if ($numero%$cont1 == 0) {
                $verifica += $cont1;
                $numeros_perfeitos[] = $cont1;
            }
        }

        if ($verifica == $numero) {
            $perfeito=1;
        }

        for ($i = 1; $i <= $numero; $i++) {
            if ($numero % $i == 0) {
                $divisores++;
            }
        }
        
        if ($divisores == 2) {
            $primo = 1;
        }

    }
?>
<!-- -->
<table>
  <tr>
    <th>Número Recebido </th>
    <th> <?php echo $numero; ?></th>
  </tr>
  <tr>
    <td>Par</td>
    <td>
        <?php 
            if ($parimpar == 1) {echo "<span class='verde'>SIM</span>";} 
                else {echo "<span class='vermelho'>NÃO</span>";}
        ?>
    </td>
  </tr>
  <tr>
    <td>Impar</td>
    <td>
        <?php
            if ($parimpar == 0) {echo "<span class='verde'>SIM</span>";} 
                else {echo "<span class='vermelho'>NÃO</span>";}
        ?>
    </td>
  </tr>
  <tr>
    <td>Perfeito</td>
    <td>
        <?php
            if ($perfeito == 1) {echo "<span class='verde'>SIM</span>";} else {{echo "<span class='vermelho'>NAO</span>";}}
        ?>
    </td>
  </tr> 
  <tr>
    <td>Quantidade de divisores</td>
    <td>
    <?php   
        echo "<span class='amarelo'>".$divisores." </span>";
        echo "<span class='amarelo'>( </span> ";
        foreach ($numeros_perfeitos as $num) {
            echo "<span class='amarelo'>".$num ." </span>";
        }
        echo "<span class='amarelo'>".$numero." )</span>";
    ?>
    </td>
  </tr> 
  <tr>
    <td>Primo</td>
    <td> 
        <?php
            if ($primo == 1) {
                echo "<span class='verde'>SIM</span>";
            } else {
                echo "<span class='vermelho'>NAO</span>";
            }
        ?>    
    </td>
  </tr>
</table>

    </body>
</html>