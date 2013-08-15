function openfile () {
	$(" #adda ").modal("show");
}
function closefile () {
	$(" #adda ").modal("hide");
}
var win;
function openaddm () {

	var myLeft = (screen.width-800)/2;
	var myTop = (screen.height-700)/2;
	var plane = document.getElementById("plane").innerHTML;
	var proid = document.getElementById("proid").innerHTML;
	//alert(plane);

	var caracteristicas="toolbar=0, location=0, directories=0, resizable=0, scrollbars=yes, height=600, width=800, top="+myTop+", left="+myLeft;
	win = window.open(
	"includes/incaddmat.php?"+"plane="+plane+"&proid="+proid,
	"Agregar Material",caracteristicas);

	//win.onunload = function() {
	//	intvl = setInterval(testClosedProperty,100);
	//}
}
/*
function testClosedProperty() {
  if (win.closed) {
    if (intvl) clearInterval(intvl);
    	location.href = '';
  }
}
*/
function aproved () {
	$.msgBox({
		title : "Mensaje",
		content : "Seguro que desea aprobar esta \r\nlista de materiales para este sector?",
		type : "confirm",
		opacity: 0.8,
		buttons : [{value: "Si"}, {value: "Cancelar"}],
		success : function (result) {
			if (result == 'Si') {
				var pro = document.getElementById("txtproid").value;
				var sub = document.getElementById("txtsubpro").value;
				var sec = document.getElementById("txtplane").value;
				var prm = {
					'tra' : 'apro',
					'pro' : pro,
					'sub' : sub,
					'sec' : sec,
				}
				$.ajax({
					data : prm,
					url : 'includes/incopmetrado.php',
					type : 'POST',
					success : function(response){
						if (response == "hecho") {
							location.href = '';
						}else{
							return;
						}
					},
					error: function(objeto, quepaso, otroobj){
						alert("Estas viendo esto por que fallé\r\n"+"Pasó lo siguiente: "+quepaso + objeto.id + otroobj);
					}
				});
			}
		}
	});
	/*if (confirm("Seguro que desea aprobar esta \r\nlista de materiales para este sector?")) {
		
	}*/
}

function viewlist () {
	$( "#vm" ).modal("show");
}
function conedit (matid) {
	if (matid != "") {
		var pro = document.getElementById("txtpro").value;
		var sub = document.getElementById("txtsub").value;
		var sec = document.getElementById("txtsec").value;
		var prm = {
			'tra' : 'conedit',
			'mid' : matid,
			'pro' : pro,
			'sub' : sub,
			'sec' : sec
		}
		$.ajax({
			data : prm,
			url : 'includes/incmeter.php',
			type : 'POST',
			success : function(response){
				if (response != "none") {
					var cad = response.split('|');
					$( "#txtmatid" ).val(cad[0]);
					$( "#txtnom" ).val(cad[1]);
					$( "#txtmed" ).val(cad[2]);
					$( "#txtcant" ).val(cad[3]);
					$( "#edit" ).modal( 'show' );
				}
			},
			error: function(objeto, quepaso, otroobj){
				alert("Estas viendo esto por que fallé\r\n"+"Pasó lo siguiente: "+quepaso);
			}
		});
	}
}
function editope () {
	var matid = $( "#txtmatid" ).val();
	var cant = $( "#txtcant" ).val();
	if (matid != "" && cant != "") {
		var pro = $("#txtpro").val();
		var sub = $("#txtsub").val();
		var sec = $("#txtsec").val();
		var prm = {
			'tra' : 'upcant',
			'matid' : matid,
			'cant' : cant,
			'pro' : pro,
			'sub' : sub,
			'sec' : sec
		}
		$.ajax({
			data : prm,
			url : 'includes/incmeter.php',
			type : 'POST',
			success : function(response){
				if (response == 'hecho') {
					location.href = '';
				}
			},
			error: function(objeto, quepaso, otroobj){
				alert("Estas viendo esto por que fallé\r\n"+"Pasó lo siguiente: "+quepaso);
			}
		});
	}
}
function delmat (matid) {
	if (matid != "") {
		if (confirm("Realamente Desea Eliminar el Material?")) {
			var pro = $("#txtpro").val();
			var sub = $("#txtsub").val();
			var sec = $("#txtsec").val();
			var prm = {
				'tra' : 'delmat',
				'matid' : matid,
				'pro' : pro,
				'sub' : sub,
				'sec' : sec
			}
			$.ajax({
				data : prm,
				url : 'includes/incmeter.php',
				type : 'POST',
				success : function(response){
					if (response == 'hecho') {
						location.href = '';
					}
				},
				error: function(objeto, quepaso, otroobj){
					alert("Estas viendo esto por que fallé\r\n"+"Pasó lo siguiente: "+quepaso);
				}
			});
		}
	}
}
function addmat () {
	$("#mnot").modal("show");
}
function resizesmall () {
	$( "#plano" ).animate({
		height: "2em"
	},1000);
	$("#vpdf").css('display','none');
}
function resizefull () {
	$( "#plano" ).animate({
		height: "31em"
	},1000);
	$( "#vpdf").css('display','block');
}
function openfull () {
	$( "#fullscreen-icr" ).show("clip",{},1600);
	$("#fullpdf").css('display','block');
}
function closefull () {
	$( "#fullscreen-icr" ).hide("clip",{},2000);
}
function saveobs () {
	var prm = {
		'tra' : 'saveobs',
		'pro' : $("#pro").val(),
		'sub' : $("#sub").val(),
		'sec' : $("#sec").val(),
		'top' : $("#cbovent").val(),
		'obs' : $("#obs").val()
	}
	$.ajax({
		data : prm,
		url : 'includes/incmeter.php',
		type : 'POST',
		success : function (response) {
			alert(response);
			if (response == 'success') {
				location.href = 'addmatope.php?'+'pro='+$("#pro").val()+'&sub='+$("#sub").val()+'&sec='+$("#sec").val();
			}
		},
		error : function (obj,quepaso,otroobj) {
			$("#mnot").modal("hide");
			$.msgBox({
				title : 'ERROR',
				content : 'Si estas viendo esto es por fallé',
				type : 'error',
				autoClose : false
			});
		}
	});
}
function refaddmat () {
	location.href = 'addmatope.php?'+'pro='+$("#pro").val()+'&sub='+$("#sub").val()+'&sec='+$("#sec").val();
}