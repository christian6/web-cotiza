$(document).ready(function () {
	$("*").tooltip();
	if ($("#edit").val() == 'TRUE') {
		$('#tab li a[href=#upkeep]').tab('show');	
	}else{
		$('#tab li a[href=#list]').tab('show');
	}
});
var upkeep = function (r,n,a,d,t,c,p,de,pr,di) {
	$("#ruc").val(r);
	$("#nom").val(n);
	$("#abre").val(a);
	$("#dir").val(d);
	$("#tel").val(t);
	$("#con").val(c);
	triggerChange('pais', p);
	cboxTerritorio( { 'tra':'cbod', 'pais':p }, 'dep', de);
	cboxTerritorio({ 'tra':'cbop', 'pais':p, 'dep':de }, 'pro', pr );
	cboxTerritorio( { 'tra':'cbodi','pais':p, 'dep':de, 'pro':pr }, 'dis', di );	

	$('#tab li a[href=#upkeep]').tab('show')
}
var cboxTerritorio = function (prm,cbo,val) {
	if (cbo != '') {
		//console.log(cbo);
		$.ajax({
			url : '../includes/ciudades.php',
			type : 'POST',
			data : prm,
			dataType : 'json',
			success : function (response) {
				//console.log(response);
				if (response.status == 'success') {
					if (response.list.length > 0){
						var cbox = document.getElementById(cbo);
						cbox.innerHTML = '';
						for (var i = 0; i < response.list.length; i++) {
							var opt = document.createElement('option');
							opt.value = response.list[i].val;
							opt.text = response.list[i].name;
							cbox.appendChild(opt);
						};
						triggerChange(cbo,val);	
					}
				};
			}
		});
	};
}
var validData =  function () {
	var sts = false;
	$("#upkeep input").each(function () {
		var item = this;
		if ($.trim(item.value) == "" && item.id != 'edit') {
			$("#"+item.id).tooltip({trigger : 'focus'});
			item.focus();
			sts = false;
			return false;
		}else{
			sts = true;
		};
	});
	return sts;
}
var savedCustomers = function () {
	if (validData()) {
		var prm = {
			'tra' : 'savedCus',
			'ruc' : $("#ruc").val(),
			'nom' : $("#nom").val(),
			'abre' : $("#abre").val(),
			'dir' : $("#dir").val(),
			'tel' : $("#tel").val(),
			'con' : $("#con").val(),
			'pais' : $("#pais").val(),
			'dep' : $("#dep").val(),
			'pro' : $("#pro").val(),
			'dis' : $("#dis").val()
		}
		$.ajax({
			url : 'includes/inccustomers.php',
			type : 'POST',
			data : prm,
			dataType : 'json',
			success : function (response) {
				if (response.status == 'success') {
					msgInfo('Info','Datos guardados correctamente.',true);
					setTimeout(function() {
						location.reload();	
					}, 1600);
				};
			},
			error : function (obj,que,otr) {
				msgError(null,null,null);
			}
		});
	};
}
function triggerChange (select,val) {
	var obj = document.getElementById(select);
	obj.onChange = '';
	var A= obj.options, L= A.length;
	if (L > 0) {
		for (var i = 0; i < L; i++) {
			if (A[i].value == val) {
				obj.selectedIndex = i;
				//$("#"+select).trigger('change');
				i = L;
				return true;
			}
		}
		obj.onChange = changeCombo();
	}else{
		//console.log(select+'  '+val);
		setTimeout(function() {
			triggerChange(select,val);	
		}, 2000);
		
	}

}
var changeCombo = function  () {
	$("#edit").val('TRUE');
	document.frmcus.submit();
}
var deleteCus =  function (ruc) {
	msgConfirm('Eliminar Cliente?','Realmente desea Eliminar Cliente?',[{value:'Si'},{value:'OH, No'}],ruc);
}
var resultComfirm = function (result,ruc) {
	console.log(result);
	if (result == 'Si') {
		$.ajax({
			url : 'includes/inccustomers.php',
			type : 'POST',
			data : {'tra':'delC', 'ruc':ruc},
			dataType : 'json',
			success :function (response) {
				if (response.status == 'success') {
					msgInfo(null,null,null);
					setTimeout(function() { location.reload(); }, 1600);
				}else{
					msgError(null,'No se ha podido relizar la transacción!',null);
				}
			},
			error : function (obj,que,otr) {
				msgError(null,null,null);
			}
		});
	};
}
