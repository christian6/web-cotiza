$(function (){
	if( $("#new").val() != 'TRUE' ){
		$("#frmcustomer").find(':input').each(function () {
			var item = this;
			item.disabled = true;
		});
	}
});
function alonenum (event) {
	var code = ('charCode' in event) ? event.charCode : event.keyCode;
	return (code >= 48 && code <= 57);
}
function alonechar (event){
	var code = ('charCode' in event) ? event.charCode : event.keyCode;
	return (code < 48 || code > 57);
}
function nuevo () {
	$("#frmcustomer").find(':input').each(function () {
		var item = this;
		item.disabled = false;
	});
	$("#new").val('TRUE');
}
function reset () {
	$( "#frmcustomer" ).find(':input').each(function () {
		var item = this;
		item.value('');
	});
	$( "#frmcustomer" ).find(':select').each(function () {
		var item = this;
		item.selectedIndex = 0;
	});
}
function validdata () {
	var bool = false;
	$( "#frmcustomer" ).find(':input').each(function () {
		var item = this;
		if (item.value == '') {
			//alert(item.id);
			$( "#malert" ).css('display','block');
			item.focus();
			bool = false;
			return false;
		}else{
			bool = true;
		}
	});
	return bool;
}
function savedata () {
	var val = validdata();
	if (val == true) {
		//alert($("#pro").val());
		//alert('dentro de if');
		var prm = {
			'tra' : 'save',
			'ruc' : $("#ruc").val(),
			'rz' : $("#rz").val(),
			'abr' : $("#abr").val(),
			'pai' : $("#pais").val(),
			'dep' : $("#dep").val(),
			'pro' : $("#pro").val(),
			'dis' : $("#dis").val(),
			'dir' : $("#dir").val(),
			'tel' : $("#tel").val(),
			'con' : $("#cont").val(),
		}
		//alert('pasamos');
		$.ajax({
			data : prm,
			url : 'includes/inccustomer.php',
			type : 'POST',
			dataType : 'html',
			beforeSend : function (obj) {
				$("#prog").css('display','block');
				setTimeout(function() {}, 1000);
			},
			success : function (response) {
				//alert(response);
				if (response == 'hecho') {
					$("#asu").css('display','block');
					$("#prog").css('display','none');
					setTimeout(function() {
						$("#asu").css('display','none');
						location.href = '';
					}, 3000);
				}else{
					$("#aex").css('display','block');
				}
			},
			error : function (obj,quepaso,otrobj) {
				alert('Si estas viendo esto es por que falle '+quepaso);
			}
		});
	}else{
		return false;
	}
}
function listemployee () {
	var prm = {
		'tra' : 'list'
	}
	$.ajax({
		data : prm,
		url : 'includes/inccustomer.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			if (response != 'nada') {
				$("#list").html(response);
			}else{
				
			}
		},
		error : function (obj,quepaso,otrobj) {
			alert('Si estas viendo esto es por que falle');
		}
	})
}