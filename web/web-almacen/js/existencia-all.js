var windowObjectReference;

function openRequestedPopup() {

	var myLeft = (screen.width-800)/2;
    var myTop = (screen.height-700)/2;

	var caracteristicas="toolbar=0, location=0, directories=0, resizable=0, scrollbars=yes, height=600, width=800, top="+myTop+", left="+myLeft;

	var alid = document.getElementById("txtalid").value;
	var empdni = document.getElementById("txtempid").value;
	var fec = document.getElementById("txtfecreq").value;
	var chk = document.getElementsByName("matid");
	var ids = new Array();
	var t = 0;
	for (var i = 0; i < chk.length; i++) {
		if (chk[i].checked) {
			ids[t] = chk[i].id;
			t++;
		}
	}
  	windowObjectReference = window.open(
    "http://190.41.246.91/web/web-almacen/include/incsaveosum?matid="+encodeURIComponent(ids)+"&alid="+encodeURIComponent(alid)+"&empid="+encodeURIComponent(empdni)+"&fec="+encodeURIComponent(fec),
    "DescriptiveWindowName",caracteristicas);

   	windowObjectReference.onunload = function() {
    	intvl = setInterval(testClosedProperty,100);
 	}
}

function testClosedProperty() {
  if (windowObjectReference.closed) {
    if (intvl) clearInterval(intvl);
    	document.getElementById("txtfecreq").disabled = true;
    	document.getElementById("btncancelar").disabled = true;
    	document.getElementById("btnnext").disabled = true;
    	document.getElementById("btnsalir").style.display = 'inline';
  }
}

function valfec (val) {
	if (val.length != 0) {
		document.getElementById("btnnext").disabled = false;
	} else{
		return;
	}
}
