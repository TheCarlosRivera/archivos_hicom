
/**********************************************************/
/* checkbox  ************************************************/
/**********************************************************/

var option_1 = document.getElementById("option_1");//china
var option_2 = document.getElementById("option_2");//japonesa
var opt_1 = document.getElementById("opt_1");
var opt_2 = document.getElementById("opt_2");

	option_1.addEventListener("click", checkear_opt_1);
	function checkear_opt_1(){
		if(opt_1.checked == true)
		{
			$("#option_1").addClass("green");
			$("#icon_opt_1").removeClass("icon-check_box_outline_blank");
			$("#icon_opt_1").addClass("icon-check1");
		}
		else
		{
			$("#option_1").removeClass("green");
			$("#icon_opt_1").removeClass("icon-check1");
			$("#icon_opt_1").addClass("icon-check_box_outline_blank");
		}

	}

	option_2.addEventListener("click", checkear_opt_2);
	function checkear_opt_2(){
		if(opt_2.checked == true)
		{
			$("#option_2").addClass("green");
			$("#icon_opt_2").removeClass("icon-check_box_outline_blank");
			$("#icon_opt_2").addClass("icon-check1");
		}
		else
		{
			$("#option_2").removeClass("green");
			$("#icon_opt_2").removeClass("icon-check1");
			$("#icon_opt_2").addClass("icon-check_box_outline_blank");
		}

	}
