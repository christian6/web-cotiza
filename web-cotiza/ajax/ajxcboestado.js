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
function estados()
{
	xmlhttp = peticion();
	var selectest=document.getElementById("cbogen2");
    var optionest=selectest.options[selectest.selectedIndex].value;
	xmlhttp.onreadystatechange=function()
	{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		document.getElementById("tbl").innerHTML=xmlhttp.responseText;
	}
	}
	var urlRequest = "";
	urlRequest = "includes/cboestado.php"+"?id="+encodeURIComponent(optionest);
	xmlhttp.open("POST",urlRequest,true);
	xmlhttp.send();
}
function mofest(id)
{
	var nom = document.getElementById(id).value;
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		if ("hecho" == xmlhttp.responseText){
			alert("Se Modifico Correctamente!");
			document.location.href='';
		}
	}
	}
	var urlRequest = "";
	urlRequest = "includes/incestmantenice.php"+"?id="+encodeURIComponent(id)+"&tip=m"+"&nom="+encodeURIComponent(nom);
	xmlhttp.open("POST",urlRequest,true);
	xmlhttp.send();
}
function delest(id)
{
	if (confirm("Seguro que Desea Eliminar?")) {
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		if ("hecho" == xmlhttp.responseText){
			alert("Se Elimino Correctamente!");
			document.location.reload();
		}
	}
	}
	var urlRequest = "";
	urlRequest = "includes/incestmantenice.php"+"?id="+encodeURIComponent(id)+"&tip=d";
	xmlhttp.open("POST",urlRequest,true);
	xmlhttp.send();
	}
}
function insest()
{
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		if ("hecho" == xmlhttp.responseText){
			alert("Se Guardo Correctamente!");
			document.location.reload();
		}
	}
	}
	var selectest=document.getElementById("cbogen");
    var optionest=selectest.options[selectest.selectedIndex].value;
    var txt = document.getElementById("txtesp").value;
    alert(optionest);
	var urlRequest = "";
	urlRequest = "includes/incestmantenice.php"+"?eid="+encodeURIComponent(optionest)+"&text="+encodeURIComponent(txt);
	xmlhttp.open("POST",urlRequest,true);
	xmlhttp.send();
}