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

function keygen()
{
	var nro = document.getElementById('nrocot').value;
	var ruc = document.getElementById('rucpro').value;
	var key = document.getElementById('tagen').value;
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		var men = xmlhttp.responseText;
		if (men=="passed") {
			location.href = "solcotizacion.php"+"?nro="+encodeURIComponent(nro)+"&ruc="+encodeURIComponent(ruc);
		}else{
			document.getElementById('msg').innerHTML = men;
		}
	}
	}
  	var requestUrl;
  	requestUrl = "includes/inckey.php"+"?nro="+encodeURIComponent(nro)+"&ruc="+encodeURIComponent(ruc)+"&key="+encodeURIComponent(key);
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
}

