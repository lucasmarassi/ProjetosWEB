<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/Curso.php";
require_once "modelo/MeuTokenJWT.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);



if (!isset($obj->email) || !isset($obj->senha)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos. Por favor, forneça email e senha ."
    ]);
    exit();
}

$email = $obj->email;
$senha = $obj->senha;
$papel = $obj->papel;

// Sanitize input

if($papel== "curso") {
    $curso = new Curso();
    $curso->setEmail($email);
    $curso->setSenha($senha);

    if ($curso->login()) {
        
        $tokenJWT = new MeuTokenJWT();
        $objectClaimsToken = new stdClass();
        
        $objectClaimsToken->email = $curso->getEmail();
        $objectClaimsToken->senha = $curso->getSenha();
        $objectClaimsToken->papel = $papel;
        $novoToken = $tokenJWT->gerarToken($objectClaimsToken);
        
        echo json_encode([
            "cod" => 201,
            "msg" => "login feito com sucesso!!!",
            "curso" => [
                "id_curso" => $curso->getIdCurso(),
                "nome_curso" => $curso->getNomeCurso(),
                "preco_curso" => $curso->getPrecoCurso(),
                "anos_conclusao" => $curso->getAnosConclusao(),
                "id_professor" => $curso->getIdProfessor(),
                "email" => $curso->getEmail(),
                "senha" => $curso->getSenha()
            ],
            "token" => $novoToken
        ]);
    } else {
        echo json_encode([
            "cod" => 500,
            "msg" => "ERRO ao cadastrar o curso"
        ]);
    }
}else {
    echo json_encode([
        "cod" => 401,
        "msg" => "ERRO: Login inválido. Verifique suas credenciais de curso."
    ]);
}
?>
