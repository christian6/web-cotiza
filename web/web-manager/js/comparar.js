$(function() {
	$( "#medit" ).draggable();
	$( "#mdel" ).draggable();
	$( "#alist").draggable();
});
function viewedit(id,nom,med){
	if (id != "") {
		$( "#medit" ).modal('show');
		$( "#txtid" ).val(id);
		$( "#txtnom ").val(nom);
		$( "#txtmed" ).val(med);
	}
}
function viewdelete(id){
	if (id != "") {
		$( "#txtid" ).val(id);
		$( "#mdel" ).modal('show');
	}
}
function viewapro (){
	$( "#alist" ).modal('show');
}
function aprobar (){
	var pro = $( "#txtpro" ).val();
	var sub = $( "#txtsub" ).val();
	var sec = $( "#txtsec" ).val();
	var rv = document.getElementById( "rv" );
	var ro = document.getElementById( "ro" );
	var rad = '';
	if (rv.checked) {
		rad = 'v';
	}else if(ro.checked){
		rad = 'o';
	}

	if (rad != '') {
		var prm = {
			'tra' : 'venta',
			'pro' : pro,
			'sub' : sub,
			'sec' : sec,
			'rad' : rad
		}
		$.ajax({
			data : prm,
			url : 'includes/inccompare.php',
			type : 'POST',
			success : function (response){
				//alert(response);
				if (response == "hecho"){
					$( "#alist" ).modal('hide');
					$( "#fullscreen-icr" ).css("display","block");
					$( "#loading-icr" ).css("display","block");
					setTimeout(function() {
						window.close();
					}, 3000);
				}
			},
			error : function (object,paso,otrobject){
				alert('Si estas viendo esto es por que faller\r\n');
			}
		});
	}else{
		alert('Debe de Seleccionar por lo menos una opcion');
	}
}
function edit () {
	var ve = document.getElementById("rbtnve");
	var op = document.getElementById("rbtnop");
	var rad = '';

	if (ve.checked) {
		rad = 'v';
	}else if(op.checked){
		rad = 'o';
	}else{
		alert('Seleccione un opción\r\nVentas o Operaciones');
		return;
	}

	var cant = $( "#txtcant" ).val();
	if (cant != "") {
		var pro = $( "#txtpro" ).val();
		var sub = $( "#txtsub" ).val();
		var sec = $( "#txtsec" ).val();
		var id = $( "#txtid" ).val();

		var prm = {
			'tra' : 'edit',
			'pro' : pro,
			'sub' : sub,
			'sec' : sec,
			'mid' : id,
			'rad' : rad,
			'cant' : cant
		}

		$.ajax({
			data : prm,
			url : 'includes/inccompare.php',
			type : 'POST',
			success : function(response){
				if (response == "hecho") {
					location.href = ''
				}
			},
			error: function(objeto, quepaso, otroobj){
				alert("Estas viendo esto por que fallé\r\n"+"Pasó lo siguiente: "+quepaso + objeto.id + otroobj);
			}
		});
	}else{
		alert('No ha ingresado una cantidad.');
	}
}
function del (){
	var pro = $( "#txtpro" ).val();
	var sub = $( "#txtsub" ).val();
	var sec = $( "#txtsec" ).val();
	var id = $( "#txtid" ).val();

	var vd = document.getElementById("rbtnvd");
	var od = document.getElementById("rbtnod");
	var rad = '';
	if (vd.checked) {
		rad = 'v';
	}else if(od.checked){
		rad = 'o';
	}else{
		alert('Seleccione un opción\r\nVentas o Operaciones');
		return;
	}
	if (id != "") {
		var prm = {
			'tra' : 'del',
			'pro' : pro,
			'sub' : sub,
			'sec' : sec,
			'mid' : id,
			'rad' : rad
		}
		$.ajax({
			data : prm,
			url : 'includes/inccompare.php',
			type : 'POST',
			success : function(response) {
				if (response == "hecho") {
					location.href = '';
				}
			},
			error : function (objeto, quepaso, otroobj){
				alert("Estas viendo esto por que falle");
			}
		});
	}
}
function aprobarall () {
	var rv = document.getElementById( "rv" );
	var ro = document.getElementById( "ro" );
	var pass = $( "#txtpass" ).val();
	var rad = '';
	if (rv.checked) {
		rad = 'v';
	}else if(ro.checked){
		rad = 'o';
	}
	if (rad != '' && pass != '') {
		// Verificando la contrasenia del administrador
		var cbo = document.getElementById("cboadmin");
		var op = cbo.options[cbo.selectedIndex].value;
		var pro = $( "#txtpro" ).val();
		var sub = $( "#txtsub" ).val();
		var admin = {
			'tra' : 'admin',
			'rad' : rad,
			'usu' : op,
			'pwd' : hex_md5(pass),
			'pro' : pro,
			'sub' : sub
		}
		$.ajax({
			data : admin,
			url : 'includes/inccompare.php',
			type : 'POST',
			dataType : 'html',
			beforeSend : function (obj){
				$( ".progress").css('display','block');
			},
			complete : function (obj, success){
				if (success == 'success') { $( ".progress" ).css('display','none'); }
			},
			success : function (response){
				//alert(response);
				if (response == 'success') {
					$( "#lblaut" ).css('display','none');
					$( "#lblsu" ).css('display','inline');
					setTimeout(function() { location.href = "proyectoma.php"; }, 1200);
				}else if (response == 'fallida') {
					$( "#lblaut" ).css('display','inline');
					$( "#lblsu" ).css('display','none');
				}
			},
			error : function (objeto, quepaso, otroobj){
				alert("Estas viendo esto es por que fallé.");
			}
		});
	}else{
		if (rad == '') { alert('Seleccione una opción.');}
		if (pass == '') { alert('Ingrese la contraseña del Administrador.');}
	}
}
/* comparelist.php */
function delsector () {
	var pro = $( "#txtpro" ).val();
	var sub = $( "#txtsub" ).val();
	var sec = $( "#txtsec" ).val();

	if (confirm("Realmente desea eliminar la lista de este sector?")) {
		var prm = {
			'tra' : 'delsec',
			'pro' : pro,
			'sub' : sub,
			'sec' : sec
		}
		$.ajax({
			data : prm,
			url : 'includes/inccompare.php',
			type : 'POST',
			dataType : 'html',
			success : function(response) {
				if (response == "hecho") {
					location.href = '';
				}
			},
			error : function (objeto, quepaso, otroobj){
				alert("Estas viendo esto por que falle");
			}
		});
	}
}
function editpre () {
	var epre = document.getElementsByName("snpre");
	for (var i = 0; i < epre.length; i++) {
		epre[i].disabled = false;
	}
}
function refreshpre () {
	var pre = document.getElementsByName("snpre");
	var vent = document.getElementsByName("cvent");
	var oper = document.getElementsByName("coper");
	var tv = 0, to = 0;
	try{
		for (var i = 0; i < pre.length; i++) {
			//alert(pre[i].value +' ' +vent[i].value);
			tv += (pre[i].value * vent[i].value);
			to += (pre[i].value * oper[i].value);
		}
		$("#vmpre").val(tv);
		$("#ompre").val(to);
	}catch(e){
		alert(e);
	}
}