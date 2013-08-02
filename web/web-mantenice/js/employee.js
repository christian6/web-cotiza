$(function (){
	$("#fecnac").datepicker({ minDate: "" , maxDate: "0" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd"});
	$("#frmemployee").find(':input').each(function () {
		var item = this;
		item.disabled = true;
	});
});
function alonenum (event) {
	var code = ('charCode' in event) ? event.charCode : event.keyCode;
	return (code > 48 && code < 57);
}
function alonechar (event){
	var code = ('charCode' in event) ? event.charCode : event.keyCode;
	return (code < 48 || code > 57);
}
function nuevo () {
	$("#frmemployee").find(':input').each(function () {
		var item = this;
		item.disabled = false;
	});
}
function reset () {
	$( "#frmemployee" ).find(':input').each(function () {
		var item = this;
		item.value('');
	});
	$( "#frmemployee" ).find(':select').each(function () {
		var item = this;
		item.selectedIndex = 0;
	});
}
function validdata () {
	$( "#frmemployee" ).find(':input').each(function () {
		var item = this;
		if (item.value == '') {
			alert(item.id);
			$( "#malert" ).css('display','block');
			item.focus();
			return false;
		}
	});
	return true;
}
function savedata () {
	var val = validdata();
	if (val == true) {
		//alert($("#pro").val());
		//alert('dentro de if');
		var prm = {
			'tra' : 'save',
			'dni' : $("#dni").val(),
			'nom' : $("#nom").val(),
			'ape' : $("#ape").val(),
			'fec' : $("#fecnac").val(),
			'pai' : $("#pais").val(),
			'dep' : $("#dep").val(),
			'pro' : $("#pro").val(),
			'dis' : $("#dis").val(),
			'dir' : $("#dir").val(),
			'tel' : $("#tel").val(),
			'car' : $("#car").val()
		}
		//alert('pasamos');
		$.ajax({
			data : prm,
			url : 'includes/incemployee.php',
			type : 'POST',
			dataType : 'html',
			beforeSend : function (obj) {
				$("#prog").css('display','block');
				setTimeout(function() {}, 1000);
			},
			success : function (response) {
				//alert(response);
				if (response == 'hecho') {
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
		url : 'includes/incemployee.php',
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