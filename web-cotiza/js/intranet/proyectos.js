function status(ty){
	var txtpr = document.getElementById("txtpr");
	var txtfec = document.getElementById("txtfec");
	var txtdir = document.getElementById("txtdir");
	var cbopais = document.getElementById("cbopais");
	var cboest = document.getElementById("cboest");
	var cbocli = document.getElementById("cbocli");
	var btn = document.getElementById("btnsa");
	var txtobser = document.getElementById("txtobser");
	
	if (ty == "f") {
		txtpr.disabled = true;
		txtfec.disabled = true;
		txtdir.disabled = true;
		cbopais.disabled = true;
		cbocli.disabled = true;
		txtobser.disabled = true;
		cboest.disabled = true;
		btn.disabled = true;
	}else if(ty == "t"){
		txtpr.disabled = false;
		txtfec.disabled = false;
		txtdir.disabled = false;
		cbocli.disabled = false;
		cbopais.disabled = false;
		txtobser.disabled = false;
		cboest.disabled = false;
		btn.disabled = false;
	}
}
function estado(obj)
{
	var cbopro = document.getElementById("cbopro");
	var cboest = document.getElementById("cboest");
	var btnsa = document.getElementById("btnsa");
	if(obj == "f"){
		cbopro.disabled = true;
		cboest.disabled = true;
		btnsa.disabled=true;
	}else if(obj == "t"){
		cbopro.disabled = false;
		cboest.disabled = false;
		btnsa.disabled=false;
	}
}
function refresh () {
	location.href='';
}
var winup
function updateproyecto (cod) {
	if (cod != "") {
		var myLeft = (screen.width-800)/2;
		var myTop = (screen.height-700)/2;
		var caracteristicas="toolbar=0, location=0, directories=0, resizable=0, scrollbars=yes, height=600, width=800, top="+myTop+", left="+myLeft;
		winup = window.open(
		"http://190.41.246.91/web-cotiza/intranet/edit-proyecto?cod="+encodeURIComponent(cod),"Modificar Proyecto",caracteristicas);
   	winup.onunload = function() {
    	intvl = setInterval(testClosedProperty,100);
 	}
}
}
function testClosedProperty() {
  if (winup.closed) {
    if (intvl) clearInterval(intvl);
 		refresh();
  }
}
function updatesubpro (cod,sub) {
	if (cod !="" && sub != "") {
		var myLeft = (screen.width-800)/2;
		var myTop = (screen.height-700)/2;
		var caracteristicas="toolbar=0, location=0, directories=0, resizable=0, scrollbars=yes, height=400, width=800, top="+myTop+", left="+myLeft;
		winup = window.open(
		"http://190.41.246.91/web-cotiza/intranet/edit-subproyecto?cod="+encodeURIComponent(cod)+"&sub="+encodeURIComponent(sub),"Modificar Sub-Proyecto",caracteristicas);
	   	winup.onunload = function() {
	    	intvl = setInterval(testClosedProperty,100);
	 	}
	}
}
function updatesector (cod,sub,nrp) {
	if (cod !="" && sub != "") {
		var myLeft = (screen.width-800)/2;
		var myTop = (screen.height-700)/2;
		var caracteristicas="toolbar=0, location=0, directories=0, resizable=0, scrollbars=yes, height=400, width=800, top="+myTop+", left="+myLeft;
		winup = window.open(
		"http://190.41.246.91/web-cotiza/intranet/edit-sector?cod="+encodeURIComponent(cod)+"&sub="+encodeURIComponent(sub)+"&nrp="+encodeURIComponent(nrp),"Modificar Sub-Proyecto",caracteristicas);
	   	winup.onunload = function() {
	    	intvl = setInterval(testClosedProperty,100);
	 	}
	}
}