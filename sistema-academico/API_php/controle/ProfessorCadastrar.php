<?php

require_once "modelo/Professor.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);



if ( !isset($obj->nome) || !isset($obj->email)|| !isset($obj->data_nascimento) || !isset($obj->senha)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos. Por favor, forneça id_curso, nome_curso, preco_curso, anos_conclusao e id_professor."
    ]);
    exit();
}


$nome = $obj->nome;
$email= $obj->email;
$data_nascimento = $obj->data_nascimento;
$senha = $obj->senha;


// Sanitize input
$nome = strip_tags($nome);

$Professor = new Professor();
$Professor->setNome($nome);
$Professor->setEmail($email);
$Professor->setData_nascimento($data_nascimento);
$Professor->setSenha($senha);

if ($Professor->emailExiste($email)) {
    echo json_encode([
        "cod" => 409,  // Código 409: Conflito (email já existe)
        "msg" => "ERRO: Este email já está cadastrado."
    ]);
    exit();
}
if ($Professor->cadastrar()) {
    echo json_encode([
        "cod" => 204,
        "msg" => "Cadastrado com sucesso!!!"
        
    ]);
} else {
    echo json_encode([
        "cod" => 500,
        "msg" => "ERRO ao cadastrar o professor"
    ]);
}
?>
