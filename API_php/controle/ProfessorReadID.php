<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/Professor.php";
require_once "modelo/MeuTokenJWT.php";
$vetor = explode("/", $_SERVER['REQUEST_URI']);
$metodo = $_SERVER['REQUEST_METHOD'];

$headers=getallheaders();
    $autorization=$headers['Authorization'];
   $meutoken= new MeuTokenJWT();

   if($meutoken->validarToken($autorization)==true){
            $payloadRecuperado=$meutoken->getPayload();
        if ($metodo == "GET") {
            $id_prof = $vetor[2];
            
            $Professor = new Professor();
            $Professor->setId_prof($id_prof);
            $ProfessorSelecionado = $Professor->readID();
            $tokenNovo=$meutoken->gerarToken($payloadRecuperado);
            if ($ProfessorSelecionado) {
                header("HTTP/1.1 200 OK");
                echo json_encode([
                    "cod" => 200,
                    "msg" => "Curso encontrado",
                    "curso" => [
                        "id_prof" => $ProfessorSelecionado->getId_prof(),
                        "nome" => $ProfessorSelecionado->getNome(),
                        "email" => $ProfessorSelecionado->getEmail(),
                        "data_nascimento" => $ProfessorSelecionado->getData_nascimento()
                        
                    
                    ],
                    "token" => $tokenNovo
                ]);
            } else {
                header("HTTP/1.1 404 Not Found");
                echo json_encode([
                    "cod" => 404,
                    "msg" => "Curso não encontrado"
                ]);
            }
        } else {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "cod" => 405,
                "msg" => "Método não permitido"
            ]);
        }
   }else{
    
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["mensagem" => "Token Invalido!!."]);

   }
?>