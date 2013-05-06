function atender (cod) {
	if (confirm("Desea Atender este Pedido?")) {
		var mat = document.getElementsByName("matid");
		var ids = new Array();
		var c = 0;
		var t = 0;
		for (var i = 0; i < mat.length; i++) {
			//ids[i] = document.getElementById("").value;
			if (mat[i].checked) {
				ids[t] = mat[i].id;
				t++;
			}else{
				c++;
			}
		}
		if (ids.length > 0) {
			xmlhttp = peticion();
			if (c > 0) {
				if (confirm("Existe materiales que no tienen Stock para ser atendidos. Desea Continuar?")) {
					xmlhttp.onreadystatechange = function () {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							if (xmlhttp.responseText == "hecho"){
								location.href = "generardoc?nro="+encodeURIComponent(cod);
							}
						}
					}
					var requestUrl = "";
					requestUrl = "include/incatender.php" + "?tipo=a"+"&nro="+encodeURIComponent(cod)+"&status=i"+"&matid="+encodeURIComponent(ids);
					xmlhttp.open("POST",requestUrl,true);
					xmlhttp.send();
				}

			}else{
				xmlhttp = peticion();
				xmlhttp.onreadystatechange = function () {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (xmlhttp.responseText == "hecho"){
							location.href = "generardoc?nro="+encodeURIComponent(cod);
						}
					}
				}
				var requestUrl = "";
				requestUrl = "include/incatender.php" + "?tipo=a"+"&nro="+encodeURIComponent(cod)+"&status=c"+"&matid="+encodeURIComponent(ids);
				xmlhttp.open("POST",requestUrl,true);
				xmlhttp.send();
			}
		}else{
			alert('Debe de Seleccionar por lo menos un material!!');
		}
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