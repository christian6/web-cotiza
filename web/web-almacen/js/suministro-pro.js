$(document).ready(function () {
	listloadtmp();
});
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
	})
}