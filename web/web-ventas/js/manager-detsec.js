var msec = false;
$(document).ready(function () {
	hidemsec();
	disableded();
});
function showmsec () {
	if (msec) {
		hidemsec();
	}else{
		$( "#msec" ).show('blind',{},800);
		$("#btnmsec i").removeClass('icon-chevron-down').addClass('icon-chevron-up');
		msec = true;
	}
}
function hidemsec () {
	$( "#msec" ).hide('blind',800);
	$("#btnmsec i").removeClass('icon-chevron-up').addClass('icon-chevron-down');
	msec = false;
}
function disableded () {
	if ($("#btnmsec").val() == '0'){
		showmsec();
	}else{
		hidemsec();
	}
}
function aprobar () {
	$.msgBox({
		title : 'Aprobar Modificación',
		content : 'Seguro que desea aprobar la modificación?',
		type : 'confirm',
		buttons : [ {value : 'Si'},{value : 'No'} ],
		opacity : 0.6,
		success : function (result) {
			if (result == 'Si') {
				var prm = {
					'tra' : 'apmsec',
					'pro' : $("#pro").val(),
					'sub' : $("#sub").val(),
					'sec' : $("#sec").val()
				}
				$.ajax({
					data : prm,
					url : 'includes/incmanagersec.php',
					type : 'POST',
					dataType : 'html',
					success : function (response) {
						if (response == 'success') {
							location.href='';
						}
					},
					error : function (obj,que,otr) {
						$.msgBox({
							title : 'Error',
							content : 'Si estas viendo es por que fallé',
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
function anular () {
	$.msgBox({
		title : 'Anular Modificación',
		content : 'Seguro que desea anular la modificación?',
		type : 'confirm',
		buttons : [ {value : 'Si'},{value : 'No'} ],
		opacity : 0.6,
		success : function (result) {
			if (result == 'Si') {
				var prm = {
					'tra' : 'anmsec',
					'pro' : $("#pro").val(),
					'sub' : $("#sub").val(),
					'sec' : $("#sec").val()
				}
				$.ajax({
					data : prm,
					url : 'includes/incmanagersec.php',
					type : 'POST',
					dataType : 'html',
					success : function (response) {
						alert(response);
						if (response == 'success') {
							location.href='';
						}
					},
					error : function (obj,que,otr) {
						$.msgBox({
							title : 'Error',
							content : 'Si estas viendo es por que fallé',
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
function savemsgsec () {
	var prm = {
		'tra' : 'msgsec',
		'pro' : $("#pro").val(),
		'sub' : $("#sub").val(),
		'sec' : $("#sec").val(),
		'obs' : $("#obsec").val(),
		'tfr' : 'v'
	}
	$.ajax({
		data : prm,
		url : 'includes/incmanagersec.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			alert(response);
			if (response == 'success') {
				location.href='';
			}else{
				$.msgBox({
					title : 'Error',
					content : 'Al parecer hay un error, al publicar su mensage.',
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
function onobs () {
	$("#obsec").animate({ height : "7em" },800);
}
function obsblur () {
	if ($("#obsec").val() == "") {
		$("#obsec").animate({ height : "1.5em" },800);
	}
}
function shownewadi () {
	$("#mnewsec").modal("show");
}
function savenewadi () {
	$("#mlistadi").modal("hide");
	$.msgBox({
		title : 'confirmar',
		content : 'Seguro(a) de generar Adicional',
		type : 'confirm',
		opacity : 0.6,
		buttons : [{value:'Si'},{value:'No'}],
		success : function (result) {
			if (result == 'Si') {
				var mid = document.getElementsByName("maid");
				var arrm = new Array();
				var j = 0;
				for (var i = 0; i < mid.length; i++) {
					if (mid[i].checked) {
						arrm[j] = mid[i].value;
						j++;
					}
				}
				if (j > 0) {
					var prm = {
						'tra' : 'newadi',
						'pro' : $("#pro").val(),
						'sub' : $("#sub").val(),
						'sec' : $("#sec").val(),
						'noc' : $("#noc").val(),
						'des' : $("#adides").val(),
						'obs' : $("#adiobs").val(),
						'mat' : arrm
					}
					$.ajax({
						data : prm,
						url : 'includes/incmanagersec.php',
						type : 'POST',
						dataType : 'html',
						success : function (response) {
							alert(response);
							if (response == 'success') {
								location.href ='';
							}else{
								$.msgBox({
									title : 'Error',
									content : 'Al parecer hay un error.',
									type : 'error',
									opacity : 0.6,
									autoClose : true
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
				}else{
					$.msgBox({
						title : 'Warning',
						content : 'Al parecer no ha seleccionado los materiales.',
						type : 'warning',
						opacity : 0.6,
						autoClose : true
					});
					setTimeout(function() {$("#mlistadi").modal("show");}, 2500);
				}
			}
		}
	});
}
function nextadicional () {
	$("#mnewsec").modal("hide");
	$("#mlistadi").modal("show");
}