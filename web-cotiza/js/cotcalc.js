function addproyecto()
{
	var selector = document.getElementById("cbotpro");
	var optionor = selector.options[selector.selectedIndex].value;
	var textor = selector.options[selector.selectedIndex].text;
	var selectdes = document.getElementById("cboprosel");
	
	if(optionor!=""){
		var cot=0;
		for (var i = 0; i < selectdes.options.length; i++) {
			if (selectdes.options[i].text==textor) {
				cot++;
			}
		}
		if (cot==0) {
		var newoption=document.createElement("option");
		if (selectdes.length==0) {
			selectdes.length=0;
			newoption.value = optionor;
			newoption.innerHTML = textor;
		}else{
			newoption.value = optionor;
			newoption.innerHTML = textor;
		}
		selectdes.appendChild(newoption);
		var del = document.getElementById("cbotpro").options[document.getElementById("cbotpro").selectedIndex];
		del.parentNode.removeChild(del);
		}else{
			alert("El Item ya Existe en la Lista!");
		}
	}
}

function rmproyecto()
{
	var selector = document.getElementById("cboprosel");
	var optionor = selector.options[selector.selectedIndex].value;
	var textor = selector.options[selector.selectedIndex].text;
	var selectdes = document.getElementById("cbotpro");
	
	if(optionor!=""){
		var cot=0;
		for (var i = 0; i < selectdes.options.length; i++) {
			if (selectdes.options[i].text==textor) {
				cot++;
			}
		}
		if (cot==0) {
		var newoption=document.createElement("option");
		if (selectdes.length==0) {
			selectdes.length=0;
			newoption.value = optionor;
			newoption.innerHTML = textor;
		}else{
			newoption.value = optionor;
			newoption.innerHTML = textor;
		}
		selectdes.appendChild(newoption);
		var del = document.getElementById("cboprosel").options[document.getElementById("cboprosel").selectedIndex];
		del.parentNode.removeChild(del);
		}else{
			alert("El Item ya Existe en la Lista!");
		}
	}
}