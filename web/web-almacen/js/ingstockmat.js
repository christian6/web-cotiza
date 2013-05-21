function viewtbl () {
	var fs = document.getElementById("fullscreen-icr");
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
		//alert(xmlhttp.responseText);
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			if(xmlhttp.responseText!=""){
				document.getElementById("viewtable").innerHTML = xmlhttp.responseText;
			}else{
				alert("Error al Listar.");
			}
			fs.style.display = "none";
		}
	}
  	var requestUrl = "";
  	requestUrl = "include/incingstockmat.php"+"?tra=tbl";
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
	fs.style.display = "block";
}
function agregarstock () {
	if (confirm("Realmente desea ingresar el stock pasado?\r\nEste proceso puede tardar varios minutos.")) {
		var fs = document.getElementById("fullscreen-icr");
		xmlhttp = peticion();
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				if(String(xmlhttp.responseText).length > 0){
					document.getElementById("dal").innerHTML = "Cantidad de materiales que ya existian en el periodo "+String(xmlhttp.responseText).substring(5,xmlhttp.responseText.length);
					document.getElementById("als").style.display = "block";
				}
				fs.style.display = "none";
			}
		}
		var requestUrl = "";
		requestUrl = "include/incingstockmat.php"+"?tra=addstock";
		xmlhttp.open("POST",requestUrl,true);
		xmlhttp.send();
		fs.style.display = "block";
	}
}
function fullperiodo () {
	if (confirm("Realmente desea ingresar la lista de materiales del periodo pasado?\r\nEste proceso puede tardar varios minutos.")) {
		var fs = document.getElementById("fullscreen-icr");
		xmlhttp = peticion();
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				if(xmlhttp.responseText.length > 0){
					document.getElementById("dal").innerHTML = "Cantidad de materiales que ya existian en el periodo "+xmlhttp.responseText.substring(5,xmlhttp.responseText.length);
					document.getElementById("als").style.display = "block";
				}
				fs.style.display = "none";
			}
		}
		var requestUrl = "";
		requestUrl = "include/incingstockmat.php"+"?tra=addlist";
		xmlhttp.open("POST",requestUrl,true);
		xmlhttp.send();
		fs.style.display = "block";
	}
}
function matlist() {
	if (confirm("Realmente desea ingresar la lista de materiales?\r\nEste proceso puede tardar varios minutos.")) {
		var fs = document.getElementById("fullscreen-icr");
		xmlhttp = peticion();
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				if(xmlhttp.responseText.length > 0){
					document.getElementById("dal").innerHTML = "Cantidad de materiales que ya existian en el periodo "+xmlhttp.responseText.substring(5,xmlhttp.responseText.length);
					document.getElementById("als").style.display = "block";
				}
				fs.style.display = "none";
			}
		}
		var cboal = document.getElementById("cboal");
		var op = cboal.options[cboal.selectedIndex].value;
		var stk = document.getElementById("txtstkm").value;
		var requestUrl = "";
		requestUrl = "include/incingstockmat.php"+"?tra=addmat"+"&alid="+op+"&stk="+stk;
		xmlhttp.open("POST",requestUrl,true);
		xmlhttp.send();
		mat('c');
		fs.style.display = "block";
	}
}
function mat(op) {
	if (op == 'o') {
		$("#prm").modal("show");
	}else if (op == 'c') {
		$("#prm").modal('hide');
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