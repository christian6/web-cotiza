var winb;
var wino;
var con;
function viewbusiness(){
	var pro = document.getElementById("txtpro").value;
	var sec = document.getElementById("txtsec").value;
	var sub = document.getElementById("txtsub").value;

	var myLeft = (screen.width-1000)/2;
	var myTop = (screen.height-700)/2;
	/*var plane = document.getElementById("plane").innerHTML;
	var proid = document.getElementById("proid").innerHTML;*/

	var caracteristicas="toolbar=0, location=0, directories=0, resizable=0, scrollbars=yes, height=600, width=1000, top="+myTop+", left="+myLeft;
	winb = window.open("listbusiness.php?"+"&sec="+sec+"&pro="+pro+"&sub="+sub,
					"Lista de ventas",caracteristicas);

}
function viewoperation(){
	var pro = document.getElementById("txtpro").value;
	var sub = document.getElementById("txtsub").value;
	var sec = document.getElementById("txtsec").value;

	var myLeft = (screen.width-1000)/2;
	var myTop  = (screen.height-700)/2;
	
	var caracteristicas = "toolbar=0, location=0, directories=0, resizable=0, scrollbars=yes, height=600, width=1000, top="+myTop+", left="+myLeft;
	var wino = window.open("listoperation.php?"+"&pro="+pro+"&sub="+sub+"&sec="+sec,"Lista de Operaciones",caracteristicas);
}
function viewcompare () {
	var pro = document.getElementById("txtpro").value;
	var sub = document.getElementById("txtsub").value;
	var sec = document.getElementById("txtsec").value;

	var myLeft = (screen.width-1200)/2;
	var myTop  = (screen.height-700)/2;
	
	var caracteristicas = "toolbar=0, location=0, directories=0, resizable=0, scrollbars=yes, height=600, width=1200, top="+myTop+", left="+myLeft;
	var wino = window.open("comparelist.php?"+"&pro="+pro+"&sub="+sub+"&sec="+sec,"Comparar Listas",caracteristicas);
}