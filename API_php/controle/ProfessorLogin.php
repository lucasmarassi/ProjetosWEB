<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\MeuTokenJWT;

require_once "modelo/Professor.php";
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

$senha= $obj->senha;
$email= $obj->email;
$papel= $obj->papel;



$professor = new Professor();
$professor->setSenha($senha);
$professor->setEmail($email);

if ($papel=="professor") {
    if ($professor->login()) {
    
        $tokenJWT = new MeuTokenJWT();
        $objectClaimsToken = new stdClass();
        $objectClaimsToken->email = $professor->getEmail();
        $objectClaimsToken->senha = $professor->getSenha();
        $objectClaimsToken->papel = $papel;
        
        $novoToken = $tokenJWT->gerarToken($objectClaimsToken);
        
        echo json_encode([
            "cod" => 200,
            "msg" => "Login realizado com sucesso!!!",
            "Professor" => [
                "id_prof" => $professor->getId_prof(),
                "nome" => $professor->getNome(),
                "email" => $professor->getEmail(),
                "data_nascimento" => $professor->getData_nascimento()
                
                
            
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
        "msg" => "ERRO: Login inválido. Verifique seu papel."
    ]);
}
?>
