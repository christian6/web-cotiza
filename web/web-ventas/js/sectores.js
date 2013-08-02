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