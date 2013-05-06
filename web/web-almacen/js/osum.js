$(function() {
    $( ".dropdown-toggle" ).dropdown();
    $("#txtfec").datepicker({ minDate: "0" , maxDate: "+2M +10D" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd"});
});
function view(id,nom,med) {
	document.getElementById("txtid").value = id;
	document.getElementById("txtnom").value = nom;
	document.getElementById("txtmed").value = med;
	$("#myModal").modal('show');
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
function tmpsum () {
	xmlhttp = peticion();
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState ==4 && xmlhttp.status == 200) {
			if(xmlhttp.responseText == "hecho"){
				$("#myModal").modal('hide');
			}
		}
	}
	var id = document.getElementById("txtid").value;
	var can = document.getElementById("txtcant").value;
	var requestUrl = '';
	requestUrl = "include/incsum.php"+"?tmp=s"+"&dni="+encodeURIComponent(id)+"&matid="+encodeURIComponent(id)+"&cant="+encodeURIComponent(can);
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
}
function viewtbl () {
	xmlhttp = peticion();
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState ==4 && xmlhttp.status == 200) {
			if(xmlhttp.responseText != ""){
				document.getElementById("tbl").innerHTML = xmlhttp.responseText;
				$("#modeltbl").modal('show');
			}else{
				alert("Error al listar!!!");
			}
		}
	}
	var requestUrl = '';
	requestUrl = "include/incsum.php"+"?tmp=l";
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
}
function toclose (r) {
	if (r == "s") {
		$(".alert").alert('close');
	}else if(r == "n"){
		xmlhttp = peticion();
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState ==4 && xmlhttp.status == 200) {
				if(xmlhttp.responseText == "hecho"){
					document.getElementById("tbl").innerHTML = xmlhttp.responseText;
					$(".alert").alert('close');
				}else{
					alert("Error!!!");
				}
			}
		}
		var requestUrl = '';
		requestUrl = "include/incsum.php"+"?tmp=d";
		xmlhttp.open("POST",requestUrl,true);
		xmlhttp.send();
	}
}
function openos () {
	$("#modelos").modal('show');
}
function genos () {
	var fec = document.getElementById("txtfec");
	if (fec.value == "") {
		alert("La Fecha esta Vacia!, Ingrese la Fecha de Requerida");
		fec.focus();
		return;
	}
	if (confirm("Desea Generar Orden de Suministro!")) {
		xmlhttp = peticion();
		xmlhttp.onreadystatechange = function () {
			alert(xmlhttp.responseText);
			if (xmlhttp.readyState ==4 && xmlhttp.status == 200) {
				if(xmlhttp.responseText == "hecho"){
					location.href = '';
				}else{
					alert("Error!!!");
				}
			}
		}
		var cboal = document.getElementById("cboalos");
		var op = cboal.options[cboal.selectedIndex].value;
		var dni = document.getElementById("txtdni").value;
		var requestUrl = '';
		requestUrl = "include/incsum.php"+"?tmp=g"+"&cboal="+encodeURIComponent(op)+"&dni="+encodeURIComponent(dni)+"&fecr="+encodeURIComponent(fec.value);
		xmlhttp.open("POST",requestUrl,true);
		xmlhttp.send();
	}else{
		return;
	}
}
function deldet () {
	var mids = document.getElementsByName("matids");
	var chk = 0;
	var ids = new Array();
	for (var i = 0; i < mids.length; i++) {
		if (mids[i].checked){
			ids[chk] = mids[i].id;
			chk++;
		}
	}
	if (chk > 0) {
		if (confirm("Desea Eliminar los materiales seleccionados?")) {
			xmlhttp = peticion();
			xmlhttp.onreadystatechange = function(){
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					if(xmlhttp.responseText == "hecho"){
						$("#modeltbl").modal('hide');
					}else{
						alert("Error !!!");
					}
				}
			}
			var requestUrl = "";
			requestUrl = "include/incsum.php"+"?tmp=dd"+"&mids="+encodeURIComponent(ids);
			xmlhttp.open("POST",requestUrl,true);
			xmlhttp.send();
		}
	}else{
		alert("Seleccione por lo menos un material!!");
	}
}