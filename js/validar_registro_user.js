const formulario = document.getElementById("form_registro");
const inputs = document.querySelectorAll("#form_registro input");

const expresiones =
{
	exp_nombres: /^[a-zA-Z\s]{1,25}$/,
	exp_usuario: /^[a-zA-Z0-9\_\-]{10,15}$/,
	exp_password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$/.%*?])([A-Za-z\d$/.%*?]|[^ ]){8,20}$/,
	exp_correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
	exp_preg_resp: /^[a-zA-Z0-9\s]{5,30}$/

}

const campos =
{
	nombre: false,
	usuario: false,
	password: false,
	correo: false,
	pregunta: false,
	respuesta: false
}

const validarFormulario = (e) => {
	switch(e.target.name)
	{

		//NOMBRE
		case "nombre":			
			validarCampos(expresiones.exp_nombres, e.target, 'nombre');
		break;

		//USUARIO
		case "usuario":			
			validarCampos(expresiones.exp_usuario, e.target, 'usuario');
		break;

		//PASSWORD
		case "password":
			validarCampos(expresiones.exp_password, e.target, 'password');				
			validarPassword();
		break;

		//PASSWORD REPEAT
		case "password_repeat":
			validarPassword();
		break;

		//CORREO
		case "correo":
			validarCampos(expresiones.exp_correo, e.target, 'correo');			
		break;

		//PREGUNTA
		case "pregunta":
			validarCampos(expresiones.exp_preg_resp, e.target, 'pregunta');			
		break;

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
		campos['password'] = false;
	}
	else
	{
		document.getElementById('grupo__password_repeat').classList.add('correcto');
		document.getElementById('grupo__password_repeat').classList.remove('error');
		document.querySelector('#grupo__password_repeat i').classList.add('fa-check-circle');
		document.querySelector('#grupo__password_repeat i').classList.remove('fa-times-circle');		
		campos['password'] = true;
	}
}

const validarUsuario = (e) =>
{
	switch(e.target.name)
	{
		case "usuario":
			if(campos.usuario)
			{
				var val = 
				{
					"val_user": formulario.usuario.value,
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

		case "correo":
			if(campos.correo)
			{
				var val = 
				{
					"val_correo": "registro",
					"correo": formulario.correo.value,
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


	document.getElementById("box__select").addEventListener("click", () =>
		{
			document.getElementById("box__select").classList.remove('error'); 
		});	


inputs.forEach((input) => {
	input.addEventListener("keyup", validarFormulario);
	input.addEventListener("blur", validarFormulario);
	input.addEventListener("blur", validarUsuario);	
});

formulario.addEventListener("submit", (e) => {
	e.preventDefault();

	if(formulario.tipo_cuenta.value !== "")
	{
		
	if(campos.nombre && campos.usuario && campos.password && campos.correo && campos.pregunta && campos.respuesta)
	{

		if(formulario.error_registro.value == "bien")
		{

			formulario.enviar.disabled = true;
			formulario.enviar.classList.add('inactivo');

			var datos = new FormData($("#form_registro")[0]);

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
		
		}
		else
		{
			document.getElementById('alert_mensaje').innerHTML='Debe completar los campos.';
			$("#alert_error_form").addClass("mostrar");
			setTimeout(() =>
			{
				$("#alert_error_form").removeClass("mostrar");			
			}, 5000);
		}

	}
	else
	{
		validarCamposVacios();
		document.getElementById('alert_mensaje').innerHTML='Debe completar los campos.';
		$("#alert_error_form").addClass("mostrar");
		setTimeout(() =>
		{
			$("#alert_error_form").removeClass("mostrar");			
		}, 5000);

	}
			
	}
	else
	{
		document.getElementById("box__select").classList.add('error');
		document.getElementById('alert_mensaje').innerHTML='Seleccione tipo de cuenta';
		$("#alert_error_form").addClass("mostrar");
		setTimeout(() =>
		{
			$("#alert_error_form").removeClass("mostrar");			
		}, 5000);
	}

});

const validarCamposVacios = () =>
{
	for (var i = 0; i < inputs.length; i++) {
		if(inputs[i].type == "text" || inputs[i].type == "tel"  || inputs[i].type == "password"){
			if (inputs[i].value <= 0) {
				document.getElementById(`grupo__${inputs[i].name}`).classList.add('error');
			}else{
				document.getElementById(`grupo__${inputs[i].name}`).classList.remove('error');
			}
		}
	}
};



  var mostrar = document.getElementById("mostrarSelect");
  var ocultar = document.getElementById("options");

  mostrar.onclick = function()
  {
    $("#mostrarSelect").addClass(" ocultar");
    $("#box__select").addClass(" abrir");
  }

  ocultar.onclick = function()
  {
    $("#mostrarSelect").removeClass(" ocultar");
    $("#box__select").removeClass(" abrir");
  }


  function mostrarPass(e){
      var tipo = document.getElementById(e);
      var eye = "#pass-"+e;

      if(tipo.type == "password"){
          tipo.type = "text";
					$(eye).removeClass("fa-eye");
					$(eye).addClass("fa-eye-slash");
      }else{
          tipo.type = "password";
					$(eye).addClass("fa-eye");
					$(eye).removeClass("fa-eye-slash");
      }
  }
 