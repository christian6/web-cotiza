$(function () {
	list();
});
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
function savemat(){
	if ($("#cant").val() == "") {
		alert('WARNING\r\nIngrese un cantidad!');
		return;
	}
	var prm = {
		'tra' : 'save',
		'proid' : $("#pro").val(),
		'sub' : $("#sub").val(),
		'pla' : $("#sec").val(),
		'cod' : $("#cod").html(),
		'cant' : $("#cant").val()
	}
	$.ajax({
		data : prm,
		url : 'includes/incmeter.php',
		type : 'POST',
		dataType : 'html',
		success : function (response) {
			if (response == 'hecho') {
				$().css('display','block');
				list();
			}
		},
		error : function (obj,quepaso,otroobj) {
			alert('Si estas viendo esto es por que fallé');
		}
	});	
}
function list () {
	var prm = {
		'tra' : 'list',
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
			if (response != '') {
				$("#list").html(response);
			}
		},
		error : function (obj,quepaso,otroobj) {
			alert('Si estas viendo esto es por que fallé');
		}
	});	
}
function showedit(id){
	if(id != ''){
		var prm = {
			'tra' : 'conedit',
			'pro' : $("#pro").val(),
			'sub' : $("#sub").val(),
			'sec' : $("#sec").val(),
			'mid' : id
		}
		$.ajax({
			data : prm,
			url : 'includes/incmeter.php',
			type : 'POST',
			dataType : 'html',
			success : function (response) {
				if(response != '')	{
					var re = response.split('|');
					$("#matid").val(re[0]);
					$("#matnom").val(re[1]);
					$("#matmed").val(re[2]);
					$("#cantedit").val(re[3]);
					$( "#medit").modal('show');
				}
			},
		error : function (obj,quepaso,otroobj) {
			alert('Si estas viendo esto es por que fallé');
		}
		});
	}
}
function showdel (id) {
	if (id != "") {
		$("#matdel").html(id);
		$("#mdel").modal('show');
	}
}
function edit () {
	if ($("#matid").val() != '') {
		var prm = {
			'tra' : 'upcant',
			'pro' : $("#pro").val(),
			'sub' : $("#sub").val(),
			'sec' : $("#sec").val(),
			'matid' : $("#matid").val(),
			'cant' : $("#cantedit").val()
		}
		$.ajax({
			data : prm,
			url : 'includes/incmeter.php',
			type : 'POST',
			dataType : 'html',
			success : function (response) {
				if(response == 'hecho')	{
					list();
					$( "#medit").modal('hide');
				}
			},
			error : function (obj,quepaso,otroobj) {
				alert('Si estas viendo esto es por que fallé');
			}
		});
	}
}
function eliminar () {
	var id = $("#matdel").html();
	if (id != "") {
		var prm = {
			'tra' : 'delmat',
			'pro' : $("#pro").val(),
			'sub' : $("#sub").val(),
			'sec' : $("#sec").val(),
			'matid' : id
		}
		$.ajax({
			data : prm,
			url : 'includes/incmeter.php',
			type : 'POST',
			dataType : 'html',
			success : function (response) {
				if(response == 'hecho')	{
					list();
					$( "#mdel").modal('hide');
				}
			},
			error : function (obj,quepaso,otroobj) {
				alert('Si estas viendo esto es por que fallé');
			}
		});
	}
}
function openfull () {
	$( "#fullscreen-icr" ).show("clip",{},1600);
	$("#fullpdf").css('display','block');
}
function closefull () {
	$( "#fullscreen-icr" ).hide("clip",{},2000);
}