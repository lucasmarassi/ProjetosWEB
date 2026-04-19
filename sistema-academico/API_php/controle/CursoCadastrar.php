<?php

require_once "modelo/Curso.php";


$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);


if (!isset($obj->nome_curso) || !isset($obj->preco_curso) || !isset($obj->anos_conclusao) || !isset($obj->id_professor) || !isset($obj->email) || !isset($obj->senha)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos. Por favor, forneça id_curso, nome_curso, preco_curso, anos_conclusao e id_professor."
    ]);
    exit();
}


$nome_curso = $obj->nome_curso;
$preco_curso = $obj->preco_curso;
$anos_conclusao = $obj->anos_conclusao;
$id_professor = $obj->id_professor;
$email = $obj->email;
$senha = $obj->senha;

// Sanitize input
$nome_curso = strip_tags($nome_curso);

    $curso = new Curso();
    $curso->setNomeCurso($nome_curso);
    $curso->setPrecoCurso($preco_curso);
    $curso->setAnosConclusao($anos_conclusao);
    $curso->setIdProfessor($id_professor);
    $curso->setEmail($email);
    $curso->setSenha($senha);


    if ($curso->cadastrar()) {
        
        
    
        echo json_encode([
            "cod" => 201,
            "msg" => "Cadastrado com sucesso!!!"
            
        ]);
    } else {
        echo json_encode([
            "cod" => 500,
            "msg" => "ERRO ao cadastrar o curso"
        ]);
    }

?>
