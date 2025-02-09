<?php
// Exibe erros para facilitar o debug


// Inclui a classe Aluno, que contém funcionalidades relacionadas ao banco de dados e aos alunos
require_once("modelo/Professor.php");


    // Verifica se o arquivo foi enviado corretamente
    
        $nomeArquivo = $_FILES["variavelArquivo"]["tmp_name"];
        $ponteiroArquivo = fopen($nomeArquivo, "r");

        

        $qtdProfessor = 0; // Contador de alunos cadastrados com sucesso
        $professor = array(); // Array para armazenar objetos Aluno

        // Loop que lê cada linha do arquivo CSV
        while (($linhaArquivo = fgetcsv($ponteiroArquivo, 1000, ";")) !== false) {
            // Ignora linhas vazias
            if (empty($linhaArquivo[0]) && empty($linhaArquivo[1]) && empty($linhaArquivo[2])) {
                continue;
            }
            
               
           
                

            

            // Converte os valores da linha para UTF-8
            $linhaArquivo = array_map("utf8_encode", $linhaArquivo);

            // Valida se o ID do curso é numérico (inteiro)
            

            // Cria um novo objeto da classe Aluno e define as propriedades
            $professor = new Professor();
            $professor->setNome($linhaArquivo[0]);   // Coluna 0: Nome
            $professor->setEmail($linhaArquivo[1]);  // Coluna 1: Email
            $professor->setData_nascimento($linhaArquivo[2]); // Coluna 2: ID do curso (validado como número)
            $professor->setSenha($linhaArquivo[3]);  // Coluna 3: Senha (opcional)
                     
            if ($professor->emailExiste($linhaArquivo[1])) {
                throw new Exception("email inválido: " . $linhaArquivo[1]);
            }
           
                
            

            // Chama o método para cadastrar o aluno no banco de dados
            if ($professor->cadastrar()) {
                 // Armazena o objeto aluno no array
                $qtdProfessor++; 
                
            }
            
        }

    

        // Monta a resposta para enviar de volta ao cliente
        $resposta = new stdClass();
        $resposta->status = true;
        $resposta->msg = "Cadastrados com sucesso";
        $resposta->totalAlunos = $qtdProfessor;
        $resposta->ProfessorCadastrados = $professor;
        echo json_encode($resposta);
   
?>