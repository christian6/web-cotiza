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
function updateprice(iditem)
{
	if (iditem.value != 0) {
		xmlhttp = peticion();
		var matid = iditem.id;
		var precio = iditem.value;
		var ruc = document.getElementById("ruc").value;
		var nro = document.getElementById("nro").value;
		/*xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
			}
		}*/
		var requestURL;
		requestURL = "includes/incsave.php"+"?matid="+encodeURIComponent(matid)+"&price="+encodeURIComponent(precio)+"&ruc="+encodeURIComponent(ruc)+"&nro="+encodeURIComponent(nro);
		xmlhttp.open("POST",requestURL,true);
		xmlhttp.send();
	}
}