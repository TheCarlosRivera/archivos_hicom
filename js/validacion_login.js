const formulario = document.getElementById("form_login");
const inputs = document.querySelectorAll("#form_login input");

const expresiones =
{
	exp_usuario: /^[a-zA-Z0-9\_\-]{10,15}$/
}

const campos =
{
	usuario: false,
	password: false
}

const validarFormulario = (e) => {
	switch(e.target.name)
	{
		//USUARIO
		case "usuario":			
			validarCampos(expresiones.exp_usuario, e.target, 'usuario');
		break;

		//PASSWORD
		case "password":
			validarPassword();
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

	if(password.value.length >= 5)
	{
		document.getElementById('grupo__password').classList.add('correcto');
		document.getElementById('grupo__password').classList.remove('error');
		document.querySelector('#grupo__password i').classList.add('fa-check-circle');
		document.querySelector('#grupo__password i').classList.remove('fa-times-circle');	
		campos['password'] = true;
	}
	else
	{
		document.getElementById('grupo__password').classList.remove('correcto');
		document.getElementById('grupo__password').classList.add('error');
		document.querySelector('#grupo__password i').classList.remove('fa-check-circle');
		document.querySelector('#grupo__password i').classList.add('fa-times-circle');	
		campos['password'] = false;
	}
}

inputs.forEach((input) => {
	input.addEventListener("keyup", validarFormulario);
	input.addEventListener("blur", validarFormulario);
});

formulario.addEventListener("submit", (e) => {
	e.preventDefault();

	if (campos.usuario && campos.password)
	{

			var datos = new FormData($("#form_login")[0]);

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
		document.getElementById("grupo__usuario").classList.add('error');
		document.getElementById("grupo__password").classList.add('error');
		document.getElementById('alert_mensaje').innerHTML='Debe completar los campos.';
		$("#alert_error_form").addClass("mostrar");
		setTimeout(() =>
		{
			$("#alert_error_form").removeClass("mostrar");			
		}, 5000);

	}

});


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