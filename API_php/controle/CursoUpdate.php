<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/Curso.php";
require_once "modelo/MeuTokenJWT.php";
$vetor = explode("/", $_SERVER['REQUEST_URI']);
$metodo = $_SERVER['REQUEST_METHOD'];

    $headers=getallheaders();
    $autorization=$headers['Authorization'];
   $meutoken= new MeuTokenJWT();

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);
if (!isset($obj->nome_curso) || !isset($obj->preco_curso) || !isset($obj->anos_conclusao) || !isset($obj->id_professor) || !isset($obj->email) || !isset($obj->senha) ) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos. Por favor, forneça nome_curso, preco_curso, anos_conclusao e id_professor."
    ]);
    exit();
}


$nome_curso = $obj->nome_curso;
$preco_curso = $obj->preco_curso;
$anos_conclusao = $obj->anos_conclusao;
$id_professor = $obj->id_professor; // Adicionando o id_professor
$email = $obj->email;
$senha = $obj->senha;
// Remover tags HTML e PHP

$nome_curso = strip_tags($nome_curso);
$preco_curso = strip_tags($preco_curso);
$anos_conclusao = strip_tags($anos_conclusao);
$id_professor = strip_tags($id_professor);
$email = strip_tags($email);
$senha = strip_tags($senha);
$resposta = array();
$verificador = 0;

// Verificação se o nome do curso está vazio
if ($nome_curso == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "nome nao pode ser vazio";
    $verificador = 1;
} else if ($anos_conclusao == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "anos nao pode ser vazio";
    $verificador = 1;
}

// Verificação se os anos de conclusão e preço do curso são válidos
if (!is_numeric($preco_curso)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "preco_curso deve ser um número";
    $verificador = 1;
}

if (!is_numeric($anos_conclusao)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "anos_conclusao deve ser um número";
    $verificador = 1;
}

if (!is_numeric($id_professor)) { // Verificação do id_professor
    $resposta['cod'] = 3;
    $resposta['msg'] = "id_professor deve ser um número";
    $verificador = 1;
}



if($meutoken->validarToken($autorization)==true){
    $payloadRecuperado=$meutoken->getPayload();
    $tokenNovo=$meutoken->gerarToken($payloadRecuperado);
        if ($verificador == 0) {
            // Instanciar e atualizar o curso
            if ($metodo == "PUT") {
                $id_curso = $vetor[2];
            $curso = new Curso();
            // Certifique-se de que $rota[2] está definido corretamente
            $curso->setIdcurso($id_curso);
            $curso->setNomecurso($nome_curso);
            $curso->setPrecocurso($preco_curso);
            $curso->setAnosconclusao($anos_conclusao);
            $curso->setIdprofessor($id_professor); // Definindo o id_professor
            $curso->setEmail($email);
            $curso->setSenha($senha);
            $resultado = $curso->update();
            if ($resultado == true) {
                header("HTTP/1.1 201 Created");
                $resposta['cod'] = 4;
                $resposta['msg'] = "Atualizado com sucesso!!!";
                $resposta['curso'] = $curso;
                $resposta['Token'] = $tokenNovo;
            } else {
                header("HTTP/1.1 500 Internal Server Error");
                $resposta['cod'] = 5;
                $resposta['msg'] = "ERRO ao cadastrar o curso";
            }
        }
        }

        echo json_encode($resposta);
    }
?>

