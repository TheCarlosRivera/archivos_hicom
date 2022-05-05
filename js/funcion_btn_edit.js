const actualizar_proveedor = (camp) =>
{
		var datos = { "id_edit": camp.target.value };
		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}

		});

	$("#act_proveedor").addClass(" mostrar");
}

const edit = document.querySelectorAll(".edit");
edit.forEach((camp) => 
{
	camp.addEventListener("click", actualizar_proveedor);
});