$(document).ready(function () {
	viewobj(true);
	resizeadd();
	listtmp();
});
var ra = false;
function resizeadd () {
	if (ra) {
		$("#add").show( "blind" ,1000);
		$("#btnr i").removeClass("icon-plus").addClass("icon-minus");
		ra = false;
	}else{
		$("#add").hide("blind",1000);
		$("#btnr i").removeClass("icon-minus").addClass("icon-plus");
		ra =true;
	}
}
function heighttext (id,status) {
	if (status) {
		$("#"+id).animate({ "height" : "3em" }, 800);
	}else{
		$("#"+id).animate({ "height" : "1.5em" }, 0);
	}
}
function viewobj (sts) {
	$("input,textarea,select").each(function (){ 
		var item = this;
		if (sts) { item.disabled = sts; }else{ item.disabled = sts; }
	});
}
var vobj = false;
function valid_obj () {
	var i = 0;
	$("#al,#fec,#obs,#pro").each(function () {
		var item = this;
		if (item.value == '') {
			item.placeholder = 'Campo vacio';
			item.focus();
			vobj = false;
			return;
		}else{
			i++;
		}
	});
	console.log(i);
	if (i == 4) {
		vobj = true;
	}
}
var vtmp = false;
function valid_tmp () {
	$.ajax({
		url : 'include/incdevolucion.php',
		type : 'POST',
		data : { 'tra' : 'ctmp' },
		success : function (response) {
			console.log(response);
			if (response != 'success') {
				$.msgBox({
					title : 'Mensage',
					content : 'No has ingresado un detalle a la devolución',
					type : 'warning',
					opacity : 0.8
				});
				vtmp = false;
			}else{
				vtmp = true;
			}
		},
		error : function (ob,que,otr) {
			msgError(null,null,null);
		}
	});
}
function datapro () {
	//console.log($("#pro").val());
	$.ajax({
		url : 'include/incdevolucion.php',
		type : 'POST',
		data : { 'tra' :'dpro' ,'pro' : $("#pro").val() },
		dataType : 'html',
		success : function (response) {
			console.log(response);
			response = response.split('|');
			if (response[2] == 'success') {
				$("#npro").html(response[0]);
				$("#dpro").html(response[1]);
			}
		},
		error : function (obj,que,otr) {
			msgError(null,null,null);
		}
	});
}
function searchmeter (e) {
	var txtse = document.getElementById("txtse");
	var evt = e ? e : event;
    var key = window.Event ? evt.which : evt.keyCode;
    if (key == 13) {
    	//alert(txtse.value);
    	search(txtse.value);
    };
}
function search(des){
	if ($.trim(des) != "") {
		var prm = {
			'tra' : 'med',
			'nom' : des
		}
		$.ajax({
			data : prm,
			url : '../web-ventas/includes/incmeter.php',
			type: 'POST',
			success : function(response){
				//alert(response);
            if (response != "") {
                setTimeout(function() {
                    $( "#med" ).html(response);
                }, 600);    
            }else{
                alert("Se produjo un Error.");
            }
	        },
			error: function(objeto, quepaso, otroobj){
				msgError(null,null,null);
			}
		});
	}else{
		return;
	}
}
function showdet () {
	var nom = document.getElementById("txtse").value;
	var cbo = document.getElementById("med");
	var opm = cbo.options[cbo.selectedIndex].value;
	//alert(nom +" "+opm);
	if (nom !="" && opm != "") {
		var prm = {
			'tra' : 'data',
			'nom' : nom,
			'med' : opm
		}
		$.ajax({
			data : prm,
			url : '../web-ventas/includes/incmeter.php',
			type : 'POST',
			success : function(response){
				//alert(response);
            if (response != "") {
                //setTimeout(function() {
                $( "#cdet" ).html(response);
                //}, 600);    
            }else{
                alert("Se produjo un Error.");
            }
	        },
			error: function(objeto, quepaso, otroobj){
				msgError(null,null,null);
			}
		});
	}else{
		return;
	}
}
function savetmp () {
	if (validstmp()) {
		var $cant = $("#cant");
		var $est = $("#est");
		var prm = {
		'tra' : 'stmp',
		'mat' : $("#cod").html(),
		'can' : $cant.val(),
		'est' : $est.val()
		}
		$.ajax({
			url : 'include/incdevolucion.php',
			type : 'POST',
			data : prm,
			dataType : 'html',
			success : function  (response) {
				if (response == 'success') {
					uploadimg (true,$("#cod").html());
					listtmp();
				}
			},
			error : function (obj,que,otr) {
				msgError(null,null,null);
			}
		});
	}
}
function validstmp () {
	var $cant = $("#cant");
	var $est = $("#est");
	var sts = false;

	if ($est.val() == '') {
		$.msgBox({
			title : 'Alerta',
			content : 'Campo: Estado se encuentra vacio, desea continuar?',
			type : 'warning',
			opacity : 0.8,
			buttons : [{value:'Continuar'},{value:'Estado'},{value:'Cancelar'}],
			success : function (response) {
				if (response == 'Estado') {
					$est.focus();
					sts = false;
				}else if (response == 'Cancelar') {
					sts = false;
				}else{
					sts = true;
				}
			}
		});
	}

	if ( $cant.val() == '' ){
		$cant.focus();
		$.msgBox({
			title : 'Alerta',
			content : 'Cantidad : Campo vacio.',
			type : 'warning',
			opacity : 0.8,
			autoClose : true
		});
		sts = false;
	}
	if ($est.val() != '' && $cant.val() != '') { sts = true; }

	return sts;
}
function listtmp () {
	$.ajax({
		url : 'include/incdevolucion.php',
		type : 'POST',
		data : { 'tra' : 'ltmp' },
		success : function (response) {
			//console.log(response);
			response = response.split('|');
			if (response[1] == 'success') {
				$("#dett").html(response[0]);
			}
		},
		error : function (obj,que,otr) {
			msgError(null,null,null);
		}
	});
}
function delalltmp () {
	$.msgBox({
		title : 'Confirmar?',
		content : 'Desea eliminar todo el detalle de devolución?',
		type : 'confirm',
		opacity : 0.8,
		buttons : [{value : 'Si'},{value : 'No'}],
		success : function (response) {
			if (response == 'Si') {
				$.ajax({
					url : 'include/incdevolucion.php',
					type : 'POST',
					data : { 'tra' : 'delltmp' },
					dataType : 'html',
					success : function (response) {
						if (response == 'success') {
							listtmp();
						}
					},
					error : function (obj,que,otr) {
						msgError(null,null,null);
					}
				});
			}
		}	
	});
}
function delmattmp (mat) {
	$.msgBox({
		title : 'Confirmar?',
		content : 'Desea eliminar material '+mat+'?',
		type : 'confirm',
		opacity : 0.8,
		buttons : [{value : 'Si'},{value : 'No'}],
		success : function (response) {
			if (response == 'Si') {
				$.ajax({
					url : 'include/incdevolucion.php',
					type : 'POST',
					data : { 'tra' : 'delmat', 'mat' : mat },
					dataType : 'html',
					success : function (response) {
						if (response == 'success') {
							listtmp();
						}
					},
					error : function (obj,que,otr) {
						msgError(null,null,null);
					}
				});
			}
		}	
	});
}
function showedi (mat) {
	$("#medit").html(mat);
	$("#modaled").modal("show");
}
function editmat () {
	var mat = $("#medit").html();
	$.ajax({
		url : 'include/incdevolucion.php',
		type : 'POST',
		data : { 'tra' : 'modify', 'mat': mat, 'cant': $("#cedit").val() },
		dataType : 'html',
		success : function (response) {
			if (response) {
				uploadimg(false,mat);
				listtmp();
			}
		},
		error : function (obj,que,otr) {
			msgError(null,null,null);
		}
	});
}
function uploadimg (types,mat) {
	var inputFileImage = null;
	if (types) {
		inputFileImage = document.getElementById("mimg");
	}else{
		inputFileImage = document.getElementById("imgmodify");
	}
	
	var file = inputFileImage.files[0];
	if (file != null) {
		var data = new FormData();
		data.append('tra','upimg');
		data.append('img',file);
		data.append('mat',mat);
		var url = "include/incdevolucion.php";
		$.ajax({
			data : data,
			url : url,
			type : 'POST',
			contentType : false,
			processData : false,
			cache : false,
			success : function (response) {
				//alert(response);
				if (response == "success") {
					cleanfile(types);
				}else{
					alert(response);
				}
			},
			error : function (obj,quepaso,otrobj) {
				msgError(null,null,null);
			}
		});
		return true;
	}else{
		return false;
	}
}
function delimgtmp () {
	
}
function cleanfile (tra) {
	if (tra) {
		var $fileupload = $("#mimg");
		$fileupload.val("");
		//$fileupload.replaceWith($fileupload.clone(true));
	}else{
		var $fileupload = $("#imgmodify");
		$fileupload.val("");
		//$fileupload.replaceWith($fileupload.clone(true));
	}
	
}
function saveDev () {
	valid_obj();
	valid_tmp();
	if ( vtmp && vobj ) {
		$.msgBox({
			title : 'Guardar Cambios?',
			content : 'Desea guardar los cambios e ingresar los materiales almacén?',
			type : 'confirm',
			opacity : 0.8,
			buttons : [{value:'Si'},{value:'No'}],
			success : function (response) {
				if (response == 'Si') {
					var prm = {
						'tra' : 'savedev',
						'alm' : $('#al').val(),
						'nrg' : $('#nrg').val(),
						'pro' : $('#pro').val(),
						'obs' : $('#obs').val()
					}
					$.ajax({
						url : 'include/incdevolucion.php',
						type : 'POST',
						data : prm,
						dataType : 'html',
						success : function (response) {
							console.log(response);
							if (response.length == 8) {
								$.msgBox({
									title : 'Bien Hecho!',
									content : '<strong>Nro de Devolución</strong><strong class="t-info" style="font-size: 16px;">'+response+'</strong>',
									type : 'info',
									opacity : 0.8,
									buttons : [{value : 'Continuar'},{value : 'Ver Doc'}],
									success : function (resp) {
										if (resp == 'Continuar') {
											location.href = '';
										}else if(resp == 'Ver Doc'){
											window.open('http://190.41.246.91/web/reports/almacen/pdf/rptdevolucion.php?nro='+response);
										}
									}
								});
							}
						},
						error : function (obj,que,otr) {
							msgError(null,null,null);
						}
					});
				}
			}
		});
	}
}