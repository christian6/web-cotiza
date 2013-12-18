var filterNomMat = function (e) {
	var code = e.keyCode;
	
	if (code != 13 && code < 37 || code > 40) {
		//console.log('primero '+code);
		var $nom = $("#matnom");
		if ($nom.val() != "") {
			$.ajax({
				url : 'includes/incsectores.php',
				type : 'GET',
				data : {'tra':'matnom','nom':$nom.val()},
				dataType : 'json',
				success : function (response) {
					//console.log(response);
					if (response.status == 'success') {
						if (response.list.length > 0) {
							$("#onemat").remove();
							var ul = document.createElement('ul');
							ul.setAttribute('class','span6');
							ul.setAttribute('id','onemat');
							ul.innerHTML = ""
							count=0;
							for (var i = 0; i < response.list.length; i++) {
								var li = document.createElement('li'),
										a = document.createElement('a');
								li.setAttribute('class','ui-menu-item');
								li.setAttribute('role','presentation');
								a.innerHTML = response.list[i].matnom;
								a.setAttribute('class','ui-corner-all');
								li.setAttribute('onClick','valFilter("'+response.list[i].matnom+'");');
								//li.setAttribute('onKeyPress','selectEnter(e,"'+response.list[i].matnom+'");');
								li.setAttribute('id','filtermat'+i);
								li.appendChild(a);
								ul.appendChild(li);
							};
							$nom.focus().after(ul);
						};
					};
				}
			});
		};
	}
	if(code === 13){
		if ($("#onemat").length > 0) {
			$("#matnom").val($("#onemat li.selected a").html());
			delmatnom();
		};
		if ($("#matnom").val().length > 0 ) {
			search($.trim($("#matnom").val()));
		};
	}
}
var delmatnom = function () {
	setTimeout(function() { $("#onemat").remove(); }, 100);
}
var valFilter = function (val) {
	$("#matnom").val(val);
	$("#matnom").focus();
	delmatnom();
}
var count = 0;
var moveTopBottom = function (e) {
	var code = e.keyCode;
	//$(window).keydown(function(e){
	//var count = 0;
	var ul = document.getElementById('onemat');
	if(code === 40){ //down
		if($('#onemat li.selected').length == 0){ //Si no esta seleccionado nada
			$('#onemat li:first').addClass('selected');
		}else{
			$('#onemat li:first').addClass('selected');
		}
	}else if(code === 38){ //arriba
		$('#onemat li.selected').removeClass('selected');
	}else if(code === 39){ //abajo
		var liSelected = $('#onemat li.selected');
		if(liSelected.length === 1 && liSelected.next().length === 1){
			liSelected.removeClass('selected').next().addClass ('selected');
			if (count > 9) {
				ul.scrollTop+=30;
			};
			count++;
		}
	}else if(code === 37){ //izquierda
		var liSelected = $('#onemat li.selected');
		if(liSelected.length === 1 && liSelected.prev().length === 1){
			liSelected.removeClass('selected').prev().addClass ('selected');
			if (count > 9) {
				ul.scrollTop-=30;
			};
			count--;
		}
	}
	//console.log(count);
	//});
}
var selectEnter = function (e,val) {
	var evt = e ? e : event;
	var key = window.Event ? evt.which : evt.keyCode;
	if (key == 13) {
		valFilter(val);
	}
}