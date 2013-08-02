<?php
include ("../../datos/postgresHelper.php");

$id = $_REQUEST['id'];
if (isset($id)) {
$cn = new PostgreSQL();
$query = $cn->consulta("SELECT esid,esnom FROM admin.estadoes WHERE estid LIKE '$id'");
if ($cn->num_rows($query)>0) {
?>
	<table>
				<caption><?echo $_POST['cbogen2'];?></caption>
				<thead>
					<tr>
						<th>Codigo</th>
						<th>Descripción</th>
						<th>Modificar</th>
						<th>Eliminar</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<tr>";
						echo "<td>".$result['esid']."</td>";
						echo "<td><input type='text' id='".$result['esid']."' name='txtesnom' value='".$result['esnom']."'></td>";
						?>
						<td style='text-align:center'><a href="javascript:mofest('<?php echo $result['esid'];?>');"><img src='../source/editar16.png' /></a></td>
						<td style='text-align:center'><a href="javascript:delest('<?php echo $result['esid'];?>');"><img src='../source/delete.png' /></a></td>
						<?php
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
<?php
}
}
?>
