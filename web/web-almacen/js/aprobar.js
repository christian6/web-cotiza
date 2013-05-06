function aprobar(nrope) {
	//var nrope = document.getElementById("lblnro").innerText;
	if (confirm("Esta Seguro de Aprobar Pedido "+nrope+"?")) {
		xmlhttp = peticion();
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="hecho"){
					location.href = 'aprobarpedido';
				}
			}
		}
  		var requestUrl;
  		requestUrl = "include/incaprobar.php" + "?tipo=a"+"&nro="+encodeURIComponent(nrope);
		xmlhttp.open("POST",requestUrl,true);
		xmlhttp.send();
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

function anular() {
	var nrope = document.getElementById("lblnro").innerText;
	if (confirm("Esta Seguro de Anular el Pedido "+nrope+"?")) {
		xmlhttp = peticion();
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="hecho"){
					location.href = 'aprobarpedido';
				}
			}
		}
  		var requestUrl;
  		requestUrl = "include/incaprobar.php" + "?tipo=n"+"&nro="+encodeURIComponent(nrope);
		xmlhttp.open("POST",requestUrl,true);
		xmlhttp.send();
	}
}