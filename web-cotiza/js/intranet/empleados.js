function status(ty){
	var txtdni = document.getElementById("txtdni");
	var txtnom = document.getElementById("txtnom");
	var txtape = document.getElementById("txtape");
	var txtfnc = document.getElementById("txtfnc");
	var cbopais = document.getElementById("cbopais");
	var txtdir = document.getElementById("txtdir");
	var txttel = document.getElementById("txttel");
	var cbocar = document.getElementById("cbocar");
	var cboest = document.getElementById("cboest");
	var btn = document.getElementById("btnsa");
	if (ty == "f") {
		txtdni.disabled = true;
		txtnom.disabled = true;
		txtape.disabled = true;
		txtfnc.disabled = true;
		cbopais.disabled = true;
		txttel.disabled = true;
		txtdir.disabled = true;
		cbocar.disabled = true;
		cboest.disabled = true;
		btn.disabled = true;
	}else if(ty == "t"){
		txtdni.disabled = false;
		txtnom.disabled = false;
		txtape.disabled = false;
		txtfnc.disabled = false;
		cbopais.disabled = false;
		txttel.disabled = false;
		txtdir.disabled = false;
		cbocar.disabled = false;
		cboest.disabled = false;
		btn.disabled = false;
	}
}