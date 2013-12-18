$(document).ready(function () {
	$('input[name=fec]').datepicker({showAnim:'slide',dateFormat:'yy-mm-dd', changeYear:true, changeMonth:true});
	$('#tab a[href="#list"]').tab('show');
});
var edithingEmployee = function (d,n,a,f,p,de,pr,di,dir,t,c) {
	$("input[name=dni]").val(d);
	$("input[name=nom]").val(n);
	$("input[name=ape]").val(a);
	$("input[name=fec]").val(f);
	$("input[name=dir]").val(dir);
	$("input[name=tel]").val(t);
	$("select[name=car]").val(c);
	//$("select[name=pais]").val(p);
	$("input[name=dep]").val(de);
	$("input[name=pro]").val(pr);
	$("input[name=dis]").val(di);
	triggerChange('pais',p);
	triggerChange('car',c);
}
var changeCombo = function () {
	form1.submit();
}
var savedEmployee = function () {
	$.msgBox({
		title : 'Confirmar?',
		content : 'Desea Guardar los Cambios?',
		type : 'confirm',
		buttons : [{value:'Si'},{value:'No'}],
		opacity : 0.8,
		success : function (response) {
			if (response == 'Si') {
				var prm = {
					'tra' : 'save',
					'dni' : $("input[name=dni]").val(),
					'nom' : $("input[name=nom]").val(),
					'ape' : $("input[name=ape]").val(),
					'fec' : $("input[name=fec]").val(),
					'dir' : $("input[name=dir]").val(),
					'tel' : $("input[name=tel]").val(),
					'car' : $("select[name=car]").val(),
					'pais' : $("select[name=pais]").val(),
					'dep' : $("select[name=dep]").val(),
					'pro' : $("select[name=pro]").val(),
					'dis' : $("select[name=dis]").val()
				}
				if (prm != null) {
					$.ajax({
						url : 'includes/incemployee.php',
						type : 'POST',
						data : prm,
						success : function (response) {
							console.log(response);
							if (response == 'success') {
								location.reload();
							}
						},
						error : function (obj,que,otr) {
							msgError(null,null,null);
						}
					});
				}
			}
		}
	});	
}
function triggerChange (select,val) {
	var obj = document.getElementById(select);
	var A= obj.options, L= A.length;
	if (L > 0) {
		for (var i = 0; i < L; i++) {
			if (A[i].value == val) {
				obj.selectedIndex = i;
				$("#"+select).trigger('change');
				i = L;
				return true;
			}
		}
	}else{
		console.log(select+'  '+val);
		setTimeout(function() {
			triggerChange(select,val);	
		}, 2000);
		
	}
}
var deleteEmployee = function (dni) {
	$.msgBox({
		title : 'Confirmar?',
		content : 'Desea Eliminar Empleado?',
		type : 'confirm',
		opacity : 0.8,
		buttons : [{value:'Si'},{value:'No'}],
		success : function (response) {
			if (response == 'Si') {
				$.ajax({
					url : 'includes/incemployee.php',
					type : 'POST',
					data : { 'tra' : 'delete', 'dni' : dni },
					success : function (response) {
						console.log(response);
						if (response == 'success') {
							location.reload();
						};
					},
					error : function (obj,que,otr) {
						msgError(null,null,null);
					}
				});
			}
		}
	});
}
var consulthing = function () {
	$.ajax({
		url : 'includes/incemployee.php',
		type : 'POST',
		data : { 'tra' : 'clo', 'emp' : $("#emp").val() },
		dataType : 'html',
		success : function (response) {
			//console.log(response);
			response = response.split('|');
			if (response[0] == 'success') {
				$(".form-horizontal").removeClass("hide");
				if (response[1] == 'exists') {
					$("#dnin").val(response[2]);
					$("#user").val(response[3]);
					$("#user").attr('disabled',true);
				}else if(response[1] == 'noexists'){
					$("#dnin").val(response[2]);
					$("#user").val('');
					$("#user").attr('disabled',false);
				}
			}
		},
		error : function (obj,que,otr) {
			msgError(null,null,null);
		}
	});
}
var savedlogin = function () {
	var $po = $('#pwdn'),
			$pc = $('#pwdc');
		if ($po.val() == $pc.val()) {
			$.ajax({
				url : 'includes/incemployee.php',
				type : 'POST',
				data : { 'tra':'slog','dni':$("#dnin").val(),'user':$('#user').val(),'pwd': $po.val()},
				dataType : 'html',
				success : function (response) {
					console.log(response);
					if (response == 'success') {
						location.reload();
					}
				},
				error : function (obj,que,otr) {
					msgError(null,null,null);
				}
			});
		}else{
			msgWarning(null,'El password no coinciden?',true);
			$po.val('');
			$pc.val("");
		}
}