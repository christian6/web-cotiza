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
		title : 'Aprobar?',
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

						}
					},
					error : function (obj.que,otr) {
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