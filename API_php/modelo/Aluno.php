<?php
require_once "Banco.php";

class Aluno {
    private $id_a;
    private $nome;
    private $email;
    private $id_curso;
    private $senha;
    
  

    // Métodos getters e setters...

    // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
    public function jsonSerialize()
    {
        // Cria um objeto stdClass para armazenar os dados do cargo
        $objetoResposta = new stdClass();
        // Define as propriedades do objeto com os valores das propriedades da classe
        $objetoResposta->id_a = $this->id_a;
        $objetoResposta->nome = $this->nome;
        $objetoResposta->email = $this->email;
        $objetoResposta->id_curso = $this->id_curso;
        $objetoResposta->senha = $this->senha;

        // Retorna o objeto para serialização
        return $objetoResposta;
    }

    public function getId_a() {
        return $this->id_a;
    }

    public function setId_a($id_a) {
        $this->id_a = $id_a;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getEmail() {
        return $this-> email;
    }
    public function setEmail($email) {
        $this->email= $email;
    }


    public function getIdcurso() {
        return $this->id_curso;
    }

    public function setIdcurso($id_curso) {
        $this->id_curso = $id_curso;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    

    // Método para converter a instância para um array associativo
    public function toArray() {
        return [
            'id_a' =>$this->getId_a(),
            'nome' =>$this->getNome(),
            'email' =>$this->getEmail(),
            'id_curso' =>$this->getIdcurso()
          
          
        ];
    }
    public function emailExiste() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stmt = $conexao->prepare("SELECT COUNT(*) FROM Aluno WHERE email = ?");
        if ($stmt === false) {
            return false;
        }
            $count=0;
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0;
    }

    
    
    public function id_cursoExiste() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stmt = $conexao->prepare("SELECT COUNT(*) FROM Curso WHERE id_curso = ?");
        if ($stmt === false) {
            return false;
        }
        $count=0;

        $stmt->bind_param("s", $this->id_curso);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0;
    }

    public function cadastrar() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stmt = $conexao->prepare("INSERT INTO Aluno(nome, email, id_curso, senha) VALUES ( ?, ?, ?, ?)");
        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("ssis", $this->nome, $this->email, $this->id_curso,$this->senha);

        return $stmt->execute();
    }

    

    public function login() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();
        $SQL = "SELECT * FROM Aluno WHERE email = ? AND senha = ?;";
        $prepareSQL = $conexao->prepare($SQL);
        $prepareSQL->bind_param("ss", $this->email, $this->senha);
        $prepareSQL->execute();
        $matrizTupla = $prepareSQL->get_result();
    
        if ($tupla = $matrizTupla->fetch_object()) {
            $this->setId_a($tupla->id_a);
            $this->setNome($tupla->nome);
            $this->setEmail($tupla->email);
            $this->setIdcurso($tupla->id_curso);
            
            return true;  // Login bem-sucedido
        }
    
        return false;  // Login falhou
    }
    
  
    

    public function read() {
        // Obtém a conexão com o banco de dados
        $meuBanco = new Banco();
        // Define a consulta SQL para selecionar todos os cargos ordenados por nome
        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM Aluno");
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) {
            return null; // Se nenhum curso for encontrado, retorna null
        }

        // Inicializa um vetor para armazenar os cursos
        $vetorAlunos = array();
        // Itera sobre as tuplas do resultado
        while ($tupla = $resultado->fetch_object()) {
            // Cria uma nova instância de Curso para cada tupla encontrada
            $aluno = new Aluno();
            // Define o ID e o nome do curso na instância
            $aluno->setId_a($tupla->id_a);
            $aluno->setNome($tupla->nome);
            $aluno->setEmail($tupla->email);
            $aluno->setIdcurso($tupla->id_curso);
           
           
            $vetorAlunos[] = $aluno;
        }
        // Retorna o vetor com os cursos encontrados
        return $vetorAlunos;
    }

    public function readID() {
        $meuBanco = new Banco();
        $id_a = $this->id_a;

        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM Aluno WHERE id_a = ?");
        $stm->bind_param("i", $this->id_a);
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) {
            return null; // Se nenhum curso for encontrado, retorna null
        }

        $linha = $resultado->fetch_object();
        $Aluno = new Aluno(); // Instancia um novo objeto Curso

        // Define as propriedades do curso com os valores do banco de dados
        $Aluno->setId_a($linha->id_a);
        $Aluno->setNome($linha->nome);
        $Aluno->setEmail($linha->email);
        $Aluno->setIdcurso($linha->id_curso);
      
       

        return $Aluno; // Retorna o objeto Curso encontrado
    }

    public function update() {
        $meuBanco = new Banco();
        $id_a = $this->id_a;
        $sql="UPDATE Aluno SET nome=?,email=?, id_curso=?, senha=? WHERE  id_a = ? ";
        $stm = $meuBanco->getConexao()->prepare($sql);

        if ($stm === false) {
            // Handle error if the statement couldn't be prepared
            return false;
        }

        // Tipos de parâmetros: "s" para strings, "d" para doubles, "i" para inteiros
        $stm->bind_param("ssisi", $this->nome, $this->email, $this->id_curso,$this->senha,$this->id_a);
        
        $resultado = $stm->execute();

        return $resultado;
    }

    public function delete() {
        $meuBanco= new Banco();
        $conexao =  $meuBanco->getConexao();

        // Verifica se a conexão foi bem-sucedida
        if ($conexao->connect_error) {
            die("Falha na conexão: " . $conexao->connect_error);
        }

        // Define a consulta SQL para excluir um curso pelo ID
        $SQL = "DELETE FROM Aluno WHERE id_a = ?;"; 

        // Prepara a consulta
        if ($prepareSQL = $conexao->prepare($SQL)) {
            // Define o parâmetro da consulta com o ID do curso
            $prepareSQL->bind_param("i", $this->id_a);

            // Executa a consulta
            if ($prepareSQL->execute()) {
                // Fecha a consulta preparada
                $prepareSQL->close();
                return true;
            } else {
                // Exibe o erro de execução da consulta
                echo "Erro na execução da consulta: " . $prepareSQL->error;
                return false;
            }
        } else {
            // Exibe o erro na preparação da consulta
            echo "Erro na preparação da consulta: " . $conexao->error;
            return false;
        }
    }
    
}
?>
