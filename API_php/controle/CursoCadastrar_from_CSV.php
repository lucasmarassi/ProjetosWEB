<?php
// Exibe erros para facilitar o debug


// Inclui a classe Aluno, que contém funcionalidades relacionadas ao banco de dados e aos alunos
require_once("modelo/Curso.php");


    // Verifica se o arquivo foi enviado corretamente
    
        $nomeArquivo = $_FILES["variavelArquivo"]["tmp_name"];
        $ponteiroArquivo = fopen($nomeArquivo, "r");

        

        $qtdCurso = 0; // Contador de alunos cadastrados com sucesso
        $curso = array(); // Array para armazenar objetos Aluno

        // Loop que lê cada linha do arquivo CSV
        while (($linhaArquivo = fgetcsv($ponteiroArquivo, 1000, ";")) !== false) {
            // Ignora linhas vazias
            if (empty($linhaArquivo[0]) && empty($linhaArquivo[1]) && empty($linhaArquivo[2])) {
                continue;
            }
            
               
           
                

            

            // Converte os valores da linha para UTF-8
            $linhaArquivo = array_map("utf8_encode", $linhaArquivo);

            // Valida se o ID do curso é numérico (inteiro)
            if (!is_numeric($linhaArquivo[3])) {
                throw new Exception("ID do professor inválido: " . $linhaArquivo[2]);
            }

            // Cria um novo objeto da classe Aluno e define as propriedades
            $curso = new Curso();
            $curso->setNomeCurso($linhaArquivo[0]);   // Coluna 0: Nome
            $curso->setPrecoCurso($linhaArquivo[1]);  // Coluna 1: Email
            $curso->setAnosConclusao($linhaArquivo[2]);
            $curso->setIdProfessor($linhaArquivo[3]);    // Coluna 3: Senha (opcional)
            $curso->setEmail($linhaArquivo[4]); // Coluna 2: ID do curso (validado como número)
            $curso->setSenha($linhaArquivo[5]);       
            if ($curso->nomeCursoExiste($linhaArquivo[0])) {
                throw new Exception("nomeCurso inválido, ja esta sendo utilizado: " . $linhaArquivo[0]);
            }
            if ($curso->id_professorExiste($linhaArquivo[3])) {
                
            

            // Chama o método para cadastrar o aluno no banco de dados
            if ($curso->cadastrar()) {
                 // Armazena o objeto aluno no array
                $qtdCurso++; 
                
            }
            }else{
                throw new Exception("ID_prof inválido: " . $linhaArquivo[3]);
            }
        }

    

        // Monta a resposta para enviar de volta ao cliente
        $resposta = new stdClass();
        $resposta->status = true;
        $resposta->msg = "Cadastrados com sucesso";
        $resposta->totalCursos = $qtdCurso;
        $resposta->CursoCadastrados = $curso;
        echo json_encode($resposta);
   
?>