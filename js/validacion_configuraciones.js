
const abrir = document.querySelectorAll("#config p");

abrir.forEach((e) =>{
	e.addEventListener("click", function(){
		if ($(e).hasClass("mostrar"))
		{
			e.classList.remove("mostrar");
			e.querySelector("i").classList.remove("fa-chevron-up");

			e.querySelector("i").classList.add("fa-chevron-down");
		}
		else
		{
			e.classList.add("mostrar");			
			e.querySelector("i").classList.remove("fa-chevron-down");
			e.querySelector("i").classList.add("fa-chevron-up");
		}

	});
});

const expresiones =
{
	exp_comision: /^[0-9(.)]{1,4}$/,
	exp_monedas: /^[0-9]{1,6}$/	
}


/*******************************/
/*** VALIDANDO COMISION ********/
/*******************************/


const form_comision = document.getElementById("form_comision");
const input_comision = document.querySelectorAll("#form_comision .input");

const campos_comision =
{
	comision: false
}

const validar_form_comision = (e) =>
{
	switch(e.target.name)
	{
		case "comision":
			validar_campos_comision(expresiones.exp_comision, e.target, 'comision');
		break;
	}
}

const validar_campos_comision = (expresion, input, grupo) =>
{
	if (expresion.test(input.value))
	{
		document.getElementById(`grupo__${grupo}`).classList.remove('error');
		document.getElementById(`grupo__${grupo}`).classList.add('correcto');		
		document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-times-circle');
		document.querySelector(`#grupo__${grupo} i`).classList.add('fa-check-circle');
		campos_comision[grupo] = true;
	}
	else
	{
		document.getElementById(`grupo__${grupo}`).classList.remove('correcto');
		document.getElementById(`grupo__${grupo}`).classList.add('error');
		document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-check-circle');
		document.querySelector(`#grupo__${grupo} i`).classList.add('fa-times-circle');	
		campos_comision[grupo] = false;
	}
}

input_comision.forEach((input) =>
{
	input.addEventListener("keyup", validar_form_comision);
	input.addEventListener("blur", validar_form_comision);
});

form_comision.addEventListener("submit", (e) =>
{
	e.preventDefault();
	if(campos_comision.comision)
	{
		form_comision.enviar.disabled = true;
		form_comision.enviar.classList.add("inactivo");
		
		var datos = new FormData($('#form_comision')[0]);
		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			contentType: false,
			processData: false,
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}
		});

		form_comision.reset();
		document.querySelectorAll(".box_input").forEach((icono) =>
		{
			icono.classList.remove('correcto');
		});
		form_comision.enviar.disabled = false;
		form_comision.enviar.classList.remove("inactivo");

	}
	else
	{
		document.getElementById("grupo__comision").classList.add('error');
		document.getElementById('alert_mensaje').innerHTML='Debe completar los campos.';
		$("#alert_error_form").addClass("mostrar");
		setTimeout(function(){
		$("#alert_error_form").removeClass("mostrar");			
		}, 5000);
	}


});


/*******************************/
/*** VALIDANDO MONEDAS  ********/
/*******************************/

const form_monedas = document.getElementById("form_monedas");
const input_monedas = document.querySelectorAll("#form_monedas .input");

const campos_monedas =
{
	monedas: false
}

const validar_form_monedas = (e) =>
{
	switch(e.target.name)
	{
		case "monedas":
			validar_campos_monedas(expresiones.exp_monedas, e.target, 'monedas');
		break;
	}
}

const validar_campos_monedas = (expresion, input, grupo) =>
{
	if (expresion.test(input.value))
	{
		document.getElementById(`grupo__${grupo}`).classList.remove('error');
		document.getElementById(`grupo__${grupo}`).classList.add('correcto');		
		document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-times-circle');
		document.querySelector(`#grupo__${grupo} i`).classList.add('fa-check-circle');
		campos_monedas[grupo] = true;
	}
	else
	{
		document.getElementById(`grupo__${grupo}`).classList.remove('correcto');
		document.getElementById(`grupo__${grupo}`).classList.add('error');
		document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-check-circle');
		document.querySelector(`#grupo__${grupo} i`).classList.add('fa-times-circle');	
		campos_monedas[grupo] = false;
	}
}

input_monedas.forEach((input) =>
{
	input.addEventListener("keyup", validar_form_monedas);
	input.addEventListener("blur", validar_form_monedas);
});

form_monedas.addEventListener("submit", (e) =>
{
	e.preventDefault();
	if(campos_monedas.monedas)
	{
		form_monedas.enviar.disabled = true;
		form_monedas.enviar.classList.add("inactivo");
		
		var datos = new FormData($('#form_monedas')[0]);
		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			contentType: false,
			processData: false,
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}
		});

		form_monedas.reset();
		document.querySelectorAll(".box_input").forEach((icono) =>
		{
			icono.classList.remove('correcto');
		});
		form_monedas.enviar.disabled = false;
		form_monedas.enviar.classList.remove("inactivo");

	}
	else
	{
		document.getElementById("grupo__monedas").classList.add('error');
		document.getElementById('alert_mensaje').innerHTML='Debe completar los campos.';
		$("#alert_error_form").addClass("mostrar");
		setTimeout(function(){
		$("#alert_error_form").removeClass("mostrar");			
		}, 5000);
	}


});

