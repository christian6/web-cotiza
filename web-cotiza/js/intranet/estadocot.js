$(function(){
	$( "#fini" ).datepicker({ minDate: "", maxDate: "" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd" });
	$( "#ffin" ).datepicker({ minDate: "", maxDate: "" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd" });
});
function changer () {
	var	n = document.getElementById("btnn");
	var f = document.getElementById("btnf");
	if (n.checked) {
		document.getElementById("txtnro").disabled = "";
		document.getElementById("fini").disabled   = "disabled";
		document.getElementById("ffin").disabled   = "disabled";
	}else if(f.checked){
		document.getElementById("txtnro").disabled = "disabled";
		document.getElementById("fini").disabled   = "";
		document.getElementById("ffin").disabled   = "";
	}
}
function view (nro,ruc) {
	window.open("../reports/pdfs/solcotpdf.php?ruc="+ruc+"&nro="+nro);
}
function viewcompra (nro,ruc) {
	window.open("../reports/pdfs/system/intordencomprapdf.php?ruc="+ruc+"&nro="+nro);
}
function peticion(){
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlhttp;
}
function anular (nro,ruc) {
	if (nro != "" & ruc != "") {
		if (confirm("Seguro que Desea Anular la Cotización "+nro+" para "+ruc+"?")) {
			xmlhttp = peticion();
			xmlhttp.onreadystatechange=function()
			{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				if (xmlhttp.responseText=='ok') {
					document.getElementById("al").style.display = 'block';
					setTimeout(function() { document.location.reload(); }, 3000);
				}else{
					alert("Termino con Errores!!!.");
					return;
				}
			}
			}
	  		var requestUrl;
	  		requestUrl = "includes/incestadocot.php"+"?nro="+encodeURIComponent(nro)+"&ruc="+encodeURIComponent(ruc)+"&t=a";
			xmlhttp.open("POST",requestUrl,true);
			xmlhttp.send();
		}else{
			return;
		}
	}
}

function anularcompra (nro) {
	if (nro != "") {
		if (confirm("Seguro que Desea Anular la Orden de Compra "+nro+"?")) {
			xmlhttp = peticion();
			xmlhttp.onreadystatechange=function()
			{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				if (xmlhttp.responseText=='ok') {
					document.getElementById("al").style.display = 'block';
					setTimeout(function() { document.location.reload(); }, 3000);
				}else{
					alert("Termino con Errores!!!.");
					return;
				}
			}
			}
	  		var requestUrl;
	  		requestUrl = "includes/incestadocomp.php"+"?nro="+encodeURIComponent(nro)+"&t=a";
			xmlhttp.open("POST",requestUrl,true);
			xmlhttp.send();
		}else{
			return;
		}
	}
}