$(function() {
    $( ".dropdown-toggle" ).dropdown();
    $("#txtfini").datepicker({ minDate: "" , maxDate: "" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd"});
    $("#txtffin").datepicker({ minDate: "" , maxDate: "" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd"});
});
function radios () {
	var c = document.getElementById("rbtnc");
	var f = document.getElementById("rbtnf");
	if (c.checked) {
		document.getElementById('txtnco').disabled = false;
		document.getElementById('txtfini').disabled = true;
		document.getElementById('txtffin').disabled = true;
	}else if(f.checked){
		document.getElementById('txtfini').disabled = false;
		document.getElementById('txtffin').disabled = false;
		document.getElementById('txtnco').disabled = true;
	}
}
function view (nro,ruc) {
	if (nro != "") {
		window.open('../../web-cotiza/reports/pdfs/system/intordencomprapdf.php?ruc='+ruc+'&nro='+nro+'');
	}
}
function recibir (nro) {
	if (nro != "") {
		location.href = 'recibircompra.php?nro='+nro;
	}
}
function chkdetmatfull () {
	var chk = document.getElementById("chkf");
	if (chk != null){
		var chkm = document.getElementsByName("matid");
		var val = 1;
		if (chk.checked) {
			for (var i = 0; i < chkm.length; i++) {
				chkm[i].checked = true;
				document.getElementById("cant"+val).disabled = false;
				document.getElementById("fec"+val).disabled = false;
				val++;
			}
		}else if (!chk.checked) {
			for (var i = 0; i < chkm.length; i++) {
				chkm[i].checked = false;
				document.getElementById("cant"+val).disabled = true;
				document.getElementById("fec"+val).disabled = true;
				val++;
			}
		}
	}
}
function change (chk) {
	if (chk != null) {
		var val = chk.value;
		if (chk.checked) {
			document.getElementById("cant"+val).disabled = false;
			document.getElementById("fec"+val).disabled = false;
		}else if (!chk.checked) {
			document.getElementById("cant"+val).disabled = true;
			document.getElementById("fec"+val).disabled = true;
		}
	}
}
$(document).ready(function () {
	var chkm = document.getElementsByName("matid");
	for (var i = 1; i <= chkm.length; i++) {
		$("#fec"+i).datepicker({ minDate: "" , maxDate: "" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy-mm-dd"});
	};
});
function valc (cant,nc) {
	if (cant != "") {
		if (cant < nc.value) {
			nc.value = cant;
		}else if (nc.value < 0) {
			nc.value = cant;
		}
	}
}
function recibirmat () {
	$("#mtd").modal("show");
}
function next (pos) {
	if (pos != "") {
		switch(pos){
			case 2: 
				$("#mtd").modal("hide");
				$("#mtd2").modal("show");
			break;
			case 3: 
				$("#mtd2").modal("hide");
				$("#mtd3").modal("show");
			break;
		}
	}
}
function back (pos) {
	if (pos != "") {
		switch(pos){
			case 2:
				$("#mtd").modal("show");
				$("#mtd2").modal("hide");
			break;
			case 3: 
				$("#mtd3").modal("hide");
				$("#mtd2").modal("show");
			break;
		}
	}
}
function guardar () {
	// validando si todos los check del detalle estan marcados
	var ch = 0;
	var matid =  document.getElementsByName("matid");
	for (var i = 0; i < matid.length; i++) {
		if(matid[i].checked){
			ch++;
		}
	}
	if (ch < matid.length) {
		if (!confirm("No estan seleccionados todos los\r\n materiales para ser ingresados\r\n Desea Continuar de todas formas?")) {
			return;
		}
	}
	//validando las cantidades
	var c = document.getElementsByName("cants");
	var ca = 0;
	for (var i = 0; i < c.length; i++) {
		//alert(c[i].max +'  '+c[i].value);
		if(c[i].value < c[i].max){
			ca++;
		}
	}
	if (ca > 0) {
		if (!confirm("Algunas cantidades son distintas a las\r\nque se han pedido\r\n Desea continuar de todas formas?")) {
			return;
		}
	}
	// validando almacenid
	var h = document.getElementById("alid");
	var cal = document.getElementById("cboal");
	var alid ="";
	if (h != null) {
		alid = h.value;
	}else if (cal != null) {
		alid = cal.options[cal.selectedIndex].value;
	}else{
		alert("Error en Codigo de almacen");
		return;
	}
	// validando nro de guia
	var ng = document.getElementById("txtnrog");
	if (ng.value == "") {
		if (!confirm("El Número de Guia no se ha ingresado\r\n Desea continuar?")) {
			ng.focus();
			return;
		}
	}
	// Validando numero de factura
	var nf = document.getElementById("txtnrof");
	if (nf.value == "") {
		if (!confirm("El Número de la Factura no se ha ingresado\r\n Desea continuar?")) {
			nf.focus();
			return;
		}
	}
	// Validando motivo
	var mot = document.getElementById("txtmot");
	if (mot.value == "") {
		if (!confirm("No se ha ingresado el Motivo de Ingreso\r\n Desea continuar?")) {
			mot.focus();
			return;
		}
	}
	// observacion
	var obser = document.getElementById("txtobser");
	if (obser.value == "") {
		if (!confirm("No ha ingresado una observacion\r\n Desea continuar?")) {
			obser.focus();
			return;
		}
	}
	if (confirm("Seguro(a) que desea generar la Nota de Ingreso?")) {
		xmlhttp = peticion();
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="hecho"){
					guardardet();
				}
			}
		} 
		var requestUrl;
  		var ncom = document.getElementById("ncom");
  		var nc = document.getElementById("txtnroc");
  		var cre = document.getElementById("cbore");
  		var ore = cre.options[cre.selectedIndex].value;
  		var cins = document.getElementById("cboins");
  		var oins = cins.options[cins.selectedIndex].value;
  		var cvb = document.getElementById("cbovb");
  		var ovb = cvb.options[cvb.selectedIndex].value;
  		requestUrl = "include/incnotaingreso.php"+"?tra=addc"+"&alid="+encodeURIComponent(alid)+"&ng="+encodeURIComponent(ng.value)+"&ncom="+
  		encodeURIComponent(ncom.value)+"&nc="+encodeURIComponent(nc.value)+"&nf="+encodeURIComponent(nf.value)+"&mot="+encodeURIComponent(mot.value)+"&obser="+
  		encodeURIComponent(obser.value)+"&rec="+encodeURIComponent(ore)+"&ins="+encodeURIComponent(oins)+"&vb="+encodeURIComponent(ovb);
		xmlhttp.open("POST",requestUrl,true);
		xmlhttp.send();
	}
}

function guardardet () {
	// Recuperando los id de los materiales y las Cantidades
	var matid =  document.getElementsByName("matid");
	var c = document.getElementsByName("cants");
	var pre = document.getElementsByName("precios");
	var h2 = document.getElementById("alid");
	var cal2 = document.getElementById("cboal");
	var alid = "";
	if (h2 != null) {
		alid = h2.value;
	}else if (cal2 != null) {
		alid = cal2.options[cal2.selectedIndex].value;
	}else{
		alert("Error en Codigo de almacen");
		return;
	}
	for (var i = 0; i < matid.length; i++) {
		var requestUrl = "include/incnotaingreso.php?tra=addd";
		if(matid[i].checked){
			//alert(matid[i].id);
			xmlhttp = peticion();
			requestUrl += "&matid="+encodeURIComponent(matid[i].id)+"&cantidad="+encodeURIComponent(c[i].value)+
			"&cantv="+encodeURIComponent(c[i].max)+"&pre="+encodeURIComponent(pre[i].value)+"&alid="+encodeURIComponent(alid);
			alert(requestUrl);
			xmlhttp.open("POST",requestUrl,true);
			xmlhttp.send();
		}
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