function showpersonal () {
		$( "#per" ).modal("show");
}
function saveper () {
	// alert("cmienzo");
	var cbo = document.getElementById("cboper");
	var dni = cbo.options[cbo.selectedIndex].value;
	var proid = document.getElementById("txtproid").value;
	//alert("prm");
	var prm = {
		'tra' : 'sper',
		'proid' : proid,
		'dni' : dni
	}
	//alert("termino");
	$.ajax({
		data : prm,
		url : 'includes/incmeter.php',
		type : 'POST',
		success : function(response){
			alert(response);
			if (response == "hecho") {
				location.href = '';
			}else{
				alert("Se produjo un Error.");
			}
		},
		error: function(objeto, quepaso, otroobj){
			alert("Estas viendo esto por que fallé\r\n"+"Pasó lo siguiente: "+quepaso);
		}
	});
}