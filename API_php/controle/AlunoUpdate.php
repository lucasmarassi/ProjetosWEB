<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/Aluno.php";
require_once "modelo/MeuTokenJWT.php";
$vetor = explode("/", $_SERVER['REQUEST_URI']);
$metodo = $_SERVER['REQUEST_METHOD'];
    

$headers=getallheaders();
    $autorization=$headers['Authorization'];
   $meutoken= new MeuTokenJWT();

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

if ( !isset($obj->nome) || !isset($obj->email) || !isset($obj->id_curso) || !isset($obj->senha)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos. Por favor, forneça id_a, nome, email, id_curso."
    ]);
    exit();
}


$nome= $obj->nome;
$email = $obj->email;
$id_curso= $obj->id_curso;
$senha=$obj->senha;


// Remover tags HTML e PHP

$nome = strip_tags($nome);
$email = strip_tags($email);
$id_curso = strip_tags($id_curso);
$senha = strip_tags($senha);

$resposta = array();
$verificador = 0;



// Verificação se o nome do curso está vazio
if ($nome== '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "nome nao pode ser vazio";
    $verificador = 1;
} else if ($email == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "anos nao pode ser vazio";
    $verificador = 1;
}

// Verificação se os anos de conclusão e preço do curso são válidos


if (!is_numeric($id_a)) { // Verificação do id_professor
    $resposta['cod'] = 3;
    $resposta['msg'] = "id_prof deve ser um número";
    $verificador = 1;
}
if (!is_numeric($id_curso)) {
    $resposta['cod'] = 3;
    $resposta['msg'] = "anos_conclusao deve ser um número";
    $verificador = 1;
}

if($meutoken->validarToken($autorization)==true){
    $payloadRecuperado=$meutoken->getPayload();
    $tokenNovo=$meutoken->gerarToken($payloadRecuperado);
    if ($verificador == 0) {
        if ($metodo == "PUT") {
            $id_a = $vetor[2];
        // Instanciar e atualizar o curso
        $aluno = new Aluno();
        
        // Certifique-se de que $rota[2] está definido corretamente
        $aluno->setId_a($id_a);
        $aluno->setNome($nome);
        $aluno->setEmail($email);
        $aluno->setIdcurso($id_curso);
        $aluno->setSenha($senha);

      

        $resultado = $aluno->update();
        if ($resultado == true) {
            header("HTTP/1.1 201 Created");
            $resposta['cod'] = 4;
            $resposta['msg'] = "Auteracao feita com sucesso!!!, Seu Novo Token!";
            
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

