<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\MeuTokenJWT;

require_once "modelo/Aluno.php";
require_once "modelo/MeuTokenJWT.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

if (!isset($obj->email) || !isset($obj->senha)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incorretos ou incompletos. Por favor, forneça email e id_curso válido."
    ]);
    exit();
}

$email = $obj->email;
$senha = $obj->senha;
$papel = $obj->papel;

if($papel== "aluno") {
    $aluno = new Aluno();
    $aluno->setEmail($email);
    $aluno->setSenha($senha);

    if ($aluno->login()) {
    
        $tokenJWT = new MeuTokenJWT();
        $objectClaimsToken = new stdClass();
        
        $objectClaimsToken->email = $aluno->getEmail();
       
        $objectClaimsToken->senha = $aluno->getSenha();
        $objectClaimsToken->papel = $papel;
        $novoToken = $tokenJWT->gerarToken($objectClaimsToken);
        
        echo json_encode([
            "cod" => 200,
            "msg" => "Login realizado com sucesso!!!",
            "Aluno" => [
                "id_a" => $aluno->getId_a(),
                "nome" => $aluno->getNome(),
                "email" => $aluno->getEmail(),
                "id_curso" => $aluno->getIdcurso(),
                "senha" => $aluno->getSenha()
            ],
            "token" => $novoToken
        ]);
    } else {
        echo json_encode([
            "cod" => 401,
            "msg" => "ERRO: Login inválido. Verifique suas credenciais."
        ]);
    }
}else {
    echo json_encode([
        "cod" => 401,
        "msg" => "ERRO: Login inválido. Verifique suas credenciais de aluno."
    ]);
}
?>
