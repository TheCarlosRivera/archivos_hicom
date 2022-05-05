<?php

	class Auditorias extends Conexion
	{

		public function __construct()
		{
			parent::__construct();
		}

		public function audit_hicom_users($datos)
		{

			$this->consul_prev = $this->ConexSQL->prepare("SELECT * FROM hicom_users WHERE id_user = :id");				
			$this->consul_prev->bindParam(":id", $datos[2], PDO::PARAM_STR);
			$this->consul_prev->execute();
			$this->resul = $this->consul_prev->fetch();
			
			$this->consul = $this->ConexSQL->prepare("INSERT INTO audit_hicom_users(nombre_apellido, usuario, password, correo, pregunta_seguridad, respuesta_seguridad, tipo_cuenta, estado_cuenta, id_afectado, id_autor, tipo_accion) VALUES(:nombres, :usuario, :password, :correo, :pregunta, :respuesta, :tipo_cuenta, :estado_cuenta, :id_afectado, :id_autor, :tipo_accion)");

			$this->consul->bindParam(":nombres", $this->resul[1], PDO::PARAM_STR);
			$this->consul->bindParam(":usuario", $this->resul[2], PDO::PARAM_STR);
			$this->consul->bindParam(":password", $this->resul[3], PDO::PARAM_STR);
			$this->consul->bindParam(":correo", $this->resul[4], PDO::PARAM_STR);
			$this->consul->bindParam(":pregunta", $this->resul[5], PDO::PARAM_STR);
			$this->consul->bindParam(":respuesta", $this->resul[6], PDO::PARAM_STR);
			$this->consul->bindParam(":tipo_cuenta", $this->resul[7], PDO::PARAM_STR);			
			$this->consul->bindParam(":estado_cuenta", $this->resul[8], PDO::PARAM_STR);	
			$this->consul->bindParam(":id_afectado", $datos[2], PDO::PARAM_STR);
			$this->consul->bindParam(":id_autor", $datos[1], PDO::PARAM_STR);
			$this->consul->bindParam(":tipo_accion", $datos[0], PDO::PARAM_STR);

			$this->consul->execute();
			if (!$this->consul)
			{
				$this->error_proceso("Ocurrio un error, si se vuelve a presentar recargue la página o comuniquese con el desarrollador del sistema.");				
			}
		}

		public function audit_proveedores($datos)
		{
			$this->consul_prev = $this->ConexSQL->prepare("SELECT * FROM proveedores WHERE id_proveedor = :id");
			$this->consul_prev->bindParam(":id", $datos[2], PDO::PARAM_STR);
			$this->consul_prev->execute();
			$this->resul = $this->consul_prev->fetch();

			$this->consul = $this->ConexSQL->prepare("INSERT INTO audit_proveedores(nombre_proveedor, empresa_proveedor, direccion_proveedor, rubro_proveedor, sitio_web, telefono_1_proveedor, telefono_2_proveedor, telefono_3_proveedor, correo_proveedor, id_afectado, id_autor, tipo_accion) VALUES(:nombre, :empresa, :direccion, :rubro, :sitio_web, :telefono_1, :telefono_2, :telefono_3, :correo, :id_afectado, :id_autor, :tipo_accion)");

			$this->consul->bindParam(":nombre", $this->resul[1], PDO::PARAM_STR);
			$this->consul->bindParam(":empresa", $this->resul[2], PDO::PARAM_STR);
			$this->consul->bindParam(":direccion", $this->resul[3], PDO::PARAM_STR);
			$this->consul->bindParam(":rubro", $this->resul[4], PDO::PARAM_STR);
			$this->consul->bindParam(":sitio_web", $this->resul[5], PDO::PARAM_STR);
			$this->consul->bindParam(":telefono_1", $this->resul[6], PDO::PARAM_STR);
			$this->consul->bindParam(":telefono_2", $this->resul[7], PDO::PARAM_STR);
			$this->consul->bindParam(":telefono_3", $this->resul[8], PDO::PARAM_STR);
			$this->consul->bindParam(":correo", $this->resul[9], PDO::PARAM_STR);

			$this->consul->bindParam(":id_afectado", $datos[2], PDO::PARAM_STR);
			$this->consul->bindParam(":id_autor", $datos[1], PDO::PARAM_STR);
			$this->consul->bindParam(":tipo_accion", $datos[0], PDO::PARAM_STR);	

			$this->consul->execute();
			if (!$this->consul)
			{
				$this->error_proceso("Ocurrio un error, si se vuelve a presentar recargue la página o comuniquese con el desarrollador del sistema.");				
			}

	}

	public function audit_ventas($datos)
	{
			$this->consul_prev = $this->ConexSQL->prepare("SELECT * FROM ventas WHERE id_venta = :id");
			$this->consul_prev->bindParam(":id", $datos[2], PDO::PARAM_STR);
			$this->consul_prev->execute();
			$this->resul = $this->consul_prev->fetch();

			$this->consul = $this->ConexSQL->prepare("INSERT INTO audit_ventas(fecha_venta, efectivo, monedas, pago_rut_transf, debito_credito, tarjeta_comision, efectivo_final, contado, ventas, diferencia, color_ident, id_afectado, id_autor, tipo_accion) VALUES(:fecha_venta, :efectivo, :monedas, :pagos, :debito_credito, :tarjeta_comision, :efectivo_final, :contado, :ventas, :diferencia, :color_ident, :id_afectado, :id_autor, :tipo_accion)");

			$this->consul->bindParam(":fecha_venta", $this->resul[1], PDO::PARAM_STR);
			$this->consul->bindParam(":efectivo", $this->resul[2], PDO::PARAM_STR);
			$this->consul->bindParam(":monedas", $this->resul[3], PDO::PARAM_STR);
			$this->consul->bindParam(":pagos", $this->resul[4], PDO::PARAM_STR);
			$this->consul->bindParam(":debito_credito", $this->resul[5], PDO::PARAM_STR);
			$this->consul->bindParam(":tarjeta_comision", $this->resul[6], PDO::PARAM_STR);
			$this->consul->bindParam(":efectivo_final", $this->resul[7], PDO::PARAM_STR);
			$this->consul->bindParam(":contado", $this->resul[8], PDO::PARAM_STR);
			$this->consul->bindParam(":ventas", $this->resul[9], PDO::PARAM_STR);
			$this->consul->bindParam(":diferencia", $this->resul[10], PDO::PARAM_STR);
			$this->consul->bindParam(":color_ident", $this->resul[11], PDO::PARAM_STR);

			$this->consul->bindParam(":id_afectado", $datos[2], PDO::PARAM_STR);
			$this->consul->bindParam(":id_autor", $datos[1], PDO::PARAM_STR);
			$this->consul->bindParam(":tipo_accion", $datos[0], PDO::PARAM_STR);	

			$this->consul->execute();
			if (!$this->consul)
			{
				$this->error_proceso("Ocurrio un error, si se vuelve a presentar recargue la página o comuniquese con el desarrollador del sistema.");				
			}		
	}


	public function audit_caja($datos)
	{

			$this->consul = $this->ConexSQL->prepare("INSERT INTO audit_caja(valor, id_afectado, id_autor, tipo_accion) VALUES(:valor, :id_afectado, :id_autor, :tipo_accion)");

			$this->consul->bindParam(":valor", $datos[3], PDO::PARAM_STR);
			$this->consul->bindParam(":id_afectado", $datos[2], PDO::PARAM_STR);
			$this->consul->bindParam(":id_autor", $datos[1], PDO::PARAM_STR);
			$this->consul->bindParam(":tipo_accion", $datos[0], PDO::PARAM_STR);

			$this->consul->execute();
			if (!$this->consul)
			{
				$this->error_proceso("Ocurrio un error, si se vuelve a presentar recargue la página o comuniquese con el desarrollador del sistema.");				
			}

	}

	public function audit_monedas($datos)
	{

			$this->consul = $this->ConexSQL->prepare("INSERT INTO audit_monedas(valor, id_afectado, id_autor, tipo_accion) VALUES(:valor, :id_afectado, :id_autor, :tipo_accion)");

			$this->consul->bindParam(":valor", $datos[3], PDO::PARAM_STR);
			$this->consul->bindParam(":id_afectado", $datos[2], PDO::PARAM_STR);
			$this->consul->bindParam(":id_autor", $datos[1], PDO::PARAM_STR);
			$this->consul->bindParam(":tipo_accion", $datos[0], PDO::PARAM_STR);

			$this->consul->execute();
			if (!$this->consul)
			{
				$this->error_proceso("Ocurrio un error, si se vuelve a presentar recargue la página o comuniquese con el desarrollador del sistema.");				
			}

	}


	public function audit_config_comision($datos)
	{
		$this->prev = $this->ConexSQL->prepare("SELECT * FROM config_comision_venta WHERE id_comision = :id");
		$this->prev->bindParam(":id", $datos[2], PDO::PARAM_STR);
		$this->prev->execute();
		$this->r = $this->prev->fetch();

		$this->consul = $this->ConexSQL->prepare("INSERT INTO audit_comision_ventas(comision, id_afectado, id_autor, tipo_accion) VALUES(:comision, :id_afectado, :id_autor, :tipo_accion)");

			$this->consul->bindParam(":comision", $this->r[1], PDO::PARAM_STR);
			$this->consul->bindParam(":id_afectado", $datos[2], PDO::PARAM_STR);
			$this->consul->bindParam(":id_autor", $datos[1], PDO::PARAM_STR);
			$this->consul->bindParam(":tipo_accion", $datos[0], PDO::PARAM_STR);	

			$this->consul->execute();
			if (!$this->consul)
			{
				$this->error_proceso("Ocurrio un error, si se vuelve a presentar recargue la página o comuniquese con el desarrollador del sistema.");				
			}			
	}

	public function audit_config_monedas($datos)
	{
		$this->prev = $this->ConexSQL->prepare("SELECT * FROM config_monedas_ventas WHERE id_monedas_ventas = :id");
		$this->prev->bindParam(":id", $datos[2], PDO::PARAM_STR);
		$this->prev->execute();
		$this->r = $this->prev->fetch();

		$this->consul = $this->ConexSQL->prepare("INSERT INTO audit_monedas_ventas(monedas_ventas, id_afectado, id_autor, tipo_accion) VALUES(:monedas_ventas, :id_afectado, :id_autor, :tipo_accion)");

			$this->consul->bindParam(":monedas_ventas", $this->r[1], PDO::PARAM_STR);
			$this->consul->bindParam(":id_afectado", $datos[2], PDO::PARAM_STR);
			$this->consul->bindParam(":id_autor", $datos[1], PDO::PARAM_STR);
			$this->consul->bindParam(":tipo_accion", $datos[0], PDO::PARAM_STR);	

			$this->consul->execute();
			if (!$this->consul)
			{
				$this->error_proceso("Ocurrio un error, si se vuelve a presentar recargue la página o comuniquese con el desarrollador del sistema.");				
			}			
	}


	public function audit_ingresos_egresos($datos)
	{
		$this->prev = $this->ConexSQL->prepare("SELECT * FROM ingresos_egresos WHERE id_ingreso_egreso = :id");
		$this->prev->bindParam(":id", $datos[2], PDO::PARAM_STR);
		$this->prev->execute();
		$this->r = $this->prev->fetch();

		$this->consul = $this->ConexSQL->prepare("INSERT INTO audit_ingresos_egresos(valor, valor_efectivo, valor_monedas, id_proveedor, otro_proveedor, nota, tipo_accion) VALUES(:valor, :valor_efectivo, :valor_monedas, :id_proveedor, :otro_proveedor, :nota, :tipo_accion)");

		$this->consul->bindParam(":valor", $this->r[2], PDO::PARAM_STR);
		$this->consul->bindParam(":valor_efectivo", $this->r[3], PDO::PARAM_STR);
		$this->consul->bindParam(":valor_monedas", $this->r[4], PDO::PARAM_STR);
		$this->consul->bindParam(":id_proveedor", $this->r[5], PDO::PARAM_STR);
		$this->consul->bindParam(":otro_proveedor", $this->r[6], PDO::PARAM_STR);
		$this->consul->bindParam(":nota", $this->r[7], PDO::PARAM_STR);
		$this->consul->bindParam(":tipo_accion", $this->r[8], PDO::PARAM_STR);

			$this->consul->execute();
			if (!$this->consul)
			{
				$this->error_proceso("Ocurrio un error, si se vuelve a presentar recargue la página o comuniquese con el desarrollador del sistema.");				
			}	

	}

	public function audit_pago_electronico($datos)
	{
		$this->prev = $this->ConexSQL->prepare("SELECT * FROM pago_electronico WHERE id_pago_electronico = :id");
		$this->prev->bindParam(":id", $datos[2], PDO::PARAM_STR);
		$this->prev->execute();
		$this->r = $this->prev->fetch();

		$this->consul = $this->ConexSQL->prepare("INSERT INTO audit_pago_electronico(valor, id_afectado, id_autor, tipo_accion) VALUES(:valor, :id_afectado, :id_autor, :tipo_accion)");

			$this->consul->bindParam(":valor", $this->r[1], PDO::PARAM_STR);
			$this->consul->bindParam(":id_afectado", $datos[2], PDO::PARAM_STR);
			$this->consul->bindParam(":id_autor", $datos[1], PDO::PARAM_STR);
			$this->consul->bindParam(":tipo_accion", $datos[0], PDO::PARAM_STR);	

			$this->consul->execute();
			if (!$this->consul)
			{
				$this->error_proceso("Ocurrio un error, si se vuelve a presentar recargue la página o comuniquese con el desarrollador del sistema.");				
			}			
	}


	public function audit_pago_tarjetas($datos)
	{
		$this->prev = $this->ConexSQL->prepare("SELECT * FROM pago_tarjetas WHERE id_pago_tarjetas = :id");
		$this->prev->bindParam(":id", $datos[2], PDO::PARAM_STR);
		$this->prev->execute();
		$this->r = $this->prev->fetch();

		$this->consul = $this->ConexSQL->prepare("INSERT INTO audit_pago_tarjetas(valor, id_afectado, id_autor, tipo_accion) VALUES(:valor, :id_afectado, :id_autor, :tipo_accion)");

			$this->consul->bindParam(":valor", $this->r[1], PDO::PARAM_STR);
			$this->consul->bindParam(":id_afectado", $datos[2], PDO::PARAM_STR);
			$this->consul->bindParam(":id_autor", $datos[1], PDO::PARAM_STR);
			$this->consul->bindParam(":tipo_accion", $datos[0], PDO::PARAM_STR);	

			$this->consul->execute();
			if (!$this->consul)
			{
				$this->error_proceso("Ocurrio un error, si se vuelve a presentar recargue la página o comuniquese con el desarrollador del sistema.");				
			}			
	}





}//fin class

?>