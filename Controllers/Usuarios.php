<?php 

	class Usuarios extends Controllers{
		public function __construct()
		{
			parent::__construct();
			session_start();
			//session_regenerate_id(true);
			if(empty($_SESSION['login']))
			{
				header('Location: '.base_url().'/login');
			}
			getPermisos(MUSUARIOS);
			
		}
		
		public function Usuarios()
		{
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Usuarios";
			$data['page_title'] = "USUARIOS <small>Sistema RRHH</small>";
			$data['page_name'] = "usuarios";
			$data['page_functions_js'] = "functions_usuarios.js";
			$this->views->getView($this,"usuarios",$data);
		}

		public function setUsuario(){
			if($_POST){
				// || empty($_POST['txtEmail']) || empty($_POST['listRolid']) || empty($_POST['listStatus'])
				if(empty($_POST['txtIdentificacion']) || empty($_POST['txtEmail']) )
				{
					$idUsuario = $_POST['idUsuario'];
					$strIdentificacion = strClean($_POST['txtIdentificacion']);
					
					$strEmail = strtolower(strClean($_POST['txtEmail']));
					$intTipoId = strClean($_POST['listRolid']);
					$intStatus = strClean($_POST['listStatus']);

					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos '.
					$idUsuario.''.$strIdentificacion.''.$strEmail.''.$intTipoId.''.$intStatus.'');
				}else{ 
					$idUsuario = intval($_POST['idUsuario']);
					$strIdentificacion = strClean($_POST['txtIdentificacion']);
					
					$strEmail = strtolower(strClean($_POST['txtEmail']));
					$intTipoId = intval(strClean($_POST['listRolid']));
					$intStatus = intval(strClean($_POST['listStatus']));
					// $intTipoId = 4;
					// $intStatus = 1;
					$request_user = "";

					if($idUsuario == 0)
					{
						$option = 1;
						$strPassword =  empty($_POST['txtPassword']) ? hash("SHA256","1234567890") : hash("SHA256",$_POST['txtPassword']);
						if($_SESSION['permisosMod']['w']){
							$request_user = $this->model->insertUsuario($strIdentificacion,
																				
																				$strEmail,
																				$strPassword, 
																				$intTipoId, 
																				$intStatus );
						}
					if($request_user != "exist" && $request_user > 0)
					{
							$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}else if($request_user == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '??Atenci??n! el email o la identificaci??n ya existe, ingrese otro.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}																			
					}else{
						$option = 2;
						$strPassword =  empty($_POST['txtPassword']) ? "" : hash("SHA256",$_POST['txtPassword']);
						if($_SESSION['permisosMod']['u']){
							$request_user = $this->model->updateUsuario($idUsuario,
																		$strIdentificacion, 
																		
																		$strEmail,
																		$strPassword, 
																		$intTipoId, 
																		$intStatus);
						}
					if($request_user > 0)
					{
						if($option == 1){
							$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($request_user == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '??Atenci??n! el email o la identificaci??n ya existe, ingrese otro.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}

					}			
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getUsuarios()
		{
			if($_SESSION['permisosMod']['r']){
				$arrData = $this->model->selectUsuarios();
				for ($i=0; $i < count($arrData); $i++) {
						$btnView = '';
						$btnEdit = '';
						$btnDelete = '';

					if($arrData[$i]['status'] == 1)
					{
						$arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
					}
					if($_SESSION['permisosMod']['r']){
						$btnView = '<button class="btn btn-info btn-sm btnViewUsuario" onClick="fntViewUsuario('.$arrData[$i]['id_usuario'].')" title="Ver usuario"><i class="far fa-eye"></i></button>';
					}
					if($_SESSION['permisosMod']['u']){
						if(($_SESSION['idUser'] == 1 and $_SESSION['userData']['idrol'] == 1) ||
							($_SESSION['userData']['idrol'] == 1 and $arrData[$i]['idrol'] != 1) ){
							$btnEdit = '<button class="btn btn-primary  btn-sm btnEditUsuario" onClick="fntEditUsuario(this,'.$arrData[$i]['id_usuario'].')" title="Editar usuario"><i class="fas fa-pencil-alt"></i></button>';
						}else{
							$btnEdit = '<button class="btn btn-secondary btn-sm" disabled ><i class="fas fa-pencil-alt"></i></button>';
						}
					}
					if($_SESSION['permisosMod']['d']){
						if(($_SESSION['idUser'] == 1 and $_SESSION['userData']['idrol'] == 1) ||
							($_SESSION['userData']['idrol'] == 1 and $arrData[$i]['idrol'] != 1) and
							($_SESSION['userData']['id_usuario'] != $arrData[$i]['id_usuario'] )
							){
							$btnDelete = '<button class="btn btn-danger btn-sm btnDelUsuario" onClick="fntDelUsuario('.$arrData[$i]['id_usuario'].')" title="Eliminar usuario"><i class="far fa-trash-alt"></i></button>';
						}else{
							$btnDelete = '<button class="btn btn-secondary btn-sm" disabled ><i class="far fa-trash-alt"></i></button>';
						}
					}
					$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
				
			}
			die();
		}

		public function getUsuario($id_usuario){
			if($_SESSION['permisosMod']['r']){
				$idusuario = intval($id_usuario);
				if($idusuario > 0)
				{
					$arrData = $this->model->selectUsuario($idusuario);
					if(empty($arrData))
					{
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function delUsuario()
		{
			if($_POST){
				if($_SESSION['permisosMod']['d']){
					$intid_usuario = intval($_POST['idUsuario']);
					$requestDelete = $this->model->deleteUsuario($intid_usuario);
					if($requestDelete)
					{
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el usuario');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el usuario.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}
		
		
	}
 ?>