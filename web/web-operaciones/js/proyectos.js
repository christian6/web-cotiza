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
function aproproop () {
	$.msgBox({
		title : 'Aprobar Proyecto',
		content : 'Aprobar el proyecto para desarrollo, prodra realizar pedidos a almacén.\r\nDesea aprobar el proyecto?',
		opacity : 0.8,
		type : 'confirm',
		buttons : [{value : 'Si'},{value : 'Cancelar'}],
		success : function (result) {
			if (result == 'Si') {
				var prm = {
					'tra' : 'apropro',
					'pro' : $("#pro").val()
				}
				$.ajax({
					data : prm,
					url : 'includes/incproyecto.php',
					type : 'POST',
					dataType : 'html',
					success : function (response) {
						//alert(response);
						if (response == 'success') {
							location.href='sectorsub.php?pro='+$("#pro").val();
						}else if(response == 'sectores-falta'){
							$.msgBox({
								title : 'Alerta',
								content : '¡Oh no puede ser! Mejor que lo compruebes tu mismo, pero parece que hay sectores sin mandar a producción.',
								type : 'warning',
								opacity : 0.6
							});
						}
					},
					error : function (obj,quepaso,otroobj) {
						$.msgBox({
							title : 'ERROR',
							content : 'Oh no!, si estas viendo esto es por que no pude volar',
							type : 'error',
							autoClose : true,
							opacity : 0.8
						});
					}
				});
			}
		}
	});
}