<?php 

	class ModulosModel extends Mysql
	{
        private $intIdModulo;
		private $strITitulo;


		private $strDescripcion;

		private $intStatus;

		public function __construct()
		{
			parent::__construct();
		}	

        public function insertModulo(string $titulo, string $descripcion){

			$this->strITitulo = $titulo;
			$this->strDescripcion = $descripcion;

			$query_insert  = "INSERT INTO modulo VALUES(null,?,?,1)";

	        	$arrData = array($this->strITitulo,
        						$this->strDescripcion
        						);
	        	$this->insert($query_insert,$arrData);
		}

		public function selectModulos()
		{			

			$sql = "SELECT * FROM modulo WHERE status=1";

					$request = $this->select_all($sql);
					return $request;
		}

		public function selectModulo(int $idmodulo){
			$this->intIdModulo = $idmodulo;
			$sql ="SELECT * FROM modulo WHERE idmodulo=$this->intIdModulo";

			$request = $this->select($sql);
			return $request;
		}
		
		public function updateModulo( int $idmodulo , string $titulo, string $descripcion){

			$this->intIdModulo = $idmodulo;
			$this->strITitulo = $titulo;
			$this->strDescripcion = $descripcion;

			$query_update  = "UPDATE modulo 
							SET
							titulo =?,
							descripcion=?
							WHERE idmodulo = $this->intIdModulo";

	        	$arrData = array(
								$this->strITitulo,
        						$this->strDescripcion
        						);
	        	$this->update($query_update,$arrData);
		}

		
		public function deleteModulo(int $idmodulo)
		{
			$this->intIdModulo = $idmodulo;
			$sql = "UPDATE modulo SET status = ? WHERE idmodulo = $this->intIdModulo ";
			$arrData = array(0);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		

	}
 ?>