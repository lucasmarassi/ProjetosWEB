<?php
require_once "Banco.php";

class Curso {
    private $id_curso;
    private $nome_curso;
    private $preco_curso;
    private $anos_conclusao;
    private $id_professor;
    private $email;
    private $senha;

    // Métodos getters e setters...

    // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
    public function jsonSerialize()
    {
        // Cria um objeto stdClass para armazenar os dados do cargo
        $objetoResposta = new stdClass();
        // Define as propriedades do objeto com os valores das propriedades da classe
        $objetoResposta->id_curso = $this->id_curso;
        $objetoResposta->nome_curso = $this->nome_curso;
        $objetoResposta->preco_curso = $this->preco_curso;
        $objetoResposta->anos_conclusao = $this->anos_conclusao;
        $objetoResposta->id_professor = $this->id_professor;
        $objetoResposta->email = $this->email;
        $objetoResposta->senha= $this->senha;

        // Retorna o objeto para serialização
        return $objetoResposta;
    }

    public function getIdCurso() {
        return $this->id_curso;
    }

    public function setIdCurso($id_curso) {
        $this->id_curso = $id_curso;
    }

    public function getNomeCurso() {
        return $this->nome_curso;
    }

    public function setNomeCurso($nome_curso) {
        $this->nome_curso = $nome_curso;
    }

    public function getPrecoCurso() {
        return $this->preco_curso;
    }

    public function setPrecoCurso($preco_curso) {
        $this->preco_curso = $preco_curso;
    }

    public function getAnosConclusao() {
        return $this->anos_conclusao;
    }

    public function setAnosConclusao($anos_conclusao) {
        $this->anos_conclusao = $anos_conclusao;
    }

    public function getIdProfessor() {
        return $this->id_professor;
    }

    public function setIdProfessor($id_professor) {
        $this->id_professor = $id_professor;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha= $senha;
    }


    // Método para converter a instância para um array associativo
    public function toArray() {
        return [
            'id_curso' => $this->getIdCurso(),
            'nome_curso' => $this->getNomeCurso(),
            'preco_curso' => $this->getPrecoCurso(),
            'anos_conclusao' => $this->getAnosConclusao(),
            'id_professor' => $this->getIdProfessor(),
            'email' => $this->getEmail()
            
            
            
        ];
    }

    public function nomeCursoExiste() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stmt = $conexao->prepare("SELECT COUNT(*) FROM Curso WHERE nome_curso = ?");
        if ($stmt === false) {
            return false;
        }
            $count=0;
        $stmt->bind_param("s", $this->nome_curso);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0;
    }
    
    public function id_professorExiste() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stmt = $conexao->prepare("SELECT COUNT(*) FROM Professor WHERE id_prof = ?");
        if ($stmt === false) {
            return false;
        }
        $count=0;

        $stmt->bind_param("s", $this->id_professor);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0;
    }
    
    public function cadastrar() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stmt = $conexao->prepare("INSERT INTO Curso ( nome_curso, preco_curso, anos_conclusao, id_professor,email,senha) VALUES ( ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("sisiss", $this->nome_curso, $this->preco_curso, $this->anos_conclusao, $this->id_professor,$this->email,$this->senha);

        return $stmt->execute();
    }

    public function login() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();
        $SQL = "SELECT * FROM Curso WHERE email = ? AND senha = ?;";
        $prepareSQL = $conexao->prepare($SQL);
        $prepareSQL->bind_param("ss", $this->email, $this->senha);
        $prepareSQL->execute();
        $matrizTupla = $prepareSQL->get_result();
    
        if ($tupla = $matrizTupla->fetch_object()) {
            $this->setIdCurso($tupla->id_curso);
            $this->setNomeCurso($tupla->nome_curso);
            $this->setPrecoCurso($tupla->preco_curso);
            $this->setAnosConclusao($tupla->anos_conclusao);
            $this->setIdProfessor($tupla->id_professor);
            $this->setEmail($tupla->email);
            
            return true;  // Login bem-sucedido
        }
    
        return false;  // Login falhou
    }
    

    public function read() {
        // Obtém a conexão com o banco de dados
        $meuBanco = new Banco();
        // Define a consulta SQL para selecionar todos os cargos ordenados por nome
        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM Curso");
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) {
            return null; // Se nenhum curso for encontrado, retorna null
        }

        // Inicializa um vetor para armazenar os cursos
        $vetorCursos = array();
        // Itera sobre as tuplas do resultado
        while ($tupla = $resultado->fetch_object()) {
            // Cria uma nova instância de Curso para cada tupla encontrada
            $curso = new Curso();
            // Define o ID e o nome do curso na instância
            $curso->setIdCurso($tupla->id_curso);
            $curso->setNomeCurso($tupla->nome_curso);
            $curso->setPrecoCurso($tupla->preco_curso);
            $curso->setAnosConclusao($tupla->anos_conclusao);
            $curso->setIdProfessor($tupla->id_professor);
            $curso->setEmail($tupla->email);
           
            $vetorCursos[] = $curso;
        }
        // Retorna o vetor com os cursos encontrados
        return $vetorCursos;
    }

    public function readID() {
        $meuBanco = new Banco();
        $id_curso = $this->id_curso;

        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM Curso WHERE id_curso = ?");
        $stm->bind_param("i", $this->id_curso);
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) {
            return null; // Se nenhum curso for encontrado, retorna null
        }

        $linha = $resultado->fetch_object();
        $curso = new Curso(); // Instancia um novo objeto Curso

        // Define as propriedades do curso com os valores do banco de dados
        $curso->setIdCurso($linha->id_curso);
        $curso->setNomeCurso($linha->nome_curso);
        $curso->setPrecoCurso($linha->preco_curso);
        $curso->setAnosConclusao($linha->anos_conclusao);
        $curso->setIdProfessor($linha->id_professor);
        $curso->setEmail($linha->email);
        
        return $curso; // Retorna o objeto Curso encontrado
    }

    public function update() {
        $meuBanco = new Banco();
        $id_curso = $this->id_curso;
        $sql = "UPDATE Curso SET nome_curso = ?, preco_curso = ?, anos_conclusao = ?, id_professor = ?, email=?, senha=? WHERE id_curso = ?";
        $stm = $meuBanco->getConexao()->prepare($sql);

        if ($stm === false) {
            // Handle error if the statement couldn't be prepared
            return false;
        }

        // Tipos de parâmetros: "s" para strings, "d" para doubles, "i" para inteiros
        $stm->bind_param("sddissi", $this->nome_curso, $this->preco_curso, $this->anos_conclusao, $this->id_professor, $this->email, $this->senha, $this->id_curso);
        
        $resultado = $stm->execute();

        return $resultado;
    }

    public function delete() {
        $meuBanco= new Banco();
        $conexao = $meuBanco->getConexao();

        // Verifica se a conexão foi bem-sucedida
        if ($conexao->connect_error) {
            die("Falha na conexão: " . $conexao->connect_error);
        }

        // Define a consulta SQL para excluir um curso pelo ID
        $SQL = "DELETE FROM Curso WHERE id_curso = ?;"; 

        // Prepara a consulta
        if ($prepareSQL = $conexao->prepare($SQL)) {
            // Define o parâmetro da consulta com o ID do curso
            $prepareSQL->bind_param("i", $this->id_curso);

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
