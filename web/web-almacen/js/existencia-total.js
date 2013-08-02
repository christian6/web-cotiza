$(function () {
	$("#fec").datepicker({ minDate: "0" , maxDate: "+3M" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd"});
});
function checkall () {
	var chk = document.getElementsByName("matids");
	for (var i = 0; i < chk.length; i++) {
		chk[i].checked = true;
	};
}
function descheckall () {
	var chk = document.getElementsByName("matids");
	for (var i = 0; i < chk.length; i++) {
		chk[i].checked = false;
	};
}
var idmat = {}
function loadvec () {
	var items = document.getElementsByName("matids");
	var c = 0;
	for (var i = 0; i < items.length; i++) {
		if (items[i].checked){
			idmat[c] = {"id":items[i].id, "cant":items[i].value}
			c++;
		}
	}
}
function addtmp () {
	var items = document.getElementsByName("matids");

	for (var i = 0; i < items.length; i++) {
		//alert('length vector '+items.length +' item '+ i);
		if (items[i].checked){
			var id = items[i].id;
			$("#cod").val(id);
			$("#cant").val(items[i].value);
			var prm = {
				'tra' : 'datos',
				'id' : id
			}
			$.ajax({
				data : prm,
				url : 'include/inctotal.php',
				type : 'POST',
				dataType : 'html',
				success : function (response){
					//alert(response);
					if (response != "nada") {
						var cad = response.split('|');
						$("#des").val(cad[0]);
						$("#med").val(cad[1]);
					}
				},
				error : function (obj,quepaso,otrobj){
					alert("Si estas viendo esto es por que fallé");
				}
			});
			document.getElementById(id).checked=false;
			break;
		}
	}
	return count;
}
function showquets () {
	var items = document.getElementsByName("matids");
	var c = 0;
	for (var i = 0; i < items.length; i++) {
		if (items[i].checked){
		c++;	
		}
	}
	if (c > 0) {
		$("#mquest").modal("show");
	}else{
		alert('WARNING\r\nSeleccione por lo menos un material.');
	}
}
function quest () {
	var d = document.getElementById("rd");
	var c = document.getElementById("rc");
	if (d.checked) {
		$("#mquest").modal('hide');
	}else if(c.checked){
		$("#mdet").modal("show");
		addtmp();
		/*loadvec();
		$.each(idmat, function(k,v){
							alert(k+" -> " +v.id + ", "+v.cant); 
							}
		);*/
		$("#mquest").modal('hide');
	}else{
		alert('WARNING\r\nNo se ha seleccionado una opción.');
	}
}
function dettmp () {
	var prm = {
			'tra' : 'stmp',
			'cod' : $("#cod").val(),
			'ca' : $("#cant").val()
		}
	if (valchk() == 1) {
		alert($("#cant").val());
		$.ajax({
			data : prm,
			url : 'include/inctotal.php',
			type : 'POST',
			dataType : 'html',
			success : function (response) {
				alert(response);
				if (response == 'hecho') {
					addtmp();
				}
			},
			error : function (obj,quepaso,otrobj) {
				alert("Si estas viendo esto es por que fallé");
			}
		});
	}else if(valchk() == 0){
		$.ajax({
			data : prm,
			url : 'include/inctotal.php',
			type : 'POST',
			dataType : 'html',
			success : function (response) {
				alert(response);
				if (response == 'hecho') {
					addtmp();
					$("#msu").modal('show');
				}
			},
			error : function (obj,quepaso,otrobj) {
				alert("Si estas viendo esto es por que fallé");
			}
		});
	}
}
function valchk () {
	var items = document.getElementsByName("matids");
	var r = 0;
	for (var i = 0; i < items.length; i++) {
		if (items[i].checked){
			r = 1;
			break;
		}else if(!items[i].checked){
			r = 0;
		}
	}
	return r;
}
function savesum () {
	if ($("#fec").val() == "") {
		alert("WARNING\n\rDebe de ingresar una Fecha");
		$("#fec").focus();
		return;
	}
	var prm = {
		'tra' : 'ssum',
		'al' : $("#al").val(),
		'dni' : $("#dni").val(),
		'fec' : $("#fec").val()
	}
	$.ajax({
		data : prm,
		url : 'include/inctotal.php',
		type : 'POST',
		dataType : 'html'
	});
}