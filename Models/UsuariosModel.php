<?php 

	class UsuariosModel extends Mysql
	{
        private $intIdUsuario;
		private $strIdentificacion;


		private $strEmail;
		private $strPassword;

		private $intTipoId;
		private $intStatus;

		public function __construct()
		{
			parent::__construct();
		}	

        public function insertUsuario(string $identificacion, string $email, string $password, int $tipoid, int $status){

			$this->strIdentificacion = $identificacion;

			$this->strEmail = $email;
			$this->strPassword = $password;
			$this->intTipoId = $tipoid;
			$this->intStatus = $status;
			$return = 0;

			$sql = "SELECT * FROM usuario WHERE 
					user_name = '{$this->strEmail}' or persona_dni = '{$this->strIdentificacion}' ";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO usuario(persona_dni,user_name,user_passw,rol_ususario_id_rol_usa,status) 
				VALUES(?,?,?,?,?)";
	        	$arrData = array($this->strIdentificacion,
        						
        						$this->strEmail,
        						$this->strPassword,
        						$this->intTipoId,
        						$this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}

		public function selectUsuarios()
		{
			$whereAdmin = "";
			// if($_SESSION['idUser'] != 1 ){
			// 	$whereAdmin = " and u.id_usuario != 1 ";
			// }

			

			$sql = "SELECT u.id_usuario,
						u.persona_dni,
						u.user_name,
						
						r.idrol,
						r.nombrerol,
						u.status 
							FROM usuario u
							
							INNER JOIN rol r
							ON u.rol_ususario_id_rol_usa = r.idrol
							WHERE u.status != 0";

					// .$whereAdmin;
					$request = $this->select_all($sql);
					return $request;
		}

		public function selectUsuario(int $id_usuario){
			$this->intIdUsuario = $id_usuario;
			$sql = "SELECT u.id_usuario,
			u.persona_dni,
			
			u.user_name,
			r.idrol,
			r.nombrerol,
			u.status 
				FROM usuario u
				INNER JOIN rol r
				ON u.rol_ususario_id_rol_usa = r.idrol
				WHERE u.id_usuario = $this->intIdUsuario";
			$request = $this->select($sql);
			return $request;
		}

		public function updateUsuario(int $idUsuario, string $persona_dni,  string $email, string $password, int $tipoid, int $status){

			$this->intIdUsuario = $idUsuario;
			$this->strIdentificacion = $persona_dni;
			
			$this->strEmail = $email;
			$this->strPassword = $password;
			$this->intTipoId = $tipoid;
			$this->intStatus = $status;

			$sql = "SELECT * FROM usuario WHERE (user_name = '{$this->strEmail}' AND id_usuario != $this->intIdUsuario)
										  OR (persona_dni = '{$this->strIdentificacion}' AND id_usuario != $this->intIdUsuario) ";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				if($this->strPassword  != "")
				{
					$sql = "UPDATE usuario 
							SET persona_dni=?, user_name=?, 
							user_passw=?, rol_ususario_id_rol_usa=?, status=? 
							WHERE id_usuario = $this->intIdUsuario ";

					$arrData = array($this->strIdentificacion,
	        						
	        						$this->strEmail,
	        						$this->strPassword,
	        						$this->intTipoId,
	        						$this->intStatus);
				}else{
					$sql = "UPDATE usuario 
							SET persona_dni=?, user_name=?, 
							rol_ususario_id_rol_usa=?, status=? 
							WHERE id_usuario = $this->intIdUsuario ";
							
					$arrData = array($this->strIdentificacion,
	        						
	        						$this->strEmail,
	        						$this->intTipoId,
	        						$this->intStatus);
				}
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		}
		
		public function deleteUsuario(int $intIdusuario)
		{
			$this->intIdUsuario = $intIdusuario;
			$sql = "UPDATE usuario SET status = ? WHERE id_usuario = $this->intIdUsuario ";
			$arrData = array(0);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		

	}
 ?>