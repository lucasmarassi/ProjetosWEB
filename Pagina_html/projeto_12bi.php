<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Verificador de CPF</title>
</head>
<style>
    .verde {
        color: #0f0;
    }
    .vermelho {
        color: #f00;
    }
    body {
        text-align: center;
    }
</style>
<body>
    <form method="POST">
        <span class="container my-5"><h4> Digite o CPF <h4></span>
        <input type="text" name="cpf" placeholder="ex:11122233344"><br><br>
        <input type="submit" value="verificar">
    </form>
    <?php
        if(isset($_POST['cpf'])){
            $cpf = $_POST['cpf'];
            $controla = 10;
            $soma = 0;

            // primeiro digito
            for ($x = 0; $x <= 8; $x++) {
                $soma += $cpf[$x] * $controla;
                $controla--;
            }

            if ($soma % 11 < 2) {
                $primeirodig = 0;
            } else {
                $primeirodig = 11 - ($soma % 11);
            }

            // segundo digito
            $controla = 11;
            $soma = 0; // Resetando a soma para calcular o segundo dígito
            for ($x = 0; $x <= 8; $x++) {
                $soma += $cpf[$x] * $controla;
                $controla--;
            }
            $soma += $primeirodig * $controla;

            if ($soma % 11 < 2) {
                $segundodig = 0;
            } else {
                $segundodig = 11 - ($soma % 11);
            }

            if (($primeirodig == $cpf[9]) && ($segundodig == $cpf[10])) {
                echo "<br> <br> <span class='verde'>CPF CORRETO</span>";
            } else {
                echo "<br> <br> <span class='vermelho'>CPF INCORRETO</span>";
            }
        }
        
    ?>

</body>
</html>