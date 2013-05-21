function MostrarForm(nro)
{
  var DisplayForm1 = document.getElementById('fullscreen');
  var DisplayForm = document.getElementById('Form');
  DisplayForm1.style.display = 'block';
  DisplayForm.style.display = 'block';
  document.getElementById('nrocot').value = nro;
}
function CerrarForm()
{
  var DisplayForm1 = document.getElementById('fullscreen');
  var DisplayForm = document.getElementById('Form');
  DisplayForm1.style.display = 'none';
  DisplayForm.style.display = 'none';
}