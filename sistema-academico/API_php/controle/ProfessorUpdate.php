<?php
use Firebase\JWT\MeuTokenJWT;
require_once "modelo/Professor.php";
require_once "modelo/MeuTokenJWT.php";
$vetor = explode("/", $_SERVER['REQUEST_URI']);
$metodo = $_SERVER['REQUEST_METHOD'];

$headers=getallheaders();
$autorization=$headers['Authorization'];
$meutoken= new MeuTokenJWT();

$jsonRecebidoBodyRequest = file_get_contents('php://input');
$obj = json_decode($jsonRecebidoBodyRequest);

if (!isset($obj->nome) || !isset($obj->email)|| !isset($obj->data_nascimento) || !isset($obj->senha)) {
    echo json_encode([
        "cod" => 400,
        "msg" => "Dados incompletos. Por favor, forneça  nome_curso, preco_curso, anos_conclusao e id_professor."
    ]);
    exit();
}


$nome= $obj->nome;
$email= $obj->email;
$data_nascimento = $obj->data_nascimento;
$senha= $obj->senha;

// Remover tags HTML e PHP

$nome = strip_tags($nome);
$email = strip_tags($email);
$data_nascimento = strip_tags($data_nascimento);
$senha = strip_tags($senha);


$resposta = array();
$verificador = 0;

// Verificação se o nome do curso está vazio
if ($nome== '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "nome nao pode ser vazio";
    $verificador = 1;
} else if ($data_nascimento == '') {
    $resposta['cod'] = 3;
    $resposta['msg'] = "anos nao pode ser vazio";
    $verificador = 1;
}

// Verificação se os anos de conclusão e preço do curso são válidos


if (!is_numeric($id_prof)) { // Verificação do id_professor
    $resposta['cod'] = 3;
    $resposta['msg'] = "id_prof deve ser um número";
    $verificador = 1;
}
if($meutoken->validarToken($autorization)==true){
    $payloadRecuperado=$meutoken->getPayload();
    $tokenNovo=$meutoken->gerarToken($payloadRecuperado);

    if ($verificador == 0) {
        if ($metodo == "PUT") {
            $id_prof = $vetor[2];
        // Instanciar e atualizar o curso
        $professor = new Professor();
        // Certifique-se de que $rota[2] está definido corretamente
        $professor->setId_prof($id_prof );
        $professor->setNome($nome);
        $professor->setEmail($email);
        $professor->setData_nascimento($data_nascimento);
        $professor->setSenha($senha);
        
       

        $resultado = $professor->update();
        if ($resultado == true) {
            header("HTTP/1.1 201 Created");
            $resposta['cod'] = 4;
            $resposta['msg'] = "Atualizacao com sucesso!!!";
            $resposta['professor'] = $professor;
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

