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
			validarCampos_two(expresiones_two.exp_password, e.target, 'password_repeat');	
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

inputs_two.forEach((input_two) => {
	input_two.addEventListener("keyup", validarFormulario_two);
	input_two.addEventListener("blur", validarFormulario_two);
});


formulario_two.addEventListener("submit", (e) => {
	e.preventDefault();

	if (campos_two.password)
	{
			formulario_two.enviar.disabled = true;
			formulario_two.enviar.classList.add('inactivo');

			var datos = new FormData($("#form_cambio_pass")[0]);

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