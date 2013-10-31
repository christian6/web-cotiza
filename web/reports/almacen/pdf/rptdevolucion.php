<?php

include("../../../datos/postgresHelper.php");
require("../../../modules/fpdf.php");

class PDF extends FPDF
{
	function Header()
	{
		// Logo
		$this->Image('../../../resource/icrlogo.png',10,10,0,0,'PNG');
		// Font type
		$this->SetFont('Arial','B',7);
		// Titulo
		$this->SetXY(80,10);
		$this->Cell(20,4,'Jr. Gral. Jose de San Martin Mz. E Lote 6 Huachipa',0,1,'C',false);
		$this->SetXY(80,14);
		$this->Cell(20,4,'Lurigancho Lima 15, Peru',0,1,'C',false);
		$this->SetXY(80,18);
		$this->Cell(20,4,'Central Telefonica: (511) 371-0443',0,1,'C',false);
		$this->SetXY(80,22);
		$this->Cell(20,4,'E-mail: logistica@icrperusa.com',0,1,'C',false);
		$this->Ln(20);
	}

	function cabnro($nro = '')
	{
		$this->SetXY(126,10);
		$this->SetLineWidth(.6);
		$this->Cell(74.5,20,'',1,1,'C',false);
		$this->SetFont('Arial','B',15);
		$this->SetXY(126.5,10.5);
		#$this->SetDrawColor(245,169,169);
		$this->SetFillColor(180,0,0);
		$this->SetTextColor(255,255,255);
		$this->Cell(74,8,utf8_decode('Nro de Devolución'),0,1,'C',true);
		$this->SetXY(127,20);
		$this->SetTextColor(80,80,80);
		$this->Cell(0,8,$nro,0,1,'C',false);
	}

	function dataDev($nro = '')
	{
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT DISTINCT d.devolucionid,p.descripcion,a.descri,d.nguia,d.fecha,d.obs FROM almacen.devolucion d 
								INNER JOIN ventas.proyectos p 
								ON d.proyectoid LIKE p.proyectoid
								INNER JOIN admin.almacenes a 
								ON d.almacenid LIKE a.almacenid
								WHERE d.devolucionid LIKE '".$nro."' LIMIT 1 OFFSET 0");
		if ($cn->num_rows($query) > 0) {
			$result = $cn->ExecuteNomQuery($query);
		}
		$cn->close($query);
		$this->SetFont('','B',10);
		$this->SetTextColor(40,40,40);
		// Proyecto
		$this->SetXY(10,40);
		$this->Cell(20,5,'Proyecto',0,1,'L',false);
		$this->SetXY(30,40);
		$this->Cell(20,5,utf8_decode($result['descripcion']),0,1,'L',false);
		// Almacen
		$this->SetXY(10,45);
		$this->Cell(20,5,utf8_decode('Almacén'),0,1,'L',false);
		$this->SetXY(30,45);
		$this->Cell(20,5,utf8_decode($result['descri']),0,1,'L',false);
		// Guia
		$this->SetXY(10,50);
		$this->Cell(20,5,'Nro Guia',0,1,'L',false);
		$this->SetXY(30,50);
		$this->Cell(20,5,$result['nguia'],0,1,'L',false);
		// Fecha
		$this->SetXY(10,55);
		$this->Cell(20,5,'Fecha',0,1,'L',false);
		$this->SetXY(30,55);
		$this->Cell(20,5,$result['fecha'],0,1,'L',false);
		// Observacion
		$this->SetXY(120,40);
		$this->Cell(20,5,utf8_decode('Observación'),0,1,'L',false);
		$this->SetXY(120,45);
		$this->SetFont('Arial','I',9);
		$this->MultiCell(85,4,utf8_decode($result['obs']),0,'L',false);

	}

	function detalle($nro = '')
	{
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT d.materialesid,m.matnom,m.matmed,m.matund,d.cantidad,d.est
								FROM almacen.detdevolucion d INNER JOIN admin.materiales m 
								ON d.materialesid LIKE m.materialesid WHERE d.devolucionid LIKE '".$nro."' ");
		if ($cn->num_rows($query) > 0) {
			$item = 1;
			$y = 65;
			$yimg = 0;
			$path = $_SERVER['DOCUMENT_ROOT'].'/web/web-almacen/devolucionesimg/'.$nro.'/';
			while ($result = $cn->ExecuteNomQuery($query)) {
				$i++;
				$this->SetFont('Arial','B',9);
				$this->SetXY(10,$y);
				$this->SetFillColor(8,75,138);
				$this->SetTextColor(240,240,240);
				$this->Cell(190,4,'Item '.$i,0,1,'L',true);
				$y+=5;
				$yimg = $y;
				$this->SetTextColor(40,40,40);
				$this->SetXY(10,$y);
				$this->Cell(20,4,'Codigo',0,1,'L',false);
				$this->SetXY(30,$y);
				$this->Cell(30,4,$result['materialesid'],0,1,'L',false);
				$y+=5;
				$this->SetXY(10,$y);
				$this->Cell(20,4,utf8_decode('Descripción'),0,1,'L',false);
				$this->SetFont('Arial','',9);
				$this->SetXY(30,$y);
				$this->Cell(120,4,utf8_decode($result['matnom']),0,1,'L',false);
				$y+=5;
				$this->SetXY(10,$y);
				$this->SetFont('Arial','B',9);
				$this->Cell(20,4,utf8_decode('Medida'),0,1,'L',false);
				$this->SetFont('Arial','',9);
				$this->SetXY(30,$y);
				$this->Cell(120,4,utf8_decode($result['matmed']),0,1,'L',false);
				$y+=5;
				$this->SetXY(10,$y);
				$this->SetFont('Arial','B',9);
				$this->Cell(20,4,utf8_decode('Unidad'),0,1,'L',false);
				$this->SetFont('Arial','',9);
				$this->SetXY(30,$y);
				$this->Cell(120,4,utf8_decode($result['matund']),0,1,'L',false);
				/// Cantidad
				$this->SetFont('Arial','B',9);
				$this->SetXY(50,$y);
				$this->Cell(20,4,'Cantidad',0,1,'L',false);
				$this->SetXY(70,$y);
				$this->Cell(10,4,$result['cantidad'],0,1,'L',false);
				// Estado
				$y+=5;
				$this->SetXY(10,$y);
				$this->Cell(20,4,'Estado',0,1,'L',false);
				$this->SetXY(30,$y);
				$this->SetFont('Arial','',9);
				$this->MultiCell(120,5,$result['est'],0,'L',false);

				$y+=15;

				/* Image */
				if (file_exists($path)) {
					$dir = opendir($path);
					while ($file = readdir($dir)) {
						if (is_file($path.$file)) {
							$tmp = explode('.', $file);
							if ($tmp[0] == $result['materialesid']) {
								$this->Image($path.$file,160,$yimg,35,35,'');
							}
						}
					}
					closedir($dir);
				}
			}
		}
		$cn->close($query);
	}

	function Footer()
	{
		//Posicion a 1.,5 cm del final
		$this->SetY(-15);
		// Arial Italic 8
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		$this->SetX(0);
		$this->Cell(0,0,'Fecha Impresion: '.date("d-m-Y H:i:s"),0,1,'L',false);
	}

}


$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->cabnro($_GET['nro']);
$pdf->dataDev($_GET['nro']);
$pdf->detalle($_GET['nro']);
$pdf->Output();

?>