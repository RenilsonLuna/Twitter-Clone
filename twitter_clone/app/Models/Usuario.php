<?php 

namespace App\Models;
use MF\Model\Model;

class Usuario extends Model
{
	private $id;
	private $nome;
	private $email;
	private $senha;

	public function __get($attr)
	{
		return $this->$attr;
	}

	public function __set($attr, $value)
	{
		$this->$attr = $value;
	}

	// salvar
	public function salvar()
	{
		$query = "INSERT INTO usuarios(nome, email, senha) VALUES(:nome, :email, :senha)";
		try {
			
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(':nome', $this->__get('nome'));
			$stmt->bindValue(':email', $this->__get('email'));
			$stmt->bindValue(':senha', $this->__get('senha')); // md5() -> hash de 32 caracteres
			$stmt->execute();
		
		} catch (\PDOException $e) {
			return $e->getCode().': '.$e->getMessage();
		}
	}

	// validar cadastro
	public function validarCadastro()
	{
		$valido = true;
		if (strlen($this->__get('nome')) < 3) {
			$valido = false;
		}
		if (strlen($this->__get('email')) < 3) {
			$valido = false;
		}
		if (strlen($this->__get('senha')) < 3) {
			$valido = false;
		}

		return $valido;
	}

	// recuperar por email
	public function getByEmail()
	{
		$query = "SELECT nome, email FROM usuarios WHERE email = :email";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	// autenticando usuario
	public function autenticar()
	{
		$email = $this->__get('email');
		$senha = $this->__get('senha');

		$query = 'SELECT id, nome, email FROM usuarios WHERE email = :email AND senha = :senha';
		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':email', $email);
		$stmt->bindValue(':senha', $senha);
		$stmt->execute();

		$usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

		if (isset($usuario['id']) && isset($usuario['nome'])) {
			$this->__set('id', $usuario['id']);
			$this->__set('nome', $usuario['nome']);
		}

		return $this;
	}

	// recuperando por pesquisa
	public function getAll()
	{
		$query = "SELECT u.id, u.nome, u.email, 
				(
					SELECT count(*) 
					FROM usuarios_seguidores AS us 
					WHERE us.id_usuario = :id 
					AND us.id_usuario_seguindo = u.id 
				) AS seguindo_sn
			FROM usuarios AS u
			WHERE u.nome 
			LIKE :nome 
			AND u.id != :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
		$stmt->bindValue(':id', $this->__get('id'));
		$stmt->execute();
		$usuarios = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $usuarios;
	}

	public function seguirUsuario($idUsuario)
	{
		$query = "INSERT INTO usuarios_seguidores(id_usuario, id_usuario_seguindo) VALUES(:id_usuario, :id_usuario_seguindo)";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario_seguindo', $idUsuario);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		return true;
	}

	public function desSeguirUsuario($idUsuario)
	{
		$query = "DELETE FROM usuarios_seguidores WHERE id_usuario_seguindo = :id_usuario_seguindo AND id_usuario = :id_usuario";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario_seguindo', $idUsuario);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		return true;
	}

	// nome do usuario
	public function getInfoUser()
	{
		$query = "SELECT nome FROM usuarios WHERE id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue('id', $this->__get('id'));
		$stmt->execute();
		return $stmt->fetch();
	}

	// total de tweets
	public function totalTweets()
	{
		$query = "SELECT count(*) as total_tweets FROM tweets WHERE id_usuario = :id_usuario";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();
		return $stmt->fetch();
	}

	// total de seguidores
	public function totalSeguidores()
	{
		$query = "SELECT count(*) as total_seguidores FROM usuarios_seguidores WHERE id_usuario_seguindo = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $this->__get('id'));
		$stmt->execute();
		return $stmt->fetch();
	}

	// total de seguindo
	public function totalSeguindo()
	{
		$query = "SELECT count(*) as total_seguindo FROM usuarios_seguidores WHERE id_usuario = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id', $this->__get('id'));
		$stmt->execute();
		return $stmt->fetch();
	}
}