$(function () {
	var bPreguntar = true;

	window.onbeforeunload = preguntarAntesDeSalir;

	function preguntarAntesDeSalir()
	{
		if (bPreguntar){
			
			return "¿Seguro que quieres salir?";
		}
	}
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
	var n = 0;
	var arrnip = new Array();
	for (var i = 0; i < mat.length; i++) {
		if (mat[i].checked){
			ids[c] = mat[i].id;
			if (String(mat[i].id).substring(0,3) == '115') {
				 arrnip[n] = mat[i].id;
				 n++;
			}
			c++;
		}
	}
	if (n > 0) {
		if (!confirm('Seguro(a) que ha terminado de llenar la lista de niples?')) {
			$("#mpe").modal('hide');
			return;
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
			'tra' : 'sp',//cambiar esto
			'pro' : pro,
			'sub' : sub,
			'sec' : sec,
			'alm' : op,
			'fec' : fec.value,
			'dni' : dni,
			'obs' : obs,
			'mat' : ids
		}
		$("#mpe").modal('hide');
		$.msgBox({
			title : 'Confirmar',
			content : 'Seguro(a) que desea guardar los datos y generar pedido a almacén?',
			type : 'confirm',
			opacity : 0.6,
			buttons : [{value:'Si'},{value:'No'}],
			success : function (result) {
				if (result == 'Si') {
					$("#mpe").modal('show');
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
							if (response.length == 15) {
								for (var i = 0; i < arrnip.length; i++) {
									niplesock(response,arrnip[i]);
								}
								uploadpeido(response);
								location.href = '';
							}
						},
						error : function (obj,que,otro){
							$.msgBox({
								title : 'Error',
								content : 'Si esta viendo es por que fallé',
								type : 'error',
								autoClose : true,
								opacity : 0.6
							});
						}
					});
				}
			}
		});
	}else{
		$( "#mpe" ).modal('hide');
		$.msgBox({
			title : 'Atención',
			content : 'Seleccione por lo menos un material',
			type : 'warning',
			autoClose : true,
			opacity : 0.6
		});		
		return;
	}
}
function addniple (med,mat) {
	$.msgBox({
		title : "Agregar Niple "+med+'"',
		/*inputs : [{ header: med+'"', type : 'text', name : 'med'+med }],*/
		content : "<table style='margin-top: -5em;'><tr><td>Medida</td>"+
					"<td><input type='number' class='span2' id='nipmed'min='0' max='100' step='0.01'></td></tr>"+
					"<tr><td>Tipo</td><td><select id='cbot' class='span2'>"+
					"<option value='A'>Rosca -> A</option>"+
					"<option value='B'>Ranura -> B</option>"+
					"<option value='C'>Rosca-Ranura -> C</option>"+
					"</select></td></tr></table>",
		buttons : [{value: 'Add'}, {value: 'Cancel'}],
		success : function (result) {
			if(result == 'Add'){
				if ($("#nipmed").val() != '') {
					var prm = {
						'tra' : 'tmpnip',
						'matid' : mat,
						'met' : $("#nipmed").val(),
						'tip' : $('#cbot').val(),
						'pro' : $("#txtpro").val(),
						'sub' : $("#txtsub").val(),
						'sec' : $("#txtsec").val(),
						'adi' : $("#adi").val()
					}
					$.ajax({
						data : prm,
						url : 'includes/incpedido.php',
						type : 'POST',
						success : function (response) {
							//alert(response);
							if (response == 'success') {
								tmplist(mat,med);
							}else{
								$.msgBox({
								title : 'Error',
								content : 'Se ha encontrado errores ',
								type : 'error',
								opacity : 0.8,
								autoClose : true
							});
							}
						},
						error : function (obj,quepaso,otrobj) {
							$.msgBox({
								title : 'Error',
								content : 'Si estas viendo esto es por que falle',
								type : 'error',
								opacity : 0.8,
								autoClose : true
							});
						}
					});
				}else{
					$.msgBox({
						title : 'Error',
						content : 'Medida esta vacia.',
						type : 'error',
						autoClose : true,
						opacity : 0.6
					});
				}
			}
		},
		type : 'info',
		opacity : 0.8
	});
}
function tmplist (mat,med) {
	var prm = {
		'tra' : 'listnip',
		'mat' : mat,
		'med' : med
	}
	$.ajax({
		data : prm,
		url : 'includes/incpedido.php',
		type : 'POST',
		success : function (response) {
			var cad = response.split('|');
			if (cad[1] == 'success') {
				document.getElementById("nip"+med+"").innerHTML = cad[0];
				document.getElementById("qd"+med).innerHTML = cad[2];
				//alert(parseFloat(document.getElementById("ct"+med).innerHTML) - parseFloat(cad[2]));
				var re =  parseFloat(document.getElementById("ct"+med).innerHTML) - parseFloat(cad[2]);
				document.getElementById("tf"+med).innerHTML = re.toFixed(2);
			}
		},
		error : function (obj,quepaso,otrobj) {
			$.msgBox({
				title : 'Error',
				content : 'Si estas viendo esto es por que falle',
				type : 'error',
				opacity : 0.8,
				autoClose : true
			});
		}
	});
}
function closep () {
	alert('hello');
}
function delniple (id,mat,med) {
	$.msgBox({
		title : 'Eliminar Niple',
		content : 'Desea eliminar el Niple?',
		type : 'confirm',
		buttons : [{value : 'Si'},{value : 'No'}],
		opacity : 0.6,
		success : function (result) {
			if (result == 'Si') {
				var prm = {
					'tra' : 'delniple',
					'id' : id
				}
				$.ajax({
					data : prm,
					url : 'includes/incpedido.php',
					type : 'POST',
					dataType : 'html',
					success : function  (response) {
						//alert(response);
						if (response == 'success') {
							tmplist(mat,med);
						}else{
							$.msgBox({
								title : 'Error',
								content : 'Parece que hay un error, mejor revisalo tu mismo.',
								type : 'error',
								opacity : 0.6
							});
						}
					},
					error : function (obj,que,otr) {
						$.msgBox({
							title : 'Error',
							content : 'Si estas viendo esto es por que falle.',
							autoClose : true,
							opacity : 0.6
						});
					}
				});
			}
		}
	});
}
function selectall () {
	var rb = document.getElementsByName("rbchk");
	var mat = document.getElementsByName('mats');
	for (var i = 0; i < rb.length; i++) {
		if(rb[i].checked && rb[i].value == 'a'){
			for (var i = 0; i < mat.length; i++) {
				mat[i].checked = true;	
			}
		}else{
			for (var i = 0; i < mat.length; i++) {
				mat[i].checked = false;
			};
		}
	}
}
function openadj () {
	$("#fileadj").click();
}
function fchan () {
	$("#cad, #cad a").animate({
		backgroundColor : '#86B404',
		color : '#000'
	},1600);
	$("#cad a").html('Listo para subir Archivo');
}
function uploadpeido (nro) {
	var fpde = document.getElementById("fileadj");
	var file = fpde.files[0];
	if (file != null) {
		var data = new FormData();
		data.append('tra','upfile');
		data.append('fpedido',file);
		data.append('pro',$("#txtpro").val());
		data.append('sub',$("#txtsub").val());
		data.append('adi',$("#adi").val());
		data.append('nrop',nro);
		var url = 'includes/incpedido.php';
		$.ajax({
			data : data,
			url : url,
			type : 'POST',
			contentType : false,
			processData : false,
			cache : false,
			success : function (response) {
				if (response != 'success') {
					$.msgBox({
						title : 'Error',
						content : 'Al subir archivo.',
						type : 'error',
						autoClose : true,
						opacity : 0.6
					});
				}
			},
			error : function (obj,que,otr) {
				$.msgBox({
					title : 'Error',
					content : 'Si estas viendo esto es por que fallé',
					type : 'error',
					autoClose : true
				});
			}
		});
	}
}
function niplesock (nro,mat) {

	var prm = {
		'tra' : 'saveniple',//cambiar
		'pro' : $("#txtpro").val(),
		'sub' : $("#txtsub").val(),
		'sec' : $("#txtsec").val(),
		'nro' : nro,
		'mat' : mat
	}
	$.ajax({
		data : prm,
		url : 'includes/incpedido.php',
		type : 'POST',
		success : function (response) {
			alert(response);
			if (response != 'success') {
				$.msgBox({
					title : 'Error',
					content : 'Parece que hay un error, revisalo',
					type : 'error',
					autoClose : true,
					opacity : 0.6
				});
			}
		},
		error : function (obj,que,otr) {
			$.msgBox({
				title : 'Error',
				content : 'Si estas viendo esto es por que fallé',
				type : 'error',
				autoClose : true,
				opacity : 0.6
			});
		}
	});
}
var adi = false;
function showadicionales () {
	if (adi == false) {
		tmpmodify();
		$("#btnadi i").removeClass('icon-chevron-down').addClass('icon-chevron-up');
		$("#adic").show( 'blind',{});
		adi = true;
	}else{
		hideadicionales();
	}
}
function hideadicionales () {
	$("#btnadi i").removeClass('icon-chevron-up').addClass('icon-chevron-down');
	$("#adic").hide( 'blind',{});
	adi = false;
}
function tmpmodify () {
	var prm = {
		'tra' : 'tmpmod',
		'pro' : $("#txtpro").val(),
		'sub' : $("#txtsub").val(),
		'sec' : $("#txtsec").val()
	}
	$.ajax({
		data : prm,
		url : 'includes/incpedido.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			var cad = response.split('|');
			if (cad[1] == 'success') {
				$("#dettbl").html(cad[0]);
				$("#ptn").val(cad[2]);
				console.log(cad[2]);
			}
		},
		error : function (obj,que,otr) {
			$.msgBox({
				title : 'Error',
				content : 'Si estas viendo esto es por que falle',
				type : 'error',
				autoClose : true,
				opacity : 0.6
			});
		}
	});
}
function hideaddmat () {
	$("#addmat").hide('blind',{},1200);
	saddm = false;
}
var saddm = false;
function showaddmat () {
	if (saddm == false) {
		$("#addmat").show('blind',{},1200);
		saddm = true;
	}else{
		hideaddmat();
		saddm = false;
	}
	
}