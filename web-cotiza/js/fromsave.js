function MostrarForm()
{
  var DisplayForm1 = document.getElementById('fullscreen');
  var DisplayForm = document.getElementById('Form');
  DisplayForm1.style.display = 'block';
  DisplayForm.style.display = 'block';
}
function CerrarForm()
{
  var DisplayForm1 = document.getElementById('fullscreen');
  var DisplayForm = document.getElementById('Form');
  DisplayForm1.style.display = 'none';
  DisplayForm.style.display = 'none';
}
function addproveedor()
{
	var selector = document.getElementById("cboproveedor");
	var optionor = selector.options[selector.selectedIndex].value;
	var textor = selector.options[selector.selectedIndex].text;
	if (optionor!="") {
		var selectdes;
		selectdes = document.getElementById("lstpro");
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
	}
}
