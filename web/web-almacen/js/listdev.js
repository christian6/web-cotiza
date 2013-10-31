$(document).ready(function () {
	$("#fi,#ff").datepicker({ maxDate : '0', showAnim : 'slide', changeYear : true, changeMonth : true, dateFormat : "yy-mm-dd"});
	firstlist();
});
function changeradio () {
	$("[name=rbtn]").each(function () {
		var item = this;
		if (item.checked) {
			$("input[type=text]").each(function () {
				var txt = this;
				if (item.value == 'c') {
					if (txt.id != 'cod') {
						txt.disabled = true;
					}else{
						txt.disabled = false;
					}
				}else if (item.value == 'f' ){
					if (txt.id == 'cod') {
						txt.disabled = true;
					}else{
						txt.disabled = false;
					}
				}
			});
		}
	});
}
function listdev () {
	var prm = {};
	$chk = $("[name=rbtn]").each(function () {
		var item = this;
		if (item.checked) {
			if (item.value == 'c') {
				prm = { 'tra' : 'list', 'cod' : $('#cod').val() }
			}else if(item.value == 'f'){
				if ($('#fi').val() != '' && $('#ff').val() == '' ) {
					prm = { 'tra' : 'list', 'fi' : $('#fi').val() }
				}else if ($('#fi').val() != '' && $('#ff').val() != '' ) {
					prm = { 'tra' : 'list', 'fi' : $('#fi').val(), 'ff' : $('#ff').val() }
				}
			}
		}
	});
	if (Object.keys(prm).length > 0 ) {
		$.ajax({
			url : 'include/incdevolucion.php',
			type : 'POST',
			data : prm,
			dataType : 'html',
			success : function (response) {
				console.log(response);
				response = response.split('|');
				if (response[1] == 'success') {
					$("#tdet").html(response[0]);
				}
			},
			error : function (obj,que,otr) {
				msgError(null,null,null);
			}
		});
	}
}
function firstlist () {
	$.ajax({
		url : 'include/incdevolucion.php',
		type : 'POST',
		data : { 'tra' : 'list', 'limit' : 10 },
		dataType : 'html',
		success : function (response) {
			response = response.split('|');
			if (response[1] == 'success') {
				$("#tdet").html(response[0]);
			}
		},
		error : function (obj,que,otr) {
			msgError(null,null,null);
		}
	});
}