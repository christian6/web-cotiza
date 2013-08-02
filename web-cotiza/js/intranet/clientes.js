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
function status(ty){
	var txtruc = document.getElementById("txtruc");
	var cbopais = document.getElementById("cbopais");
	var txttel = document.getElementById("txttel");
	var txtrz = document.getElementById("txtrz");
	var cboest = document.getElementById("cboest");
	var txtcont = document.getElementById("txtcont");
	var txtabre = document.getElementById("txtabre");
	var txtdir = document.getElementById("txtdir");
	var btn = document.getElementById("btnsa");
	var txtobser = document.getElementById("txtobser");

	if (ty == "f") {
		txtruc.disabled = true;
		cbopais.disabled = true;
		txttel.disabled = true;
		txtrz.disabled = true;
		cboest.disabled = true;
		txtcont.disabled = true;
		txtabre.disabled = true;
		txtdir.disabled = true;
		btn.disabled = true;
	}else if(ty == "t"){
		txtruc.disabled = false;
		cbopais.disabled = false;
		txttel.disabled = false;
		txtrz.disabled = false;
		cboest.disabled = false;
		txtcont.disabled = false;
		txtabre.disabled = false;
		txtdir.disabled = false;
		btn.disabled = false;
	}
}
function refresh () {
	location.href='';
}
function deletecliente (ruc) {
	if (ruc!="") {
		if (confirm("Realmente Desea Eliminar a "+ruc)) {
			xmlhttp = peticion();
			var ae = document.getElementById("msg-e");
			var as = document.getElementById("msg-s");
			xmlhttp.onreadystatechange=function()
			{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				if(xmlhttp.responseText == "complete"){
					as.style.display = 'block';
					setTimeout('refresh()',5000);
				}else{
					ae.style.display = 'block';
				}
			}
			}
			var urlRequest = "";
			urlRequest = "includes/inccliente.php"+"?tra=d&ruc="+encodeURIComponent(ruc);
			xmlhttp.open("POST",urlRequest,true);
			xmlhttp.send();
		}else{
			return;
		}
	}
}
var winup;
function updatecliente (ruc) {
	if (ruc != "") {
		var myLeft = (screen.width-800)/2;
		var myTop = (screen.height-700)/2;
		var caracteristicas="toolbar=0, location=0, directories=0, resizable=0, scrollbars=yes, height=600, width=800, top="+myTop+", left="+myLeft;
		winup = window.open(
		"http://190.41.246.91/web-cotiza/intranet/edit-cliente?ruc="+encodeURIComponent(ruc),"Modificar Cliente",caracteristicas);
   	winup.onunload = function() {
    	intvl = setInterval(testClosedProperty,100);
 	}
}
}

function testClosedProperty() {
  if (winup.closed) {
    if (intvl) clearInterval(intvl);
 		refresh();
  }
}