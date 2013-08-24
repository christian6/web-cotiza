function openfile () {
	$(" #adda ").modal("show");
}
function closefile () {
	$(" #adda ").modal("hide");
}
var win;
/*function openaddm () {

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
}*/
/*
function testClosedProperty() {
  if (win.closed) {
    if (intvl) clearInterval(intvl);
    	location.href = '';
  }
}
*/
function showaddnm () {
	$("#mmat").modal('show');
}
function open() {
	$("#upl").click();
    //$('#drop a').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        //$(this).parent().find('input').click();
        //uploadAjax();
    //});
    //uploadAjax();
}
function uploadAjax(){
	var inputFileImage = document.getElementById("upl");
	var file = inputFileImage.files[0];
	if (file != null) {
		//alert('enviado archivo');
		var data = new FormData();
		data.append('archivo',file);
		data.append('pro', $("#pro").val());
		data.append('sub', $("#sub").val());
		data.append('sec', $("#sec").val());
		var url = "includes/upload.php";
		$.ajax({
			data : data,
			url : url,
			type : 'POST',
			contentType : false,
			processData : false,
			cache : false,
			/*complete : function (obj,success) {
				alert(success);
			},*/
			success : function (response) {
				//alert(response);
				if (response == "success") {
					location.href = '';
				}else{
					alert('Algo Salio Mal');
				}
			},
			error : function (obj,quepaso,otrobj) {
				alert("ERROR\n\rSi estas viendo esto es por que falle");
			}
		});
	}else{
		alert('archivo vacio');
	}
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
function showaddmat () {
	$("#maddmat").show('blind');
}
function closeaddmat () {
	$("#maddmat").hide('blind',{},1000);	
}
function searchmeter (e) {
	//var txtse = $("#txtse").val();
	var evt = e ? e : event;
    var key = window.Event ? evt.which : evt.keyCode;
    if (key == 13) {
    	//alert(txtse.value);
    	search($.trim($("#nommat").val()));
    }
}
function search(des){
	if ($.trim(des) != "") {
		//alert('estamos en search');
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
                    $( "#matmed" ).html(response);
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
	var nom = document.getElementById("nommat").value;
	var cbo = document.getElementById("cbomed");
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
function savemat () {
	if ($("#cod").html() != '') {
		var prm = {
			'tra' : 'savedata',
			'pro' : $("#pro").val(),
			'sub' : $("#sub").val(),
			'sec' : $("#sec").val(),
			'id' : $("#cod").html(),
			'cant' : $("#cant").val()
		}
		$.ajax({
			data : prm,
			url : 'includes/incmeter.php',
			type : 'POST',
			dataType : 'html',
			success : function (response) {
				//alert(response);
				if (response == 'success') {
					//$("#tbld").html('');
					listtbl();
					$("#matmed").html('');
					$("#data").html('');
					$("#cant").val('');
				}else if(response == 'exists'){
					$.msgBox({
						title : 'ALERTA',
						content : 'Oh no!, parace que el material ya esta en tu lista, mejor verificalo tu mismo.',
						type : 'warning',
						opacity : 0.5
					});
				}
			},
			error : function (obj,quepaso,otroobj) {
				alert("Si estas viendo esto es por que fallé");
			}
		})
	}
}
function listtbl() {
	//alert('lista');
	var prm = {
		'tra' : 'listtbl',
		'pro' : $("#pro").val(),
		'sub' : $("#sub").val(),
		'sec' : $("#sec").val()
	}
	$.ajax({
		data : prm,
		url : 'includes/incmeter.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			//alert(response);
			if (response != 'nothing') {
				$("#tbld").html(response);
			}
		},
		error : function (obj,quepaso,otroobj) {
			alert("Si estas viendo esto es por que fallé");
		}
	});
}
/**/

function delmat (id,nom,med) {
	$.msgBox({
		type : 'confirm',
		title : 'Eliminar Material',
		content : 'Realmente Desea Eliminar?<br>'+nom+'<br>'+med,
		buttons : [{value:'Si'},{value: 'No'}],
		success : function (result) {
			if (result == 'Si') {
				var prm = {
					'tra' : 'delmat',
					'id' : id,
					'pro' : $('#pro').val(),
					'sub' : $('#sub').val(),
					'sec' : $('#sec').val()
				}
				$.ajax({
					data : prm,
					url : 'includes/incmeter.php',
					type : 'POST',
					success : function (response) {
						if (response == 'success') {
							listtbl();
							$.msgBox({
								type : 'info',
								title : 'SUCCESS',
								content : 'Se ha realizado la transacción correctamente.',
								opacity : 0.6,
								autoClose : true
							});
						}else{
							$.msgBox({
								type : 'error',
								title : 'ERROR',
								content : 'Si estas viendo esto es por que me perdi.',
								opacity : 0.6
							});
						}
					},
					error : function (obj,que,otr) {
						$.msgBox({
							type : 'error',
							title : 'ERROR',
							content : 'Si estas viendo esto es por que fallé',
							opacity : 0.6,
							autoClose : true
						});
					}
				});
			}
		},
		opacity : 0.8
	});
}
function showedit (id,nom,med,cant) {
	$("#mid").val(id);
	$("#mnom").val(nom);
	$("#mmed").val(med);
	$("#mcant").val(cant);
	$("#modmat").modal('show');
}
function editmat () {
	var prm = {
			'tra' : 'editmat',
			'id' : $("#mid").val(),
			'pro' : $('#pro').val(),
			'sub' : $('#sub').val(),
			'sec' : $('#sec').val(),
			'cant' : $("#mcant").val()
		}
	$.ajax({
		data : prm,
		url : 'includes/incmeter.php',
		type : 'POST',
		success : function (response) {
			//alert(response);
			if (response == 'success') {
				listtbl();
				$("#modmat").modal('hide');
				$.msgBox({
					type : 'info',
					title : 'SUCCESS',
					content : 'Se ha realizado la transacción correctamente.',
					opacity : 0.6,
					autoClose : true
				});
			}else{
				$("#modmat").modal('hide');
				$.msgBox({
					type : 'error',
					title : 'ERROR',
					content : 'Si estas viendo esto es por que me perdi.',
					opacity : 0.6
				});
			}
		},
		error : function (obj,que,otr) {
			$("#modmat").modal('hide');
			$.msgBox({
				type : 'error',
				title : 'ERROR',
				content : 'Si estas viendo esto es por que fallé',
				opacity : 0.6,
				autoClose : true
			});
			$("#modmat").modal('show');
		}
	});
}
function showsol () {
	$("#mmat").modal('show');
}
function savenmat () {
	var prm = {
		'tra' : 'newmat',
		'matid' : $("#matid").val(),
		'nom' : $("#nom").val(),
		'med' : $("#med").val(),
		'und' : $("#und").val(),
		'mar' : $("#mar").val(),
		'mod' : $("#mod").val(),
		'aca' : $("#aca").val()
	}
	$.ajax({
		data : prm,
		url : 'includes/incmeter.php',
		type : 'POST',
		success : function (response) {
			//alert(response);
			if (response == 'success') {
				$("#modmat").modal('hide');
				$.msgBox({
					type : 'info',
					title : 'SUCCESS',
					content : 'Se ha realizado la transacción correctamente.',
					opacity : 0.6,
					autoClose : true
				});
				location.href='';
			}else{
				$("#mmat").modal('hide');
				$.msgBox({
					type : 'error',
					title : 'ERROR',
					content : 'Si estas viendo esto es por que me perdi.',
					opacity : 0.6
				});
			}
		},
		error : function (obj,que,otr) {
			$("#mmat").modal('hide');
			$.msgBox({
				type : 'error',
				title : 'ERROR',
				content : 'Si estas viendo esto es por que fallé',
				opacity : 0.6,
				autoClose : true
			});
		}
	});
}