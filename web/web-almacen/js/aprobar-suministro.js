var windet;
 
function openwin(nos) {

	var myLeft = (screen.width-800)/2;
    var myTop = (screen.height-700)/2;

	var caracteristicas="toolbar=0, location=0, directories=0, resizable=0, scrollbars=yes, height=600, width=800, top="+myTop+", left="+myLeft;
  	windet = window.open(
    "http://190.41.246.91/web/web-almacen/include/incdosum.php?nros="+encodeURIComponent(nos),
    "DescriptiveWindowName",caracteristicas);

   	windet.onunload = function() {
    	intvl = setInterval(testClosedProperty,100);
 	}
}

function testClosedProperty() {
  if (windet.closed) {
    if (intvl) clearInterval(intvl);
    	location.href = '';
  }
}
