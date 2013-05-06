function chkall() {
	var btnall = document.getElementById("rbtnall");
	var btnclr = document.getElementById("rbtnclear");
	var chk = document.getElementsByName("matid");
	if (btnall.checked) {
		for (var i = 0; i < chk.length; i++) {
			chk[i].checked=true;
		}
	} else if(btnclr.checked){
		for (var i = 0; i < chk.length; i++) {
			chk[i].checked=false;
		}
	}
}
function  os(oc) {
	var chk = document.getElementsByName("matid");
	var t = 0;
	for (var i = 0; i < chk.length; i++) {
			if(chk[i].checked==true){
				t++;
			}
	}
	if(t!=0){
		var f = document.getElementById("fullscreem");
		var frm = document.getElementById("frmos");
		if (oc == "o") {
			f.style.display = 'block';
			frm.style.display = 'block';
			var cbo = document.getElementById("cboal");
			var op = cbo.options[cbo.selectedIndex].text;
			document.getElementById("txtalnom").value = op;
		} else if(oc == "c"){
			f.style.display = 'none';
			frm.style.display = 'none';
		}
	}else{
		alert("No se ha Seleccionado Ningun Material!");
		return;
	}
}

function save () {
	if (confirm("Seguro(a) que desea Generar la Orden de Suministro?")) {
		var alid = document.getElementById("txtalid").value;
		var empdni = document.getElementById("txtempid").value;
		var fec = document.getElementById("txtfecreq").value;
		var chk = document.getElementsByName("matid");
		var cbo = document.getElementById("cboes");
		var op = cbo.options[cbo.selectedIndex].value;
		var ids = new Array();
		var t = 0;
		for (var i = 0; i < chk.length; i++) {
			if (chk[i].checked) {
			ids[t] = chk[i].id;
			t++;
			}
		}
		if (t!=0) {
			xmlhttp = peticion();
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					if (xmlhttp.responseText != ""){
						document.getElementById("nrosu").innerHTML = "NRO "+xmlhttp.responseText;
						document.getElementById("frmos").style.display = 'none';
						document.getElementById("fullscreem").style.display = 'block';
						document.getElementById("generator").style.display = 'block';
					}
				}
			}
			var requestUrl = "";
			requestUrl = "include/incexistenciaallpedido.php" + "?"+"&alid="+encodeURIComponent(alid)+"&empid="+encodeURIComponent(empdni)+"&fec="+encodeURIComponent(fec)+"&es="+encodeURIComponent(op)+"&matid="+encodeURIComponent(ids);
			xmlhttp.open("POST",requestUrl,true);
			xmlhttp.send();
		}else{
			alert("No hay materiales para la orden de Suministro!.");
			return;
		}
	} else{
		return;
	}
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