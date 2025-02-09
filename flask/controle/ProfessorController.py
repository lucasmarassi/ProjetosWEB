from flask import jsonify
from model.Professor import Professor

class ProfessorController:
    # Classe responsável por controlar as operações CRUD para a entidade Cargo.
    
    def __init__(self):
        # Inicializa a classe CargoController.
        # Cria uma instância da classe Cargo para gerenciar as operações de banco de dados.
        self._professor = Professor()

    # Valida o nome do cargo.
    # Levanta uma exceção se o nome do cargo for nulo ou tiver menos de 3 caracteres.
    def validar_nomeProfessor(self):
        if self._professor.nome is None:
            # Verifica se o nome do cargo é nulo
            raise ValueError("O nome do professor não pode ser vazio")
        if len(self._professor.nome) < 3:
            # Verifica se o nome do cargo tem menos de 3 caracteres
            raise ValueError("O nome do professor não pode ser vazio e deve ter pelo menos 3 caracteres.")
    
    # Obtém todos os cargos do banco de dados.
    # Retorna um JSON contendo a lista de cargos ou uma mensagem de erro em caso de falha.
    def read_all(self):

        professores = self._professor.readAll()
        if professores is not None:
            # Retorna a lista de cargos em formato JSON e o status HTTP 200 (OK)
            return jsonify(professores), 200
        else:
            # Retorna uma mensagem de erro e o status HTTP 500 (Internal Server Error)
            return jsonify({"message": "Não foi possível obter os professores"}), 500
    
    # Obtém um cargo específico pelo ID.
    # Retorna um JSON com os dados do cargo ou uma mensagem de erro caso o cargo não seja encontrado.    
    def read_by_id(self):
        
        professor_data = self._professor.readById()
        if professor_data:
            # Retorna os dados do cargo em formato JSON e o status HTTP 200 (OK)
            return jsonify(professor_data), 200
        else:
            # Retorna uma mensagem de erro e o status HTTP 404 (Not Found)
            return jsonify({"message": "Professor não encontrado"}), 404
        
    # Cria um novo cargo no banco de dados.
    # Valida o nome do cargo antes de inserir os dados no banco.
    # Retorna o ID do novo cargo criado ou uma mensagem de erro caso falhe.
    def create_control(self):

        self.validar_nomeProfessor()  # Valida o nome do cargo antes de prosseguir
        id_novo_professor = self._professor.create()  # Tenta criar o novo cargo no banco de dados
        if id_novo_professor:
            # Retorna o ID do novo cargo e o nome em formato JSON com o status HTTP 201 (Created)
            return jsonify({"id_prof": id_novo_professor, 
                            "nome": self._professor.nome,
                            "email": self._professor.email,
                            "data_nascimento": self._professor.data_nascimento
                            }), 201
        else:
            # Retorna uma mensagem de erro e o status HTTP 500 (Internal Server Error)
            return jsonify({"message": "Não foi possível criar o professor"}), 500

    # Atualiza um cargo existente no banco de dados.
    # Valida o nome do cargo antes de atualizar os dados.
    # Retorna os dados do cargo atualizado ou uma mensagem de erro em caso de falha.
    def update(self):
   
        self.validar_nomeProfessor()  # Middleware para validar o nome do cargo
        id_novo_professor = self._professor.update()  # Tenta atualizar o cargo no banco de dados
        if id_novo_professor:
            # Retorna o ID do cargo atualizado e o nome em formato JSON com o status HTTP 200 (OK)
             return jsonify({"id_prof": id_novo_professor, 
                            "nome": self._professor.nome,
                            "email": self._professor.email,
                            "data_nascimento": self._professor.data_nascimento,
                            "senha": self._professor.senha

                            }), 200
        else:
            # Retorna uma mensagem de erro e o status HTTP 500 (Internal Server Error)
            return jsonify({"message": "Não foi possível atualizar o professor"}), 500


    def delete():
        # Exclui um cargo do banco de dados pelo ID.
        # Retorna uma mensagem de sucesso ou erro dependendo do resultado.
        professor = Professor()  # Cria uma instância temporária da classe Cargo
        linhas_afetadas = professor.delete()  # Tenta deletar o cargo pelo ID
        if linhas_afetadas:
            # Retorna uma mensagem de sucesso e o status HTTP 200 (OK)
            return jsonify({"message": "professor excluído com sucesso"}), 200
        else:
            # Retorna uma mensagem de erro e o status HTTP 404 (Not Found)
            return jsonify({"message": "professor não encontrado"}), 404

    # Getter para acessar o objeto cargo
    @property
    def professor(self):
        # Retorna a instância da classe Cargo.
        return self._professor

    # Setter para modificar o objeto cargo
    @professor.setter
    def professor(self, value):
        # Permite modificar a instância da classe Cargo.
        self._professor = value
