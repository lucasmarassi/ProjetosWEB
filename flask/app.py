from flask import Flask, jsonify, request, Response
from controle.ProfessorController import ProfessorController
from model.Professor import Professor # Ajuste conforme a localização do arquivo da classe Cargo

# Cria a aplicação Flask
app = Flask("rest_api")

# Função auxiliar para lidar com erros de validação
# Retorna uma mensagem de erro em formato JSON e o status HTTP 400 (Bad Request)
def handle_validation_error(e):
    return jsonify({"erro": str(e)}), 400

# Função responsável por obter todos os cargos
# Endpoint: GET /cargos/
# Retorna a lista de todos os cargos no banco de dados
@app.route('/professor/flask', methods=['GET'])
def readAll():
    try:
        # Instancia o controlador de Cargo
        professorController = ProfessorController()
        # Chama o método para buscar todos os cargos e retorna o resultado
        return professorController.read_all()
    except ValueError as e:
        # Se houver um erro de validação, lida com ele
        return handle_validation_error(e)

# Função responsável por obter um cargo específico pelo ID
# Endpoint: GET /cargos/<int:id>
# Retorna o cargo correspondente ao ID fornecido
@app.route('/professor/flask/<int:id>', methods=['GET'])
def readById(id):
    try:
        # Instancia o controlador de Cargo
        objProfessorController = ProfessorController()
        # Define o ID do cargo no objeto CargoController
        objProfessorController.professor.id_prof = id
        # Chama o método para buscar o cargo pelo ID e retorna o resultado
        return objProfessorController.read_by_id()
    except ValueError as e:
        # Lida com erros de validação e retorna a mensagem de erro
        return handle_validation_error(e)

# Função responsável por criar um novo cargo
# Endpoint: POST /cargos/
# Recebe os dados do cargo em formato JSON e cria um novo cargo no banco de dados
@app.route('/professor/flask', methods=['POST'])
def create():
    try:
        # Obtém o corpo da requisição em formato JSON
        body = request.get_json()
        # Instancia o controlador de Cargo
        objProfessorController = ProfessorController()
        # Define o nome do cargo com base nos dados recebidos
        objProfessorController.professor.nome = body['professor']['nome']
        objProfessorController.professor.email = body['professor']['email']
        objProfessorController.professor.data_nascimento = body['professor']['data_nascimento']
        objProfessorController.professor.senha = body['professor']['senha']
       

        # Chama o método para criar o cargo e retorna o resultado
        return objProfessorController.create_control()
    except ValueError as e:
        # Lida com erros de validação e retorna a mensagem de erro
        return handle_validation_error(e)

# Função responsável por atualizar um cargo existente
# Endpoint: PUT /cargos/<int:id>
# Recebe os dados do cargo em formato JSON e atualiza o cargo no banco de dados
@app.route('/professor/flask/<int:id>', methods=['PUT'])
def update(id):
    try:
        # Obtém o corpo da requisição em formato JSON
        body = request.get_json()
        # Instancia o controlador de Cargo
        objProfessorController = ProfessorController()
        # Define o nome e o ID do cargo com base nos dados recebidos
        objProfessorController.professor.nome = body['professor']['nome']
        objProfessorController.professor.email = body['professor']['email']
        objProfessorController.professor.data_nascimento = body['professor']['data_nascimento']
        objProfessorController.professor.senha = body['professor']['senha']
        objProfessorController.professor.id_prof = id
        # Chama o método para atualizar o cargo e retorna o resultado
        return objProfessorController.update()
    except ValueError as e:
        # Lida com erros de validação e retorna a mensagem de erro
        return handle_validation_error(e)

# Função responsável por deletar um cargo pelo ID
# Endpoint: DELETE /cargos/<int:id>
# Remove o cargo correspondente ao ID fornecido
@app.route('/professor/flask/<int:id>', methods=['DELETE'])
def delete(id):
    try:
        # Instancia o controlador de Cargo
        objProfessorController = ProfessorController()
        # Define o ID do cargo a ser deletado
        objProfessorController.professor.id_prof = id
        # Chama o método para deletar o cargo e verifica o resultado
        professoresExcluidos = objProfessorController.delete()
        if professoresExcluidos:
            # Se o cargo foi excluído com sucesso, retorna uma mensagem de sucesso
            return jsonify({"message": "Professor deletado com sucesso"}), 200
        else:
            # Se o cargo não foi encontrado, retorna uma mensagem de erro
            return jsonify({"message": " não encontrado"}), 404
    except ValueError as e:
        # Lida com erros de validação e retorna a mensagem de erro
        return handle_validation_error(e)

# Inicia o servidor Flask na porta 8080
app.run(host='0.0.0.0', port=8080)
