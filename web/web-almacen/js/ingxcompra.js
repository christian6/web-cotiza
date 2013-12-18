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
function valid () {
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
	var h = document.getElementById("alid");
	var cal = document.getElementById("cboal");

	var alid ="";
	if (h != null) {
		alid = h.value;
	}else if (cal != null) {
		alid = cal.options[cal.selectedIndex].value;
	}else{
		alert("Error en Codigo de almacen");
		return false;
	}
	var sts = false;

	// validando nro de guia
	var ng = document.getElementById("txtnrog");
	if (ng.value == "") {
		if (!confirm("El Número de Guia no se ha ingresado\r\n Desea continuar?")) {
			ng.focus();
			sts = false;
		}else{
			sts = true;
		}
	}else{
		sts = true;
	}
	// Validando numero de factura
	var nf = document.getElementById("txtnrof");
	if (nf.value == "") {
		if (!confirm("El Número de la Factura no se ha ingresado\r\n Desea continuar?")) {
			nf.focus();
			sts = false;
		}else{
			sts = true;
		}
	}else{
		sts = true;
	}
	// Validando motivo
	var mot = document.getElementById("txtmot");
	if (mot.value == "") {
		if (!confirm("No se ha ingresado el Motivo de Ingreso\r\n Desea continuar?")) {
			mot.focus();
			sts = false;
		}else{
			sts = true;
		}
	}else{
		sts = true;
	}
	// observacion
	var obser = document.getElementById("txtobser");
	if (obser.value == "") {
		if (!confirm("No ha ingresado una observacion\r\n Desea continuar?")) {
			obser.focus();
			sts = false;
		}else{
			sts = true;
		}
	}else{
		sts = true;
	}
	console.log(' Listo para guardar');
	console.log(sts);
	if (sts) {
		guardar();
	}else{
		return sts;
	}
}
function guardar () {
	console.log('Ingresamos a guardar!!!');
	// validando si todos los check del detalle estan marcados
	var ch = 0;
	var matid =  document.getElementsByName("matid");
	/*for (var i = 0; i < matid.length; i++) {
		if(matid[i].checked){
			ch++;
		}
	}
	if (ch < matid.length) {
		if (!confirm("No estan seleccionados todos los\r\n materiales para ser ingresados\r\n Desea Continuar de todas formas?")) {
			return;
		}
	}*/
	//validando las cantidades
	var c = document.getElementsByName("cants");
	var ca = 0;
	/*for (var i = 0; i < c.length; i++) {
		//alert(c[i].max +'  '+c[i].value);
		if(c[i].value < c[i].max){
			ca++;
		}
	}
	if (ca > 0) {
		if (!confirm("Algunas cantidades son distintas a las\r\nque se han pedido\r\n Desea continuar de todas formas?")) {
			return;
		}
	}*/
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
	/*if (ng.value == "") {
		if (!confirm("El Número de Guia no se ha ingresado\r\n Desea continuar?")) {
			ng.focus();
			return;
		}
	}*/
	// Validando numero de factura
	var nf = document.getElementById("txtnrof");
	/*if (nf.value == "") {
		if (!confirm("El Número de la Factura no se ha ingresado\r\n Desea continuar?")) {
			nf.focus();
			return;
		}
	}*/
	// Validando motivo
	var mot = document.getElementById("txtmot");
	/*if (mot.value == "") {
		if (!confirm("No se ha ingresado el Motivo de Ingreso\r\n Desea continuar?")) {
			mot.focus();
			return;
		}
	}*/
	// observacion
	var obser = document.getElementById("txtobser");
	/*if (obser.value == "") {
		if (!confirm("No ha ingresado una observacion\r\n Desea continuar?")) {
			obser.focus();
			return;
		}
	}*/
	if (confirm("Seguro(a) que desea generar la Nota de Ingreso?")) {
		console.log('Enviando datos a include');
		xmlhttp = peticion();
		xmlhttp.onreadystatechange=function()
		{
			console.log(xmlhttp.responseText);
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="hecho"){
					guardardet();
					
				}
			}
		} 
		var requestUrl = "";
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
  		console.log(requestUrl);
		xmlhttp.open("POST",requestUrl,true);
		xmlhttp.send();
	}
}

function guardardet(){
	// Recuperando los id de los materiales y las Cantidades
	console.log("Alistando detalle de para saved!");
	var matid =  document.getElementsByName("matid");
	var c = document.getElementsByName("cants");
	var pre = document.getElementsByName("precios");
	var h2 = document.getElementById("alid");
	var cal2 = document.getElementById("cboal");
	var ncom = document.getElementById("ncom");
	var rucpro = document.getElementById("txtrucpro");
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
			if(c[i].value < c[i].max){
				var cv = (c[i].max - c[i].value);
				requestUrl += "&matid="+encodeURIComponent(matid[i].id)+"&cantidad="+encodeURIComponent(c[i].value)+
				"&es="+encodeURIComponent("i")+"&cv="+encodeURIComponent(cv)+"&pre="+encodeURIComponent(pre[i].value)+
				"&alid="+encodeURIComponent(alid)+"&ncom="+encodeURIComponent(ncom.value)+"&rucp="+encodeURIComponent(rucpro.value);
			}else if(c[i].value == c[i].max){
				requestUrl += "&matid="+encodeURIComponent(matid[i].id)+"&cantidad="+encodeURIComponent(c[i].value)+
				"&es="+encodeURIComponent("c")+"&pre="+encodeURIComponent(pre[i].value)+"&alid="+encodeURIComponent(alid)+
				"&ncom="+encodeURIComponent(ncom.value)+"&rucp="+encodeURIComponent(rucpro.value);
			}
			xmlhttp = peticion();
			//alert(requestUrl);//eliminar
			xmlhttp.open("POST",requestUrl,true);
			xmlhttp.send();
		}
	}

	setTimeout(function() {mostrarnroing();}, 1000);
}
function mostrarnroing () {
	xmlhttp = peticion();
	var sts = "";
	var ncom = document.getElementById("ncom");
	xmlhttp.onreadystatechange=function () {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			sts = xmlhttp.responseText;
			sts = sts.trim();
			if (sts.length == 10) {
				document.getElementById("nronota").innerHTML = sts;
				next(3);
				document.getElementById("btnrecibirc").disabled = true;
			}else{
				alert("Se ha producido un Error!!!");
				return;
			}
		}
	}
	var requestUrl = "include/incnotaingreso.php?";
	requestUrl+="tra=condi"+"&ncom="+encodeURIComponent(ncom.value);
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
}

function printview () {
	var nron = document.getElementById("nronota").innerHTML;
	nron = nron.trim();
	window.open("../reports/almacen/pdf/rptnotaingreso.php?nro="+nron);
}
var win;
function report () {
	var nroi = document.getElementById("nronota").innerHTML;
	nroi = nroi.trim();
	var myLeft = (screen.width-600)/2;
	var myTop = (screen.height-700)/2;
	var caracteristicas="toolbar=0, location=0, directories=0, resizable=no, scrollbars=yes, height=600, width=600, top="+myTop+", left="+myLeft;
	win = window.open("forminspeccion.php?nro="+encodeURIComponent(nroi),"Reporte de Inspeccion",caracteristicas);
}

function peticion(){
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlhttp;
}