function actupre(iditem)
{
	var arp = document.getElementsByName(iditem);
	var ltot = document.getElementById("ruc"+iditem);
	var tot = 0;
	for (var i = 0; i < arp.length; i++) {
		tot += parseFloat(arp[i].value);
	}
	ltot.innerHTML = tot.toFixed(2);
}
function listamat(ruc)
{
	//newnrocompra();
	var prin = document.getElementById("compra");
	var mat = document.getElementsByName(ruc);
	var div = document.getElementById("dcom");
	var sp = document.createElement("BR");
	var l = document.getElementById("ruccom");
	l.value = ruc;
	div.appendChild(sp);

	for (var i = 0; i < mat.length; i++) {
		var chk = document.createElement("input");
		chk.type = 'checkbox';
		chk.id = mat[i].id;
		chk.value = mat[i].id;
		chk.name = 'chkc';
		div.appendChild(chk);
		var label = document.createElement("label");
		label.innerHTML = mat[i].id;
		label.for = 'matid';
		label.style.marginLeft = '10px';
		div.appendChild(label);
		var txt = document.createElement("input");
		txt.type = 'number';
		txt.id = mat[i].id;
		txt.name = 'precio'
		txt.step = '0.01';
		txt.min = '0';
		txt.value = mat[i].value;
		txt.style.textAlign = 'right';
		txt.style.marginLeft = '10px';
		div.appendChild(txt);
		var sp2 = document.createElement("BR");
		div.appendChild(sp2);
	}
	prin.style.display = 'block';
	contacto(ruc);
}
function chkfull(itemid)
{
	var chk = document.getElementsByName("chkc");
	if (itemid.checked) {
		for (var i = 0; i < chk.length; i++) {
			chk[i].checked = true;
		}
	}else{
		for (var i = 0; i < chk.length; i++) {
			chk[i].checked = false;
		}
	}
}
function enviar()
{
	alert("Enviando");
	var nro = document.getElementById("nrocom").value;
	var ruc = document.getElementById("ruccom").value;
	var cot = document.getElementById("nro").value;
	var chk = document.getElementsByName("chkc");
	var j = 0;
	var miarray = new Array();

	for (var i = 0; i < chk.length; i++) {
		miarray[i] = new Array(chk[i].id.length);
		if(chk[i].checked){
//			alert(chk[i].id);
//			alert(document.getElementById(chk[i].id).value);
			miarray[j][0] = chk[i].id;
			miarray[j][1] = document.getElementById(chk[i].id).value;
			j++;
		}
	}
	var matprej = JSON.encode(miarray);
	var requestUrl = "";
	requestUrl = "?nro="+encodeURIComponent(nro)+"&nro="+encodeURIComponent(nro)+"&cot="+encodeURIComponent(cot)+"&ruc="+encodeURIComponent(ruc)+"&matarr="+matprej;
	alert(requestUrl);
	var xmlhttp = new Request
    ({
    	url: "includes/incdetcompra.php",
    	data: requestUrl,
    	onSuccess: function(textrespuesta){
    		$("textres").set("html",textrespuesta);
    	},
    	onFailure: function(){
    		$("textres").set("html","Fallo la Conexion Ajax");
    	}
    });
    xmlhttp.send();
    alert("Enviado");
}