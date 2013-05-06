$(document).ready(function(){
  $("#username").focus();
  $(".button").click(function(){
      execute_en();
  });
  $("#username").keyup(function(e){
    if (e.which == '13') {
      execute_en();
    }
  });
  $("#password").keyup(function(e){
    if (e.which == '13') {
      execute_en();
    }
  });
  $("#username, #password").keyup(function(){
    if( $("#username").val() != "" || $("#password").val() != "" ){
        $(".error").fadeOut();
        return false;
      }
  });

});
function peticion(){
  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  }
  else
  {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  return xmlhttp;
}

function execute_en() {
      $(".error").remove();
        if( $("#username").val() == "" ){
            $("#username").focus().after("<span class='error' style='color: rgb(255,255,255);' >Ingrese su Usuario</span>");
            return false;
        }
        if ($("#password").val() == "") {
          $("#password").focus().after("<span class='error' style='color: rgb(255,255,255);'>Ingrese su Clave</span>")
          return false;
        }

        if ( $("#username").val() != "" && $("#password").val() != "" ) {
              xmlhttp = peticion();
              xmlhttp.onreadystatechange=function()
              {
		            alert(xmlhttp.responseText);
                if (xmlhttp.readyState==4 && xmlhttp.status==200)
                {
                      var tra = xmlhttp.responseText;
                      tra.trim();
                      tra = tra.toLowerCase();
                      if (tra != "false") {
                        switch(tra){
                          case "administrator":
                          case "gerencia":
                                window.location='manager.php';
                          break;
                          case "logistica":
                                window.location='../web-cotiza/intranet/menu-int.php';
                          break;
                          case "almacen":
                                window.location='web-almacen/home.php';
                          break;
                        }

                      }else if(tra == "false"){
                        $(".result").css("background-color","rgba(252,247,200,1)");
                        $(".result").css("color","rgba(200,35,35,1)");
                        $(".result").css("border-radius",".8em");
                        $(".result").css("font-size","12px");
                        $(".result").css("font-family","'Archivo Narrow', sans-serif");
                        $(".result").css("padding",".2em 2em .2em 2em");
                        $(".result").html("Usuario o Password son Incorrectos");
                        return false;
                      }
                }
              }

              var requestUrl = "";
              requestUrl = "includes/signin.php"+"?usr="+encodeURIComponent($("#username").val())+"&pwd="+encodeURIComponent(hex_md5($("#password").val()));
              $(".result").css("background-color","rgba(252,247,200,1)");
              $(".result").css("color","rgba(0,0,0,.7)");
              $(".result").css("border-radius",".8em");
              $(".result").css("font-size","12px");
              $(".result").css("font-family","'Archivo Narrow', sans-serif");
              $(".result").css("padding",".2em 2em .2em 2em");
              $(".result").html("Procesando, espere por favor...");
              xmlhttp.open("POST",requestUrl,true);
              xmlhttp.send();
        }
}
