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
function updatemoneda(id)
{
	if (id!="") {
		xmlhttp = peticion();
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				if(xmlhttp.responseText == "completado"){
					location.href="moneda.php";
				}
			}
		}
		var cod = id;
		var nom = document.getElementById("n"+id).value;
		var sim = document.getElementById("s"+id).value;
		var cbo = document.getElementById("cbo"+id);
		var opt = cbo.options[cbo.selectedIndex].value;
		var requestURL = "";
		requestURL = "includes/incmoneda.php"+"?t=u"+"&t=u"+"&cod="+encodeURIComponent(cod)+"&nom="+encodeURIComponent(nom)+"&sim="+encodeURIComponent(sim)+"&est="+encodeURIComponent(opt);
		xmlhttp.open("POST",requestURL,true);
		xmlhttp.send();
	}
}
function deletemoneda(id)
{
	if (id!="") {
		xmlhttp = peticion();
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				if(xmlhttp.responseText == "completado"){
					location.href="moneda.php";
				}
			}
		}
		var cod = id;
		var requestURL = "";
		requestURL = "includes/incmoneda.php?"+"t=d"+"&t=d"+"&cod="+encodeURIComponent(cod);
		xmlhttp.open("POST",requestURL,true);
		xmlhttp.send();
	}
}