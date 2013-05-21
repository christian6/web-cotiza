<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src="modules/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript">
	      tinyMCE.init({
        theme : "simple",
        mode : "textareas",
        plugins : "fullpage",
        theme_advanced_buttons3_add : "fullpage"
});
</script>
<script>
	function view () {
		var t = document.getElementById("editor_texto");
		alert(t.value);
	}
</script>
</head>
<body>
<h3>Editor de textos Web</h3>
<h1 class="edit">Edit this title</h1>
<form action="" method="get">
	<textarea id="editor_texto" name="editor_texto" style="width: 30%;">Texto de prueba</textarea>
	<button type="Sublimt" onClick="view();" >val</button>
</form>
<?php
	echo $_GET['editor_texto'];
?>
</body>
</html>
