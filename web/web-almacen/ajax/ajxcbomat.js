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

function showmed()
{
	var selectOrigin=document.getElementById("combobox");
    var optionselect=selectOrigin.options[selectOrigin.selectedIndex].value;

	xmlhttp = peticion();

	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("medida").innerHTML=xmlhttp.responseText;
		}
	}
  	var requestUrl = "";
  	requestUrl = "include/incmatmed.php"+"?nom="+encodeURIComponent(optionselect)+"&tipo="+encodeURIComponent('nom');
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
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
  	var requestUrl = "";
  	requestUrl = "include/incmatmed.php"+"?nom="+encodeURIComponent(optionNom)+"&med="+encodeURIComponent(optionMed)+"&tipo="+encodeURIComponent('med');
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
}

function sub()
{
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("sub").innerHTML=xmlhttp.responseText;
		}
	}
	var cbo = document.getElementById("cbopro");
	var pro = cbo.options[cbo.selectedIndex].value;
	var requestUrl = "";
	requestUrl = "include/incmatmed.php"+"?tipo="+encodeURIComponent("sub")+"&pro="+encodeURIComponent(pro);
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
}

function subsec () {
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("sec").innerHTML=xmlhttp.responseText;
		}
	}
	var cbop = document.getElementById("cbopro");
	var pro = cbop.options[cbop.selectedIndex].value;
	var cbos = document.getElementById("cbosub");
	var sub = cbos.options[cbos.selectedIndex].value;
	var requestUrl = "";
	requestUrl = "include/incmatmed.php"+"?tipo="+encodeURIComponent("subsec")+"&pro="+encodeURIComponent(pro)+"&sub="+encodeURIComponent(sub);
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
}

function grilla(code)
{
	xmlhttp = peticion();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("detgrilla").innerHTML=xmlhttp.responseText;
		}
	}
	var requestUrl = "";

	if (code=="lista") {
		requestUrl = "include/incmatmed.php"+"?tip=lista"+"&tipo="+encodeURIComponent('grilla');
	}else if(code == "add"){
		var textcod = document.getElementById("txtcod").value;
		var txtcant = document.getElementById("txtcant").value;
		if (txtcant != "" || txtcant != 0) {
			requestUrl = "include/incmatmed.php"+"?tip=add&cod="+encodeURIComponent(textcod)+"&cant="+encodeURIComponent(txtcant)+"&tipo="+encodeURIComponent('grilla');
		}else{
			alert("No ha ingresado una cantidad");
			return;
		}
	}else if(code=="all"){
		if (document.getElementById("chkdel").checked == true){
			if(confirm("Realmente Quiere Eliminar Todo!")){
				requestUrl = "include/incmatmed.php"+"?tip=all"+"&tipo="+encodeURIComponent('grilla');
			}else{
			document.getElementById("chkdel").click();
			return;
			}
		}
	}else{
		if (confirm("Desea Eliminar "+code+"!")) {
			requestUrl = "include/incmatmed.php"+"?tip=del&cod="+encodeURIComponent(code)+"&tipo="+encodeURIComponent('grilla');
		}else{
			return;
		}
	}

	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
}
function mostrar (status) {
	var full = document.getElementById("fullscreen");
	var form = document.getElementById("Form");
	if (status == true) {
		full.style.display = 'block';
		form.style.display = 'block';
	}else if (status == false) {
		full.style.display = 'none';
		form.style.display = 'none';
	}
}

function savepedido () {
	var cbop = document.getElementById("cbopro");
	var cbos = document.getElementById("cbosub");
	var cboc = document.getElementById("cbosec");
	var cboa = document.getElementById("cboal");
	var op = cbop.options[cbop.selectedIndex].value;
	if (cbos != null)var os = cbos.options[cbos.selectedIndex].value;else os = "";
	if (cboc != null)var oc = cboc.options[cboc.selectedIndex].value;else oc = "";
	var oa = cboal.options[cboal.selectedIndex].value;
	var ent = document.getElementById("txtfecha").value;
	var obs = document.getElementById("txtobser").value;
	xmlhttp.onreadystatechange=function()
	{
			if (xmlhttp.status==200 && xmlhttp.readyState==4) {
				if (xmlhttp.responseText != "") {
					location.href = "include/incpostpedido.php"+"?num="+xmlhttp.responseText;
				}
			}
	}
	var requestUrl = "";
	requestUrl = "include/incmatmed.php"+"?tipo=save"+"&pro="+encodeURIComponent(op)+"&sub="+encodeURIComponent(os)+"&sec="+encodeURIComponent(oc)+"&fec="+encodeURIComponent(ent)+"&obs="+encodeURIComponent(obs)+"&al="+encodeURIComponent(oa);
	xmlhttp.open("POST",requestUrl,true);
	xmlhttp.send();
}