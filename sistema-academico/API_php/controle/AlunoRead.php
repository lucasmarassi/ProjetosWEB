<?php
use Firebase\JWT\MeuTokenJWT;
    require_once "modelo/Aluno.php";
    require_once "modelo/MeuTokenJWT.php";
    $headers=getallheaders();
    $autorization=$headers['Authorization'];
   $meutoken= new MeuTokenJWT();
   
   if($meutoken->validarToken($autorization)==true){
    $payloadRecuperado=$meutoken->getPayload();

    $aluno = new Aluno();
    $alunos = $aluno->read();

    header("Content-Type: application/json");
    if ($alunos) {
        header("HTTP/1.1 200 OK");

        // Converter cada objeto Curso em um array
        $alunosArray = array_map(function($aluno) {
            return $aluno->toArray();
        }, $alunos);
                $tokenNovo=$meutoken->gerarToken($payloadRecuperado);
            // Codificar o resultado como JSON
        echo json_encode($alunosArray, JSON_PRETTY_PRINT);
        echo json_encode(["Token Novo"]);
        echo json_encode($tokenNovo, JSON_PRETTY_PRINT);
        
    } else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["mensagem" => "Nenhum curso encontrado."]);
    }


   }else{
    
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["mensagem" => "Token Invalido!!."]);

   }
    
    
?>