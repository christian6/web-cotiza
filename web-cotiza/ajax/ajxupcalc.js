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
function calc(){
	var list = document.getElementById("cboprosel");
    var lstpro = new Array();
    for (var i = 0; i < list.options.length; i++) {
    	lstpro[i] = list.options[i].value;
    }
    if (lstpro.length==0) {
    	alert("Por los menos debe Ingresar un Proyecto!");
    	return;
    }
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		document.getElementById("upcalc").innerHTML=xmlhttp.responseText;
	}
	}
  	var requestUrl;
  	requestUrl = "includes/incupcalc.php"+"?lstcod="+encodeURIComponent(lstpro);
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
}