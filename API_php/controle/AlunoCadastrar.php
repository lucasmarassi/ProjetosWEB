<?php
require_once "modelo/Aluno.php";

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);


if (!isset($obj->nome) || !isset($obj->email) || !isset($obj->id_curso) || !isset($obj->senha)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos. Por favor, forneça nome, email, id_curso."
    ]);
    exit();
}


$nome = $obj->nome;
$email= $obj->email;
$id_curso = $obj->id_curso;
$senha = $obj->senha;


// Sanitize input
$nome = strip_tags($nome);

$aluno = new Aluno();

$aluno->setNome($nome);
$aluno->setEmail($email);
$aluno->setIdcurso($id_curso);
$aluno->setSenha($senha);

if ($aluno->emailExiste($email)) {
    echo json_encode([
        "cod" => 409,  // Código 409: Conflito (email já existe)
        "msg" => "ERRO: Este email já está cadastrado."
    ]);
    exit();
}

if ($aluno->id_cursoExiste($id_curso)) {

    
 
    if ($aluno->cadastrar()) {
        echo json_encode([
            "cod" => 201,
            "msg" => "Cadastrado com sucesso!!!"
            
        ]);
    } else {
        echo json_encode([
            "cod" => 500,
            "msg" => "ERRO ao cadastrar o Aluno"
        ]);
    }
}else {



echo json_encode([
    "cod" => 409,  // Código 409: Conflito (email já existe)
    "msg" => "ERRO: Este id_cursonao existe."
]);
}

?>




