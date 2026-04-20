<?php
require_once "Banco.php";

class Professor {
    private $id_prof;
    private $nome;
    private $email;
    private $data_nascimento;
    private $senha;
    
   
  

    // Métodos getters e setters...

    // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
    public function jsonSerialize()
    {
        // Cria um objeto stdClass para armazenar os dados do cargo
        $objetoResposta = new stdClass();
        // Define as propriedades do objeto com os valores das propriedades da classe
        $objetoResposta->id_prof = $this->id_prof;
        $objetoResposta->nome = $this->nome;
        $objetoResposta->email = $this->email;
        $objetoResposta->data_nascimento = $this->data_nascimento;
        $objetoResposta->senha = $this->senha;
       

        // Retorna o objeto para serialização
        return $objetoResposta;
    }

    public function getId_prof() {
        return $this->id_prof;
    }

    public function setId_prof($id_prof) {
        $this->id_prof = $id_prof;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getData_nascimento() {
        return $this->data_nascimento;
    }

    public function setData_nascimento($data_nascimento) {
        $this->data_nascimento = $data_nascimento;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email= $email;
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
            'id_prof' => $this->getId_prof(),
            'nome' => $this->getNome(),
            'email' => $this->getEmail(),
            'data_nascimento' => $this->getData_nascimento()
           
           
        ];
    }
    
    public function emailExiste() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stmt = $conexao->prepare("SELECT COUNT(*) FROM Professor WHERE email = ?");
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

    public function cadastrar() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();

        $stmt = $conexao->prepare("INSERT INTO Professor (nome,email, data_nascimento, senha) VALUES ( ?,?,?,?)");
        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("ssss",  $this->nome,$this->email, $this->data_nascimento,$this->senha);

        return $stmt->execute();
    }


    public function login() {
        $meuBanco = new Banco();
        $conexao = $meuBanco->getConexao();
        $SQL = "SELECT * FROM Professor WHERE email = ? AND senha = MD5(?);";
        $prepareSQL = $conexao->prepare($SQL);
        $prepareSQL->bind_param("ss", $this->email, $this->senha);
        $prepareSQL->execute();
        $matrizTupla = $prepareSQL->get_result();
    
        if ($tupla = $matrizTupla->fetch_object()) {
            $this->setId_prof($tupla->id_prof);
            $this->setNome($tupla->nome);
            $this->setEmail($tupla->email);
            $this->setData_nascimento($tupla->data_nascimento);
         
            
            
            return true;  // Login bem-sucedido
        }
    
        return false;  // Login falhou
    }
    

    public function read() {
        // Obtém a conexão com o banco de dados
        $meuBanco = new Banco();
        // Define a consulta SQL para selecionar todos os cargos ordenados por nome
        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM Professor");
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) {
            return null; // Se nenhum curso for encontrado, retorna null
        }

        // Inicializa um vetor para armazenar os cursos
        $vetorProfessor = array();
        // Itera sobre as tuplas do resultado
        while ($tupla = $resultado->fetch_object()) {
            // Cria uma nova instância de Curso para cada tupla encontrada
            $Professor = new Professor();
            // Define o ID e o nome do curso na instância
            $Professor->setId_prof($tupla->id_prof);
            $Professor->setNome($tupla->nome);
            $Professor->setEmail($tupla->email);
            $Professor->setData_nascimento($tupla->data_nascimento);
            
            
            $vetorProfessor[] = $Professor;
        }
        // Retorna o vetor com os cursos encontrados
        return $vetorProfessor;
    }

    public function readID() {
        $meuBanco = new Banco();
        $id_prof = $this->id_prof;

        $stm = $meuBanco->getConexao()->prepare("SELECT * FROM Professor WHERE id_prof = ?");
        $stm->bind_param("i", $this->id_prof);
        $stm->execute();
        $resultado = $stm->get_result();

        if ($resultado->num_rows === 0) {
            return null; // Se nenhum curso for encontrado, retorna null
        }

        $linha = $resultado->fetch_object();
        $Professor = new Professor(); // Instancia um novo objeto Curso

        // Define as propriedades do curso com os valores do banco de dados
        $Professor->setId_prof($linha->id_prof);
        $Professor->setNome($linha->nome);
        $Professor->setEmail($linha->email);
        $Professor->setData_nascimento($linha->data_nascimento);
        

        return $Professor; // Retorna o objeto Curso encontrado
    }

   


        public function update() {
            $meuBanco = new Banco();
            $id_prof = $this->id_prof;
            $sql="UPDATE Professor SET nome=?, data_nascimento=?, email=?, senha=? WHERE id_prof = ? ";
            $stm = $meuBanco->getConexao()->prepare($sql);
    
            if ($stm === false) {
                // Handle error if the statement couldn't be prepared
                return false;
            }
    
            // Tipos de parâmetros: "s" para strings, "d" para doubles, "i" para inteiros
            $stm->bind_param("ssssi", $this->nome, $this-> data_nascimento,$this->email,$this->senha, $this->id_prof);
            
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
        $SQL = "DELETE FROM Professor WHERE id_prof = ?;"; 

        // Prepara a consulta
        if ($prepareSQL = $conexao->prepare($SQL)) {
            // Define o parâmetro da consulta com o ID do curso
            $prepareSQL->bind_param("i", $this->id_prof);

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
