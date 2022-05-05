<?php

	Class Consultas extends Conexion
	{

		public function __construct()
		{
			parent::__construct();
		}

		public function Registro_existentes()
		{
			$this->consul = $this->ConexSQL->prepare("SELECT id_user FROM hicom_users");
			$this->consul->execute();
			$this->resul = $this->consul->rowCount();

			$this->resul = $this->consul->fetch();
			return $this->resul;
		}

	}

?>