$(document).ready(function  () {
	resizesmall();
});
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
					////console.log('des '+response);
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
	var nom = document.getElementById("matnom").value;
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
				//console.log('medida '+response);
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
	if ($.trim($("#cod").html()) != '') {
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
					$("#matnom").val('');
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

var searchProyecto = function  (it,e) {
	var evt = e ? e : event;
    var key = window.Event ? evt.which : evt.keyCode;
    if (key == 13) {
    	//alert(txtse.value);
    	//search($.trim($("#nommat").val()));
     var prm = {
	   	'tra' : 'lpro',
	   }
	   if (it.id == 'nro') {
	   	prm['nro'] = it.value;
	   	prm['tipo'] = 'nro';
	   }else{
	   	prm['nom'] = it.value;
	   	prm['tipo'] = 'des';
	   }
  
		$.ajax({
			url : 'includes/incmeter.php',
			type : 'POST',
			data : prm,
			success : function (response) {
				//console.log(response);
				response = JSON.parse(response);
				if (response.status == 'success') {
					var tb = document.getElementById('lpro');
					tb.innerHTML = "";
					for (var i = 0; i < response.list.length; i++) {
						var tr = document.createElement('tr'),	item = document.createElement('td'), cod = document.createElement('td'),
							pro = document.createElement('td'),cli = document.createElement('td'),te = document.createElement('td'),
							btn = document.createElement('btn'),ico = document.createElement('i');
						item.setAttribute('id','tc');
						te.setAttribute('id','tc');
						ico.setAttribute('class','icon-chevron-right');
						btn.setAttribute('class', "btn btn-mini btn-success t-d");
						btn.appendChild(ico);
						btn.setAttribute('onClick',"listSector('"+response.list[i].proyectoid+"')");
						item.innerHTML = i + 1;
						cod.innerHTML = response.list[i].proyectoid;
						pro.innerHTML = response.list[i].descripcion;
						cli.innerHTML = response.list[i].razonsocial;
						te.appendChild(btn);
						tr.appendChild(item);
						tr.appendChild(cod);
						tr.appendChild(pro);
						tr.appendChild(cli);
						tr.appendChild(te);
						tb.appendChild(tr);
					};
					
				};
			},
			error : function (obj,que,otr) {
				msgError(null,null,null);
			}
		});
}
}
var showOpenCopyPro = function () {
	$("#mlp").modal('show');
}
var listSector = function  (pro) {
	$("#mb1").hide('slide',900);
	listSubproyecto(pro);
	$.ajax({
		url : 'includes/incmeter.php',
		type : 'POST',
		data : { 'tra' : 'lsec','pro' : pro },
		dataType : 'json',
		complete : function (obj, com) {
			if (com == 'success') {
				$("#mb2").show('slide',2100);
			}
		},
		success : function (response) {
			//console.log(response);
			if (response.status == 'success') {
				var tb = document.getElementById('lsec');
				tb.innerHTML = "";
				for (var i = 0; i < response.list.length; i++) {
					var tr = document.createElement('tr'),	item = document.createElement('td'), cod = document.createElement('td'),
						sec = document.createElement('td'),te = document.createElement('td'),	btn = document.createElement('btn'),ico = document.createElement('i');
					item.setAttribute('id','tc');
					te.setAttribute('id','tc');
					ico.setAttribute('class','icon-share-alt');
					btn.setAttribute('class', "btn btn-mini btn-info t-d");
					btn.appendChild(ico);
					btn.setAttribute('onClick',"listMaterials('"+pro+"','','"+response.list[i].nroplano+"')");
					item.innerHTML = i + 1;
					cod.innerHTML = response.list[i].nroplano;
					sec.innerHTML = response.list[i].sector;
					te.appendChild(btn);
					tr.appendChild(item);
					tr.appendChild(cod);
					tr.appendChild(sec);
					tr.appendChild(te);
					tb.appendChild(tr);
				};
			};
		},
		error : function (obj,que,otr) {
			msgError(null,null,null);
		}
	});
}
var listSubproyecto = function (pro) {
	$.ajax({
		url : 'includes/incmeter.php',
		type : 'POST',
		data : {'tra' : 'lsub', 'pro' : pro },
		dataType : 'json',
		success : function (response) {
			//console.log(response);
			if (response.status == 'success') {
				var tb = document.getElementById('lsub');
				tb.innerHTML = "";
				for (var i = 0; i < response.list.length; i++) {
					var tr = document.createElement('tr'),	item = document.createElement('td'), cod = document.createElement('td'),
						sub = document.createElement('td'),te = document.createElement('td'),	btn = document.createElement('btn'),ico = document.createElement('i');
					item.setAttribute('id','tc');
					te.setAttribute('id','tc');
					ico.setAttribute('class','icon-chevron-right');
					btn.setAttribute('class', "btn btn-mini btn-success t-d");
					btn.appendChild(ico);
					btn.setAttribute('onClick',"listsubsec('"+pro+"','"+response.list[i].subproyectoid+"')");
					item.innerHTML = i + 1;
					cod.innerHTML = response.list[i].subproyectoid;
					sub.innerHTML = response.list[i].subproyecto;
					te.appendChild(btn);
					tr.appendChild(item);
					tr.appendChild(cod);
					tr.appendChild(sub);
					tr.appendChild(te);
					tb.appendChild(tr);
				};
			};
		},
		error : function (obj,que,otr) {
			msgError(null,null,null);
		}
	});
}
function backFirst () {
	$("#mb2").hide('slide',1200);
	$("#mb1").show('slide',900);
}
var listsubsec = function (pro,sub) {
	$.ajax({
		url : 'includes/incmeter.php',
		type : 'POST',
		data : { 'tra' : 'lsec','pro' : pro, 'sub' : sub },
		dataType : 'json',
		success : function (response) {
			//console.log('sec sub '+response);
			if (response.status == 'success') {
				var tb = document.getElementById('lsec');
				tb.innerHTML = "";
				for (var i = 0; i < response.list.length; i++) {
					var tr = document.createElement('tr'),	item = document.createElement('td'), cod = document.createElement('td'),
						sec = document.createElement('td'),te = document.createElement('td'),	btn = document.createElement('btn'),ico = document.createElement('i');
					item.setAttribute('id','tc');
					te.setAttribute('id','tc');
					ico.setAttribute('class','icon-share-alt');
					btn.setAttribute('class', "btn btn-mini btn-info t-d");
					btn.appendChild(ico);
					btn.setAttribute('onClick',"listMaterials('"+pro+"','"+sub+"',"+response.list[i].nroplano+"')");
					item.innerHTML = i + 1;
					cod.innerHTML = response.list[i].nroplano;
					sec.innerHTML = response.list[i].sector;
					te.appendChild(btn);
					tr.appendChild(item);
					tr.appendChild(cod);
					tr.appendChild(sec);
					tr.appendChild(te);
					tb.appendChild(tr);
				};
			};
		},
		error : function (obj,que,otr) {
			msgError(null,null,null);
		}
	});
}
function backFirst () {
	$("#mb2").hide('slide',1200);
	$("#mb1").show('slide',900);
}
function backTwo () {
	$("#mb3").hide('slide',1200);
	$("#mb2").show('slide',900);
	document.getElementById('btnccm').setAttribute('onClick','backFirst();');
	$("#btnccsaved").addClass('hide');
	document.getElementById('btnccsaved').setAttribute('onClick','');
}
var listMaterials = function (pro,sub,sec) {
	$("#mb2").hide('slide',1200);
	document.getElementById('btnccm').setAttribute('onClick','backTwo();');
	$("#btnccsaved").removeClass('hide');	
	$.ajax({
		url : 'includes/incmeter.php',
		type : 'POST',
		data : {'tra':'lmat','pro':pro,'sub':sub,'sec':sec},
		dataType : 'json',
		complete : function (obj,success) {
			if (success == 'success') {
					$("#mb3").show('slide',900);
			}
		},
		success : function (response) {
			////console.log(response);
			if (response.status == 'success') {
				var tb = document.getElementById('lmat');
				tb.innerHTML = "";
				for (var i = 0; i < response.list.length; i++) {
					var tr = document.createElement('tr'),
							item = document.createElement('td'),cod = document.createElement('td'),nom =document.createElement('td'),med=document.createElement('td'),
							und = document.createElement('td'),cant = document.createElement('td'),tc = document.createElement('td'), chk = document.createElement('input');
					item.setAttribute('id','tc');
					tc.setAttribute('id','tc');
					cant.setAttribute('id','tc');
					chk.setAttribute('type','CheckBox');
					chk.setAttribute('name','ccmat');
					chk.setAttribute('value', "'"+response.list[i].materialesid+"'");

					item.innerHTML = i + 1;
					cod.innerHTML = response.list[i].materialesid;
					nom.innerHTML = response.list[i].matnom;
					med.innerHTML = response.list[i].matmed;
					und.innerHTML = response.list[i].matund;
					cant.innerHTML = response.list[i].cant;
					tc.appendChild(chk);
					
					tr.appendChild(item);
					tr.appendChild(cod);
					tr.appendChild(nom);
					tr.appendChild(med);
					tr.appendChild(und);
					tr.appendChild(cant);
					tr.appendChild(tc);
					tb.appendChild(tr);
				};
				if (response.list.length > 0) {
					document.getElementById('btnccsaved').setAttribute('onClick','savedCopyListmateriales("'+pro+'","'+sub+'","'+sec+'");');
				}
			};
		},
		error : function (obj,que,otr) {
			msgError(null,null,null);
		}
	});
}
var chkccm = function () {
	$('input[name=rbt]').each(function () {
		var item = this;
		if (item.checked) {
			var sts = false;
			if (item.value == 'f') {
				sts = true;
			}else if(item.value == 'n'){
				sts = false;
			}
			$('input[name=ccmat]').each(function () {
				var chk = this;
				chk.checked = sts;
			});
		};
	});
}
var savedCopyListmateriales = function (pro,sub,sec) {
	var count = 0;
	var ar = '';
	$('input[name=ccmat]').each(function () {
		var chk = this;
		if (chk.checked) {
			ar += ""+chk.value+",";
			count++;
		};
	});
	$("#mlp").modal('hide');
	if (count > 0) {
		$.msgBox({
			title : 'Confirmar?',
			content : 'Realmente desea Copiar la lista materiales?',
			opacity : 0.8,
			type : 'confirm',
			buttons : [{value:'Si'},{value:'No'}],
			success : function  (response) {
				if (response == 'Si') {
					ar = ar.substring(0,(ar.length - 1));
					$.ajax({
						url : 'includes/incmeter.php',
						type : 'POST',
						data : { 'tra': 'savedcopy', 'pro':pro,'sub':sub, 'sec':sec, 'mat': ar,	'pron' : $("#pro").val(),'subn' : $("#sub").val(),'secn' : $("#sec").val() },
						dataType : 'json',
						success : function (response) {
							console.log(response);
							if (response.status == 'success') {
								//alert(response.list);
								location.reload();
							};
						},
						error : function (obj,que,otr) {
							msgError(null,null,null);
						}
					});
				};
			}
		});
	}else{
		msgInfo("Info",'No se han seleccionado materiales para copiar.',true);
		setTimeout(function() { $("#mlp").modal('show'); }, 2600);
	}
}