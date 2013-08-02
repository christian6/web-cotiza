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
function deletepro(id)
{
	if (id!="") {
		xmlhttp = peticion();
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				if(xmlhttp.responseText == "completado"){
					location.href="proveedor.php";
				}
			}
		}
		var cod = id;
		var requestURL = "";
		requestURL = "includes/incproveedor.php?"+"t=b"+"&cod="+encodeURIComponent(cod);
		xmlhttp.open("POST",requestURL,true);
		xmlhttp.send();
	}
}
var winup;
function editarpro (ruc) {
	if (ruc != "") {
		var myLeft = (screen.width-800)/2;
		var myTop = (screen.height-700)/2;
		var caracteristicas="toolbar=0, location=0, directories=0, resizable=0, scrollbars=yes, height=600, width=800, top="+myTop+", left="+myLeft;
		winup = window.open(
		"http://190.41.246.91/web-cotiza/intranet/edit-proveedor?ruc="+encodeURIComponent(ruc),"Modificar Proveedor",caracteristicas);
	   	winup.onunload = function() {
	    	intvl = setInterval(testClosedProperty,100);
	 	}
	}
}
function testClosedProperty() {
  if (winup.closed) {
    if (intvl) clearInterval(intvl);
 		document.location.reload();
  }
}
function cbos(type)
{
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			if(xmlhttp.responseText.substr(0,2) == "de"){
				document.getElementById("dcbodepartamento").innerHTML = xmlhttp.responseText.substr(2);
			}else if(xmlhttp.responseText.substr(0,1) == "p"){
				document.getElementById("dcboprovincia").innerHTML = xmlhttp.responseText.substr(1);
			}else if(xmlhttp.responseText.substr(0,1) == "d"){
				document.getElementById("dcbodistrito").innerHTML = xmlhttp.responseText.substr(1);
			}
		}
	}
	var requestURL = "";
	var pais;
	var dep;
	var pro;
	var dis;
	var pa;
	var de;
	var pr;
	if (type=="de") {
		pais = document.getElementById("cbopais");
		pa = pais.options[pais.selectedIndex].value;
		requestURL = "includes/incproveedor.php"+"?t="+encodeURIComponent(type)+"&pa="+encodeURIComponent(pa)+"&cmd=cbo";
	}else if(type=="pro"){
		pais = document.getElementById("cbopais");
		pa = pais.options[pais.selectedIndex].value;
		dep = document.getElementById("cbodepartamento");
		de = dep.options[dep.selectedIndex].value;
		requestURL = "includes/incproveedor.php"+"?t="+encodeURIComponent(type)+"&pa="+encodeURIComponent(pa)+"&de="+encodeURIComponent(de)+"&cmd=cbo";
	}else if(type=="d"){
		pais = document.getElementById("cbopais");
		pa = pais.options[pais.selectedIndex].value;
		dep = document.getElementById("cbodepartamento");
		de = dep.options[dep.selectedIndex].value;
		pro = document.getElementById("cboprovincia");
		pr = pro.options[pro.selectedIndex].value;
		requestURL = "includes/incproveedor.php"+"?t="+encodeURIComponent(type)+"&pa="+encodeURIComponent(pa)+"&de="+encodeURIComponent(de)+"&pr="+encodeURIComponent(pr)+"&cmd=cbo";
	}
	xmlhttp.open("POST",requestURL,true);
	xmlhttp.send();
}
