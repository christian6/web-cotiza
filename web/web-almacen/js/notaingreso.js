$(function() {
    $( ".dropdown-toggle" ).dropdown();
    $("#fecini").datepicker({ minDate: "" , maxDate: "" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd"});
    $("#fecfin").datepicker({ minDate: "" , maxDate: "" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd"});
});

function radios () {
	var c = document.getElementById("rbtnc");
	var f = document.getElementById("rbtnf");
	if (c.checked) {
		document.getElementById("txtnro").disabled = false;
		document.getElementById("fecini").disabled = true;
		document.getElementById("fecfin").disabled = true;
	}else if (f.checked) {
		document.getElementById("txtnro").disabled = true;
		document.getElementById("fecini").disabled = false;
		document.getElementById("fecfin").disabled = false;
	}
}
function view (nro) {
	window.open('http://190.41.246.91/web/reports/almacen/pdf/rptnotaingreso.php?nro='+nro);
}
function viewins (nro) {
	window.open('http://190.41.246.91/web/reports/almacen/pdf/rptinspeccion.php?nro='+nro);
}