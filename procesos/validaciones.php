<?php

	require_once "Conexion.php";
	require_once "Procesos.php";

	session_start();

	//expresiones regulares
	$texto_num = "/^[a-zA-Z0-9\_\-]/";	
	$exp_nombres = "/^[a-zA-Z\s]{1,25}$/";	
	$usuario = "/^[a-zA-Z0-9\_\-]{10,15}$/";
	$password = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$\/.%*?])([A-Za-z\d$\/.%*?]|[^ ]){8,20}$/";
	$correo = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/";
	$pregunta = "/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü0-9\s]{5,30}$/";
	$respuesta = "/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü0-9\s]{5,30}$/";
	$exp_nombre = "/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü\s]{5,20}$/";	
	$exp_telefono = "/^[0-9]{9,11}$/";
	$exp_empresa_dir = "/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü0-9(-.,*)\s]{5,50}$/";	
	$exp_rubro = "/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü0-9(-.,*)\s]{1,500}$/";
	$exp_comision = "/^[0-9(.)]{1,4}$/";
	$exp_monedas = "/^[0-9]{1,6}$/";
	$exp_numero = "/^[0-9-.]/";
	$exp_web = "/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü0-9-._]{1,500}$/";


	class Validaciones extends Procesos
	{

		public function __construct()
		{
			parent::__construct();
		}

	public function msj_validacion_error($msj)
	{
			?>
				<script type="text/javascript">
				document.getElementById('mensaje_error_proceso').innerHTML='Ocurrio un error, si se vuelve a presentar recargue la página o comuniquese con el desarrollador del sistema. <?php echo "Error: " . $msj ?>';
				$("#alert_error_proceso").addClass("mostrar");
				setTimeout(() =>
				{
					$("#alert_error_proceso").removeClass("mostrar");			
				}, 7500);					
				</script>
			<?php
			exit();			
	}

	//validando las expresiones regulares
	public function validar_exp($expresion, $variable)
	{
		if(!preg_match($expresion, $variable))
		{
			$this->msj_validacion_error("(v0001)");
		}
	}

	//validando que el array no tenga variables vacias
	public function validar_campos_vacios($datos)
	{
		foreach ($datos as $key => $value) {
			if(empty($datos[$key]))
			{
				$this->msj_validacion_error("(v0002)");
				exit();
			}
		}
	}

	//enviando los datos al registro correspondiente
	public function enviar_form($datos, $tipo_proceso)
	{
		$this->$tipo_proceso($datos);			
	}

	public function enviar_form_two($datos, $tipo_proceso, $extra)
	{
		$this->$tipo_proceso($extra, $datos);			
	}	

}//fin class Consultas

/* instanciando la clase*/
$validar = new Validaciones;

	/*validando usuario existente*/
	if(isset($_POST['val_user']) && !empty($_POST['val_user']))
	{
		$validar->validar_exp($usuario, $_POST['val_user']);
		$validar->enviar_form($_POST['val_user'], "consul_user_existente");		
	}

	/*validando correo existente*/
	if(isset($_POST['val_correo']) && !empty($_POST['val_correo']))
	{
		$datos = [ $_POST['val_correo'], $_POST['correo'] ];

		$validar->validar_exp($correo, $_POST['correo']);
		$validar->enviar_form($datos, "consul_correo_existente");		
	}

	//REGISTRO DE USUARIO

	/*validando que esten definidos y no vacias las variables*/
	if(isset($_POST['registro']) && !empty($_POST['registro']) && $_POST['registro'] == "registro")
	{

		//convirtiendo las variables en un  array
		$datos = 
		[
			$_POST['nombre'], $_POST['usuario'], 
			$_POST['password'],	$_POST['correo'], 
			$_POST['pregunta'],	$_POST['respuesta'], 
			$_POST['tipo_cuenta']
		];

		$validar->validar_campos_vacios($datos);

		//recorriendo las variables y validandolas
		foreach ($_POST as $key => $value) {
			switch ($key) {
				case 'nombre':
						$validar->validar_exp($exp_nombres, $_POST['nombre']);
					break;

				case 'usuario':
						$validar->validar_exp($usuario, $_POST['usuario']);
					break;

				case 'password':
						$validar->validar_exp($password, $_POST['password']);
					break;

				case 'correo':
						$validar->validar_exp($correo, $_POST['correo']);
					break;

				case 'pregunta':
						$validar->validar_exp($pregunta, $_POST['pregunta']);
					break;

				case 'respuesta':
						$validar->validar_exp($respuesta, $_POST['respuesta']);
						$validar->enviar_form($datos, "registro_usuario");
					break;

			}//fin switch
		}//fin foreach	
	}//fin if


	//CONSULTA DE PREGUNTA DE SEGURIDAD PARA RECUPERAR USUARIO
	if (isset($_POST['busq']) && !empty($_POST['busq']) && is_numeric($_POST['busq'])) 
	{
		$validar->enviar_form($_POST['busq'], "consul_pregunta_seguridad");
	}

	//RECUPERACION DE DATOS DEL USUARIO
	if (isset($_POST['recuperar']) && !empty($_POST['recuperar']) && $_POST['recuperar'] == "recuperar") 
	{

		$datos = [ $_POST['id'], $_POST['respuesta'] ];

		$validar->validar_exp($respuesta, $_POST['respuesta']);
		$validar->enviar_form($datos, "consul_respuesta_seguridad");
	}

	/*validando password existente*/
	if(isset($_POST['val_pass']) && !empty($_POST['val_pass']))
	{
		$datos = [$_POST['val_pass'], $_POST['id_pass'] ];
		$validar->validar_exp($password, $_POST['val_pass']);
		$validar->enviar_form($datos, "consul_pass_existente");		
	}

	//CAMBIANDO LA CONTRASEÑA DEL USUARIO
	if (isset($_POST['cambio_pass']) && !empty($_POST['cambio_pass']) && $_POST['cambio_pass'] == "cambio_pass") 
	{

		$datos = [ $_POST['id'], $_POST['password'] ];
		$validar->validar_exp($password, $_POST['password']);
		$validar->enviar_form($datos, "actualizar_password");
	}

	//CONFIRMACION DEL CORREO PARA ENVIAR CODIGO DE RECUPERACION
	if (isset($_POST['enviar_datos']) && !empty($_POST['enviar_datos'])) 
	{
		$datos = [ $_POST['enviar_datos'], $_POST['id_user'] ];

		$validar->validar_exp($correo, $_POST['enviar_datos']);
		$validar->enviar_form($datos, "consul_correo");
	}	

	//INICIANDO SESSION
	if (isset($_POST['login']) && !empty($_POST['login']) && $_POST['login'] == "login") {

		$datos = 
		[
			$_POST['usuario'], $_POST['password']
		];

		$validar->validar_exp($usuario, $_POST['usuario']);		
		$validar->enviar_form($datos, "consultar_datos_user");
	}

	//CERRANDO SESSION
	if (isset($_POST['cerrar_session']) && !empty($_POST['cerrar_session']) && $_POST['cerrar_session'] == "cerrar_session") {
		$validar->enviar_form("destruir", "cerrar_session");
	}

	//BUSCADOR
	if(isset($_POST['buscar']) && !empty($_POST['buscar']))
	{
		$datos = 
		[
			$_POST['id'], $_POST['buscar']
		];
		$validar->enviar_form($datos, "buscar_provedor");
	}

	//REGISTRO DE PROVEEDOR
	if (isset($_POST['nuevo_proveedor']) && !empty($_POST['nuevo_proveedor']) && $_POST['nuevo_proveedor'] == "nuevo_proveedor")
	{

		if (!empty($_POST['telf_1']))
		{
			$validar->validar_exp($exp_telefono, $_POST['telf_1']);
		}

		if (!empty($_POST['telf_2']))
		{
			$validar->validar_exp($exp_telefono, $_POST['telf_2']);
		}

		if (!empty($_POST['telf_3']))
		{
			$validar->validar_exp($exp_telefono, $_POST['telf_3']);
		}

		if (!empty($_POST['correo']))
		{
			$validar->validar_exp($correo, $_POST['correo']);
		}

		if (!empty($_POST['web']))
		{
			$validar->validar_exp($exp_web, $_POST['web']);
		}

		//convirtiendo las variables en un  array
		$prev_datos = 
		[
			$_POST['nombre'], $_POST['empresa'],
			$_POST['direccion'], $_POST['rubro']
		];

		$validar->validar_campos_vacios($prev_datos);

		$datos = 
		[
			$_POST['nombre'], $_POST['empresa'],
			$_POST['direccion'], $_POST['rubro'],
			$_POST['telf_1'], $_POST['telf_2'],
			$_POST['telf_3'], $_POST['correo'],
			$_POST['web']
		];

		//recorriendo las variables y validandolas
		foreach ($_POST as $key => $value) {
			switch ($key) {
				case 'nombre':
						$validar->validar_exp($exp_nombre, $_POST['nombre']);
					break;

				case 'empresa':
						$validar->validar_exp($exp_empresa_dir, $_POST['empresa']);
					break;

				case 'direccion':
						$validar->validar_exp($exp_empresa_dir, $_POST['direccion']);
					break;

				case 'rubro':
						$validar->validar_exp($exp_rubro, $_POST['rubro']);
					break;

				case 'telf_1':
						$validar->validar_exp($exp_telefono, $_POST['telf_1']);
						$validar->enviar_form($datos, "registro_nuevo_proveedor");
					break;

			}//fin switch		
		}

	}

	//CONSULTANDO INFORMACION DEL PROVEEDOR A ACTUALIZAR
	if (isset($_POST['id_edit']) && !empty($_POST['id_edit']) && is_numeric($_POST['id_edit']))
	{
		$valor = "edit";
		$datos = 
		[
			$_POST['id_edit'],
			$valor
		];

		$validar->enviar_form($datos, "consulta_proveedor");
	}

	//ACTUALIZANDO PROVEEDORES
	if (isset($_POST['act_proveedor']) && !empty($_POST['act_proveedor']) && is_numeric($_POST['act_proveedor']))
	{

		if (!empty($_POST['telf_1_two']))
		{
			$validar->validar_exp($exp_telefono, $_POST['telf_1_two']);
		}

		if (!empty($_POST['telf_2_two']))
		{
			$validar->validar_exp($exp_telefono, $_POST['telf_2_two']);
		}

		if (!empty($_POST['telf_3_two']))
		{
			$validar->validar_exp($exp_telefono, $_POST['telf_3_two']);
		}

		if (!empty($_POST['correo2']))
		{
			$validar->validar_exp($correo, $_POST['correo2']);
		}

		if (!empty($_POST['web_two']))
		{
			$validar->validar_exp($exp_web, $_POST['web_two']);
		}

		/*convirtiendo las variables en un  array*/
		$prev_datos = 
		[
			$_POST['nombre2'], $_POST['empresa2'],
			$_POST['direccion2'], $_POST['rubro2'] 
		];

		$validar->validar_campos_vacios($prev_datos);

		$datos = 
		[
			$_POST['act_proveedor'],
			$_POST['nombre2'], $_POST['empresa2'],
			$_POST['direccion2'], $_POST['rubro2'],
			$_POST['telf_1_two'], $_POST['telf_2_two'],
			$_POST['telf_3_two'], $_POST['correo2'],
			$_POST['web_two']
		];

		/*recorriendo las variables y validandolas*/
		foreach ($_POST as $key => $value) {
			switch ($key) {
				case 'nombre2':
						$validar->validar_exp($exp_nombre, $_POST['nombre2']);
					break;

				case 'empresa2':
						$validar->validar_exp($exp_empresa_dir, $_POST['empresa2']);
					break;

				case 'direccion2':
						$validar->validar_exp($exp_empresa_dir, $_POST['direccion2']);
					break;

				case 'rubro2':
						$validar->validar_exp($exp_rubro, $_POST['rubro2']);
					break;

				case 'telf_1_two':
						$validar->validar_exp($exp_telefono, $_POST['telf_1_two']);
						$validar->enviar_form($datos, "actualizar_proveedor");
					break;

			}//fin switch		
		}	
	}


	//CONSULTANDO INFORMACION DEL PROVEEDOR A ELIMINAR
	if (isset($_POST['id_elim']) && !empty($_POST['id_elim']) && is_numeric($_POST['id_elim']))
	{
		$valor = "elim";
		$datos = 
		[
			$_POST['id_elim'],
			$valor 
		];

		$validar->enviar_form($datos, "consulta_proveedor");
	}

	if (isset($_POST['elim_proveedor']) && !empty($_POST['elim_proveedor']) && is_numeric($_POST['elim_proveedor']))
	{	
		$validar->enviar_form($_POST['elim_proveedor'], "eliminar_proveedor");		
	}

	//ORDENANDO CONSULTA DE PROVEEDORES
	if (isset($_POST['order']) && !empty($_POST['order']))
	{	
		$datos = 
		[
			$_POST['order'],
			$_POST['valor']			
		];		
		$validar->enviar_form($datos, "buscar_provedor");		
	}

	//CONFIGURACION DE COMISION
	if(isset($_POST['config_comision']) && !empty($_POST['config_comision']) && $_POST['config_comision'] = "comision")
	{
		$validar->validar_exp($exp_comision, $_POST['comision']);		
		$validar->enviar_form_two($_POST['comision'], "actualizar_config_comision", "si");	
	}

	//CONFIGURACION DE MONEDAS
	if(isset($_POST['config_monedas']) && !empty($_POST['config_monedas']) && $_POST['config_monedas'] = "monedas")
	{
		$validar->validar_exp($exp_monedas, $_POST['monedas']);		
		$validar->enviar_form_two($_POST['monedas'], "actualizar_config_monedas", "si");	
	}

	//REGISTRO DE NUEVA VENTA
	if(isset($_POST['nueva_venta']) && !empty($_POST['nueva_venta']) && $_POST['nueva_venta'] = "nueva_venta")
	{

		if(isset($_POST['diaCerrado']) && $_POST['diaCerrado'] == "0")
		{
			$datos = 
			[
				$_POST['diaCerrado'], $_POST['diaCerrado'], $_POST['diaCerrado'],
				$_POST['diaCerrado'], $_POST['diaCerrado'], $_POST['diaCerrado'],
				$_POST['diaCerrado'], $_POST['diaCerrado'], $_POST['diaCerrado'], 'paleta-roja', $_POST['fecha']
			];
			$validar->enviar_form($datos, "registrar_ventas");
		}
		else
		{
			$datos = 
			[
				$_POST['efectivo'], $_POST['monedas'], $_POST['pagos'],
				$_POST['tarjetas'], $_POST['tarjetaf'], $_POST['efectivof'],
				$_POST['contado'], $_POST['ventas'], $_POST['restante']
			];	

			//recorriendo las variables y validandolas
			for ($i=0; $i < count($datos); $i++) { 
				$validar->validar_exp($exp_numero, $datos[$i]);
			}

			$datos = 
			[
				$_POST['efectivo'], $_POST['monedas'], $_POST['pagos'],
				$_POST['tarjetas'], $_POST['tarjetaf'], $_POST['efectivof'],
				$_POST['contado'], $_POST['ventas'], $_POST['restante'], 
				$_POST['color_ident'], $_POST['fecha']
			];	
			
			$validar->enviar_form($datos, "registrar_ventas");	
		}
	}

	//VALIDANDO FECHA DE VENTA
	if (isset($_POST['fecha_venta']) && !empty($_POST['fecha_venta']))
	{
		$validar->enviar_form($_POST['fecha_venta'], "consul_fecha_venta_existente");
	}


	//VALIDANDO BUSQUEDA VENTAS

	if (isset($_POST['busq_ventas']) && isset($_POST['mes']) && !empty($_POST['mes']) && isset($_POST['anio']) && !empty($_POST['anio']))
	{	
		if(isset($_POST['dia']))	
		{
			$datos = [$_POST['mes'], $_POST['anio'], $_POST['dia']];			
		}
		else
		{	
			$datos = [$_POST['mes'], $_POST['anio']];
		}

		$validar->enviar_form($datos, "buscar_ventas");		
	}
	else
	{

		if(isset($_POST['busq_ventas']) && isset($_POST['mes']) && empty($_POST['mes']) OR isset($_POST['busq_ventas']) && isset($_POST['anio']) && empty($_POST['anio']))
		{
			$datos = [date('m'), date('Y')];
			$validar->enviar_form($datos, "buscar_ventas");				
		}
	}

	//BUSCANDO USUARIOS
	if(isset($_POST['buscar_user']) && !empty($_POST['buscar_user'])) 
	{
		$datos = 
		[
			$_POST['id_user'], $_POST['buscar_user']
		];
		$validar->enviar_form($datos, "consul_usuarios");		
	}

	//PERMISOS USUARIO
	if(isset($_POST['id_usuario_permiso']) && !empty($_POST['id_usuario_permiso'])) 
	{
		$datos = 
		[
			$_POST['id_usuario_permiso'], '1'
		];
		$validar->enviar_form($datos, "consul_permisos");		
	}	

	//ACTUALIZANDO PERMISOS
	if(isset($_POST['act_permisos']) && !empty($_POST['act_permisos'])) 
	{
		$datos = 
		[
			$_POST['id_user'], $_POST['permiso_config'], 
			$_POST['permiso_venta'], $_POST['permiso_ingreso_egreso'], 
			$_POST['permiso_proveedores'], $_POST['permiso_usuarios']
		];
		$validar->enviar_form($datos, "act_permisos");		
	}	

	//ACTUALIZANDO ESTATUS DEL USUARIO
	if (isset($_POST['id_estatus_user']) && !empty($_POST['id_estatus_user']))
	{
		$validar->enviar_form($_POST['id_estatus_user'], "actualizar_estatus_usuario");		
	}


	//VALIDANDO BUSQUEDA HISTORIAL

	if (isset($_POST['busq_historial']) && isset($_POST['mes']) && !empty($_POST['mes']) && isset($_POST['anio']) && !empty($_POST['anio']))
	{	

		if(isset($_POST['dia']) && !empty($_POST['dia']))	
		{
			$fechaBusq = $_POST['anio']."-".$_POST['mes']."-".$_POST['dia'];
			$datos = [$_POST['busq_historial'], $fechaBusq];			
		}
		else
		{	
			$fechaBusq = $_POST['anio']."-".$_POST['mes'];
			$datos = [$_POST['busq_historial'], $fechaBusq];
		}

		$validar->enviar_form($datos, $_POST['historial']);		

	}
	else
	{

		if(isset($_POST['id_usuario_history']))
		{
			$validar->enviar_form($_POST['id_usuario_history'], "buscar_usuario");			
		}
	}

	//VALIDANDO INGRESO Y EGRESO

	if (isset($_POST['nuevo_movimiento']))
	{	
		$datos = 
		[
			$_POST['total_egreso'],
			$_POST['efectivo'],	
			$_POST['monedas'],
			$_POST['proveedor'],
			$_POST['tipo_accion']
		];

		//recorriendo las variables y validandolas
		for ($i=0; $i < count($datos); $i++) { 
			$validar->validar_exp($exp_numero, $datos[$i]);
		}	

		if(!empty($_POST['otro_proveedor']))
		{
			$validar->validar_exp($exp_rubro, $_POST['otro_proveedor']);
		}
		array_push($datos, $_POST['otro_proveedor'], $_POST['comentario']);

		$validar->enviar_form($datos, "registro_egreso_ingreso");		

	}

	//VALIDANDO BUSQUEDA VENTAS

	if (isset($_POST['busq_ing_egre']) && isset($_POST['mes']) && !empty($_POST['mes']) && isset($_POST['anio']) && !empty($_POST['anio']))
	{	
		if(isset($_POST['dia']))	
		{
			$datos = [$_POST['mes'], $_POST['anio'], $_POST['dia']];			
		}
		else
		{	
			$datos = [$_POST['mes'], $_POST['anio']];
		}

		$validar->enviar_form($datos, "consul_movimientos");		
	}
	else
	{

		if(isset($_POST['busq_ing_egre']) && isset($_POST['mes']) && empty($_POST['mes']) OR isset($_POST['busq_ing_egre']) && isset($_POST['anio']) && empty($_POST['anio']))
		{
			$datos = [date('m'), date('Y')];
			$validar->enviar_form($datos, "consul_movimientos");				
		}
	}

	//CONFIGURACION DEL SISTEMA

	if (isset($_POST['config_fast']) && !empty($_POST['config_fast']))
	{	

		$datos = 
		[
			$_POST['dinero'], 
			$_POST['monedas_caja'], 
			$_POST['pago_electronico'],
			$_POST['debito_credito'],		
			$_POST['monedas_venta']
		];	

		//recorriendo las variables y validandolas
		for ($i=0; $i < count($datos); $i++) 
		{ 
			$validar->validar_exp($exp_monedas, $datos[$i]);		
		}

		$validar->validar_exp($exp_comision, $_POST['comision']);

		//dinero en caja
		$validar->enviar_form_two($_POST['dinero'], "dinero_caja", "1");

		//monedas en caja	
		$validar->enviar_form_two($_POST['monedas_caja'], "monedas_caja", "1");

		//pago electronico	
		$validar->enviar_form_two($_POST['pago_electronico'], "pago_electronico", "1");

		//pago tarjetas	
		$validar->enviar_form_two($_POST['debito_credito'], "pago_tarjetas", "1");

		//comision de ventas
		$validar->enviar_form_two($_POST['comision'], "actualizar_config_comision", "no");

		//monedas para ventas
		$validar->enviar_form_two($_POST['monedas_venta'], "actualizar_config_monedas", "no");

		//actualizando estado del la configuracion rapida
		$validar->enviar_form("1", "actualizar_estado_activo");		
	}

?>