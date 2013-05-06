function init(){
	location.href = "menu.php";
}
function validar()
{
	var user = document.getElementById('txtuser').value;
	var pss = document.getElementById('txtpss').value;
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		var retorno = xmlhttp.responseText;
		if (retorno == "access") {
			location.href="menu.php";
		}else if(retorno == "restring"){
			document.getElementById('err').innerHTML = "Usuario o Contrasena son Incorrectos!";
		}
	}
	}
  	var requestUrl;
  	requestUrl = "includes/incverify.php"+"?nom="+encodeURIComponent(user)+"&pwd="+encodeURIComponent(pss);
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
}
function destroy(){
	xmlhttp = peticion();
	xmlhttp.onreadystatechange = function(){
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var response = xmlhttp.responseText;
			if (response=="destroy") {
				location.href = "logout.html";
			}
		}
	}
	var requestUrl;
	requestUrl = "includes/destroy.php";
	xmlhttp.open("POST",requestUrl,true);
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
function vista(){
	location.href="menu.php";
}
