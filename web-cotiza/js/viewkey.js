function valrbtn (iditem) {
	var fini = document.getElementById("fecini");
	var ffin = document.getElementById("fecfin");
	var idnro = document.getElementById("nro");
	if (iditem.value == "n") {
		fini.disabled = "disabled";
		ffin.disabled = "disabled";
		idnro.disabled = "";
	}else if(iditem.value == "f"){
		idnro.disabled = "disabled";
		fini.disabled = "";
		ffin.disabled = "";
	}
}

var windet;

function openWindet(ruc,nro) {

	var myLeft = (screen.width-1000)/2;
    var myTop = (screen.height-700)/2;

	var caracteristicas="toolbar=0, location=0, directories=0, resizable=0, scrollbars=yes, height=600, width=1000, top="+myTop+", left="+myLeft;
  	windet = window.open(
    "../includes/incdetcot.php?ruc="+encodeURIComponent(ruc)+"&nro="+encodeURIComponent(nro),
    "DescriptiveWindowName",caracteristicas);
}
