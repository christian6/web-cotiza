$(document).ready(function(){
    $("#send").draggable();
    refresh_mail();
    $('#message').scroll();
    $("#accordion2").collapse('toggle');
});
function send_display(dis) {
    if (dis == true) {
        $("#send").modal('show');
    }else if(dis == false){
        $("#send").modal('hide');
    }
}
function send () {
    var subject = document.getElementById("txtasunto");
	var body = document.getElementById("txtbody");
    var to = document.getElementById("cboemp");
    var op = to.options[to.selectedIndex].value;
    // validando campos
    if (!valid_mail(subject,body)) return;
    /// si todo esta bien continuamos
    //ahora guardamos el mensaje, consumiendo ajax
    var prm = {
        'tra' : "s",
        'to' : op,
        'subject' : subject.value,
        'body' : body.value
    };
    $.ajax({
        data : prm,
        url : 'include/inchome.php',
        type : 'POST',
        beforeSend : function(){
            $("#m").css("display","block");
            $("#m").html("Enviando, espere por favor ...");
        },
        success : function(response){
            if (response == "hecho") {
                $("#m").html("Mensaje enviado!!!");
                setTimeout(function() {
                    $("#m").css("display","none");
                    $("#send").modal('hide');
                    clean(subject,body);
                }, 2000);    
            }else{
                alert("Se produjo un Error.");
            }
        },
        error: function(objeto, quepaso, otroobj){
            alert("Estas viendo esto por que fallé\r\n"+"Pasó lo siguiente: "+quepaso);
        }
    });
}
function valid_mail (subject,body) {
    // Validando campos de email
    if (subject.value.length <= 0) {
        alert("Debe de Ingresar un Asunto");
        subject.focus();
        return;
    }
    if (body.value.length <= 0) {
        alert("No ha Ingresado un mensaje");
        body.focus();
        return;
    }
    return true;
}
function clean (subject,body) {
    subject.value = "";
    body.value = "";
}
function refresh_mail () {
    var prm = {
        'tra' : 'l'
    }
    $.ajax({
        data : prm,
        url : 'include/inchome.php',
        type : 'POST',
        success : function(response){
            $("#message").html(response);
        },
        error: function(objeto, quepaso, otroobj){
            alert("Estas viendo esto por que fallé\r\n"+"Pasó lo siguiente: "+quepaso);
        }
    });
}
function leido (nro) {
    if (nro != null) {
        var prm = {
            'tra' : 'r',
            'nro' : nro
        }
        $.ajax({
            data : prm,
            url : 'include/inchome.php',
            type : 'POST',
            error: function(objeto, quepaso, otroobj){
                alert("Estas viendo esto por que fallé\r\n"+"Pasó lo siguiente: "+quepaso);
            }
        });
    }
}