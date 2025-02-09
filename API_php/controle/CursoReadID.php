<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/Curso.php";
require_once "modelo/MeuTokenJWT.php";
$vetor = explode("/", $_SERVER['REQUEST_URI']);
$metodo = $_SERVER['REQUEST_METHOD'];

$headers=getallheaders();
    $autorization=$headers['Authorization'];
   $meutoken= new MeuTokenJWT();

if($meutoken->validarToken($autorization)==true){
    $payloadRecuperado=$meutoken->getPayload();
    if ($metodo == "GET") {
        $id_curso = $vetor[2];
        
        $curso = new Curso();
        $curso->setIdCurso($id_curso);
        $cursoSelecionado = $curso->readID();
        $tokenNovo=$meutoken->gerarToken($payloadRecuperado);
        if ($cursoSelecionado) {
            header("HTTP/1.1 200 OK");
            echo json_encode([
                "cod" => 200,
                "msg" => "Curso encontrado",
                "curso" => [
                    "id_curso" => $cursoSelecionado->getIdCurso(),
                    "nome_curso" => $cursoSelecionado->getNomeCurso(),
                    "preco_curso" => $cursoSelecionado->getPrecoCurso(),
                    "anos_conclusao" => $cursoSelecionado->getAnosConclusao(),
                    "id_professor" => $cursoSelecionado->getIdProfessor(),
                    "email" => $cursoSelecionado->getEmail()
                    

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
