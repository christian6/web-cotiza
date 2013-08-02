$(function  () {
	$( "#txtfec" ).datepicker({ minDate: "0", maxDate: "+3M +10D" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy-mm-dd"});
});
function showpedido () {
	$( "#mpe" ).modal("show");
}
function showlist () {
	$( "#mlist" ).modal("show");
}
function savepedido () {
	// verificando la fecha
	var fec = document.getElementById("txtfec");
	if (fec.value == "") {
		alert("Ingrese una fecha requerida");
		fec.focus();
		return;
	}
	// varificando el checkeo de materiales
	var ids = new Array();
	var mat = document.getElementsByName("mats");
	var c = 0;
	for (var i = 0; i < mat.length; i++) {
		if (mat[i].checked){
			ids[c] = mat[i].id;
			c++;
		}
	}
	if (c > 0) {

		var cbo = document.getElementById("cboal");
		var op = cbo.options[cbo.selectedIndex].value;
		var obs = document.getElementById("txtobser").value;
		var pro = document.getElementById("txtpro").value;
		var sub = document.getElementById("txtsub").value;
		var sec = document.getElementById("txtsec").value;
		var dni = document.getElementById("txtdni").value;
		//alert(op);
		var prm = {
			'tra' : 'sp',
			'pro' : pro,
			'sub' : sub,
			'sec' : sec,
			'alm' : op,
			'fec' : fec.value,
			'dni' : dni,
			'obs' : obs,
			'mat' : ids
		}
		if (confirm("Seguro(a) de guardar los cambios?")) {
			$.ajax({
				data : prm,
				url : 'includes/incpedido.php',
				type : 'POST',
				dataType : 'html',
				beforeSend : function (obj){
					$( ".progress" ).css("display","block");
				},
				complete : function (obj, success){
					if (success == 'success') {
						$( ".progress" ).css("display","none");
					}
				},
				success : function (response){
					//alert(response);
					if (response == 'hecho') {
						location.href = '';
					}
				},
				error : function (obj,que,otro){
					alert("Si estas viendo esto es por que falle");
				}
			});
		}
	}else{
		alert('Seleccione por lo menos un material');
		$( "#mpe" ).modal('hide');
		return;
	}
}
