function showadd()
{
	var add = document.getElementById("estgen");
	add.style.display = 'block';
}
function hiddenadd()
{
	var add = document.getElementById("estgen");
	add.style.display = "none";
}
function enaesp()
{
	var txt = document.getElementById("txtesp");
	txt.disabled = false;
	var btn = document.getElementById("btne");
	btn.disabled = false;
}
function disesp()
{
	var txt = document.getElementById("txtesp");
	txt.disabled = true;
	var btn = document.getElementById("btne");
	btn.disabled = true;
}