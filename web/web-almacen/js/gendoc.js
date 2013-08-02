function  edit(val) {

	if (val == "punto") {
		var p = document.getElementById("txtdestino2");
		p.disabled = false;
	}else if(val == "ruc"){
		var r = document.getElementById("txtruc");
		r.disabled = false;
	}else if(val == "rz"){
		var z = document.getElementById("txtrz");
		z.disabled = false;
	}else if(val == "p"){
		var p1 = document.getElementById("txtdestino1");
		p1.disabled = false;
	}

}

function gendoc(oc,doc){
	var f = document.getElementById("fullscreem");
	var n = document.getElementById("frmnota");
	var g = document.getElementById("frmguia");

	if (oc == "o"){
		if(doc == "n"){
			f.style.display = "block";
			n.style.display = "block";
		}else if(doc == "g"){
			f.style.display = "block";
			g.style.display = "block";
		}
	}else if(oc == "c"){
		switch(doc){
			case "n":
					n.style.display = "none";
					f.style.display = "none";
			break;
			case "g":
					g.style.display = "none";
					f.style.display = "none";
				break;
		}
	}
}

function generar (doc,nro) {
	if(confirm("Seguro(a) que Desea Generar el Documento?")){
		xmlhttp = peticion();
		if (doc == "g") {
			if (document.getElementById("txtfechas").value == "") {
				alert("Algun Campo esta Vacio!");
			return;
			}
			if (document.getElementById("cbomov").options[document.getElementById("cbomov").selectedIndex].value == "") {
				alert("Algun Campo esta Vacio!");
				return;
			}
			if (document.getElementById("cbocon").options[document.getElementById("cbocon").selectedIndex].value == "") {
				alert("Algun Campo esta Vacio!");
				return;
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					if (xmlhttp.responseText != ""){
						location.href = 'generardoc.php?nrod='+xmlhttp.responseText+'&tdoc=GUIA DE REMISION&t=g';
					}
				}
			}
			var requestUrl = "";
			/* recuperando los valores para generar la guia de remision */
			var destino = document.getElementById("txtdestino2").value;
			var rz = document.getElementById("txtrz").value;
			var ruc = document.getElementById("txtruc").value;
			var fec = document.getElementById("txtfechas").value;
			var ctra = document.getElementById("cbotra");
			var otra = ctra.options[ctra.selectedIndex].value;
			var cmov = document.getElementById("cbomov");
			var omov = cmov.options[cmov.selectedIndex].value;
			var ccon = document.getElementById("cbocon");
			var ocon = ccon.options[ccon.selectedIndex].value;

			requestUrl = "include/incgendoc.php" + "?tipo=g"+"&nro="+encodeURIComponent(nro)+"&des="+encodeURIComponent(destino)+
			"&rz="+encodeURIComponent(rz)+"&ruc="+encodeURIComponent(ruc)+"&fec="+encodeURIComponent(fec)+
			"&trans="+encodeURIComponent(otra)+"&mov="+encodeURIComponent(omov)+"&con="+encodeURIComponent(ocon);
			xmlhttp.open("POST",requestUrl,true);
			xmlhttp.send();
		} else if(doc == "n"){
			if (document.getElementById("txtfecha").value == "") {
				alert("Algun Campo esta Vacio!");
				return;
			}
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					if (xmlhttp.responseText != ""){
						location.href = 'generardoc.php?nrod='+xmlhttp.responseText+'&tdoc=NOTA DE SALIDA&t=n';
					}
				}
			}
			var requestUrl = "";

			/* recuperando valores para la nota de salida */
			var destino = document.getElementById("txtdestino1").value;
			var fec = document.getElementById("txtfecha").value;

			requestUrl = "include/incgendoc.php" + "?tipo=n"+"&nro="+encodeURIComponent(nro)+"&fec="+encodeURIComponent(fec)+"&des="+encodeURIComponent(destino);
			xmlhttp.open("POST",requestUrl,true);
			xmlhttp.send();
		}
	}
}

function trans() {
	var cbo = document.getElementById("cbotra");
	var op = cbo.options[cbo.selectedIndex].value;
	if (op != '') {
		xmlhttp = peticion();
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState ==4 && xmlhttp.status == 200) {
				document.getElementById("cbot").innerHTML = xmlhttp.responseText;
				document.getElementById("frmguia").style.height = '32em';
			}
		}
		var requestUrl = '';
		requestUrl = "include/incgendoc.php"+"?tra=t"+"&truc="+encodeURIComponent(op);
		xmlhttp.open("GET",requestUrl,true);
		xmlhttp.send();
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
function verpedido () {
	location.href='verpedido.php';
}