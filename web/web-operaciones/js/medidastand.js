function searchmeter (e) {
	var txtse = document.getElementById("txtse");
	var evt = e ? e : event;
    var key = window.Event ? evt.which : evt.keyCode;
    if (key == 13) {
    	//alert(txtse.value);
    	search(txtse.value);
    };
}
function searchbtn () {
	var txtse = document.getElementById("txtse");
	search(txtse.value);
}
function search(des){
	if ($.trim(des) != "") {
		var prm = {
			'tra' : 'med',
			'nom' : des
		}
		$.ajax({
			data : prm,
			url : 'includes/incmeter.php',
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
				alert("Estas viendo esto por que fallé\r\n"+"Pasó lo siguiente: "+quepaso);
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
			url : 'includes/incmeter.php',
			type : 'POST',
			success : function(response){
				//alert(response);
            if (response != "") {
                //setTimeout(function() {
                $( "#data" ).html(response);
                //}, 600);    
            }else{
                alert("Se produjo un Error.");
            }
	        },
			error: function(objeto, quepaso, otroobj){
				alert("Estas viendo esto por que fallé\r\n"+"Pasó lo siguiente: "+quepaso);
			}
		});
	}else{
		return;
	}
}
function savemat(){
	if ($("#cant").val() == "") {
		alert('WARNING\r\nIngrese un cantidad!');
		return;
	}
	var prm = {
		'tra' : 'save',
		'pro' : $("#txtpro").val(),
		'sub' : $("#txtsub").val(),
		'sec' : $("#txtsec").val(),
		'cod' : $("#cod").html(),
		'cant' : $("#cant").val()
	}
	$.ajax({
		data : prm,
		url : 'includes/incopmetrado.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			if (response == 'success') {
				tmpmodify();
				hideaddmat();
			}else if(response == "exists"){
				$.msgBox({
					title : 'Mensaje',
					content : 'El Material que esta intentando guardar ya existe.',
					type : 'warning',
					autoClose : true,
					opacity : 0.6
				});
			}
		},
		error : function (obj,quepaso,otroobj) {
			alert('Si estas viendo esto es por que fallé');
		}
	});	
}
function delmodifymat (cod) {
	$.msgBox({
		title : 'Mensaje',
		content : 'Desea Eliminar el Material?',
		type : 'confirm',
		buttons : [{value:'Si'},{value:'No'}],
		opacity : 0.4,
		success : function (result) {
			if (result == 'Si') {
				var prm = {
					'tra' : 'delmat',
					'pro' : $("#txtpro").val(),
					'sub' : $("#txtsub").val(),
					'sec' : $("#txtsec").val(),
					'cod' : cod
				}
				$.ajax({
					data : prm,
					url : 'includes/incopmetrado.php',
					type : 'POST',
					dataType : 'html',
					success : function (response) {
						if (response == 'success') {
							tmpmodify();
						}else{
							$.msgBox({
								title : 'Mensaje',
								content : 'Parece que hay un error.',
								type : 'error',
								autoClose : true,
								opacity : 0.6
							});
						}
					},
					error : function (obj,quepaso,otroobj) {
						alert('Si estas viendo esto es por que fallé');
					}
				});	
			}
		}
	});
}
function modifyCant (mid,cant) {
	//console.log(mid+' '+cant);
	var prm = {
		'tra' : 'editmat',
		'pro' : $("#txtpro").val(),
		'sub' : $("#txtsub").val(),
		'sec' : $("#txtsec").val(),
		'mid' : mid,
		'can' : cant
	}
	$.ajax({
		data : prm,
		url : 'includes/incopmetrado.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			console.log(response);
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
function confirmok () {
	var ori = $("#pto").val();
	var nue = $("#ptn").val();
	tmpmodify();
	console.log(ori +' '+nue);
	if (parseFloat(nue) < parseFloat(ori) && parseFloat(nue) > 0 || parseFloat(nue) == parseFloat(ori)) {
		saveconfirm();
		console.log('nuevo es menor');
	}else if(parseFloat(nue) > parseFloat(ori) && parseFloat(nue) > 0){
		console.log('nuevo es mayor');
		$("#mconfirm").modal('show');
	}
}
function saveconfirm () {
	var prm = {
		'tra' : 'confirm',
		'pro' : $("#txtpro").val(),
		'sub' : $("#txtsub").val(),
		'sec' : $("#txtsec").val(),
		'obs' : $("#txtmot").val()
	}
	$.ajax({
		data : prm,
		url : 'includes/incopmetrado.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			//alert(response);
			if (response == 'success') {
				$("#mconfirm").modal('hide');
				hideadicionales();
				$("#msgmo").show('blind',{},800);
				$("#btnadi").attr('disabled','disabled');
			}
		},
		error : function (obj,que,otr) {
			$.msgBox({
				title : 'Error',
				content : 'Si estas viendo esto es por que falle',
				type : 'error',
				opacity : 0.6,
				autoClose : true
			});
		}
	});
}
function very () {
	var ori = $("#pto").val();
	var nue = $("#ptn").val();
	console.log(ori +' -> '+ nue);
	if (ori != nue) {
		$("#btnadi").attr('disabled','disabled');
	}
}
$(document).ready(function () {
	tmpmodify();
	//\very();
});
function onobs () {
	$("#obsec").animate({ height : "7em" },800);
}
function obsblur () {
	if ($("#obsec").val() == "") {
		$("#obsec").animate({ height : "1.5em" },800);
	}
}
function savemsgsec () {
	var prm = {
		'tra' : 'msgsec',
		'pro' : $("#txtpro").val(),
		'sub' : $("#txtsub").val(),
		'sec' : $("#txtsec").val(),
		'obs' : $("#obsec").val(),
		'tfr' : 'o'
	}
	$.ajax({
		data : prm,
		url : 'includes/incpedido.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			//alert(response);
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