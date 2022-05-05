		
		var ocultar = document.getElementById("modificar");
		var cambiar = document.getElementById("cambiar_method");
		var enviar_codigo = document.getElementById("enviar_codigo");

		ocultar.onclick = function()
		{	
			var valor = $("#id_user").val();
			var datos =	{ "busq": valor, };

			$.ajax({
				data: datos,
				type: "POST",
				url: "procesos/validaciones.php",
				success: function (resultado)
				{
					$("#resul").html(resultado);
				}
			});//fin ajax
		}

		cambiar.onclick = function()
		{
			$("#form").addClass(" ocultar");
			$("#opt_recuperar").removeClass("ocultar");
		}

		enviar_codigo.onclick = function()
		{
			$("#contentLoader").removeClass("ocultar");
			var email = $("#correo").val();
			var id = $("#id_user").val();
			var datos = 
			{
				"enviar_datos": email,
				"id_user": id
			}

			$.ajax({
				data: datos,
				type: "POST",
				url: "procesos/validaciones.php",
				beforesend: function()
				{

				},
				success: function (resultado)
				{
					$("#resul").html(resultado);
				}
			});//fin ajax

		}

///////////////////////////////
//recuperando respuesta ///////
///////////////////////////////

const formulario = document.getElementById("form_recuperar");
const inputs = document.querySelectorAll("#form_recuperar input");

const expresiones =
{
	exp_preg_resp: /^[a-zA-Z0-9\s]{5,30}$/
}

const campos =
{
	respuesta: false
}

const validarFormulario = (e) => {
	switch(e.target.name)
	{
		//RESPUESTA
		case "respuesta":
			validarCampos(expresiones.exp_preg_resp, e.target, 'respuesta');				
		break;


	}
}

const validarCampos = (expresion, input, grupo) => 
{
	if(expresion.test(input.value))
	{
		document.getElementById(`grupo__${grupo}`).classList.remove('error');
		document.getElementById(`grupo__${grupo}`).classList.add('correcto');
		document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-times-circle');
		document.querySelector(`#grupo__${grupo} i`).classList.add('fa-check-circle');
		campos[grupo] = true;
	}
	else
	{
		document.getElementById(`grupo__${grupo}`).classList.remove('correcto');
		document.getElementById(`grupo__${grupo}`).classList.add('error');
		document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-check-circle');
		document.querySelector(`#grupo__${grupo} i`).classList.add('fa-times-circle');	
		campos[grupo] = false;
	}

}

inputs.forEach((input) => {
	input.addEventListener("keyup", validarFormulario);
	input.addEventListener("blur", validarFormulario);
});


formulario.addEventListener("submit", (e) => {
	e.preventDefault();

	if (campos.respuesta)
	{
			formulario.enviar.disabled = true;
			formulario.enviar.classList.add('inactivo');
			
			var id_2 = $("#id_user").val();
			var recuperar = $("#recuperar").val();
			var respuesta = $("#respuesta").val();

			var datos = 
			{
				"id": id_2,
				"recuperar": recuperar,
				"respuesta": respuesta
			}

			$.ajax({
				data: datos,
				type: "POST",
				url: "procesos/validaciones.php",
				success: function (resultado)
				{
					$("#resul").html(resultado);
				}
			});//fin ajax
			
	}
	else
	{
		document.getElementById("grupo__respuesta").classList.add('error');
		document.getElementById('alert_mensaje').innerHTML='Debe completar los campos.';
		$("#alert_error_form").addClass("mostrar");
		setTimeout(() =>
		{
			$("#alert_error_form").removeClass("mostrar");			
		}, 5000);

	}

});



///////////////////////////////
//cambiando la contraseña /////
///////////////////////////////

const formulario_two = document.getElementById("form_cambio_pass");
const inputs_two = document.querySelectorAll("#form_cambio_pass input");

const expresiones_two =
{
	exp_password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$/.%*?])([A-Za-z\d$/.%*?]|[^ ]){8,20}$/
}

const campos_two =
{
	password: false
}

const validarFormulario_two = (e) => {
	switch(e.target.name)
	{
		//PASSWORD
		case "password":
			validarCampos_two(expresiones_two.exp_password, e.target, 'password');				
			validarPassword();
		break;

		//PASSWORD REPEAT
		case "password_repeat":
			validarPassword();
		break;
	}
}

const validarCampos_two = (expresion_two, input_two, grupo_two) => 
{
	if(expresion_two.test(input_two.value))
	{
		document.getElementById(`grupo__${grupo_two}`).classList.remove('error');
		document.getElementById(`grupo__${grupo_two}`).classList.add('correcto');
		document.querySelector(`#grupo__${grupo_two} i`).classList.remove('fa-times-circle');
		document.querySelector(`#grupo__${grupo_two} i`).classList.add('fa-check-circle');
		campos_two[grupo_two] = true;
	}
	else
	{
		document.getElementById(`grupo__${grupo_two}`).classList.remove('correcto');
		document.getElementById(`grupo__${grupo_two}`).classList.add('error');
		document.querySelector(`#grupo__${grupo_two} i`).classList.remove('fa-check-circle');
		document.querySelector(`#grupo__${grupo_two} i`).classList.add('fa-times-circle');	
		campos_two[grupo_two] = false;
	}

}

const validarPassword = () =>
{
	const password = document.getElementById("password");
	const password_repeat = document.getElementById("password_repeat");

	if(password.value !== password_repeat.value)
	{
		document.getElementById('grupo__password_repeat').classList.remove('correcto');
		document.getElementById('grupo__password_repeat').classList.add('error');
		document.querySelector('#grupo__password_repeat i').classList.remove('fa-check-circle');
		document.querySelector('#grupo__password_repeat i').classList.add('fa-times-circle');
		campos_two['password'] = false;
	}
	else
	{
		document.getElementById('grupo__password_repeat').classList.add('correcto');
		document.getElementById('grupo__password_repeat').classList.remove('error');
		document.querySelector('#grupo__password_repeat i').classList.add('fa-check-circle');
		document.querySelector('#grupo__password_repeat i').classList.remove('fa-times-circle');		
		campos_two['password'] = true;
	}
}

const validarPassExistente = (e) =>
{
	var id_pass = $("#id_user").val();
	switch(e.target.name)
	{
		case "password":
			if(expresiones_two.exp_password.test(e.target.value))
			{
				var val = 
				{
					"val_pass": formulario_two.password.value,
					"id_pass": id_pass
				}

				$.ajax({
					data: val,
					type: "POST",
					url: "procesos/validaciones.php",
					beforesend: function()
					{

					},
					success: function (resultado)
					{
						$("#resul").html(resultado);
					}
				});//fin ajax		
			}	
		break;

	}
}

inputs_two.forEach((input_two) => {
	input_two.addEventListener("keyup", validarFormulario_two);
	input_two.addEventListener("blur", validarFormulario_two);
	input_two.addEventListener("blur", validarPassExistente);	
});


formulario_two.addEventListener("submit", (e) => {
	e.preventDefault();

	if (campos_two.password && formulario_two.error_pass.value == "bien")
	{
			formulario_two.enviar.disabled = true;
			formulario_two.enviar.classList.add('inactivo');

			var id_3 = $("#id_user").val();
			var cambio_pass = $("#cambio_pass").val();
			var password = $("#password").val();

			var datos = 
			{
				"id": id_3,
				"cambio_pass": cambio_pass,
				"password": password
			}

			$.ajax({
				data: datos,
				type: "POST",
				url: "procesos/validaciones.php",
				success: function (resultado)
				{
					$("#resul").html(resultado);
				}
			});//fin ajax
			
	}
	else
	{
		document.getElementById("grupo__password").classList.add('error');
		document.getElementById("grupo__password_repeat").classList.add('error');
		document.getElementById('alert_mensaje').innerHTML='Debe completar los campos.';
		$("#alert_error_form").addClass("mostrar");
		setTimeout(() =>
		{
			$("#alert_error_form").removeClass("mostrar");			
		}, 5000);

	}

});


///////////////////////////////
//confirmacion de correo //////
///////////////////////////////

const formulario_three = document.getElementById("form_confim_correo");
const inputs_three = document.querySelectorAll("#form_confim_correo input");

const expresiones_three =
{
	exp_correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/
}

const campos_three =
{
	correo: false,
}

const validarFormulario_three = (e) => {
	switch(e.target.name)
	{
		//CORREO
		case "correo":
			validarCampos_three(expresiones_three.exp_correo, e.target, 'correo');			
		break;
	}
}

const validarCampos_three = (expresion_three, input_three, grupo_three) => 
{
	if(expresion_three.test(input_three.value))
	{
		document.getElementById(`grupo__${grupo_three}`).classList.remove('error');
		document.getElementById(`grupo__${grupo_three}`).classList.add('correcto');
		document.querySelector(`#grupo__${grupo_three} i`).classList.remove('fa-times-circle');
		document.querySelector(`#grupo__${grupo_three} i`).classList.add('fa-check-circle');
		campos_three[grupo_three] = true;
	}
	else
	{
		document.getElementById(`grupo__${grupo_three}`).classList.remove('correcto');
		document.getElementById(`grupo__${grupo_three}`).classList.add('error');
		document.querySelector(`#grupo__${grupo_three} i`).classList.remove('fa-check-circle');
		document.querySelector(`#grupo__${grupo_three} i`).classList.add('fa-times-circle');	
		campos_three[grupo_three] = false;
	}

}

inputs_three.forEach((input) => {
	input.addEventListener("keyup", validarFormulario_three);
	input.addEventListener("blur", validarFormulario_three);
});


formulario_three.addEventListener("submit", (e) => {
	e.preventDefault();

	if (campos_three.correo)
	{

			formulario_three.enviar.disabled = true;
			formulario_three.enviar.classList.add('inactivo');

			var datos = new FormData($("#form_confim_correo")[0]);

			$.ajax({
				data: datos,
				type: "POST",
				url: "procesos/validaciones.php",
				contentType: false,
				processData: false,
				beforesend: function()
				{

				},
				success: function (resultado)
				{
					$("#resul").html(resultado);
				}
			});//fin ajax

		document.querySelectorAll('.box__input').forEach((icono) =>
		{
			icono.classList.remove('correcto');
		});
		formulario_three.enviar.disabled = false;
		formulario_three.enviar.classList.remove("inactivo");	
			
	}
	else
	{
		document.getElementById("grupo__correo").classList.add('error');	
		document.getElementById('alert_mensaje').innerHTML='Debe completar los campos.';
		$("#alert_error_form").addClass("mostrar");
		setTimeout(() =>
		{
			$("#alert_error_form").removeClass("mostrar");			
		}, 5000);

	}

});