<?php
require_once "modelo/Curso.php";

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === "DELETE") {
    // Extrai o ID do curso da URI
    $vetor = explode("/", $_SERVER['REQUEST_URI']);
    $id_curso = $vetor[2];

    // Cria uma instância do Curso
    $curso = new Curso();
    $curso->setIdCurso($id_curso);
    
    // Tenta excluir o curso
    if ($curso->delete()) {
        header("HTTP/1.1 204 No Content"); // Define o código de status da resposta como 204 (No Content)
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
?>

