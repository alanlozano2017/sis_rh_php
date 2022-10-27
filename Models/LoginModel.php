<?php 

	class LoginModel extends Mysql
	{
		private $intIdUsuario;
		private $strUsuario;
		private $strPassword;
		private $strToken;

		public function __construct()
		{
			parent::__construct();
		}	

		public function loginUser(string $usuario, string $password)
		{
			$this->strUsuario = $usuario;
			$this->strPassword = $password;
			
			$sql = "SELECT id_usuario,status FROM usuario WHERE 
			user_name = '$this->strUsuario' and 
			user_passw = '$this->strPassword' and 
			status != 0" ;

			$request = $this->select($sql);
			return $request;
		}

		public function sessionLogin(int $iduser){
			$this->intIdUsuario = $iduser;
			//BUSCAR ROLE 
			$sql = "SELECT u.id_usuario,
			u.persona_dni,
			p.nombres,
			p.apellidos,
			p.telefono,
			u.user_name,
			p.nit,
			p.nombrefiscal,
			p.direccionfiscal,
			r.idrol,
			r.nombrerol,
			u.status 
				FROM usuario u
				INNER JOIN persona p
				ON p.idpersona = u.persona_dni
				INNER JOIN rol r
				ON u.rol_ususario_id_rol_usa = r.idrol
				WHERE u.id_usuario = $this->intIdUsuario";
			$request = $this->select($sql);
			$_SESSION['userData'] = $request;
			return $request;
		}




		public function getUsuario(string $email, string $token){
			$this->strUsuario = $email;
			$this->strToken = $token;
			
			$sql = "SELECT id_usuario FROM usuario WHERE 
			user_name = '$this->strUsuario' and 
			status = 1 ";

			$request = $this->select($sql);
			return $request;
		}

		public function insertPassword(int $id_usuario, string $password){
			$this->intIdUsuario = $id_usuario;
			$this->strPassword = $password;
			$sql = "UPDATE usuario SET user_passw = ? WHERE id_usuario = $this->intIdUsuario ";
			$arrData = array($this->strPassword,"");
			$request = $this->update($sql,$arrData);
			return $request;
		}
	}
 ?>