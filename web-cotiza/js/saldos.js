function chekear()
{
	//alert("hola");
	var chks = document.getElementsByName("chkmat");
	var co = 0;
	for(var i=0;i<chks.length;i++){
		if (chks[i].checked) {
			co++;
		}
	}
	if(co!=0){
	var selectpro = document.getElementById("lstpro");
	var valor = selectpro.options[selectpro.selectedIndex].value;
	//alert(valor);
	var contenedor = document.getElementById("prov");
	var val = document.getElementById(valor);
	//alert(val);
	if(val == null){
	var ele = document.createElement('select');
	ele.id = valor;
	ele.multiple = "true";
	ele.name = "promat";
	//ele.onclick = optiona(valor);
	//ele.addEventListener("dblclick",optiona(valor),true);
	ele.setAttribute("ondblclick","optiona(this)");
	contenedor.appendChild(ele);
	var gop = document.createElement('optgroup');
	gop.label = selectpro.options[selectpro.selectedIndex].text;
	ele.appendChild(gop);
	}else{
	var ele = document.getElementById(valor);
	}

	for (var i = 0; i < chks.length; i++) {
		if (chks[i].checked) {
			var newopt = document.createElement('option');
			newopt.value = chks[i].value;
			newopt.innerHTML = chks[i].value;
			ele.appendChild(newopt);
		}
	}
	}else{
		alert("Seleccione por lo menos un Material!.");
		return;
	}
}
function readselect()
{
	var slt = document.getElementById("10704928501");
	//for (var i = 0; i < slt.options.length; i++) {
	var na = document.getElementsByName("promat");
	for (var i = 0; i < na.length; i++) {
		alert(na[i].options[1].value);
	}
	//alert(slt.options.length);	
	//}
}
function optiona(id)
{
	if(confirm("Esta Seguro que desea Eliminar?")){
	var op = id.options[id.selectedIndex];
	op.parentNode.removeChild(op);
	}else{
		return;
	}
}
function fullcheck(fchk)
{
	var chks = document.getElementsByName("chkmat");
	if (fchk.checked) {
		for (var i = 0; i < chks.length; i++) {
			chks[i].checked = true;
		}
	}else{
		for (var i = 0; i < chks.length; i++) {
			chks[i].checked = false;
		}
	}
}
function clearchk(){
	var chks = document.getElementsByName("chkmat");
	for (var i = 0; i < chks.length; i++) {
			chks[i].checked = false;
		}
	var chk = document.getElementById("tdo");
	chk.checked = false;
}
function delpro(){
	if (confirm("Desea Quitar al Proveedor de la lista?")) {
	var cbopro = document.getElementById("lstpro");
	var spro = document.getElementById(cbopro.options[cbopro.selectedIndex].value);
	spro.parentNode.removeChild(spro);
	}else{
		return;
	}
}
function save(){
	//alert("inicio");
	var nrocot = document.getElementById("txtnro").value;
	var dni = document.getElementById("txtdni").value;
	var nom = document.getElementById("txtpnom").value;
	var fec = document.getElementById("fecent").value;
	var selectest=document.getElementById("cboestado");
    var optionest=selectest.options[selectest.selectedIndex].value;
    var obser = document.getElementById("txtobser").value;
	var mat = document.getElementsByName("promat");
	var ruc = new Array();
	for (var i = 0; i < mat.length; i++) {
		ruc[i] = mat[i].id;
	}
	//alert("Array Ruc");
	var matarry = new Array(); 	
	for (var i = 0; i < mat.length; i++) {
		matarry[i] = new Array(mat[i].options.length);
		for (var j = 0; j < mat[i].options.length; j++) {
			matarry[i][j] = mat[i].options[j].value;
		}
	}
	var matjson = JSON.encode(matarry);
	var requestUrl;
    requestUrl = "?nro="+encodeURIComponent(nrocot)+"&nroc="+encodeURIComponent(nrocot)+"&dni="+encodeURIComponent(dni)+"&nom="+encodeURIComponent(nom)+"&fec="+encodeURIComponent(fec)+"&est="+encodeURIComponent(optionest)+"&obser="+encodeURIComponent(obser)+"&ruc="+encodeURIComponent(ruc)+"&mat="+matjson;
    var xmlhttp = new Request
    ({
    	url: "includes/incsave.php",
    	data: requestUrl,
    	onSuccess: function(textrespuesta){
    		$("contentfro").set("html",textrespuesta);
    	},
    	onFailure: function(){
    		$("contentfro").set("html","Fallo la Conexion Ajax");
    	}
    });
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

function cancelcotexc()
{
	if (confirm("Seguro que Desea Cancelar la Cotización?")) {
		xmlhttp = peticion();
		xmlhttp.onreadystatechange=function()
		{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			if (xmlhttp.responseText=='ok') {
				location.href = 'menu-int.php';
			}else{
				alert("Termino con Errores!!!.");
				return;
			}
		}
		}
  		var requestUrl;
  		requestUrl = "includes/incdeletecotexc.php"+"?or=ok";
		xmlhttp.open("POST",requestUrl,true);
		xmlhttp.send();
	}else{
		return;
	}
}