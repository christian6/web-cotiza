function radios () {
	var c = document.getElementById("rbtnc");
	var d = document.getElementById("rbtnd");
	if (c.checked) {
		document.getElementById('txtcod').disabled = false;
		document.getElementById('txtdes').disabled = true;
	}else if(d.checked){
		document.getElementById('txtcod').disabled = true;
		document.getElementById('txtdes').disabled = false;
	}
}