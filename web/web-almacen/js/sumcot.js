$(function() {
	$('.dropdown-toggle').dropdown();
	$( "#txtfecha" ).datepicker({ minDate: "" , maxDate: "+3M +10D" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd"});
});
function viewcot (nsum) {
	if (nsum != "") {
		document.getElementById("txtnsum").value = nsum;
		$("#modalccot").modal('show');
	}
}
function viewdet () {
	var fec = document.getElementById("txtfecha").value;
	var obs = document.getElementById("txtobser").value;
	var nsum = document.getElementById("txtnsum").value;
	if (nsum != "" && fec != "") {
		if (confirm("Seguro(a) que Desea Generar Cotizacion")) {
			xmlhttp = peticion();
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("nrocotizacion").value = xmlhttp.responseText;
					$("#modalccot").modal('hide');
					viewgencot(nsum);
				}
			}
			var requestUrl = '';
			requestUrl = "include/incsumcot.php"+"?tra=det"+"&nsum="+encodeURIComponent(nsum)+"&fec="+encodeURIComponent(fec)+"&obser="+encodeURIComponent(obs);
			xmlhttp.open("POST",requestUrl,true);
			xmlhttp.send();
		}
	}else{
		alert("Algún Campo esta vació!!");
	}
}
function viewgencot (nsum) {
	if (nsum != "") {
		document.getElementById("lblnsum").innerHTML = nsum;
		xmlhttp = peticion();
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.getElementById("tbl").innerHTML = xmlhttp.responseText;
			}
		}
		var requestUrl = '';
		requestUrl = "include/incsumcot.php"+"?tra=tbl"+"&nsum="+encodeURIComponent(nsum);
		xmlhttp.open("POST",requestUrl,true);
		xmlhttp.send();
		$("#modalcot").modal('show');
	}
}
function savedetpro () {
	var matids = document.getElementsByName("matids");
	var chk = 0;
	var ids = new Array();
	var cant = new Array();
	for (var i = 0; i < matids.length; i++) {
		if(matids[i].checked){
			ids[chk] = matids[i].id;
			cant[chk] = matids[i].value;
			chk++;
		}
	}
	if (chk > 0) {
		xmlhttp = peticion();
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				if (xmlhttp.responseText == "hecho"){
					document.getElementById("mend").style = 'Block';
					setTimeout(function() { document.getElementById("mend").style = 'none'; }, 3000);
				}
			}
		}
		var requestUrl = '';
		var nsum = document.getElementById("lblnsum").innerHTML;
		var cbopro = document.getElementById("cbopro");
		var op = cbopro.options[cbopro.selectedIndex].value;
		var ncot = document.getElementById("nrocotizacion").value;
		requestUrl = "include/incsumcot.php"+"?tra=savedet"+"&nsum="+encodeURIComponent(nsum)+"&cbopro="+encodeURIComponent(op)+"&matid="+encodeURIComponent(ids)+"&cants="+encodeURIComponent(cant)+"&nrocot="+encodeURIComponent(ncot);
		xmlhttp.open("POST",requestUrl,true);
		xmlhttp.send();
	}else{
		alert("Debe de Seleccionar por lo menos un Material!!!");
	}
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
function finalysum () {
	var nsum = document.getElementById("lblnsum").innerHTML;
	if (nsum != "") {
		if (confirm("Seguro que desea terminar de Cotizar?")) {
			xmlhttp = peticion();
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					if (xmlhttp.responseText == "hecho"){
						location.href = '';
					}
				}
			}
			var requestUrl = '';
			requestUrl = "include/incsumcot.php"+"?tra=statussum"+"&nsum="+encodeURIComponent(nsum);
			xmlhttp.open("POST",requestUrl,true);
			xmlhttp.send();
		}
	}
}