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
			url : 'incmeter.php',
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
			url : 'incmeter.php',
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
function savedata () {
	var cod = document.getElementById("cod").innerHTML;
	var cant = document.getElementById("txtcant").value;
	var pro = document.getElementById("proid").innerHTML;
	var pla = document.getElementById("plane").innerHTML;
	if (cod != "" && cant != "") {
		var prm = {
			'tra' : 'save',
			'proid' : pro,
			'pla' : pla,
			'cod' : cod,
			'cant' : cant
		}
		$.ajax({
			data : prm,
			url : 'incmeter.php',
			type : 'POST',
			success : function(response){
            if (response == "hecho") {
            	$( "#fullscreen-icr" ).css("display","block");
            	$( "#msg" ).css("display","block");
                setTimeout(function() { window.close(); }, 3000);
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