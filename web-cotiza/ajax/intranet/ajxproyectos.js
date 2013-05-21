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

function deleteproyecto(id)
{
	if (id!="") {
		xmlhttp = peticion();
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				if(xmlhttp.responseText == "completado"){
					location.href="proyectos.php";
				}
			}
		}
		var cod = id;
		var requestURL = "";
		requestURL = "includes/incproyectos.php?"+"t=b"+"&cod="+encodeURIComponent(cod);
		xmlhttp.open("POST",requestURL,true);
		xmlhttp.send();
	}
}

function sectores() {
	var cbo = document.getElementById("cbopro");
	var id = cbo.options[cbo.selectedIndex].value;
	if (id!="") {
		xmlhttp = peticion();
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("subpro").innerHTML = xmlhttp.responseText;
			}
		}
		var requestURL = "";
		requestURL = "includes/incproyectos.php?"+"t=s"+"&proid="+encodeURIComponent(id);
		xmlhttp.open("POST",requestURL,true);
		xmlhttp.send();
	}
}