function newnrocompra()
{
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
		alert(xmlhttp.responseText);
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		document.getElementById("nrocom").innerHTML=xmlhttp.responseText;
	}
	}
	var urlrequest = "";
	urlrequest = "includes/incnewcompra.php"+"?t=n";
	xmlhttp.open("POST",urlrequest,true);
	xmlhttp.send();
}
function contacto(ruc)
{
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
		
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("nrocom").value=xmlhttp.responseText.substr(1,10);
			document.getElementById("txtcont").value=xmlhttp.responseText.substr(11);
		}
	}
	var nro = document.getElementById("hnro").value;
	var urlrequest = "";
	urlrequest = "includes/inccontacto.php"+"?t=c"+"&nro="+encodeURIComponent(nro)+"&ruc="+encodeURIComponent(ruc);
	xmlhttp.open("POST",urlrequest,true);
	xmlhttp.send();
	
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