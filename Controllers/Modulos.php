<?php 

	class Modulos extends Controllers{
		public function __construct()
		{
			parent::__construct();
			session_start();
			//session_regenerate_id(true);
			if(empty($_SESSION['login']))
			{
				header('Location: '.base_url().'/login');
			}
			getPermisos(MMODULOS);
			
		}
		
		public function Modulos()
		{
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Modulos";
			$data['page_title'] = "Modulos <small>Sistema RRHH</small>";
			$data['page_name'] = "modulos";
			$data['page_functions_js'] = "functions_modulos.js";
			$this->views->getView($this,"modulos",$data);
		}

		// public function setModulo(){
		// 	if($_POST){
		// 		// || empty($_POST['txtEmail']) || empty($_POST['listRolid']) || empty($_POST['listStatus'])
		// 		if(empty($_POST['txtIdentificacion']) || empty($_POST['txtEmail']) )
		// 		{
		// 			$idmodulo = $_POST['idmodulo'];
		// 			$strIdentificacion = strClean($_POST['txtIdentificacion']);
					
		// 			$strEmail = strtolower(strClean($_POST['txtEmail']));
		// 			$intTipoId = strClean($_POST['listRolid']);
		// 			$intStatus = strClean($_POST['listStatus']);

		// 			$arrResponse = array("status" => false, "msg" => 'Datos incorrectos '.
		// 			$idmodulo.''.$strIdentificacion.''.$strEmail.''.$intTipoId.''.$intStatus.'');
		// 		}else{ 
		// 			$idmodulo = intval($_POST['idmodulo']);
		// 			$strIdentificacion = strClean($_POST['txtIdentificacion']);
					
		// 			$strEmail = strtolower(strClean($_POST['txtEmail']));
		// 			$intTipoId = intval(strClean($_POST['listRolid']));
		// 			$intStatus = intval(strClean($_POST['listStatus']));
		// 			// $intTipoId = 4;
		// 			// $intStatus = 1;
		// 			$request_user = "";

		// 			if($idmodulo == 0)
		// 			{
		// 				$option = 1;
		// 				$strPassword =  empty($_POST['txtPassword']) ? hash("SHA256","1234567890") : hash("SHA256",$_POST['txtPassword']);
		// 				if($_SESSION['permisosMod']['w']){
		// 					$request_user = $this->model->insertModulo($strIdentificacion,
																				
		// 																		$strEmail,
		// 																		$strPassword, 
		// 																		$intTipoId, 
		// 																		$intStatus );
		// 				}
		// 			if($request_user != "exist" && $request_user > 0)
		// 			{
		// 					$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
		// 			}else if($request_user == 'exist'){
		// 				$arrResponse = array('status' => false, 'msg' => '¡Atención! el email o la identificación ya existe, ingrese otro.');		
		// 			}else{
		// 				$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
		// 			}																			
		// 			}else{
		// 				$option = 2;
		// 				$strPassword =  empty($_POST['txtPassword']) ? "" : hash("SHA256",$_POST['txtPassword']);
		// 				if($_SESSION['permisosMod']['u']){
		// 					$request_user = $this->model->updateModulo($idmodulo,
		// 																$strIdentificacion, 
																		
		// 																$strEmail,
		// 																$strPassword, 
		// 																$intTipoId, 
		// 																$intStatus);
		// 				}
		// 			if($request_user > 0)
		// 			{
		// 				if($option == 1){
		// 					$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
		// 				}else{
		// 					$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
		// 				}
		// 			}else if($request_user == 'exist'){
		// 				$arrResponse = array('status' => false, 'msg' => '¡Atención! el email o la identificación ya existe, ingrese otro.');		
		// 			}else{
		// 				$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
		// 			}

		// 			}			
		// 		}
		// 		echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		// 	}
		// 	die();
		//
		// }

		public function listar()
		{
			if($_SESSION['permisosMod']['r']){

				$datos=$this->model->selectModulos();
				$data= Array();
				foreach($datos as $row){
					$btnView = '';
					$btnEdit = '';
					$btnDelete = '';

					$sub_array = array();
					$sub_array[] = $row["idmodulo"];
					$sub_array[] = $row["titulo"];
					$sub_array[] = $row["descripcion"];
					
					if($row["status"] == 1)
					{
						$sub_array[] = '<span class="badge badge-success">Activo</span>';
					}else{
						$sub_array[] = '<span class="badge badge-danger">Inactivo</span>';
					}

					if($_SESSION['permisosMod']['r']){
						$btnView = '<button class="btn btn-info btn-sm btnViewModulo" onClick="verModulo('.$row["idmodulo"].')" title="Ver Módulo"><i class="far fa-eye"></i></button>';
					}
					if($_SESSION['permisosMod']['u']){
						if(($_SESSION['userData']['idrol'] == 1) ){
							$btnEdit = '<button class="btn btn-primary  btn-sm btnEditModulo" onClick="editar('.$row["idmodulo"].')" title="Editar Módulo"><i class="fas fa-pencil-alt"></i></button>';
						}else{
							$btnEdit = '<button class="btn btn-secondary btn-sm" disabled ><i class="fas fa-pencil-alt"></i></button>';
						}
					}
					if($_SESSION['permisosMod']['d']){
						$btnDelete = '<button class="btn btn-danger btn-sm btnDelModulo" onClick="eliminar('.$row["idmodulo"].')" title="Eliminar Modulo"><i class="far fa-trash-alt"></i></button>';

					}
					$sub_array[] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
				
					$data[] = $sub_array;
				}
			
				$results = array(
					"sEcho"=>1,
					"iTotalRecords"=>count($data),
					"iTotalDisplayRecords"=>count($data),
					"aaData"=>$data);
				echo json_encode($results);

			
				
			}
			
		}
		public function guardaryeditar(){
			if($_POST){
				
				$strTitulo = strClean($_POST['titulo']);
				$strDescripcion = strClean($_POST['descripcion']);
				
					if(!empty($_POST["idmodulo"])  ){  
						$idmodulo = intval($_POST['idmodulo']);
						$this->model->updateModulo($idmodulo, $strTitulo , $strDescripcion); 
						  
					}else {
						$this->model->insertModulo($strTitulo , $strDescripcion);   
					}
				
			}
				
		}
//if($_SESSION['permisosMod']['r']){
//}

	//if($_SESSION['permisosMod']['u']){
	//}		

		public function mostrarModulo(){
			
				$indIdModulo = intval(strClean($_POST['idmodulo']));
				$datos=$this->model->selectModulo($indIdModulo);
				if(is_array($datos)==true and count($datos)>0){
					
						
					
					echo json_encode($datos);
				}


		}

		public function delModulo()
		{
			if($_POST){
				if($_SESSION['permisosMod']['d']){

					$intidmodulo = intval($_POST['idmodulo']);
					$datos=$this->model->selectModulo($intidmodulo);

					if(is_array($datos)==true and count($datos)>0){
						$requestDelete = $this->model->deleteModulo($intidmodulo);
					} 
					echo json_encode($requestDelete);
				}
			}
		}
		
	}
 ?>