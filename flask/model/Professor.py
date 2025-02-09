from model.Banco import Banco
from mysql.connector import Error

class Professor:
    def __init__(self):
        self._id_prof = None
        self._nome = None
        self._email = None
        self._data_nascimento = None
        self._senha = None

    def create(self):
        conexao = Banco.getConexao()
        if conexao:
            try:
                cursor = conexao.cursor()
                sql = "INSERT INTO professor (nome, email, data_nascimento, senha) VALUES (%s, %s, %s, %s)"
                cursor.execute(sql, (self.nome, self.email, self.data_nascimento, self.senha))
                conexao.commit()
                self.id_prof = cursor.lastrowid  # Atualiza o id_prof após criação
                return self.id_prof
            except Error as e:
                print(f"Erro ao criar professor: {e}")
                raise ValueError("Ocorreu um erro ao cadastrar o professor")
            finally:
                cursor.close()

    def readAll(self):
        conexao = Banco.getConexao()
        if conexao:
            try:
                cursor = conexao.cursor(dictionary=True)
                sql = "SELECT * FROM professor ORDER BY nome ASC"
                cursor.execute(sql)
                return cursor.fetchall()
            except Error as e:
                print(f"Erro ao obter professores: {e}")
                raise ValueError("Ocorreu um erro ao selecionar todos os professores")
            finally:
                cursor.close()

    def readById(self):
        conexao = Banco.getConexao()
        if conexao:
            try:
                cursor = conexao.cursor(dictionary=True)
                sql = "SELECT * FROM professor WHERE id_prof = %s"
                cursor.execute(sql, (self.id_prof,))
                linhaRespostaSQL = cursor.fetchone()
                if linhaRespostaSQL:
                    self.id_prof = linhaRespostaSQL['id_prof']
                    self.nome = linhaRespostaSQL['nome']
                    self.email = linhaRespostaSQL['email']
                    self.data_nascimento = linhaRespostaSQL['data_nascimento']
                return linhaRespostaSQL
            except Error as e:
                print(f"Erro ao obter professor por ID: {e}")
                return None
            finally:
                cursor.close()

    def update(self):
        conexao = Banco.getConexao()
        if conexao:
            try:
                cursor = conexao.cursor()
                sql = "UPDATE professor SET nome = %s, email = %s, data_nascimento = %s, senha = %s WHERE id_prof = %s"
                cursor.execute(sql, (self.nome, self.email, self.data_nascimento, self.senha, self.id_prof))
                conexao.commit()
                return cursor.rowcount
            except Error as e:
                print(f"Erro ao atualizar professor: {e}")
                raise ValueError("Erro ao atualizar o professor.")
            finally:
                cursor.close()

    def delete(self):
        conexao = Banco.getConexao()
        if conexao:
            try:
                cursor = conexao.cursor()
                sql = "DELETE FROM professor WHERE id_prof = %s"
                cursor.execute(sql, (self.id_prof,))
                conexao.commit()
                qtdExcluidos = cursor.rowcount
                return qtdExcluidos
            except Error as e:
                print(f"Erro ao deletar professor: {e}")
                return None
            finally:
                cursor.close()

    # Getter e Setter para id_prof
    @property
    def id_prof(self):
        return self._id_prof

    @id_prof.setter
    def id_prof(self, value):
        self._id_prof = value

    # Getter e Setter para nome
    @property
    def nome(self):
        return self._nome

    @nome.setter
    def nome(self, value):
        self._nome = value

    # Getter e Setter para email
    @property
    def email(self):
        return self._email

    @email.setter
    def email(self, value):
        self._email = value

    # Getter e Setter para data_nascimento
    @property
    def data_nascimento(self):
        return self._data_nascimento

    @data_nascimento.setter
    def data_nascimento(self, value):
        self._data_nascimento = value

    # Getter e Setter para senha
    @property
    def senha(self):
        return self._senha

    @senha.setter
    def senha(self, value):
        self._senha = value
