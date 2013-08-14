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
/* sectores.php */
function viewdel () {
	$( "#dellist").modal('show');
}
function delsectores (){
	var se = document.getElementById("cht");
	var su = document.getElementById("chs");
	var op;
	if (se.checked && su.checked) {
		op = 'all';
	}else if(su.checked && !se.checked){
		op = 'su';
	}else if(se.checked && !su.checked){
		op = 'se';
	}else{
		op = 'none';
	}
	if (op != 'none') {
		var pro = document.getElementById("lblpro").innerHTML;
		var prm = {
			'tra' : 'delsec',
			'pro' : pro,
			'op' : op
		}
		if (confirm("Seguro(a) que desea eliminar\r\n la lista de materiales")) {
			$.ajax({
			data : prm,
			url : 'includes/incsectores.php',
			type : 'POST',
			success : function (response){
				//alert(response);
				if (response == 'hecho') {
					location.href = '';	
				}				
			},
			error : function (objeto,quepaso,otroobj){
				alert("Estas viendo esto por que fallé");
			}
			});
		}
	}else{
		alert('No se ha seleccionado una opción.');
		return;
	}
	
}
function showuser () {
	$( "#per" ).modal('show');
}
function saveper () {
	var prm = {
		'tra' : 'sper',
		'proid' : $("#pro").val(),
		'dni' : $("#cboper").val()
	}
	$.ajax({
		data : prm,
		url : 'includes/incmeter.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			//alert(response);
			if (response == "hecho") {
				location.href = ''
			}
		},
		error : function (obj,quepaso,otrobj) {
			alert("Si estas viendo esto es por que fallé");	
		}
	});
}