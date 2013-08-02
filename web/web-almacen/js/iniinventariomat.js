function radios () {
	var c = document.getElementById("rbtnc");
	var d = document.getElementById("rbtnd");
	//alert(c.checked);
	if (c.checked) {
		document.getElementById("txtcodigo").disabled = false;
		document.getElementById("txtnombre").disabled = true;
	}else if(d.checked){
		document.getElementById("txtcodigo").disabled = true;
		document.getElementById("txtnombre").disabled = false;
	}
}
function openadd (id,nom,med,und) {
	if (id != "") {
		document.getElementById("txtid").value = id;
		document.getElementById("txtnom").value = nom;
		document.getElementById("txtmed").value = med;
		document.getElementById("txtund").value = und;
		document.getElementById("als").style.display = 'none';
		document.getElementById("ale").style.display = 'none';
		$("#madd").modal('show');
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
function addinvent () {
	var id = document.getElementById("txtid").value;
	if (id != '') {
		var cbo = document.getElementById('cboal');
		var op = cbo.options[cbo.selectedIndex].value;
		xmlhttp = peticion();
		xmlhttp.onreadystatechange = function () {
			alert(xmlhttp.responseText);
			if (xmlhttp.readyState ==4 && xmlhttp.status == 200) {
				if (xmlhttp.responseText == "hecho") {
					document.getElementById("als").style.display = 'block';
					document.getElementById("lblpro").style.display = 'none';
					setTimeout(function() {
						$("#madd").modal('hide');
						document.getElementById("als").style.display = 'none';
					}, 3500);
				}else{
					document.getElementById("ale").style.display = 'block';
					document.getElementById("lblpro").style.display = 'none';
				}
			}
		}
		var requestUrl = '';
		requestUrl = "include/incinginventario.php"+"?tra=iii"+"&matid="+encodeURIComponent(id)+"&cboal="+encodeURIComponent(op);
		xmlhttp.open("POST",requestUrl,true);
		xmlhttp.send();
		document.getElementById("lblpro").style.display = 'block';
	}
}