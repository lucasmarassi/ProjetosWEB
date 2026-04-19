<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/Aluno.php";
require_once "modelo/MeuTokenJWT.php";
$headers=getallheaders();
$autorization=$headers['Authorization'];
$meutoken= new MeuTokenJWT();



if($meutoken->validarToken($autorization)==true){
    $payloadRecuperado=$meutoken->getPayload();
    $tokenNovo=$meutoken->gerarToken($payloadRecuperado);
    
    

    $metodo = $_SERVER['REQUEST_METHOD'];

    if ($metodo === "DELETE") {
        // Extrai o ID do curso da URI
        $vetor = explode("/", $_SERVER['REQUEST_URI']);
        $id_a = $vetor[2];

        // Cria uma instância do Curso
        $aluno = new ALuno();
        $aluno->setId_a($id_a);
       
        // Tenta excluir o curso
        if ($aluno->delete()) {
            header("HTTP/1.1 200 OK");
            echo json_encode([
                "mensagem" => "Aluno excluído com sucesso.",
                "token_novo" => $tokenNovo
            ]);
            exit(); // Termina o script aqui, pois não há conteúdo para enviar na resposta
        } else {
            header("HTTP/1.1 500 Internal Server Error"); // Define o código de status da resposta como 500 (Internal Server Error)
            echo json_encode([
                "cod" => 500,
                "msg" => "Erro ao excluir curso."
            ]);
            exit();
        }
    } else {
        // Método HTTP inválido
        header("HTTP/1.1 405 Method Not Allowed");
        header("Allow: DELETE");
        echo json_encode([
            "cod" => 405,
            "msg" => "Método HTTP não permitido. Apenas o método DELETE é suportado para exclusão de curso."
        ]);
        exit();
    }
}else{
    
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["mensagem" => "Token Invalido!!."]);

   }
?>