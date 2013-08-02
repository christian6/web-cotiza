function listpro () {
	//alert("list");
	var des = document.getElementById("txtdes").value;
	var cbo = document.getElementById("cboa");
	var opa = cbo.options[cbo.selectedIndex].value;
	var prm = {
		'tra' : 'pro',
		'nom' : des,
		'anio' : opa
	}
	$.ajax({
		data : prm,
		url : 'includes/incproyecto.php',
		type : 'POST',
		success : function(response){
			$( "#cont" ).html(response);
		},
		error: function(objeto, quepaso, otroobj){
			alert("Estas viendo esto por que fallé\r\n"+"Pasó lo siguiente: "+quepaso + objeto.id + otroobj);
		}
	});
}