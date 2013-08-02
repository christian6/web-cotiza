function status(ty){
	var txt = document.getElementById("txtmo");
	var txts = document.getElementById("txtsim");
	var btn = document.getElementById("btnsa");
	if (ty == "f") {
		txt.disabled = true;
		txts.disabled = true;
		btn.disabled = true;
	}else if(ty == "t"){
		txt.disabled = false;
		txts.disabled = false;
		btn.disabled = false;
	}
}