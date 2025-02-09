<?php
// Inclui o arquivo Router.php, que provavelmente contém a definição da classe Router
require_once ("modelo/Router.php");

// Instancia um objeto da classe Router
$roteador = new Router();

// Define uma rota para a obtenção de todos os cursos
$roteador->get("/curso", function () {
    // Requer o arquivo de controle responsável por obter todos os curso
    require_once ("controle/CursoRead.php");
});

// Define uma rota para a obtenção de um curso específico pelo ID
$roteador->get("/curso/(\d+)", function ($id_curso) {
    // Requer o arquivo de controle responsável por obter um curso pelo ID
    require_once ("controle/CursoReadID.php");
});

// Define uma rota para a criação de um novo curso
$roteador->post("/curso", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/CursoCadastrar.php");
});


// Define uma rota para a atualização de um curso existente pelo ID
$roteador->put("/curso/(\d+)", function ($id_curso) {
    // Requer o arquivo de controle responsável por atualizar um curso pelo ID
    require_once ("controle/CursoUpdate.php");
});

// Define uma rota para a exclusão de um curso existente pelo ID
$roteador->delete("/curso/(\d+)", function ($id_curso) {
    // Requer o arquivo de controle responsável por excluir um curso pelo ID
    require_once ("controle/CursoDelete.php");
});

$roteador->post("/curso/login", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/CursoLogin.php");
});
$roteador->post("/curso/csv", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/CursoCadastrar_from_CSV.php");
});


// Define uma rota para a criação de um novo curso
$roteador->post("/professor/login", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/ProfessorLogin.php");
});

$roteador->get("/professor", function () {
    // Requer o arquivo de controle responsável por obter todos os curso
    require_once ("controle/ProfessorRead.php");
});

// Define uma rota para a obtenção de um curso específico pelo ID
$roteador->get("/professor/(\d+)", function ($id_prof) {
    // Requer o arquivo de controle responsável por obter um curso pelo ID
    require_once ("controle/ProfessorReadID.php");
});

// Define uma rota para a criação de um novo curso
$roteador->post("/professor", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/ProfessorCadastrar.php");
});

$roteador->post("/professor/csv", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/ProfessorCadastrar_from_CSV.php");
});


// Define uma rota para a atualização de um curso existente pelo ID
$roteador->put("/professor/(\d+)", function ($id_prof) {
    // Requer o arquivo de controle responsável por atualizar um curso pelo ID
    require_once ("controle/ProfessorUpdate.php");
});

// Define uma rota para a exclusão de um curso existente pelo ID
$roteador->delete("/professor/(\d+)", function ($id_prof) {
    // Requer o arquivo de controle responsável por excluir um curso pelo ID
    require_once ("controle/ProfessorDelete.php");
});


// Define uma rota para a criação de um novo curso
$roteador->post("/aluno/login", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/AlunoLogin.php");
});


// Define uma rota para a obtenção de todos os cursos
$roteador->get("/aluno", function () {
    // Requer o arquivo de controle responsável por obter todos os curso
    require_once ("controle/AlunoRead.php");
});

// Define uma rota para a obtenção de um curso específico pelo ID
$roteador->get("/aluno/(\d+)", function ($id_a) {
    // Requer o arquivo de controle responsável por obter um curso pelo ID
    require_once ("controle/AlunoReadID.php");
});

// Define uma rota para a criação de um novo curso
$roteador->post("/aluno", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/AlunoCadastrar.php");
});

$roteador->post("/aluno/csv", function () {
    // Requer o arquivo de controle responsável por criar um novo curso
    require_once ("controle/AlunoCadastrar_from_CSV.php");
});


// Define uma rota para a atualização de um curso existente pelo ID
$roteador->put("/aluno/(\d+)", function ($id_a) {
    // Requer o arquivo de controle responsável por atualizar um curso pelo ID
    require_once ("controle/AlunoUpdate.php");
});

// Define uma rota para a exclusão de um curso existente pelo ID
$roteador->delete("/aluno/(\d+)", function ($id_a) {
    // Requer o arquivo de controle responsável por excluir um curso pelo ID
    require_once ("controle/AlunoDelete.php");
});

// Executa o roteador para lidar com as requisições
$roteador->run();
?>