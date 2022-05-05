
/**********************************************************/
/* checks sexo ********************************************/
/**********************************************************/

var radio1 = document.getElementById("sexo1");
var radio2 = document.getElementById("sexo2");

	radio1.addEventListener("click", checkearOne);
	function checkearOne(){
		$("#opcion_1").addClass("green");
		$("#icon_sex_1").removeClass("far fa-circle");
		$("#icon_sex_1").addClass("fas fa-check-circle");

		$("#opcion_2").removeClass("green");
		$("#icon_sex_2").removeClass("fas fa-check-circle");
		$("#icon_sex_2").addClass("far fa-circle");
	}

	radio2.addEventListener("click", checkearTwo);
	function checkearTwo(){
		$("#opcion_2").addClass("green");
		$("#icon_sex_2").removeClass("far fa-circle");
		$("#icon_sex_2").addClass("fas fa-check-circle");

		$("#opcion_1").removeClass("green");
		$("#icon_sex_1").removeClass("fas fa-check-circle");
		$("#icon_sex_1").addClass("far fa-circle");
	}