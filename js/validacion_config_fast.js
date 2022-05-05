
const formulario = document.getElementById("form_config_sistema");
const inputs = document.querySelectorAll("#form_config_sistema input");

const expresiones = 
{
	exp_comision: /^[0-9(.)]{1,4}$/,
	exp_monedas: /^[0-9]{1,6}$/
}

const campos = 
{
	dinero: false,
	monedas_caja: false,
	pago_electronico: false,
	debito_credito: false,
	comision: false,
	monedas_venta: false
}

const validarFormulario = (e) =>
{
	switch(e.target.name)
	{
		case "dinero":
			validarCampos(expresiones.exp_monedas, e.target, 'dinero');		
		break;

		case "monedas_caja":
			validarCampos(expresiones.exp_monedas, e.target, 'monedas_caja');		
		break;

		case "pago_electronico":
			validarCampos(expresiones.exp_monedas, e.target, 'pago_electronico');		
		break;

		case "debito_credito":
			validarCampos(expresiones.exp_monedas, e.target, 'debito_credito');		
		break;

		case "comision":
			validarCampos(expresiones.exp_comision, e.target, 'comision');		
		break;

		case "monedas_venta":
			validarCampos(expresiones.exp_monedas, e.target, 'monedas_venta');		
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


inputs.forEach((inputs) => {
	inputs.addEventListener("keyup", validarFormulario);
	inputs.addEventListener("blur", validarFormulario);
});

formulario.addEventListener("submit", (e) => {
	e.preventDefault();

	if (campos.dinero && campos.monedas_caja && campos.pago_electronico && campos.debito_credito && campos.comision && campos.monedas_venta)
	{
			formulario.enviar.disabled = true;
			formulario.enviar.classList.add('inactivo');

			var datos = new FormData($("#form_config_sistema")[0]);

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

});