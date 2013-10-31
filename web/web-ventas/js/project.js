$(function  () {
	$("#ufec,#msfec").datepicker({ minDate: "0" , maxDate: "" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd"});
});

function showsector () {
	$( "#msec" ).modal("show");
	console.log('aqui');
}
function showsubpro () {
	$("#msub").modal("show");
}
function showadicional () {
	$("#madi").modal("show");
}
function validsec () {
	var bool = false;
	$("#frmsec").find(':input').each(function () {
		var item = this;
		if (item.value == '' && item.id != 'ssub') {
			bool = false;
			$("#sawa").css('display','block');
			item.focus();
			return false;
		}else{
			bool = true;
		}
	});
	return bool;
}
function savesec() {
	if (validsec() == true) {
		var prm = {
			'tra' : 'savesec',
			'pro' : $("#spro").val(),
			'sub' : $("#ssub").val(),
			'sec' : $("#snro").val(),
			'des' : $("#sdes").val(),
			'obs' : $("#sobs").val()
		}
		//alert("termino");
		$.ajax({
			data : prm,
			url : 'includes/incmeter.php',
			type : 'POST',
			success : function(response){
				//alert(response);
				if (response == "hecho") {
					$("#sawa").css('display','none');
					$("#saer").css('display','none');
					$("#sasu").css('display','block');
					setTimeout(function() {
						$("#sasu").css('display','none');
						location.href = '';
					}, 2000);
				}else{
					$("#saer").css('display','block');
				}
			},
			error: function(objeto, quepaso, otroobj){
				alert("Estas viendo esto por que fallé\r\n"+"Pasó lo siguiente: "+quepaso);
			}
		});
	}
}
function validsub () {
	var bool = false;
	$("#frmsub").find(':input').each(function () {
		var item = this;
		if (item.value == '') {
			$("#uawa").css('display','block');
			item.focus();
			bool = false;
			return false;
		}else{
			bool = true;
		}
	});
	return bool;
}
function savesub () {
	if (validsub() == true) {
		var prm = {
			'tra' : 'savesub',
			'pro' : $("#upro").val(),
			'des' : $("#udes").val(),
			'fec' : $("#ufec").val(),
			'obs' : $("#uobs").val()
		}
		$.ajax({
			data : prm,
			url : 'includes/incmeter.php',
			type : 'POST',
			success : function (response) {
				//alert(response);
				if (response.length == 7) {
					$("#uawa").css('display','none');
					$("#uaer").css('display','none');
					$("#uasu").css('display','block');
					setTimeout(function() {
						$("#uasu").css('display','none');
						location.href = '';
					}, 2000);
				}else{
					$("#uaer").css('display','block');
				}
			},
			error : function (obj, quepaso,otroobj) {
				alert('Si estas viendo esto es por que falle');
			}
		});
	}
}
function validadi () {
	var bool = false;
	$("#frmadi").find(':input').each(function () {
		var item = this;
		if (item.value == '' && item.id != 'dsub') {
			$("#dawa").css('display','block');
			item.focus();
			bool = false;
			return false;
		}else{
			bool = true;
		}
	});
	return bool;
}
function saveadi () {
	if (validadi() == true) {
		var d = {
			'tra' : 'valsec',
			'pro' : $("#dpro").val(),
			'sub' : $("#dsub").val(),
			'sec' : $("#dsec").val()
		}
		$.ajax({
			data : d,
			url : 'includes/incmeter.php',
			type : 'POST',
			dataType : 'html',
			success : function (response) {
				if (response == 'success') {
					var prm = {
						'tra' : 'savead',
						'pro' : $("#dpro").val(),
						'sub' : $("#dsub").val(),
						'sec' : $("#dsec").val(),
						'des' : $("#ddes").val(),
						'obs' : $("#dobs").val()
					}
					//alert($("#aobs").val());
					$.ajax({
						data : prm,
						url : 'includes/incmeter.php',
						type : 'POST',
						success : function (response) {
							alert(response);
							if (response.length == 7) {
								$("#dawa").css('display','none');
								$("#daer").css('display','none');
								$("#dasu").css('display','block');
								setTimeout(function() {
									$("#dasu").css('display','none');
									location.href = '';
								}, 3000);
							}else{
								$("#daer").css('display','block');
							}
						},
						error : function (obj, quepaso,otroobj) {
							alert('Si estas viendo esto es por que falle');
						}
					});
				}else{
					alert("WARNING\r\nEl nro Plano ingresa no es Correcto o no existe.");
				}
			},
			error : function (obj,quepaso,otrobj) {
				alert('si estas viendo esto es por que fallé');
				return;
			}
		});
		
	}
}
/* proyecto.php */
$(function(){
	$("#fec").datepicker({ minDate: "0" , maxDate: "" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd"});
	if ($("#new").val() == 'TRUE') {
		$("#mpro").modal('show');
	}
});
function addnew () {
	$('#mpro').modal('show');
	$("#new").val('TRUE');
}
function valid () {
	var bool = false;
	$("#frmproject").find(':input').each(function () {
		var item = this;
		if (item.value == '') {
			item.focus();
			bool = false;
			$('#awa').css('display','block');
			setTimeout(function() {$('#awa').css('display','none');}, 3000);
			return false;
		}else{
			bool = true;
		}
	});
	return bool;
}
function saveproject () {
	if (valid() == true) {
		var prm = {
			'tra' : 'savepro',
			'nom' : $("#des").val(),
			'fec' : $("#fec").val(),
			'cli' : $("#cli").val(),
			'pais' : $("#pais").val(),
			'dep' : $("#dep").val(),
			'pro' : $("#pro").val(),
			'dis' : $("#dis").val(),
			'dir' : $("#dir").val(),
			'obs' : $("#obs").val()
		}
		//alert($("#obs").val());
		$.ajax({
			data : prm,
			url : 'includes/incmeter.php',
			type : 'POST',
			dataType : 'html',
			success : function (response) {
				//alert(response);
				if (response.length == 7) {
					$("#asu").css('display','block');
					$("#nro").html(response);
					setTimeout(function() {
						$("#asu").css('display','none');
						location.href = '';
					}, 3000);
				}else{
					$("#aer").css('display','block');
					setTimeout(function() {$("#aer").css('display','none');}, 3000);
				}
			},
			error : function (obj,quepaso,otroobj) {
				alert('Si estas viendo esto es por que fallé');
			}
		});
	}
}
function showconf () {
	$( "#mconf" ).modal("show");
}
function validaproved () {
	/*var prm = {
		'tra' : 'aproved',
		'pro' : $("#pro").val(),
		'sub' : $("#sub").val()
	}
	$.ajax({
		data : prm,
		url : 'includes/incmeter.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			if (response == 'nothing') {
				alert("WARNING\n\rNo existe un responsable para este proyecto.");
				$( "#mconf" ).modal("hide");
				$( "#per" ).modal("show");
			}else if(response == "success"){*/
				projectstatus();
			/*}
		},
		error : function (obj,quepaso,otroobj) {
			alert("Si estas viendo esto es por que fallé");
		}
	});*/
}
function projectstatus () {
	var prm = {
		'tra' : 'prostatus',
		'pro' : $("#pro").val(),
		'sub' : $("#sub").val()
	}
	$.ajax({
		data : prm,
		url : 'includes/incmeter.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			if (response == 'success') {
				location.href = '';
			}else{
				alert("ERROR\r\nTransaction Fail.");
			}
		},
		error : function (obj,quepaso,otrobj) {
			alert("Si estas viendo esto es por que fallé");
		}
	})
}
function showesec (sec,des,obs) {
	$("#esec").modal('show');
	$("#nsec").html(sec);
	$("#msdes").val(des);
	$("#msobs").val(obs);
}
function esec () {
	var prm = {
		'tra' : 'editsec',
		'pro' : $("#spro").val(),
		'sub' : $("#ssub").val(),
		'sec' : $("#nsec").html(),
		'des' : $("#msdes").val(),
		'obs' : $("#msobs").val()
	}
	//alert(prm['pro']+prm['sub']+prm['sec']+prm['des']+prm['obs']);
	$.ajax({
		data : prm,
		url : 'includes/incsectores.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			if (response == 'success') {
				location.href='';
			}else{
				$("#esec").modal('hide');
				$.msgBox({
					title : 'ERROR',
					content : 'Si estas viendo esto es por que fallé',
					type : 'error',
					opacity : 0.6,
					autoClose : true
				});
			}
		},
		error : function (obj,que,otr) {
			$("#esec").modal('hide');
			$.msgBox({
				title : 'ERROR',
				content : 'Si estas viendo esto es por que fallé',
				type : 'error',
				opacity : 0.6,
				autoClose : true
			});
		}
	});
}
function delsec (sec) {
	$.msgBox({
		title : 'Eliminar Sector '+sec,
		content : 'Realmente desea eliminar el sector '+sec,
		type : 'confirm',
		opacity : 0.6,
		buttons : [{value:'Si'},{value:'No'}],
		success : function (resp) {
			if (resp == 'Si') {
				var prm = {
					'tra' : 'delsecv',
					'pro' : $("#spro").val(),
					'sub' : $("#ssub").val(),
					'sec' : sec
				};
				$.ajax({
					data : prm,
					url : 'includes/incsectores.php',
					type : 'POST',
					dataType : 'html',
					success : function (response) {
						if (response == 'success') {
							location.href='';
						}else{
							$.msgBox({
								title : 'ERROR',
								content : 'Si estas viendo esto es por que fallé',
								type : 'error',
								opacity : 0.6,
								autoClose : true
							});
						}
					},
					error : function (obj,que,otr) {
						$.msgBox({
							title : 'ERROR',
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
function showsubedit (pro,sub,des,fec,obs) {
	//alert('hola');
	$("#esubpro").val(pro);
	$("#esubid").val(sub);
	$("#subpro").val(des);
	$("#msfec").val(fec);
	$("#msuobs").val(obs);
	$("#meditsub").modal('show');
}
function delsub (pro,sub) {
	$.msgBox({
		title : 'Eliminar Subproyecto '+sub+'?',
		content : 'Realmente desea eliminar '+sub+'.?\r\nTenga en cuenta de que se eliminaran los sectores que incluya.',
		type : 'confirm',
		buttons : [{value:'Si'},{value:'No'}],
		opacity : 0.6,
		success : function  (resp) {
			if (resp == 'Si') {
				var prm = {
					'tra' : 'delsub',
					'pro' : pro,
					'sub' : sub
				}
				$.ajax({
					data : prm,
					url : 'includes/incsectores.php',
					type : 'POST',
					dataType : 'html',
					success : function (response) {
						if (response == 'success') {
							location.href='';
						}else{
							$.msgBox({
								title : 'ERROR',
								content : 'Si estas viendo esto es por que fallé',
								type : 'error',
								opacity : 0.6,
								autoClose : true
							});
						}
					},
					error : function (obj,que,otr) {
						$.msgBox({
							title : 'ERROR',
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
function editsub () {
	var prm = {
		'tra' : 'editsub',
		'pro' : $("#esubpro").val(),
		'sub' : $("#esubid").val(),
		'des' : $("#subpro").val(),
		'fec' : $("#msfec").val(),
		'obs' : $("#msuobs").val()
	}
	$.ajax({
		data : prm,
		url : 'includes/incsectores.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			if (response == 'success') {
				location.href='';
			}else{
				$.msgBox({
					title : 'ERROR',
					content : 'Si estas viendo esto es por que fallé',
					type : 'error',
					opacity : 0.6,
					autoClose : true
				});
			}
		},
		error : function (obj,que,otr) {
			$.msgBox({
				title : 'ERROR',
				content : 'Si estas viendo esto es por que fallé',
				type : 'error',
				opacity : 0.6,
				autoClose : true
			});
		}
	});
}
function openfc () {
	$("#fc").click();
}
function openfa () {
	$("#fa").click();
}
function showfiles () {
	$("#mfiles").modal('show');
}
function uploadfile () {
	var rarcom = document.getElementById("fc");
	var raradm = document.getElementById("fa");
	var fcom = rarcom.files[0];
	var fadm = raradm.files[0];
	if (fcom != null && fadm != null) {
		var data = new FormData();
		data.append('tra','upload');
		data.append('fcom',fcom);
		data.append('fadm',fadm);
		data.append('pro',$("#pro").val());
		data.append('sub',$("#sub").val());
		alert($("#sub").val());
		$.ajax({
			data : data,
			url : 'includes/incsectores.php',
			type : 'POST',
			contentType : false,
			processData : false,
			cache : false,
			success : function (response) {
				alert(response);
				if (response == 'success') {
					location.href = '';
				}else{
					$("#mfiles").modal('hide');
					$.msgBox({
						title : 'ERROR',
						content : 'Si estas viendo esto es por que no lo realize',
						type : 'error',
						opacity : 0.6,
						autoClose : true
					});
				}
			},
			error : function (obj,que,otr) {
				$("#mfiles").modal('hide');
				$.msgBox({
					title : 'ERROR',
					content : 'Si estas viendo esto es por que fallé',
					type : 'error',
					opacity : 0.6,
					autoClose : true
				});
			}
		});
	}else{
		if (fcom == null) $("#fc").click();
		if (fadm == null) $("#fa").click();
	}
}
function changestyle (id) {
	$("#"+id).animate({backgroundColor: "#FACC2E",borderColor: "#2D2D2D"},1000);
}