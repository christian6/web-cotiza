$(document).ready(function () {
	init();
});
function init () {
	listloadtmp();
	$("#fec").datepicker({ minDate: "+3D" , maxDate: "+3M" , changeMonth: true, changeYear: true, showAnim: "blind", dateFormat: "yy-mm-dd"});
}
function addmatsuminsitro (mid,cant) {
	if (mid != "" && cant != null) {
		var prm = {
			'tra' : 'addmatsum',
			'mat' : mid,
			'cant' : cant
		}
		$.ajax({
			data : prm,
			url : 'include/incsuministro-pro.php',
			type : 'POST',
			dataType : 'html',
			success : function (response) {
				console.log(response);
				if (response == 'success') {
					listloadtmp();
				}else{
					$.msgBox({
						title : 'Warning',
						content : 'Al parecer tienes un error, comprueba tus datos',
						type : 'warning',
						opacity : 0.6
					});
				}
			},
			error : function (obj,que,otr) {
				$.msgBox({
					title : 'Error',
					content : 'Si estas viendo esto es por que fallé',
					type : 'error',
					opacity : 0.6,
					autoClose : true
				});
			}
		});
	}
}
function listloadtmp () {
	$.ajax({
		data : { 'tra' : 'listtmp' },
		url : 'include/incsuministro-pro.php',
		type : 'POST',
		dataType : 'html',
		success : function  (response) {
			console.log(response);
			response = response.split('|');
			if (response[1] == 'success') {
				$("#tdet").html(response[0]);
			}else{
				$.msgBox({
					title : 'Warning',
					content : 'Al parecer no pude actualizr los datos.',
					type : 'warning',
					opacity : 0.6
				});
			}
		},
		error : function (obj,que,otr) {
			$.msgBox({
				title : 'Error',
				content : 'Si estsa virndo esto es por que fallé',
				type : 'error',
				opacity : 0.6,
				autoClose : true
			});
		}
	});
}
function delmattmp (mat) {
	$.msgBox({
		title : 'Question',
		content : 'Realmente desea eliminar el material '+mat,
		type : 'confirm',
		opacity : 0.6,
		buttons : [ {value:'Si'},{value:'No'} ],
		success : function (result) {
			if (result == 'Si') {
				$.ajax({
					data : { 'tra':'deltmpmat','mat':mat },
					url : 'include/incsuministro-pro.php',
					type : 'POST',
					dataType : 'html',
					success : function (response) {
						if (response == 'success') {
							listloadtmp();
						}else{
							$.msgBox({
								title : 'Error',
								content : 'No se ha podido eliminar el material del tmp.',
								type : 'error',
								opacity : 0.6,
								autoClose : true
							});
						}
					},
					error : function (obj,que,otr) {
						$.msgBox({
							title : 'Error',
							content : 'Si estsa virndo esto es por que fallé',
							type : 'error',
							opacity : 0.6,
							autoClose : true
						});
					}
				});
			}
		}
	});
}
function editmat (mat,cant) {
	$.msgBox({
		title : 'Editar Material',
		content : '<small>Ingrese Cantidad</small><br><input type="number" id="emat" class="span2" value="'+cant+'">',
		type : 'confirm',
		opacity : 0.8,
		buttons : [{value:'Si'},{value:'No'}],
		success : function (result) {
			if (result == 'Si') {
				var prm = {
					'tra' : 'edit',
					'mat' : mat,
					'cant' : $("#emat").val()
				}
				$.ajax({
					data : prm,
					url : 'include/incsuministro-pro.php',
					type : 'POST',
					success : function (response) {
						if (response == 'success') {
							listloadtmp();
						}
					},
					error : function (obj,que,otr) {
						$.msgBox({
							title : 'Error',
							content : 'Si estas viendo esto es por que fallé',
							type : 'error',
							opacity : 0.6,
							autoClose : true
						});
					}
				});
			}
		}
	});
}
function showsum () {
	$("#mos").modal('show');
}
function gensuministro () {
	$("#mos").modal('hide');
	$.msgBox({
		title : 'Orden de Suministro',
		content : 'Seguro(a) que desea generar la Orden de Suministro?',
		type : 'confirm',
		opacity : 0.6,
		buttons : [{value:'Si'},{value:'No'}],
		success : function (result) {
			if (result == 'Si') {
				var prm = {
					'tra' : 'genos',
					'alm' : $("#cboal").val(),
					'emp' : $("#dni").val(),
					'fec' : $("#fec").val()
				}
				$.ajax({
					data : prm,
					url : 'include/incsuministro-pro.php',
					type : 'POST',
					success : function (response) {
						console.log(response);
						response = response.split('|');
						if (response[1] == 'success') {
							//$("#mos").modal('show');
							$.msgBox({
								title : 'Bien hecho!',
								content : 'Se ha generado correctamente el Nro Suminsitro es '+response[0],
								type : 'info',
								opacity : 0.6,
								buttons : [{value : 'OK'}],
								success : function (result) {
									if (result == 'OK') {
										location.reload();
									}else{
										alert('No se Guardo '+response[0]);
									}
								}
							});
						}
					},
					error : function (ob,que,otr) {
						$.msgBox({
							title : 'Error',
							content : 'SI estas viendo esto es por que fallé',
							type : 'error',
							opacity : 0.6,
							autoClose : true
						});
					}
				});
			}
		}
	});
}