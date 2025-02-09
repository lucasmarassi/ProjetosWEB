<?php
// Exibe erros para facilitar o debug


// Inclui a classe Aluno, que contém funcionalidades relacionadas ao banco de dados e aos alunos
require_once("modelo/Aluno.php");


    // Verifica se o arquivo foi enviado corretamente
    
        $nomeArquivo = $_FILES["variavelArquivo"]["tmp_name"];
        $ponteiroArquivo = fopen($nomeArquivo, "r");

        

        $qtdAluno = 0; // Contador de alunos cadastrados com sucesso
        $aluno = array(); // Array para armazenar objetos Aluno

        // Loop que lê cada linha do arquivo CSV
        while (($linhaArquivo = fgetcsv($ponteiroArquivo, 1000, ";")) !== false) {
            // Ignora linhas vazias
            if (empty($linhaArquivo[0]) && empty($linhaArquivo[1]) && empty($linhaArquivo[2])) {
                continue;
            }
            
               
           
                

            

            // Converte os valores da linha para UTF-8
            $linhaArquivo = array_map("utf8_encode", $linhaArquivo);

            // Valida se o ID do curso é numérico (inteiro)
            if (!is_numeric($linhaArquivo[2])) {
                throw new Exception("ID do curso inválido: " . $linhaArquivo[2]);
            }

            // Cria um novo objeto da classe Aluno e define as propriedades
            $aluno = new Aluno();
            $aluno->setNome($linhaArquivo[0]);   // Coluna 0: Nome
            $aluno->setEmail($linhaArquivo[1]);  // Coluna 1: Email
            $aluno->setIdcurso($linhaArquivo[2]); // Coluna 2: ID do curso (validado como número)
            $aluno->setSenha($linhaArquivo[3]);  // Coluna 3: Senha (opcional)
                     
            if ($aluno->emailExiste($linhaArquivo[1])) {
                throw new Exception("email inválido: " . $linhaArquivo[1]);
            }
            if ($aluno->id_cursoExiste($linhaArquivo[1])) {
                
            

            // Chama o método para cadastrar o aluno no banco de dados
            if ($aluno->cadastrar()) {
                 // Armazena o objeto aluno no array
                $qtdAluno++; 
                
            }
            }else{
                throw new Exception("ID_curso inválido: " . $linhaArquivo[2]);
            }
        }

    

        // Monta a resposta para enviar de volta ao cliente
        $resposta = new stdClass();
        $resposta->status = true;
        $resposta->msg = "Cadastrados com sucesso";
        $resposta->totalAlunos = $qtdAluno;
        $resposta->AlunoCadastrados = $aluno;
        echo json_encode($resposta);
   
?>