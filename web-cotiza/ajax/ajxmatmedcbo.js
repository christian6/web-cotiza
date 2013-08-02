function showUser()
{
	var selectOrigin=document.getElementById("combobox");
    var optionselect=selectOrigin.options[selectOrigin.selectedIndex].value;
	if (optionselect=="")
		{
			document.getElementById("listado").innerHTML="";
			return;
		}  
	if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
	else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("listado").innerHTML=xmlhttp.responseText;
			}
		}
  		var requestUrl;
  		requestUrl = "includes/incmatmedcbo.php" + "?nom=" + encodeURIComponent(optionselect);
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
function dat(){
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		document.getElementById("data").innerHTML=xmlhttp.responseText;
	}
	}
	var selectNom=document.getElementById("combobox");
    var optionNom=selectNom.options[selectNom.selectedIndex].value;
    var selectMed=document.getElementById("matmed");
    var optionMed=selectMed.options[selectMed.selectedIndex].value;
  	var requestUrl;
  	requestUrl = "includes/inctotaldata.php"+"?nom="+encodeURIComponent(optionNom)+"&med="+encodeURIComponent(optionMed);
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
}
function grilla(code){
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function(){
	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		document.getElementById("grilla").innerHTML=xmlhttp.responseText;
	}
	}
	var requestUrl;
	if (code=="lista") {
		requestUrl = "includes/incgrilladata.php"+"?tip=lista";
	}else if(code == "add"){
		var textcod = document.getElementById("txtcod").value;
		var txtcant = document.getElementById("txtcant").value;
		requestUrl = "includes/incgrilladata.php"+"?tip=add&cod="+encodeURIComponent(textcod)+"&cant="+encodeURIComponent(txtcant);
	}else if(code=="all"){
		if (document.getElementById("chkdel").checked == true){
			if(confirm("Realmente Quiere Eliminar Todo!")){
				requestUrl = "includes/incgrilladata.php"+"?tip=all";
			}else{
			document.getElementById("chkdel").click();
			return;
			}
		}
	}else{
		if (confirm("Desea Eliminar "+code+"!")) {
			requestUrl = "includes/incgrilladata.php"+"?tip=del&cod="+encodeURIComponent(code);
		}else{
			return;
		}
	}
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
}
function save()
{
	xmlhttp = peticion();
	var nrocot = document.getElementById("txtnro").value;
	var dni = document.getElementById("txtdni").value;
	var nom = document.getElementById("txtpnom").value;
	var fec = document.getElementById("fecent").value;
	var selectest=document.getElementById("cboestado");
    var optionest=selectest.options[selectest.selectedIndex].value;
    var obser = document.getElementById("txtobser").value;
    var list = document.getElementById("lstpro");
    var lstpro = new Array();
    for (var i = 0; i < list.options.length; i++) {
    	lstpro[i] = list.options[i].value;
    }
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("contentfro").innerHTML=xmlhttp.responseText;
		}
	}
	var requestUrl;
    requestUrl = "includes/incsavecot.php"+"?nro="+encodeURIComponent(nrocot)+"&dni="+encodeURIComponent(dni)+"&nom="+encodeURIComponent(nom)+"&fec="+encodeURIComponent(fec)+"&est="+encodeURIComponent(optionest)+"&obser="+encodeURIComponent(obser)+"&pro="+encodeURIComponent(lstpro);
    xmlhttp.open("POST",requestUrl,true);
    xmlhttp.send();
}
