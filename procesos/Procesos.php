<?php


class Procesos extends Conexion
{

	public function __construct()
	{	
		if(isset($_SESSION['id']) && !empty($_SESSION['id']))
		{
			$this->autor = $_SESSION['id'];
		}

 		require 'phpMailer/PHPMailer.php';
		require 'phpMailer/SMTP.php';
		require 'phpMailer/Exception.php';
		require 'phpMailer/OAuth.php';

		$this->mail = new PHPMailer\PHPMailer\PHPMailer();
		parent::__construct();
		$this->id = "1";
		$this->registro = "1";
		$this->actualizacion = "2";
		$this->eliminacion = "3";

		require_once "Auditorias.php";
		$this->audit = new Auditorias;

	}

	////////////////////////////////
	//GENERALES ////////////////////
	////////////////////////////////

public function password_provicional($datos)
{
    $this->an = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $this->su = strlen($this->an) - 1;
    $this->code = substr($this->an, rand(0, $this->su), 2) .
            substr($this->an, rand(0, $this->su), 2) .
            substr($this->an, rand(0, $this->su), 2) .
            substr($this->an, rand(0, $this->su), 2) .
            substr($this->an, rand(0, $this->su), 2);

    $pass = password_hash($this->code, PASSWORD_DEFAULT);

    $consul = $this->ConexSQL->prepare("UPDATE hicom_users SET password = :pass, estado_cuenta = '2' WHERE id_user = :id");
    $consul->bindParam(":pass", $pass, PDO::PARAM_STR);
    $consul->bindParam(":id", $datos, PDO::PARAM_INT);
    $consul->execute();
    $resul = $consul->rowCount();

    if($resul == 0)
    {
			$this->error_proceso("(p0001)");    
    }
    else
    {
			$this->consul_two = $this->ConexSQL->prepare("SELECT usuario FROM hicom_users WHERE id_user = :id");
    	$this->consul_two->bindParam(":id", $this->id, PDO::PARAM_INT);
			$this->consul_two->execute();
			$this->resul_two = $this->consul_two->rowCount();

			$this->resul_two = $this->consul_two->fetch();
			$this->user = $this->resul_two[0];
    }
}

 public function enviar_codigo($datos)
 {
 		$this->cargar_loader();
 		$this->password_provicional($datos[0]);

		$message = "
		
<section class='content_email' 
		style='width: 100%; 
		max-width: 350px;
		margin: 0 auto; 
		overflow: hidden;'>

			<link href='https://fonts.googleapis.com/css2?family=Roboto:wght@300;700&display=swap' rel='stylesheet'>

			<div class='logo' 
			style='width: 150px;
				height: 150px;
				margin: 0 auto;
				overflow: hidden;
			'>
				<img src='https://lh3.googleusercontent.com/-qnaWiTl9H9g/W1-uzRXSTcI/AAAAAAAABhA/LcUeu5-UsDoIgmYVoO50XF6BD8kyClB0gCEwYBhgLKtMDAL1Ocqy92XCzZvkheyuH9Q6HA4Ll420C7kjtC3gDYGOhUPdLJWUKX_y2x5GZ-DbEzsIr7BcCsi1VfBsyfTmVehLiw_l3utgfy9jTxQ4Z_qJWYIItj4UerEAJKByAs_sGRiR8OO3jXF56kj_UTxVRKfzyTgBDz3AqdTG1d09DEOqjJGG-fEw0YGzcRUEEAr4TzPS5ZhiPAsHTOqdMGoG-rtFGKGRfLHrd8t_gu6Nc5jrvdl8qnMvva5UzQTzsnA2Ss-XA3YCeTX6pZP9nuLBxuZYc-pleT77WqvpSUvS83F4Jh_8JQV5xCBlin2-JlMoSlTjfPj0FtCHCDTw2B8I5oi3i5CmwN4-_EEFCAR1_d3Enb0wa7eC9py2sAbV_0p5pcamniwv-sBnfDVHbgfQjB_hAjAtEuMRBOHnpf26Q2eJ0ZDqDD2MCqmwzpqYkhDhmcV0QdR3uO921ZFtJUbyl0qC11E30SSHWizCjAWmcGhw85BxUi7SFZR_SkxlgxL311iuCme17ejOiAWAG_w0YqpiZ-X_eUB90Ziqiin08I5YGmdG1VPWPaDBjfU8rMBwF9w7LhBaX05pxq--853A9YWdE0WulPVW7vBTGySRQjXV14Ksw7MyZ-wU/w140-h140-p/IMG-0802.PNG' style='width: 100%;'>
			</div>

			<div class='info' 
				style='width: 100%;
				text-align: center;
				font-family: roboto;
			'>

			<h3 
			style='font-size: 32px; 
			font-size: 2rem; 
			border-bottom: 2px solid rgba(21,21,21,.5);
			'>
				Datos del usuario
			</h3>

			<p style='font-size: 16px; text-align: left; font-weight: bold; color: black;'>Usuario: $this->user</p>
			<p style='font-size: 16px; text-align: left; font-weight: bold; color: black;'>Contraseña provional: $this->code</p>

			<h4 
			style='color: red; 
			font-size: 16px; 
			font-size: 1rem;
			border-top: 2px solid rgba(21,21,21,.5);
			margin-top: 30px;
			padding-top: 5px;
			'>
			Inicie sessión con esta contraseña, una vez que ingrese el sistema le solicitara cambiarla. </h4>

			</div>
		</section>
		";

 		$this->mail->isSMTP();

 		$this->mail->SMTPDebug = 0;
 		$this->mail->Host = "smtp.gmail.com";
 		$this->mail->Port = 587;
 		$this->mail->SMTPSecure = 'tls';
 		$this->mail->SMTPAuth = true;
 		$this->mail->Username = "sistemahicom@gmail.com";
 		$this->mail->Password = "322985315";
 		$this->mail->SetFrom("sistemahicom@gmail.com","Sistema Hicom");
 		$this->mail->AddAddress($datos[1], "Administrador");
 		$this->mail->Subject = utf8_decode("Recuperación de datos");
 		$this->mail->Body = $message;
 		$this->mail->IsHTML(true);

 		if(!$this->mail->send())
 		{
 			$this->error_proceso("(p0002)");
 			$this->parar_loader("resul");
 			?>
 				<script type="text/javascript">
 					setTimeout(function(){
						window.location.href = ""; 
				},2100)
 				</script>
 			<?php
 		}
 		else
 		{
			$this->exito_proceso("?login", "Contraseña enviada");
 		}

 }

	public function cargar_loader()
	{
		?>
			<script type="text/javascript">
				$("#contentLoader").removeClass("ocultar");
			</script>
		<?php			
	}

	public function parar_loader($btn)
	{
		?>
			<script type="text/javascript">
				setTimeout(() =>
				{
					$("#contentLoader").addClass("ocultar");
					$('#<?php echo $btn ?>').removeClass("inactivo");
					document.getElementById("<?php echo $btn ?>").disabled = false;
				}, 1000);	
			</script>
		<?php		
	}

	public function error_proceso($msj)
	{
		?>
			<script type="text/javascript">
				$("#contentLoader").addClass("ocultar");
				document.getElementById('mensaje_error_proceso').innerHTML=' Ocurrio un error, recargue la página y si se vuelve a presentar comuniquese con el desarrollador del sistema. <?php echo $msj ?>';
				$("#alert_error_proceso").addClass("mostrar");
				setTimeout(() =>
				{
					$("#alert_error_proceso").removeClass("mostrar");			
				}, 7500);					
			</script>
		<?php
		exit();			
	}

	public function mensaje_error($msj)
	{
		?>
			<script type="text/javascript">
				$("#contentLoader").addClass("ocultar");
				document.getElementById('mensaje_error_proceso').innerHTML=' <?php echo $msj ?>';
				$("#alert_error_proceso").addClass("mostrar");
				setTimeout(() =>
				{
					$("#alert_error_proceso").removeClass("mostrar");			
				}, 7500);					
			</script>
		<?php
		exit();			
	}

	public function exito_proceso($redirect, $msj)
	{
		?>
			<script type="text/javascript">
				$("#alert_exito").addClass("mostrar");

				document.getElementById('alert_mensaje_exito').innerHTML='<?php echo $msj ?>';

				setTimeout(function(){
					$("#contentLoader").removeClass("ocultar");
				}, 1000);

				setTimeout(function(){
					$("#contentLoader").addClass("ocultar");
				}, 2000);

				setTimeout(function(){
				window.location.href = "<?php echo $redirect; ?>"; 
				},2100)
			</script>
		<?php			
		exit();			
	}

	public function exito_proceso_general($msj)
	{
		?>
			<script type="text/javascript">
				$("#alert_exito").addClass("mostrar");

				document.getElementById('alert_mensaje_exito').innerHTML='<?php echo $msj ?>';

				setTimeout(function(){
					$("#contentLoader").addClass("ocultar");
					$("#alert_exito").removeClass("mostrar");
				}, 2000);

			</script>
		<?php			
		exit();			
	}

	public function login($datos)
	{
		$_SESSION['id'] = $datos[0];
		$_SESSION['user'] = $datos[1];
		$_SESSION['tipo_cuenta'] = $datos[2];
	}

	public function cerrar_session($datos)
	{
			// Destruir todas las variables de sesión.
			$_SESSION = array();	

			//borrando la cookie de sesión
			if (ini_get("session.use_cookies")) {
			    $params = session_get_cookie_params();
			    setcookie(session_name(), '', time() - 42000,
			        $params["path"], $params["domain"],
			        $params["secure"], $params["httponly"]
			    );
			}

			// Finalmente, destruimos la sesión.
			session_destroy();
			?>
				<script type="text/javascript">
					setTimeout(function(){
						window.location = 'index.php'
					},500);
				</script>
			<?php
	}

	////////////////////////////////
	//CONSULTAS ////////////////////
	////////////////////////////////

	public function consulta_tipo_cuentas()
	{
		$this->consul = $this->ConexSQL->prepare("SELECT * FROM tipo_cuenta WHERE id_tipo_cuenta != '1'");
		$this->consul->execute();		
		if ($this->consul)
		{
			return $this->consul;
		}
		else
		{
			$this->error_proceso("(p0003)");	
		}
	}


	public function consul_user_existente($datos)
	{
		$this->consul = $this->ConexSQL->prepare("SELECT id_user FROM hicom_users WHERE usuario = :usuario");
		$this->consul->bindParam(":usuario", $datos, PDO::PARAM_STR);
		$this->consul->execute();
		$this->resul = $this->consul->rowCount();

		if(!$this->consul)
		{
			$this->error_proceso("(p0004)");	
		}
		else if($this->resul)
		{
			?>
				<script type="text/javascript">
					$("#error_registro").val("usuario");
					$("#grupo__usuario").removeClass("correcto");	
					$("#grupo__usuario").addClass(" error");					
				</script>
			<?php
			$this->error_proceso("Ingrese un usuario diferente.");
		}
		else
		{
			?>
				<script type="text/javascript">
					$("#error_registro").val("bien");
					$("#grupo__usuario").addClass("correcto");	
					$("#grupo__usuario").removeClass(" error");					
				</script>
			<?php			
		}
				
	}

	public function consul_pass_existente($datos)
	{

		$this->consul = $this->ConexSQL->prepare("SELECT password FROM hicom_users WHERE id_user = :id");
		$this->consul->bindParam(":id", $datos[1], PDO::PARAM_INT);
		$this->consul->execute();
		$this->resul = $this->consul->fetch();

		if(!$this->consul)
		{
			$this->error_proceso("(p0005)");	
		}
		else if(password_verify($datos[0], $this->resul[0]))
		{
			?>
				<script type="text/javascript">
					$("#error_pass").val("error");
					$("#grupo__password").removeClass("correcto");	
					$("#grupo__password").addClass(" error");					
				</script>
			<?php
			$this->error_proceso("Ingrese una contraseña diferente.");
		}
		else
		{
			?>
				<script type="text/javascript">
					$("#error_pass").val("bien");
					$("#grupo__password").addClass("correcto");	
					$("#grupo__password").removeClass(" error");					
				</script>
			<?php			
		}		

	}

	public function consul_correo_existente($datos)
	{
		$this->consul = $this->ConexSQL->prepare("SELECT id_user FROM hicom_users WHERE correo = :correo");
		$this->consul->bindParam(":correo", $datos[1], PDO::PARAM_STR);
		$this->consul->execute();
		$this->resul = $this->consul->fetch();

		if(!$this->consul)
		{
			$this->error_proceso("(p0006)");	
		}
		else if($this->resul)
		{
			if($datos[0] == "registro")
			{
			?>
				<script type="text/javascript">
					$("#error_registro").val("correo");
					$("#grupo__correo").removeClass("correcto");	
					$("#grupo__correo").addClass(" error");					
				</script>
			<?php

			$this->error_proceso("Ingrese un correo diferente.");
			}
			else
			{
				$this->cargar_loader();
				?>
					<script type="text/javascript">
					setTimeout(function(){
						$("#contentLoader").addClass("ocultar");
						$("#id_user").val("<?php echo $this->resul[0]; ?>");							
						$("#grupo__correo").addClass("correcto");	
						$("#grupo__correo").removeClass(" error");	
						$("#form3").addClass(" ocultar");	
						$("#opt_recuperar").removeClass("ocultar");					
					}, 2000);	
					</script>
				<?php				
			}
		}
		else
		{
			if($datos[0] == "registro")
			{
				?>
					<script type="text/javascript">
						$("#error_registro").val("bien");
						$("#grupo__correo").addClass("correcto");	
						$("#grupo__correo").removeClass(" error");					
					</script>
				<?php		
			}
			else
			{
				$this->cargar_loader();
				?>
				<script type="text/javascript">
					setTimeout(function(){
						$("#contentLoader").addClass("ocultar");
						$("#grupo__correo").removeClass("correcto");	
						$("#grupo__correo").addClass(" error");
					}, 2000);					
				</script>
				<?php					
				$this->error_proceso("El correo ingresado no existe");
			}
		}
				
	}

	public function Registro_existentes()
	{
		$this->consul = $this->ConexSQL->prepare("SELECT id_user, estado_cuenta FROM hicom_users");
		$this->consul->execute();
		$this->resul = $this->consul->fetch();

		if(!$this->consul)
		{
			$this->error_proceso("(p0007)");	
		}
		else
		{
			return $this->resul;			
		}

	}

	public function consul_pregunta_seguridad($datos)
	{
		$this->cargar_loader();	

		$this->consul = $this->ConexSQL->prepare("SELECT pregunta_seguridad FROM hicom_users WHERE id_user = :id");
		$this->consul->bindParam(":id", $datos, PDO::PARAM_INT);

		$this->consul->execute();
		$this->resul = $this->consul->rowCount();

		$this->resul = $this->consul->fetch();

		if(!$this->consul)
		{
			$this->error_proceso("(p0008)");					
		}
		else
		{
			?>
				<script type="text/javascript">
					setTimeout(function(){
						$("#contentLoader").addClass("ocultar");
						$("#form").removeClass("ocultar");
						$("#opt_recuperar").addClass(" ocultar");
					document.getElementById('pregunta').innerHTML='<?php echo $this->resul[0]; ?>';
					}, 2000);

				</script>
			<?php
		}
	}

	public function consul_respuesta_seguridad($datos)
	{
		$this->cargar_loader();		

		$this->consul = $this->ConexSQL->prepare("SELECT respuesta_seguridad FROM hicom_users WHERE id_user = :id");
		$this->consul->bindParam(":id", $datos[0], PDO::PARAM_INT);

		$this->consul->execute();
		$this->resul = $this->consul->fetch();

		if(!$this->consul)
		{
			$this->error_proceso("(p0009)");					
		}
		else if (password_verify($datos[1], $this->resul[0])) {
			?>
				<script type="text/javascript">
					setTimeout(function(){
					$("#contentLoader").addClass("ocultar");
					$('#form').addClass(" ocultar");
					$('#form2').removeClass("ocultar");
					}, 2000);

				</script>
			<?php			 	   	
		} else {
			$this->parar_loader("btnPregunta");
			$this->error_proceso("La respuesta es incorrecta");				
			exit();		    
		}
	}

	public function consul_correo($datos)
	{
		$this->cargar_loader();

		$this->consul = $this->ConexSQL->prepare("SELECT correo, id_user FROM hicom_users WHERE id_user = :id");
		$this->consul->bindParam(":id", $datos[1], PDO::PARAM_INT);

		$this->consul->execute();
		$this->resul = $this->consul->fetch();

		if(!$this->consul)
		{
			$this->error_proceso("(p0010)");					
		}
		else if($datos[0] == $this->resul[0] && $datos[1] == $this->resul[1])
		{
			$this->cargar_loader();
		
			$valores =
			[
				$this->resul[1], $this->resul[0] 
			];

			$this->enviar_codigo($valores);
		}
		else
		{
			$this->parar_loader("btnConfimCorreo");
			$this->error_proceso("El correo ingresado no existe");				
			exit();			
		}
	}

	public function consultar_datos_user($datos)
	{
		$this->cargar_loader();	

		$this->consul = $this->ConexSQL->prepare("SELECT id_user, usuario, tipo_cuenta, password, estado_cuenta FROM hicom_users WHERE usuario = :usuario");
		$this->consul->bindParam(":usuario", $datos[0], PDO::PARAM_STR);
		$this->consul->execute();
		$this->resul = $this->consul->fetch();

		if(!$this->consul)
		{
			$this->error_proceso("(p0011)");					
		}
		else if(!$this->resul)
		{
			$this->parar_loader("resul");
			$this->mensaje_error("El usuario es incorrecto.");
		}
		else if(!password_verify($datos[1], $this->resul[3]))
		{
			$this->parar_loader("resul");
			$this->mensaje_error("La contraseña es incorrecta.");
		}
		else
		{
			//validando que el usuario este activo
			if($this->resul[4] == "2")
			{
				$this->parar_loader("resul");
				$this->mensaje_error("Imposible ingresar al sistema, su usuario se encuentra inactivo");				
			}
			{
				$v = [$this->resul[0], $this->resul[1], $this->resul[2] ];
				$this->login($v);
				$this->exito_proceso("", "Bienvenido.");								
			}

		}

	}

	public function consul_general_provedores()
	{
		$this->consul = $this->ConexSQL->prepare("SELECT * FROM proveedores");		
		$this->consul->execute();
		$this->resul = $this->consul->rowCount();

		if(!$this->consul)
		{
			$this->error_proceso("(p0012)");					
		}
		else
		{
			return $this->consul;
		}
	}

	public function buscar_provedor($datos)
	{
		if(!empty($datos[0]) && $datos[1] != "null")
		{
		$this->consul = $this->ConexSQL->prepare("SELECT * FROM proveedores WHERE id_proveedor != 1 AND nombre_proveedor like '%".$datos[1]."%' OR empresa_proveedor like '%".$datos[1]."%' OR rubro_proveedor like '%".$datos[1]."%'  ORDER BY $datos[0] ASC");
		}
		else
		{
			$this->consul = $this->ConexSQL->prepare("SELECT * FROM proveedores WHERE id_proveedor != 1 ORDER BY $datos[0] ASC");
		}

		$this->consul->execute();
		$this->resul = $this->consul->rowCount();	

		if(!$this->consul)
		{
			$this->error_proceso("(p0013)");					
		}
		else if ($this->resul >= 1)
		{	
			?>
				<script type="text/javascript">
					$("#box_button_print").removeClass("ocultar");	
					$("#valor_order").val("<?php echo $datos[0]; ?>");
					$("#valor_busq").val("<?php echo $datos[1]; ?>");				
				</script>
			<?php
			while($this->resul = $this->consul->fetch())
			{
			?>
				<div class="content_resul etiq-resul">
					<div class="nombre resul">
						<b>Nombre:</b>
						<?php echo $this->resul[1]; ?>
					</div>
					<div class="empresa resul">
						<b>Empresa:</b>
						<?php echo $this->resul[2]; ?>
					</div>
					<div class="direccion resul">
						<b>Dirección:</b>
						<?php echo $this->resul[3]; ?>
					</div>
					<div class="rubro resul">
						<b>Rubro:</b>
						<?php echo $this->resul[4]; ?>
					</div>
					<div class="telef resul">
						<b>Sitio web:</b>
						<?php echo $this->resul[5]; ?>
					</div>
					<div class="telef resul">
						<b>Teléfono 1:</b>
						<?php echo $this->resul[6]; ?>
					</div>
					<div class="telef resul">
						<b>Teléfono 2:</b>
						<?php echo $this->resul[7]; ?>
					</div>
					<div class="telef resul">
						<b>Teléfono 3:</b>
						<?php echo $this->resul[8]; ?>
					</div>
					<div class="correo resul">
						<b>Correo:</b>
						<?php echo $this->resul[9]; ?>
					</div>
					<div class="controles" id="controles">
						<button value="" class="edit fas fa-pencil-alt  radio  fondo-azul" onclick="actualizar_proveedor(<?php echo $this->resul[0]; ?>);" title="Actualizar"></button>
						<button value="<?php echo $this->resul[0]; ?>" class="elim fas fa-trash-alt  radio  fondo-red" onclick="eliminar_proveedor(<?php echo $this->resul[0]; ?>);" title="Eliminar"></button>
					</div>
				</div>
			<?php				
			}	
		}
		else
		{
			echo "<b style='width: 100%; text-align: center; display: inline-block; padding: 20px;' class='color-red'>Sin resultados</b>";
			?>
				<script type="text/javascript">
					$("#box_button_print").addClass("ocultar");				
				</script>
			<?php
		}			


	}

	public function consulta_proveedor($dato)
	{
		$this->consul = $this->ConexSQL->prepare("SELECT * FROM proveedores WHERE id_proveedor = :id");

		$this->consul->bindParam(":id", $dato[0], PDO::PARAM_INT);

		$this->consul->execute();
		$this->resul = $this->consul->fetch();	

		if(!$this->consul)
		{
			$this->error_proceso("(p0014)");					
		}
		else if ($this->resul)
		{
			?>
				<script type="text/javascript">
				$("#btnImpProveedores").removeClass("inactivo");
				$("#btnImpProveedores").disabled(false);
				</script>
			<?php

			if($dato[1] == "edit")
			{

				if($this->resul[7] != "S/N")
				{
					?>
					<script type="text/javascript">
					$("#telf_2_two").val("<?php echo $this->resul[7]; ?>");
					</script>
					<?php
				}
				if($this->resul[8] != "S/N")
				{
					?>
					<script type="text/javascript">
					$("#telf_3_two").val("<?php echo $this->resul[8]; ?>");
					</script>
					<?php
				}

				?>
					<script type="text/javascript">
						$("#id_act_proveedor").val("<?php echo $this->resul[0]; ?>");
						$("#nombre2").val("<?php echo $this->resul[1]; ?>");
						$("#empresa2").val("<?php echo $this->resul[2]; ?>");
						$("#direccion2").val("<?php echo $this->resul[3]; ?>");
						$("#rubro2").val("<?php echo $this->resul[4]; ?>");
						$("#web_two").val("<?php echo $this->resul[5]; ?>");
						$("#telf_1_two").val("<?php echo $this->resul[6]; ?>");
						$("#correo2").val("<?php echo $this->resul[9]; ?>");
					</script>
				<?php
			}
			else if($dato[1] == "elim")
			{
				?>
					<script type="text/javascript">
						$("#id_elim_proveedor").val("<?php echo $this->resul[0]; ?>");
						$("#nombre_provedor").html("<?php echo $this->resul[1]; ?>");
						$("#empresa_proveedor").html("<?php echo $this->resul[2]; ?>");
					</script>
				<?php
			}
		}
	}

	public function consulta_comision()
	{
		$this->consul = $this->ConexSQL->prepare("SELECT * FROM config_comision_venta");
		$this->consul->execute();
		$this->resul = $this->consul->fetch();

		if(!$this->consul)
		{
			$this->error_proceso("(p0015)");					
		}
		else
		{
			return $this->resul;
		}
	}

	public function consulta_monedas()
	{
		$this->consul = $this->ConexSQL->prepare("SELECT * FROM config_monedas_ventas");
		$this->consul->execute();
		$this->resul = $this->consul->fetch();

		if(!$this->consul)
		{
			$this->error_proceso("(p0016)");					
		}
		else
		{
			return $this->resul;
		}	
	}

	public function buscar_ventas($datos)
	{

		if(!empty($datos[0]) && !empty($datos[1]) && !empty($datos[2]))
		{
			$fechaBusq = $datos[1]."-".$datos[0]."-".$datos[2];
			$valor_tipo = "dia";

			$this->consul = $this->ConexSQL->prepare("SELECT * FROM ventas WHERE fecha_venta = :fecha");
			$this->consul->bindParam(":fecha", $fechaBusq, PDO::PARAM_STR);
		}
		else
		{
			$fechaBusq = $datos[1]."-".$datos[0];
			$valor_tipo = "mes";

			$this->consul = $this->ConexSQL->prepare("SELECT * FROM ventas WHERE fecha_venta like '%".$fechaBusq."%' ORDER BY fecha_venta DESC LIMIT 31");			

			$this->c = $this->ConexSQL->prepare("SELECT SUM(efectivo), SUM(monedas), SUM(pago_rut_transf), SUM(debito_credito), SUM(tarjeta_comision), SUM(efectivo_final), SUM(contado), SUM(ventas), SUM(diferencia) FROM ventas WHERE fecha_venta like '%".$fechaBusq."%' ORDER BY fecha_venta DESC LIMIT 31");
			$this->c->execute();

		}

		$this->consul->execute();
		$this->resul = $this->consul->rowCount();	

		if(!$this->consul)
		{
			$this->error_proceso("(p0017)");					
		}
		else if($this->resul)
		{
			?>
				<script type="text/javascript">
					$("#box_button_print").removeClass("ocultar");
					$("#valor_tipo").val("<?php echo $valor_tipo ?>");
					$("#valor_fecha").val("<?php echo $fechaBusq ?>");				
				</script>
			<?php
			while($this->resul = $this->consul->fetch())
			{
			
			?>

				<div class="content_resul etiq-resul <?php echo $this->resul[11] ?>">
					<div class="resul">
						<b>Fecha:</b>
						<?php echo str_replace("-", "/",date("d-m-Y", strtotime($this->resul[1]))); ?>
					</div>
					<div class="resul">
						<b>Efectivo:</b>
						<?php echo str_replace(",", ".", number_format($this->resul[2])) ?>
					</div>
					<div class="resul">
						<b>Monedas:</b>
						<?php echo str_replace(",", ".", number_format($this->resul[3])) ?>
					</div>
					<div class="resul">
						<b>Pago rut / transf:</b>
						<?php echo str_replace(",", ".", number_format($this->resul[4])) ?>
					</div>
					<div class="resul">
						<b>Débito / crédito:</b>
						<?php echo str_replace(",", ".", number_format($this->resul[5])." (".number_format($this->resul[6]).")"); ?>					
					</div>
					<div class="resul">
						<b>Efectivo final:</b>
						<?php echo str_replace(",", ".", number_format($this->resul[7])) ?>
					</div>
					<div class="resul">
						<b>Contado:</b>
						<?php echo str_replace(",", ".", number_format($this->resul[8])) ?>
					</div>
					<div class="resul">
						<b>Ventas:</b>
						<?php echo str_replace(",", ".", number_format($this->resul[9])) ?>
					</div>
					<div class="resul">
						<b>Diferencia:</b>
						<?php echo str_replace(",", ".", number_format($this->resul[10])) ?>
					</div>
				
				</div>
			<?php				
			}
				if(empty($datos[2]))
				{
			$this->r = $this->c->fetch();
				?>
					<div class="content_resul etiq-resul paleta-total">
						<div class="resul">
							<?php echo "Totales:"; ?>
						</div>
						<div class="resul">
							<b>Efectivo:</b>
							<?php echo str_replace(",", ".", number_format($this->r[0])) ?>
						</div>
						<div class="resul">
							<b>Monedas:</b>
							<?php echo str_replace(",", ".", number_format($this->r[1])) ?>
						</div>
						<div class="resul">
							<b>Pago rut / transf:</b>
							<?php echo str_replace(",", ".", number_format($this->r[2])) ?>
						</div>
						<div class="resul">
							<b>Débito / crédito:</b>
							<?php echo str_replace(",", ".", number_format($this->r[3])." (".number_format($this->r[4]).")"); ?>					
						</div>
						<div class="resul">
							<b>Efectivo final:</b>
							<?php echo str_replace(",", ".", number_format($this->r[5])) ?>
						</div>
						<div class="resul">
							<b>Contado:</b>
							<?php echo str_replace(",", ".", number_format($this->r[6])) ?>
						</div>
						<div class="resul">
							<b>Ventas:</b>
							<?php echo str_replace(",", ".", number_format($this->r[7])) ?>
						</div>
						<div class="resul">
							<b>Diferencia:</b>
							<?php echo str_replace(",", ".", number_format($this->r[8])) ?>
						</div>
					</div>
			<?php
			}
		}
		else
		{
			echo "<b style='width: 100%; text-align: center; display: inline-block; padding: 20px;' class='color-red'>Sin resultados</b>";
			?>
				<script type="text/javascript">
					$("#box_button_print").addClass("ocultar");				
				</script>
			<?php
		}		
	}

	public function consul_fecha_venta_existente($datos)
	{
		$this->consul = $this->ConexSQL->prepare("SELECT fecha_venta FROM ventas WHERE fecha_venta = :fecha");
		$this->consul->bindParam(":fecha", $datos, PDO::PARAM_STR);
		$this->consul->execute();
		$this->resul = $this->consul->rowCount();
		if(!$this->consul)
		{
			$this->error_proceso("(p0027)");					
		}
		else
		{		
			if($this->resul > 0)
			{
				$this->resul = $this->consul->fetch();
				?>
					<script type="text/javascript">
						$("#error_venta").val("error");
						$("#campo_fecha_venta").removeClass("correcto");			
						$("#campo_fecha_venta").addClass(" error");
						document.querySelector("#campo_fecha_venta p").innerHTML='Existe una venta registrada en esta fecha';
					</script>
				<?php
			}
			else
			{
				?>
					<script type="text/javascript">
						$("#error_venta").val("");
					</script>
				<?php
			}	
		}		
	}

	public function consul_permisos($datos)
	{
		$this->consul = $this->ConexSQL->prepare("SELECT * FROM permisos_usuarios WHERE id_usuario = :id");
		$this->consul->bindParam(":id", $datos[0], PDO::PARAM_STR);
		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("(p0030)");					
		}
		else
		{
			$this->resul = $this->consul->fetch();

			if(!empty($datos[0]) && !empty($datos[1]))
			{
				?>
					<script type="text/javascript">
						$("#id_permisos").val("<?php echo $this->resul[1]; ?>");
						$("#permiso_config").val("<?php echo $this->resul[2]; ?>");
						$("#permiso_venta").val("<?php echo $this->resul[3]; ?>");
						$("#permiso_ingreso_egreso").val("<?php echo $this->resul[4]; ?>");
						$("#permiso_proveedores").val("<?php echo $this->resul[5]; ?>");
						$("#permiso_usuarios").val("<?php echo $this->resul[6]; ?>");
					</script>
				<?php				
			}
			else
			{
				return $this->resul;			
			}
		}		
	}

	public function consul_usuarios($datos)
	{

		if(!empty($datos[0]) && !empty($datos[1]))
		{
			$this->consul = $this->ConexSQL->prepare("SELECT hicom_users.id_user, hicom_users.nombre_apellido, hicom_users.correo, hicom_users.estado_cuenta, tipo_cuenta.descripcion_tipo_cuenta FROM hicom_users JOIN tipo_cuenta ON hicom_users.tipo_cuenta=tipo_cuenta.id_tipo_cuenta AND tipo_cuenta != '1' WHERE nombre_apellido like '%".$datos[1]."%' ");
		}
		else
		{

			$this->consul = $this->ConexSQL->prepare("SELECT hicom_users.id_user, hicom_users.nombre_apellido, hicom_users.correo, hicom_users.estado_cuenta, tipo_cuenta.descripcion_tipo_cuenta FROM hicom_users JOIN tipo_cuenta ON hicom_users.tipo_cuenta=tipo_cuenta.id_tipo_cuenta AND tipo_cuenta != '1' ORDER BY id_user DESC LIMIT 15");
		}

		$this->consul->execute();
		$this->resul = $this->consul->rowCount();	

		if(!$this->consul)
		{
			$this->error_proceso("(p0031)");					
		}
		else if($this->resul >= 1)
		{

			while($this->resul = $this->consul->fetch())
			{

				//estatus
				if($this->resul[3] == "1")
				{
					$estatus = "Activo";
					$icon = "fas fa-lock";
					$title = "Inactivar";
				}
				else
				{
					$estatus = "Inactivo";		
					$icon = "fas fa-lock-open";
					$title = "Activar";
				}

				//fecha de creacion y ultima actualizacion
				$creacion = $this->consul_fecha_creacion_user($this->resul[0]);
				$actualizacion = $this->consul_fecha_actualizacion_user($this->resul[0]);

				if(!$actualizacion)
				{
					$act = "Sin actualizaciones";
				}
				else
				{
					$act = str_replace("-", "/",date("d-m-Y", strtotime($actualizacion[0]))). " a las ".$actualizacion[1];	
				}

		?>
				<div class="content_resul etiq-resul">
					<div class="resul nombre">
						<b>NOMBRES:</b>
						<?php echo $this->resul[1]; ?>
					</div>

					<div class="resul">
						<b>TIPO CUENTA:</b>
						<?php echo $this->resul[4]; ?>
					</div>

					<div class="resul correo">
						<b>CORREO:</b>
						<?php echo $this->resul[2]; ?>
					</div>

					<div class="resul correo">
						<b>FECHA DE CREACIÓN:</b>
						<?php echo str_replace("-", "/",date("d-m-Y", strtotime($creacion[0]))). " a las ".$creacion[1]; ?>
					</div>

					<div class="resul correo">
						<b>ULTIMA ACTUALIZACIÓN:</b>
						<?php echo $act ?>
					</div>

					<div class="resul correo">
						<b>ESTATÚS:</b>
						<?php echo $estatus ?>
					</div>

					<div class="controles" id="controles">

						<button value="" class="edit fas fa-shield-alt  radio  fondo-green" onclick="permisos_usuario(<?php echo $this->resul[0]; ?>);" title="Permisos"></button>

						<button value="" class="elim fas fa-clipboard  radio  fondo-gris" onclick="historial_usuario(<?php echo "1"/*$this->resul[0]*/; ?>);" title="Historial"></button>

						<button value="" class="edit <?php echo $icon ?>  radio  fondo-red" onclick="estatus_usuario(<?php echo $this->resul[0]; ?>);" title="<?php echo $title ?>"></button>

					</div>

				</div>			
		<?php 
			}

		}
		else
		{
			echo "<b style='width: 100%; text-align: center; display: inline-block; padding: 20px;' class='color-red'>Sin resultados</b>";
		}	

	}

	public function consul_fecha_creacion_user($datos)
	{
		$this->c1 = $this->ConexSQL->prepare("SELECT fecha_ejecucion, hora_ejecucion FROM audit_hicom_users WHERE id_afectado = :id AND tipo_accion = 1");
		$this->c1->bindParam(":id", $datos, PDO::PARAM_STR);
		$this->c1->execute();

		if(!$this->c1)
		{
			$this->error_proceso("(p0032)");					
		}
		else
		{
			$this->r1 = $this->c1->fetch();
			return $this->r1;
		}			
	}

	public function consul_fecha_actualizacion_user($datos)
	{
		$this->c2 = $this->ConexSQL->prepare("SELECT fecha_ejecucion, hora_ejecucion FROM audit_hicom_users WHERE id_afectado = :id AND tipo_accion = 2 ORDER BY id_audit_user 
			DESC");
		$this->c2->bindParam(":id", $datos, PDO::PARAM_STR);
		$this->c2->execute();

		if(!$this->c2)
		{
			$this->error_proceso("(p0033)");					
		}
		else
		{
			$this->r2 = $this->c2->fetch();
			return $this->r2;
		}	
	}

	public function buscar_usuario($datos)
	{

		$this->consul = $this->ConexSQL->prepare("SELECT nombre_apellido FROM hicom_users WHERE id_user = :id");
		$this->consul->bindParam(":id", $datos, PDO::PARAM_INT);
		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("(p0034)");					
		}
		else
		{
			$this->resul = $this->consul->fetch();
			?>
				<script type="text/javascript">
					document.getElementById('nombre_usaurio').innerHTML='<?php echo "Historial de ".$this->resul[0] ?>';
					$('#busq_historial').val(<?php echo $datos ?>);					
				</script>
			<?php
		}			

	}

	public function history_caja($datos)
	{
		$this->consul = $this->ConexSQL->prepare("SELECT * FROM audit_caja WHERE id_autor = :id AND fecha_ejecucion like '%".$datos[1]."%' ORDER BY id_audit_caja DESC");		
		$this->consul->bindParam(":id", $datos[0], PDO::PARAM_INT);
		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("(p0000)");					
		}
		else
		{
			while ($this->resul = $this->consul->fetch())
			{
				
			}
		}	
	}

	public function history_comision($datos)
	{
		$this->consul = $this->ConexSQL->prepare("SELECT comision, fecha_ejecucion, hora_ejecucion FROM audit_comision_ventas WHERE id_autor = :id AND fecha_ejecucion like '%".$datos[1]."%' ORDER BY id_audit_comision_venta DESC");		
		$this->consul->bindParam(":id", $datos[0], PDO::PARAM_INT);
		$this->consul->execute();
		$this->count = $this->consul->rowCount();

		if(!$this->consul)
		{
			$this->error_proceso("(p0035)");					
		}
		else if($this->count >= 1)
		{
			while ($this->resul = $this->consul->fetch())
			{
				echo "<div class='resul'><i class='fas fa-chevron-right'></i> Actualizó comisión de venta a ".$this->resul[0]
				."% el " . 
				str_replace("-", "/",date("d-m-Y", strtotime($this->resul[1])))
				." a las ". 
				$this->resul[2] 
				."</div>";
			}
		}
		else
		{
			echo "<b class='paleta-roja'>No se encontraron resultados</b>";
		}	
	}

	public function history_usuarios($datos)
	{
		$this->consul = $this->ConexSQL->prepare("SELECT nombre_apellido, tipo_accion, id_afectado, fecha_ejecucion, hora_ejecucion FROM audit_hicom_users WHERE id_autor = :id AND fecha_ejecucion like '%".$datos[1]."%' ORDER BY id_audit_user DESC");		
		$this->consul->bindParam(":id", $datos[0], PDO::PARAM_INT);
		$this->consul->execute();
		$this->count = $this->consul->rowCount();
		
		if(!$this->consul)
		{
			$this->error_proceso("(p0036)");					
		}
		else if($this->count >= 1)
		{
			while ($this->resul = $this->consul->fetch())
			{
				if($this->resul[1] == '1')
				{
					if($this->resul[2] == $datos[0])
					{
						echo "<div class='resul'><i class='fas fa-chevron-right'></i> Usuario creado el ". str_replace("-", "/",date("d-m-Y", strtotime($this->resul[3])))
						." a las ". 
						$this->resul[4] 
						."</div>";
					}
					else
					{
						echo "<div class='resul'><i class='fas fa-chevron-right'></i> Registró al usuario ". $this->resul[0]. " el " .str_replace("-", "/",date("d-m-Y", strtotime($this->resul[3])))
						." a las ". 
						$this->resul[4] 
						."</div>";					
					}	
				}
				else if($this->resul[1] == '2')
				{
					if($this->resul[2] == $datos[0])
					{
						echo "<div class='resul'><i class='fas fa-chevron-right'></i> Actulizó sus datos el ". str_replace("-", "/",date("d-m-Y", strtotime($this->resul[3])))
						." a las ". 
						$this->resul[4] 
						."</div>";
					}
					else
					{
						echo "<div class='resul'><i class='fas fa-chevron-right'></i> Actualizó los datos del usuario ". $this->resul[0]. " el " .str_replace("-", "/",date("d-m-Y", strtotime($this->resul[3])))
						." a las ". 
						$this->resul[4] 
						."</div>";					
					}	
				}
			}
		}
		else
		{
			echo "<b class='paleta-roja'>No se encontraron resultados</b>";
		}	
	}

	public function history_monedas($datos)
	{
		$this->consul = $this->ConexSQL->prepare("SELECT valor, fecha_ejecucion, hora_ejecucion FROM audit_monedas WHERE id_autor = :id AND fecha_ejecucion like '%".$datos[1]."%' ORDER BY id_audit_monedas DESC");		
		$this->consul->bindParam(":id", $datos[0], PDO::PARAM_INT);
		$this->consul->execute();
		$this->count = $this->consul->rowCount();
		
		if(!$this->consul)
		{
			$this->error_proceso("(p0037)");					
		}
		else if($this->count >= 1)
		{
			while ($this->resul = $this->consul->fetch())
			{
				echo "<div class='resul'><i class='fas fa-chevron-right'></i> Registró la cantidad de $". str_replace(",", ".", number_format($this->resul[0]))." monedas el ". str_replace("-", "/",date("d-m-Y", strtotime($this->resul[1])))
						." a las ". 
						$this->resul[2] 
						."</div>";
			}
		}
		else
		{
			echo "<b class='paleta-roja'>No se encontraron resultados</b>";
		}	
	}

	public function history_monedas_ventas($datos)
	{
		$this->consul = $this->ConexSQL->prepare("SELECT monedas_ventas, fecha_ejecucion, hora_ejecucion FROM audit_monedas_ventas WHERE id_autor = :id AND fecha_ejecucion like '%".$datos[1]."%' ORDER BY id_audit_moneda_venta DESC");		
		$this->consul->bindParam(":id", $datos[0], PDO::PARAM_INT);
		$this->consul->execute();
		$this->count = $this->consul->rowCount();
		
		if(!$this->consul)
		{
			$this->error_proceso("(p0038)");					
		}
		else if($this->count >= 1)
		{
			while ($this->resul = $this->consul->fetch())
			{
				echo "<div class='resul'><i class='fas fa-chevron-right'></i> Actualizó cantidad de monedas para ventas a $". str_replace(",", ".", number_format($this->resul[0]))." el ". str_replace("-", "/",date("d-m-Y", strtotime($this->resul[1])))
						." a las ". 
						$this->resul[2] 
						."</div>";
			}
		}
		else
		{
			echo "<b class='paleta-roja'>No se encontraron resultados</b>";
		}	
	}

	public function history_proveedores($datos)
	{
		$this->consul = $this->ConexSQL->prepare("SELECT nombre_proveedor, empresa_proveedor, tipo_accion, fecha_ejecucion, hora_ejecucion FROM audit_proveedores WHERE id_autor = :id ORDER BY id_audit_proveedor DESC");
		$this->consul->bindParam(":id", $datos[0], PDO::PARAM_INT);
		$this->consul->execute();
		$this->count = $this->consul->rowCount();

		if(!$this->consul)
		{
			$this->error_proceso("(p0039)");					
		}
		else if($this->count >= 1)
		{
			while ($this->resul = $this->consul->fetch())
			{
				if($this->resul[2] == '1')
				{
					echo "<div class='resul'><i class='fas fa-chevron-right'></i> Registró al proveedor ". $this->resul[0]." de la empresa ".$this->resul[1]. " el " . str_replace("-", "/",date("d-m-Y", strtotime($this->resul[3])))." a las ". $this->resul[4] 
					."</div>";
				}
				else if($this->resul[2] == '2')
				{
					echo "<div class='resul'><i class='fas fa-chevron-right'></i> Actualizó datos del proveedor ". $this->resul[0]." de la empresa ".$this->resul[1]. " el " . str_replace("-", "/",date("d-m-Y", strtotime($this->resul[3])))." a las ". $this->resul[4] 
					."</div>";
				}	
				else if($this->resul[2] == '3')
				{
					echo "<div class='resul'><i class='fas fa-chevron-right'></i> Eliminó al proveedor ". $this->resul[0]." de la empresa ".$this->resul[1]. " el " . str_replace("-", "/",date("d-m-Y", strtotime($this->resul[3])))." a las ". $this->resul[4] 
					."</div>";
				}	

			}
		}	
		else
		{
			echo "<b class='paleta-roja'>No se encontraron resultados</b>";
		}	

	}

	public function history_ventas($datos)
	{
		$this->consul = $this->ConexSQL->prepare("SELECT fecha_venta, fecha_ejecucion, hora_ejecucion FROM audit_ventas WHERE id_autor = :id AND fecha_ejecucion like '%".$datos[1]."%' ORDER BY id_audit_venta DESC");		
		$this->consul->bindParam(":id", $datos[0], PDO::PARAM_INT);
		$this->consul->execute();
		$this->count = $this->consul->rowCount();

		if(!$this->consul)
		{
			$this->error_proceso("(p0040)");					
		}
		else if($this->count >= 1)
		{
			while ($this->resul = $this->consul->fetch())
			{
				echo "<div class='resul'><i class='fas fa-chevron-right'></i> Registró venta correspondiente al ". 
				str_replace("-", "/",date("d-m-Y", strtotime($this->resul[0]))) 
				. " el " . 
				str_replace("-", "/",date("d-m-Y", strtotime($this->resul[1])))
				." a las ". 
				$this->resul[2] 
				."</div>";
			}
		}	
		else
		{
			echo "<b class='paleta-roja'>No se encontraron resultados</b>";
		}
	}


	public function consul_movimientos($datos)
	{

		if(!empty($datos[0]) && !empty($datos[1]) && !empty($datos[2]))
		{
			$fechaBusq = $datos[1]."-".$datos[0]."-".$datos[2];
			$valor_tipo = "dia";

			$this->consul = $this->ConexSQL->prepare("SELECT ingresos_egresos.fecha_movimiento, ingresos_egresos.valor_efectivo, ingresos_egresos.valor_monedas, ingresos_egresos.valor, ingresos_egresos.id_proveedor, ingresos_egresos.otro_proveedor, ingresos_egresos.nota, proveedores.nombre_proveedor, tipo_accion.accion FROM ingresos_egresos JOIN proveedores ON ingresos_egresos.id_proveedor=proveedores.id_proveedor JOIN tipo_accion ON ingresos_egresos.tipo_accion=tipo_accion.id_tipo_accion WHERE fecha_movimiento = :fecha ");
			
			$this->consul->bindParam(":fecha", $fechaBusq, PDO::PARAM_STR);
		}
		else
		{

			$fechaBusq = $datos[1]."-".$datos[0];
			$valor_tipo = "mes";

			$this->consul = $this->ConexSQL->prepare("SELECT ingresos_egresos.fecha_movimiento, ingresos_egresos.valor_efectivo, ingresos_egresos.valor_monedas, ingresos_egresos.valor, ingresos_egresos.id_proveedor, ingresos_egresos.otro_proveedor, ingresos_egresos.nota, proveedores.nombre_proveedor, tipo_accion.accion FROM ingresos_egresos JOIN proveedores ON ingresos_egresos.id_proveedor=proveedores.id_proveedor JOIN tipo_accion ON ingresos_egresos.tipo_accion=tipo_accion.id_tipo_accion WHERE fecha_movimiento like '%".$fechaBusq."%' ");
		}

		$this->consul->execute();
		$this->resul = $this->consul->rowCount();	

		if(!$this->consul)
		{
			$this->error_proceso("(p0031)");					
		}
		else if($this->resul >= 1)
		{

			while($this->resul = $this->consul->fetch())
			{

				//estatus
				if($this->resul[4] == "1")
				{
					$proveedor = $this->resul[5];
				}
				else
				{
					$proveedor = $this->resul[7];
				}
		?>
				<div class="content_resul etiq-resul">
					<div class="resul nombre">
						<b>Fecha:</b>
						<?php echo str_replace("-", "/",date("d-m-Y", strtotime($this->resul[0]))) ?>
					</div>

					<div class="resul">
						<b>TIPO MOVIMIENTO:</b>
						<?php echo $this->resul[8]; ?>
					</div>

					<div class="resul ">
						<b>EFECTIVO:</b>
							<?php echo str_replace(",", ".", number_format($this->resul[1])) ?>
					</div>

					<div class="resul ">
						<b>MONEDAS:</b>
							<?php echo str_replace(",", ".", number_format($this->resul[2])) ?>
					</div>

					<div class="resul ">
						<b>TOTAL:</b>
							<?php echo str_replace(",", ".", number_format($this->resul[3])) ?>
					</div>

					<div class="resul ">
						<b>PROVEEDOR:</b>
						<?php echo $proveedor; ?>
					</div>

					<div class="resul nota">
						<b>NOTA:</b>
						<?php echo $this->resul[6]; ?>
					</div>

				</div>			
		<?php 
			}

		}
		else
		{
			echo "<b style='width: 100%; text-align: center; display: inline-block; padding: 20px;' class='color-red'>Sin resultados</b>";
		}			
	}


	public function consul_dinero_caja()
	{
		$this->consul = $this->ConexSQL->prepare("SELECT valor FROM caja");
		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("p0043");					
		}
		else
		{
			$this->resul = $this->consul->fetch();
			return $this->resul;
		}
	}

	public function consul_monedas_caja()
	{
		$this->consul = $this->ConexSQL->prepare("SELECT valor FROM monedas");
		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("p0044");					
		}
		else
		{
			$this->resul = $this->consul->fetch();
			return $this->resul;
		}
	}

	public function consul_total_ventas()
	{
		$this->consul = $this->ConexSQL->prepare("SELECT SUM(ventas) FROM ventas");
		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("p0045");					
		}
		else
		{
			$this->resul = $this->consul->fetch();
			return $this->resul;
		}
	}

	public function consul_pago_electronico()
	{
		$this->consul = $this->ConexSQL->prepare("SELECT SUM(pago_electronico) FROM pago_electronico");
		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("p0046");					
		}
		else
		{
			$this->resul = $this->consul->fetch();
			return $this->resul;
		}
	}

	public function consul_pago_tarjetas()
	{
		$this->consul = $this->ConexSQL->prepare("SELECT SUM(pago_tarjetas) FROM pago_tarjetas");
		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("p0047");					
		}
		else
		{
			$this->resul = $this->consul->fetch();
			return $this->resul;
		}
	}

	public function consul_estado_config_rapida()
	{
		$this->consul = $this->ConexSQL->prepare("SELECT activo FROM estado_activo WHERE id_estado_activo = 1");
		
		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("");					
		}
		else
		{
			$this->resul = $this->consul->fetch();
			return $this->resul;
		}

	}

	////////////////////////////////
	//REGISTROS ////////////////////
	////////////////////////////////

	public function registro_usuario($datos)
	{
		if(empty($_SESSION['id']))
		{
			$autor = '1';
		}
		else
		{
			$autor = $_SESSION['id'];
		}

		$pass = password_hash($datos[2], PASSWORD_DEFAULT);
		$resp = password_hash($datos[5], PASSWORD_DEFAULT);

		$this->consul = $this->ConexSQL->prepare("INSERT INTO hicom_users(nombre_apellido, usuario, password, correo, pregunta_seguridad, respuesta_seguridad, tipo_cuenta) VALUES(:nombres, :usuario, :password, :correo, :pregunta, :respuesta, :tipo)");
		$this->consul->bindParam(":nombres", $datos[0], PDO::PARAM_STR);
		$this->consul->bindParam(":usuario", $datos[1], PDO::PARAM_STR);
		$this->consul->bindParam(":password", $pass, PDO::PARAM_STR);
		$this->consul->bindParam(":correo", $datos[3], PDO::PARAM_STR);
		$this->consul->bindParam(":pregunta", $datos[4], PDO::PARAM_STR);
		$this->consul->bindParam(":respuesta", $resp, PDO::PARAM_STR);
		$this->consul->bindParam(":tipo", $datos[6], PDO::PARAM_INT);

		$this->consul->execute();
		$this->resul = $this->consul->rowCount();

		if(!$this->consul)
		{
			$this->error_proceso("(p0018)");					
		}
		else
		{
			$id = $this->ConexSQL->lastInsertId();

			// tipo de accion, autor, id afectado
			$valores = [$this->registro, $autor, $id ];
			$this->audit->audit_hicom_users($valores);

			$this->c_post = $this->ConexSQL->prepare("INSERT INTO permisos_usuarios(id_usuario) VALUES(:id)");
			$this->c_post->bindParam(":id", $id, PDO::PARAM_INT);

			$this->c_post->execute();

			$this->exito_proceso("", "Registro exitoso!");
		}

	}


	public function registro_nuevo_proveedor($datos)
	{
		$this->cargar_loader();
		$rubro = preg_replace("[\n|\r|\n\r]", " ", $datos[3]);		
		if (empty($datos[5]))
		{
			$telf_2 = "S/N";
		}
		else{
			$telf_2 = $datos[5];			
		}

		if (empty($datos[6]))
		{
			$telf_3 = "S/N";
		}
		else{
			$telf_3 = $datos[6];
		}

		$this->consul = $this->ConexSQL->prepare("INSERT INTO proveedores(nombre_proveedor, empresa_proveedor, direccion_proveedor, rubro_proveedor, sitio_web, telefono_1_proveedor, telefono_2_proveedor, telefono_3_proveedor, correo_proveedor) VALUES(:nombre, :empresa, :direccion, :rubro, :sitio_web, :telf_1, :telf_2, :telf_3, :correo)");

		$this->consul->bindParam(":nombre", $datos[0], PDO::PARAM_STR);
		$this->consul->bindParam(":empresa", $datos[1], PDO::PARAM_STR);
		$this->consul->bindParam(":direccion", $datos[2], PDO::PARAM_STR);
		$this->consul->bindParam(":rubro", $rubro, PDO::PARAM_STR);
		$this->consul->bindParam(":sitio_web", $datos[8], PDO::PARAM_STR);
		$this->consul->bindParam(":telf_1", $datos[4], PDO::PARAM_INT);
		$this->consul->bindParam(":telf_2", $telf_2, PDO::PARAM_STR);
		$this->consul->bindParam(":telf_3", $telf_3, PDO::PARAM_STR);
		$this->consul->bindParam(":correo", $datos[7], PDO::PARAM_STR);

		$c = $this->consul->execute();
		$this->resul = $this->consul->rowCount();

		if(!$this->consul)
		{
			$this->error_proceso("(p0019)");					
		}
		else
		{
			/* AUDITANDO PROCESO */
			$id = $this->ConexSQL->lastInsertId();
			
			// tipo de accion, autor, id afectado
			$valores = [$this->registro, $this->autor, $id];
			$this->audit->audit_proveedores($valores);

			?>
			<script type="text/javascript">
				$("#boxImpProv").load(location.href+" #boxImpProv>*","");
				$("#resul_busqueda").load(location.href+" #resul_busqueda>*","");
			</script>
			<?php
			$this->exito_proceso_general("Proveedor registrado");
		}

	}

	public function registrar_ventas($datos)
	{
		$this->cargar_loader();

		$this->prev = $this->ConexSQL->prepare("SELECT fecha_venta FROM ventas WHERE fecha_venta = :fecha");
		$this->prev->bindParam(":fecha", $datos[10], PDO::PARAM_STR);	
		$this->prev->execute();
		$this->r = $this->prev->rowCount();

		if($this->r == 0)
		{

			$this->consul = $this->ConexSQL->prepare("INSERT INTO ventas(fecha_venta, efectivo, monedas, pago_rut_transf, debito_credito, tarjeta_comision, efectivo_final, contado, ventas, diferencia, color_ident) VALUES (:fecha, :efectivo, :monedas, :pago_rut_transf, :debito_credito, :tarjeta_comision, :efectivo_final, :contado, :ventas, :diferencia, :color_ident)");

			$this->consul->bindParam(":fecha", $datos[10], PDO::PARAM_STR);			
			$this->consul->bindParam(":efectivo", $datos[0], PDO::PARAM_STR);				
			$this->consul->bindParam(":monedas", $datos[1], PDO::PARAM_STR);	
			$this->consul->bindParam(":pago_rut_transf", $datos[2], PDO::PARAM_STR);
			$this->consul->bindParam(":debito_credito", $datos[3], PDO::PARAM_STR);
			$this->consul->bindParam(":tarjeta_comision", $datos[4], PDO::PARAM_STR);
			$this->consul->bindParam(":efectivo_final", $datos[5], PDO::PARAM_STR);
			$this->consul->bindParam(":contado", $datos[6], PDO::PARAM_STR);
			$this->consul->bindParam(":ventas", $datos[7], PDO::PARAM_STR);
			$this->consul->bindParam(":diferencia", $datos[8], PDO::PARAM_STR);
			$this->consul->bindParam(":color_ident", $datos[9], PDO::PARAM_STR);
			$this->consul->execute();
			$this->resul = $this->consul->rowCount();

			if(!$this->consul)
			{
				$this->error_proceso("(p0020)");					
			}
			else
			{

				/* AUDITANDO PROCESO */
				$id = $this->ConexSQL->lastInsertId();
				
				// tipo de accion, autor, id afectado
				$valores = [$this->registro, $this->autor, $id];
				$this->audit->audit_ventas($valores);

				$this->dinero_caja($this->registro, $datos[0]);
				$this->monedas_caja($this->registro, $datos[1]);				
				$this->pago_electronico($this->registro, $datos[2]);
				$this->pago_tarjetas($this->registro, $datos[4]);

				?>
				<script type="text/javascript">
					$("#resul_busqueda").load(location.href+" #resul_busqueda>*","");
				</script>
				<?php
				$this->exito_proceso_general("Venta registrada");
			}

		}
		else
		{

			$this->error_proceso("Error, ya existe una venta con este fecha");				
		}
	}

	public function registro_egreso_ingreso($datos)
	{
		$this->consul = $this->ConexSQL->prepare("INSERT INTO ingresos_egresos(valor, valor_efectivo, valor_monedas, id_proveedor, otro_proveedor, nota, tipo_accion) VALUES(:valor, :valor_efectivo, :valor_monedas, :id_proveedor, :otro_proveedor, :nota, :tipo_accion)");

		$this->consul->bindParam(":valor", $datos[0], PDO::PARAM_INT);	
		$this->consul->bindParam(":valor_efectivo", $datos[1], PDO::PARAM_INT);
		$this->consul->bindParam(":valor_monedas", $datos[2], PDO::PARAM_INT);
		$this->consul->bindParam(":id_proveedor", $datos[3], PDO::PARAM_INT);
		$this->consul->bindParam(":otro_proveedor", $datos[5], PDO::PARAM_STR);
		$this->consul->bindParam(":nota", $datos[6], PDO::PARAM_STR);
		$this->consul->bindParam(":tipo_accion", $datos[4], PDO::PARAM_INT);

		$this->consul->execute();
		$this->resul = $this->consul->rowCount();

		if(!$this->consul)
		{
			$this->error_proceso("(p0041)");					
		}
		else
		{

				/* AUDITANDO PROCESO */
				$id = $this->ConexSQL->lastInsertId();
				
				// tipo de accion, autor, id afectado
				$valores = [$datos[4], $this->autor, $id];
				$this->audit->audit_ingresos_egresos($valores);

				$this->dinero_caja($datos[4], $datos[1]);
				$this->monedas_caja($datos[4], $datos[2]);

				?>
				<script type="text/javascript">
					$("#resul_busqueda").load(location.href+" #resul_busqueda>*","");
				</script>
				<?php
				$this->exito_proceso_general("Movimiento registrado");
			}

	}

	////////////////////////////////
	//ACTUALIZACIONES //////////////
	////////////////////////////////

	public function actualizar_password($datos)
	{
		if(empty($_SESSION['id']))
		{
			$autor = $datos[0];
		}
		else
		{
			$autor = $_SESSION['id'];
		}

		/* AUDITANDO PROCESO */
		// tipo de accion, autor, id afectado
		$valores = [ $this->actualizacion, $autor, $datos[0]];
		$this->audit->audit_hicom_users($valores);

		$pass = password_hash($datos[1], PASSWORD_DEFAULT);

		$this->consul = $this->ConexSQL->prepare("UPDATE hicom_users SET password = :pass, estado_cuenta = '1' WHERE id_user = :id");
		$this->consul->bindParam(":pass", $pass, PDO::PARAM_STR);
		$this->consul->bindParam(":id", $datos[0], PDO::PARAM_INT);
		$this->consul->execute();
		$this->resul = $this->consul->rowCount();

		if(!$this->consul)
		{
			$this->error_proceso("(p0021)");					
		}
		else
		{
			$this->exito_proceso("?login", "Contraseña actualizada!");
		}
	}

	public function actualizar_proveedor($datos)
	{

		$this->cargar_loader();
		$rubro = preg_replace("[\n|\r|\n\r]", " ", $datos[4]);

		if (empty($datos[6]))
		{
			$telf_2 = "S/N";
		}
		else{
			$telf_2 = $datos[6];			
		}

		if (empty($datos[7]))
		{
			$telf_3 = "S/N";
		}
		else{
			$telf_3 = $datos[7];
		}

		/* AUDITANDO PROCESO */
		// tipo de accion, autor, id afectado
		$valores = [ $this->actualizacion, $this->autor, $datos[0]];
		$this->audit->audit_proveedores($valores);

		$this->consul = $this->ConexSQL->prepare("UPDATE proveedores SET nombre_proveedor = :nombre, empresa_proveedor = :empresa, direccion_proveedor = :direccion, rubro_proveedor = :rubro, sitio_web = :sitio_web, telefono_1_proveedor = :telf_1, telefono_2_proveedor = :telf_2, telefono_3_proveedor = :telf_3, 
			correo_proveedor = :correo WHERE id_proveedor = :id");

		$this->consul->bindParam(":id", $datos[0], PDO::PARAM_INT);
		$this->consul->bindParam(":nombre", $datos[1], PDO::PARAM_STR);
		$this->consul->bindParam(":empresa", $datos[2], PDO::PARAM_STR);
		$this->consul->bindParam(":direccion", $datos[3], PDO::PARAM_STR);
		$this->consul->bindParam(":rubro", $rubro, PDO::PARAM_STR);
		$this->consul->bindParam(":sitio_web", $datos[9], PDO::PARAM_STR);
		$this->consul->bindParam(":telf_1", $datos[5], PDO::PARAM_INT);
		$this->consul->bindParam(":telf_2", $telf_2, PDO::PARAM_STR);
		$this->consul->bindParam(":telf_3", $telf_3, PDO::PARAM_STR);
		$this->consul->bindParam(":correo", $datos[8], PDO::PARAM_STR);

		$this->consul->execute();
		$this->resul = $this->consul->rowCount();

		if(!$this->consul)
		{
			$this->error_proceso("(p0022)");					
		}
		else
		{

			?>
			<script type="text/javascript">
				$("#resul_busqueda").load(location.href+" #resul_busqueda>*","");
				$("#act_proveedor").removeClass("mostrar");				
			</script>
			<?php
			$this->exito_proceso_general("Proveedor actualizado");
		}	
	}

	public function actualizar_config_comision($accion, $comision)
	{
		$this->cargar_loader();

		/* AUDITANDO PROCESO */
		// tipo de accion, autor, id afectado
		$valores = [ $this->actualizacion, $this->autor, '1'];
		$this->audit->audit_config_comision($valores);


		$this->consul = $this->ConexSQL->prepare("UPDATE config_comision_venta SET comision = :comision WHERE id_comision = 1");

		$this->consul->bindParam(":comision", $comision, PDO::PARAM_STR);
		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("(p0023)");					
		}
		else
		{
			if($accion != "no")
			{
				$this->exito_proceso("", "Comisión actualizada");
			}			
		}				
	}

	public function actualizar_config_monedas($accion, $monedas)
	{

		$this->cargar_loader();

		/* AUDITANDO PROCESO */
		// tipo de accion, autor, id afectado
		$valores = [ $this->actualizacion, $this->autor, '1'];
		$this->audit->audit_config_monedas($valores);

		$this->consul = $this->ConexSQL->prepare("UPDATE config_monedas_ventas SET monedas_ventas = :monedas WHERE id_monedas_ventas = 1");

		$this->consul->bindParam(":monedas", $monedas, PDO::PARAM_INT);
		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("(p0024)");					
		}
		else
		{
			if($accion != "no")
			{
				$this->exito_proceso("", "Monedas actualizada");
			}
			
		}				
	}	

	public function dinero_caja($accion, $valor)
	{
		if($accion == "5")
		{
			$this->consul = $this->ConexSQL->prepare("UPDATE caja SET valor =valor - $valor WHERE id_caja = 1");
		}
		else if($accion == "4" OR $accion == "1")
		{
			$this->consul = $this->ConexSQL->prepare("UPDATE caja SET valor =valor + $valor WHERE id_caja = 1");			
		}

		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("(p0028)");					
		}
		else
		{
			// tipo de accion, autor, id afectado, dinero
			$valores = [$accion, $this->autor, '1', $valor];
			$this->audit->audit_caja($valores);
		}
	}

	public function monedas_caja($accion, $valor)
	{
		if($accion == "5")
		{
			$this->consul = $this->ConexSQL->prepare("UPDATE monedas SET valor = valor - $valor WHERE id_monedas = 1");
		}
		else if($accion == "4" OR $accion == "1")
		{
			$this->consul = $this->ConexSQL->prepare("UPDATE monedas SET valor = valor + $valor WHERE id_monedas = 1");
		}			

		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("(p0029)");					
		}
		else
		{
			// tipo de accion, autor, id afectado, dinero
			$valores = [$accion, $this->autor, '1', $valor];
			$this->audit->audit_monedas($valores);
		}		
	}

	public function act_permisos($datos)
	{

		$this->consul = $this->ConexSQL->prepare("UPDATE permisos_usuarios SET configuraciones = :config, ventas = :ventas, ingresos_egresos = :ing_egr, proveedores = :proveedores, usuarios = :usuarios WHERE id_usuario = :id");

		$this->consul->bindParam(":id", $datos[0], PDO::PARAM_STR);
		$this->consul->bindParam(":config", $datos[1], PDO::PARAM_STR);
		$this->consul->bindParam(":config", $datos[1], PDO::PARAM_STR);
		$this->consul->bindParam(":ventas", $datos[2], PDO::PARAM_STR);
		$this->consul->bindParam(":ing_egr", $datos[3], PDO::PARAM_STR);
		$this->consul->bindParam(":proveedores", $datos[4], PDO::PARAM_STR);
		$this->consul->bindParam(":usuarios", $datos[5], PDO::PARAM_STR);

		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("(p0032)");					
		}
		else
		{
			$this->exito_proceso("", "Permisos actualizados");			
		}	
	}

	public function actualizar_estatus_usuario($datos)
	{
		/* AUDITANDO PROCESO */
		// tipo de accion, autor, id afectado
		$valores = [ $this->actualizacion, $this->autor, $datos];
		$this->audit->audit_hicom_users($valores);

		$this->prev = $this->ConexSQL->prepare("SELECT estado_cuenta FROM hicom_users WHERE id_user = :id");
		$this->prev->bindParam(":id", $datos, PDO::PARAM_STR);
		$this->prev->execute();
		$this->r = $this->prev->fetch();

		if($this->r[0] == "1")
		{
			$estatus = "2";
		}
		else
		{
			$estatus = "1";			
		}

		$this->consul = $this->ConexSQL->prepare("UPDATE hicom_users SET estado_cuenta = :estatus WHERE id_user = :id");			

		$this->consul->bindParam(":id", $datos, PDO::PARAM_STR);
		$this->consul->bindParam(":estatus", $estatus, PDO::PARAM_STR);
		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("(p0033)");					
		}
		else
		{
			?>
			<script type="text/javascript">
				$("#resul_busqueda").load(location.href+" #resul_busqueda>*","");			
			</script>
			<?php
			$this->exito_proceso_general("Estatus actualizado");			
		}	

	}

	public function pago_electronico($accion, $valor)
	{
		if($accion == "5")
		{
			$this->consul = $this->ConexSQL->prepare("UPDATE pago_electronico SET pago_electronico = pago_electronico - $valor WHERE id_pago_electronico = 1");
		}
		else if($accion == "4" OR $accion == "1")
		{
			$this->consul = $this->ConexSQL->prepare("UPDATE pago_electronico SET pago_electronico = pago_electronico + $valor WHERE id_pago_electronico = 1");
		}			

		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("(p0042)");					
		}
		else
		{
			// tipo de accion, autor, id afectado, dinero
			$valores = [$accion, $this->autor, '1', $valor];
			$this->audit->audit_pago_electronico($valores);
		}		
	}

	public function pago_tarjetas($accion, $valor)
	{
		if($accion == "5")
		{
			$this->consul = $this->ConexSQL->prepare("UPDATE pago_tarjetas SET pago_tarjetas = pago_tarjetas - $valor WHERE id_pago_tarjetas = 1");
		}
		else if($accion == "4" OR $accion == "1")
		{
			$this->consul = $this->ConexSQL->prepare("UPDATE pago_tarjetas SET pago_tarjetas = pago_tarjetas + $valor WHERE id_pago_tarjetas = 1");
		}			

		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("(p0042)");					
		}
		else
		{
			// tipo de accion, autor, id afectado, dinero
			$valores = [$accion, $this->autor, '1', $valor];
			$this->audit->audit_pago_tarjetas($valores);
		}		
	}

	public function actualizar_estado_activo($datos)
	{
		$this->consul = $this->ConexSQL->prepare("UPDATE estado_activo SET activo = 'si' WHERE id_estado_activo = :id");

		$this->consul->bindParam(":id", $datos, PDO::PARAM_STR);

		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("(p0048)");					
		}
		else
		{
			$this->exito_proceso("", "Configuración exitosa");
		}
	}

	////////////////////////////////
	//ELIMINACION //////////////////
	////////////////////////////////


	public function eliminar_proveedor($datos)
	{
		$this->cargar_loader();

		/* AUDITANDO PROCESO */
		// tipo de accion, autor, id afectado
		$valores = [ $this->eliminacion, $this->autor, $datos];
		$this->audit->audit_proveedores($valores);

		$this->consul = $this->ConexSQL->prepare("DELETE FROM proveedores WHERE id_proveedor = :id");
		$this->consul->bindParam(":id", $datos, PDO::PARAM_INT);
		$this->consul->execute();

		if(!$this->consul)
		{
			$this->error_proceso("(p0025)");					
		}
		else
		{
			?>
			<script type="text/javascript">
				$("#resul_busqueda").load(location.href+" #resul_busqueda>*","");
				$("#elim_proveedor").removeClass("mostrar");				
			</script>
			<?php
			$this->exito_proceso_general("Proveedor eliminado");
		}
	}




}//fin class Registros

?>