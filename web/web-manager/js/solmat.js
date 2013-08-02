function viewdet (id) {
	if (id != '') {
		var prm = {
			'tra' : 'det',
			'sid' : id
		}
		$.ajax({
			data : prm,
			url : 'includes/incsolmat.php',
			type : 'POST',
			dataType : 'html',
			complete : function (obj,success){
				if (success == 'success') {
					$('#mpri').modal('show');
					$('#sol').val(id);
				}
			},
			success : function (response){
				if (response != 'nada') {
					var det = response.split(',');
					$("#nom").val(det[0]);
					$("#med").val(det[1]);
					if (det[2] != '') {$("#mar").val(det[2]);}else{ $("#mar").val('S/M');}
					if (det[3] != '') {$("#mod").val(det[3]);}else{ $("#mod").val('S/M');}
					$("#obser").val(det[4]);
				}
			},
			error : function (obj,quepaso,otrobj){
				alert("Si estas viendo esto es por que fallé");
			}
		});
	}
}
function nextcod () {
	$("#mpri").modal('hide');
	$("#dnom").html($("#nom").val());
	$("#dmed").html($("#med").val());
	$("#dmar").html($("#mar").val());
	$("#dmod").html($("#mod").val());
	$("#mseg").modal('show');
}
function backpri () {
	$("#mpri").modal('show');
	$("#mseg").modal('hide');
}
function savemat () {
	var id = $("#cod").val();
	if (id != "" && id.length == 15) {
		if (confirm("QUESTION\r\nDesea guardar el material y agregar al inventario?")) {
			//validando q el codigo no exista
			var prmp = {
				'tra' : 'new',
				'cod' : id
			}
			$.ajax({
				data : prmp,
				url : 'includes/incsolmat.php',
				type : 'POST',
				dataType : 'html',
				success : function (response) {
					//alert(response);
					if (response != "exists") {
						var prm = {
							'tra' : 'save',
							'cod' : id,
							'nom' : $("#nom").val(),
							'med' : $('#med').val(),
							'mar' : $('#mar').val(),
							'mod' : $('#mod').val(),
							'und' : $('#cbound').val()
						}
						//alert('ajax');
						$.ajax({
							data : prm,
							url : 'includes/incsolmat.php',
							type : 'POST',
							success : function (response) {
								if (response == 'success') {
									$("#min").modal('show');
								}
							},
							error : function (obj,quepaso,otrobj){
								alert('Si estas viendo esto es por que fallé');
							}
						});
					}else{
						$(".alert-error").css('display','block');
					}
				},
				error : function (obj,quepaso,otrobj) {
					alert("Si estas viendo esto es por que fallé");
				}
			});
		}
	}else{
		alert("WARNING:\r\nEl codigo ingresado no es valido.");
	}
}
function addinve () {
	var id = $("#cod").val();
	var prm = {
		'tra' : 'inve',
		'cod' : id,
		'alm' : $("#cboal").val(),
		'stk' : $("#stkm").val(),
		'sol' : $("#sol").val()
	}
	$.ajax({
		data : prm,
		url : 'includes/incsolmat.php',
		type : 'POST',
		success : function  (response) {
			alert(response);
			if (response == 'success') {
				$("#min").modal('hide');
				$(".alert-success").css('display','block');
				$("#btng").css('display','none');
				$("#btnc").css('display','block');
			}else{
				$("#min").modal('hide');
				$(".alert-error").css('display','block');
			}
		},
		error : function (obj,quepaso,otrobj) {
			alert('Si estas viendo esto es por que fallé');
		}
	});
}