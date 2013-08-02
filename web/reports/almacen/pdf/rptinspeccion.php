<?php

include("../../../datos/postgresHelper.php");
# include("../../../modules/CNumeroaLetra.php");
require("../../../modules/fpdf.php");

class PDF extends FPDF
{
	var $nro_ = "";
  	#var $mone = "";
	var $widths;
	var $aligns;

function addprm($n){
	$this->nro_ = $n;
}

function Header()
{
	$this->SetXY(15,26);
	$this->Cell(180,246,'',1,1,'C',false);
	$this->SetFont('Arial','B',10);
	$this->SetXY(8,8);
	$this->Cell(50,0,utf8_decode('DEPARTAMENTO DE ALMACÉN'),0,1,'C',false);
  // Logo
  $this->Image('../../../resource/icrlogo.png',175,10,20,14,'PNG');
  // Font type
  $this->SetFont('Arial','B',16);
  $this->SetXY(5,18);
  $this->Cell(200,0,utf8_decode("REPORTE DE INSPECCIÓN"),0,1,'C',false);
}

function cabnro()
{
	$this->SetFont('Arial','B',8);
	$this->SetTextColor(240,240,240);
	$this->SetXY(15,26);
  $this->SetFillColor(0,64,140);
	$this->Cell(180,6,'DATOS GENERALES',0,1,'C',true);
	$this->SetXY(20,38);
  $this->SetTextColor(0,0,0);
	$this->Cell(50,0,'NRO DE DOCUMENTO',0,1,'L',false);
	$this->SetXY(20,48);
  $this->Cell(50,0,'RUC PROVEEDOR',0,1,'L',false);
  $this->SetXY(20,60);
  $this->Cell(50,0,'MEDIO DE TRANSPORTE',0,1,'L',false);
  $this->SetXY(20,68);
  $this->Cell(50,0,'FECHA DE LLEGADA',0,1,'L',false);
  $this->SetXY(20,76);
  $this->Cell(50,0,utf8_decode('FECHA DE RECEPCIÓN EN ALMACÉN'),0,1,'L',false);
  $this->SetXY(15,84);
  $this->Cell(180,40,'',1,1,'C',false);
  $this->SetXY(15,84);
  $this->SetTextColor(240,240,240);
  $this->SetFillColor(0,64,140);
  $this->Cell(80,6,utf8_decode('DESCRIPCIÓN GENERAL DE NOTA DE INGRESO'),1,1,'C',true);
  $this->SetXY(15,128);
  $this->Cell(180,15,'',1,1,'C',false);
  $this->SetXY(15,128);
  $this->SetTextColor(240,240,240);
  $this->SetFillColor(0,64,140);
  $this->Cell(80,6,'TIPO DE EMBARQUE',1,1,'C',true);
  $this->SetXY(15,138);
  $this->Cell(180,15,'',1,1,'C',false);
  $this->SetXY(15,143);
  $this->Cell(80,6,'FECHA DEL REPORTE',1,1,'C',true);
  $this->SetXY(15,158);
  $this->Cell(180,40,'',1,1,'C',false);
  $this->SetXY(15,158);
  $this->Cell(80,6,utf8_decode('OBSERVACIONES'),1,1,'C',true);
  $this->SetXY(15,210);
  $this->Cell(180,30,'',1,1,'C',false);
  $this->SetXY(15,204);
  $this->Cell(50,6,'RESPONSABLES',1,1,'C',true);
  $this->SetXY(15,210);
  $this->Cell(60,5,'ALMACEN',1,1,'C',true);
  $this->SetXY(15,215);
  $this->Cell(60,25,'',1,1,'C',false);
  $this->SetXY(75,210);
  $this->Cell(60,5,'INSPECCIONADO',1,1,'C',true);
  $this->SetXY(75,215);
  $this->Cell(60,25,'',1,1,'C',false);
  $this->SetXY(135,210);
  $this->Cell(60,5,'HECHO POR',1,1,'C',true);
  $this->SetXY(135,215);
  $this->Cell(60,25,'',1,1,'C',false);
  $this->SetXY(20,245);
  $this->SetTextColor(0,0,0);
  $this->Cell(50,6,utf8_decode('ALMACÉN'),0,1,'L',false);

  /////////////////////////////////////////////////////////////////////////
  $cn = new PostgreSQL();
  $query = $cn->consulta("SELECT i.nroningreso,i.transporte,i.feclleg,i.fecingal,i.desmat,i.tpemb,i.obser,i.fecha::date,i.empdni,
                          (e.empnom||' '||e.empape)as nom,n.nrocompra,o.rucproveedor,p.razonsocial,p.direccion,n.insdni,
                          (select empnom||' '||empape from admin.empleados where empdni like n.insdni) as nomins,n.almacenid,
                          a.descri FROM almacen.rptinspeccion i INNER JOIN almacen.notaingreso n
                          ON i.nroningreso LIKE n.nroningreso
                          INNER JOIN admin.empleados e
                          ON i.empdni = e.empdni
                          INNER JOIN logistica.compras o
                          ON n.nrocompra LIKE o.nrocompra
                          INNER JOIN admin.proveedor p
                          ON o.rucproveedor LIKE p.rucproveedor
                          INNER JOIN admin.almacenes a
                          ON n.almacenid LIKE a.almacenid
                          WHERE i.nroningreso LIKE '".$this->nro_."'
                          ");
  if ($cn->num_rows($query) > 0) {
    $result = $cn->ExecuteNomQuery($query);
    $this->SetXY(80,38);
    $this->SetFont('Arial','B',9);
    $this->Cell(50,0,$result['nroningreso'],0,1,'L',false);
    $this->SetXY(80,44);
    $this->Cell(20,0,$result['rucproveedor'],0,1,'L',false);
    $this->SetXY(80,44);
    $this->MultiCell(110,10,$result['razonsocial'],0,'L',false);
    $this->SetXY(80,48);
    $this->MultiCell(110,10,$result['direccion'],0,'L',false);
    $this->SetXY(80,59);
    $this->Cell(30,0,$result['transporte'],0,1,'L',false);
    $this->SetXY(80,67);
    $this->Cell(30,0,$result['feclleg'],0,1,'L',false);
    $this->SetXY(80,73);
    $this->Cell(30,5,$result['fecingal'],0,1,'L',false);
    $this->SetXY(20,90);
    $this->MultiCell(180,5,$result['desmat'],0,'L',false);
    $this->SetXY(100,130);
    $this->Cell(30,5,$result['tpemb'],0,1,'C',false);
    $this->SetXY(100,148);
    $this->Cell(30,0,$result['fecha'],0,1,'C',false);
    $this->SetXY(20,168);
    $this->MultiCell(180,5,$result['obser'],0,'L',false);
    $this->SetXY(20,220);
    $this->Cell(20,0,'ALMACEN :  '.$result['almacenid'],0,1,'L',false);
    $this->SetXY(15,224);
    $this->Cell(60,8,$result['descri'],0,1,'C',false);
    $this->SetXY(75,220);
    $this->Cell(20,0,'DNI : '.$result['insdni'],0,1,'L',false);
    $this->SetXY(75,224);
    $this->MultiCell(60,8,$result['nomins'],0,'C',false);
    $this->SetXY(135,220);
    $this->Cell(20,0,'DNI : '.$result['empdni'],0,1,'L',false);
    $this->SetXY(135,224);
    $this->MultiCell(60,8,$result['nom'],0,'C',false);
  }
  $cn->close($query);
}

function Footer()
{
	//Posicion a 1.,5 cm del final
  $this->SetTextColor(0,0,0);
	$this->SetXY(15,280);
	// Arial Italic 8
	$this->SetFont('Arial','I',8);
	$this->Cell(180,0,'Page '.$this->PageNo().'/{nb}',0,0,'C');
  $this->SetXY(0,285);
  $this->Cell(0,0,'Fecha Impresion: '.date("d-m-Y H:i:s"),0,1,'L',false);
}

}

// Creación del objeto de la clase heredada

$nro = $_GET['nro'];
$pdf = new PDF('P','mm','A4');
$pdf->SetAutoPageBreak(true,0);
$pdf->addprm($nro);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->cabnro();
//$pdf->postbody();
$pdf->Output();

?>