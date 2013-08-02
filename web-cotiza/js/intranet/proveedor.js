function status(ty){
	var txtruc = document.getElementById("txtruc");
	var txt = document.getElementById("txtrz");
	var txtdir = document.getElementById("txtdir");
	var cbopais = document.getElementById("cbopais");
	var txtt = document.getElementById("txttel");
	var cboorigen = document.getElementById("cboorigen");
	var cbotipo = document.getElementById("cbotipo");
	var cboest = document.getElementById("cboest");
	var btn = document.getElementById("btnsa");
	if (ty == "f") {
		txtruc.disabled = true;
		txt.disabled = true;
		txtdir.disabled = true;
		cbopais.disabled = true;
		txtt.disabled = true;
		cbotipo.disabled = true;
		cboorigen.disabled = true;
		cboest.disabled = true;
		btn.disabled = true;
	}else if(ty == "t"){
		txtruc.disabled = false;
		txt.disabled = false;
		txtdir.disabled = false;
		cbopais.disabled = false;
		txtt.disabled = false;
		cbotipo.disabled = false;
		cboorigen.disabled = false;
		cboest.disabled = false;
		btn.disabled = false;
	}
}