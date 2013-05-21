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

function SetWidths($w)
{
   $this->widths=$w;
}

function SetAligns($a)
{
   $this->aligns=$a;
}

function Row($data)
{
   $nb=0;
   for($i=0;$i<count($data);$i++)
      $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
   $h=4*$nb;
   $this->CheckPageBreak($h);
   $algs = '';
   for($i=0;$i<count($data);$i++)
   {
      $w=$this->widths[$i];
      $algs = 'L';
      $w=$this->widths[$i];
      if ($w == 17 ) {
        $algs = 'R';
      }else if($w == 18 || $w == 25 || $w == 24){
        $algs = 'C';
      }
      $a=isset($this->aligns[$i]) ? $this->aligns[$i] : $algs;
      $x=$this->GetX();
      $y=$this->GetY();
      //$this->Rect($x,$y,$w,$h);
      $this->MultiCell($w,3.8,$data[$i],'LR',$a,false);
      $this->SetXY($x+$w,$y);
   }
   $this->Ln($h);
}

function CheckPageBreak($h)
{
   if($this->GetY()+$h>$this->PageBreakTrigger)
      $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
   $cw=&$this->CurrentFont['cw'];
   if($w==0)
      $w=$this->w-$this->rMargin-$this->x;
   $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
   $s=str_replace("\r",'',$txt);
   $nb=strlen($s);
   if($nb>0 and $s[$nb-1]=="\n")
      $nb--;
   $sep=-1;
   $i=0;
   $j=0;
   $l=0;
   $nl=1;
   while($i<$nb)
   {
      $c=$s[$i];
      if($c=="\n")
      {
         $i++;
         $sep=-1;
         $j=$i;
         $l=0;
         $nl++;
         continue;
      }
      if($c==' ')
         $sep=$i;
      $l+=$cw[$c];
      if($l>$wmax)
      {
         if($sep==-1)
         {
            if($i==$j)
               $i++;
         }
         else
            $i=$sep+1;
         $sep=-1;
         $j=$i;
         $l=0;
         $nl++;
      }
      else
         $i++;
   }
   return $nl;
}

function Header()
{
	$this->SetXY(5,17);
	$this->Cell(285,180,'',1,1,'C',false);
	$this->SetFont('Arial','B',10);
	$this->SetXY(8,8);
	$this->Cell(50,0,utf8_decode('DEPARTAMENTO DE ALMACÉN'),0,1,'C',false);
    // Logo
    $this->Image('../../../resource/icrlogo.png',260,2,20,14,'PNG');
    // Font type
    $this->SetFont('Arial','B',16);
    $this->SetXY(5,10);
    $this->Cell(285,0,utf8_decode("NOTA DE INGRESO AL ALMACÉN"),0,1,'C',false);
}

function cabnro()
{
	$this->SetFont('Arial','B',8);
	$this->SetTextColor(0,0,0);
	$this->SetXY(5,17);
	$this->Cell(50,6,'NRO DOCUMENTO',1,1,'C',false);
	$this->SetXY(5,23);
	$this->Cell(50,15,'',1,1,'L',false);
	$this->SetXY(55,17);
	$this->Cell(30,6,'TIPO DOCUMENTO',1,1,'C',false);
	$this->SetXY(55,23);
	$this->Cell(30,15,'',1,1,'L',false);
	$this->SetXY(85,17);
	$this->Cell(135,21,'',1,1,'C',false);
	$this->SetXY(88,20);
	$this->Cell(20,0,'RECIBO DE:',0,1,'L',false);
	$this->SetXY(88,28);
	$this->Cell(20,0,'PROCEDENTE DE:',0,1,'L',false);
	$this->SetXY(88,33);
	$this->Cell(20,0,'MOTIVO:',0,1,'L',false);
	$this->SetXY(220,17);
	$this->Cell(32,7,'NRO GUIA REMISION',1,1,'L',false);
	$this->SetXY(252,17);
	$this->Cell(38,7,'',1,1,'L',false);
	$this->SetXY(220,24);
	$this->Cell(32,7,'NRO FACTURA',1,1,'L',false);
	$this->SetXY(252,24);
	$this->Cell(38,7,'',1,1,'L',false);
	$this->SetXY(220,31);
	$this->Cell(32,7,'NRO COTIZACION',1,1,'L',false);
	$this->SetXY(252,31);
	$this->Cell(38,7,'',1,1,'L',false);
  $this->SetXY(5,38);
  $this->Cell(50,12,'',1,1,'C',false);
  $this->SetXY(5,38);
  $this->Cell(50,5,utf8_decode('ALMACÉN'),1,1,'C',false);
  $this->SetXY(55,38);
  $this->Cell(30,12,'',1,1,'C',false);
  $this->SetXY(55,38);
  $this->Cell(30,5,'FECHA DE INGRESO',1,1,'L',false);
  $this->SetXY(86,41);
  $this->Cell(20,0,utf8_decode('OBSERVACIÓN:'),0,1,'L',false);

  /////////////////////////////////////////////////////////////////////////
  $cn = new PostgreSQL();
  $query = $cn->consulta("SELECT * FROM almacen.spconsultarcabnotaingreso('".$this->nro_."')");
  if ($cn->num_rows($query) > 0) {
    $result = $cn->ExecuteNomQuery($query);
    $this->SetXY(5,26);
    $this->SetFont('Arial','B',16);
    $this->SetTextColor(80,80,80);
    $this->Cell(50,10,$result['nroningreso'],0,1,'C',false);
    $this->SetXY(55,24);
    $this->SetFont('Arial','B',9);
    $this->SetTextColor(0,0,0);
    $this->MultiCell(30,6,'NOTA DE INGRESO',0,'C',false);
    $this->SetFont('Arial','',8);
    $this->SetXY(116,17.5);
    $this->Cell(20,5,$result['rucproveedor'],0,1,'L',false);
    $this->SetXY(116,18.9);
    $this->MultiCell(110,10,$result['razonsocial'],0,'L',false);
    $this->SetXY(116,23);
    $this->MultiCell(110,10,$result['direccion'],0,'L',false);
    $this->SetXY(116,28);
    $this->MultiCell(110,10,$result['motivo'],0,'L',false);
    $this->SetXY(255,18);
    $this->Cell(30,5,$result['nroguia'],0,1,'C',false);
    $this->SetXY(255,25);
    $this->Cell(30,5,$result['nrofactura'],0,1,'C',false);
    $this->SetXY(255,32);
    $this->Cell(30,5,$result['nrocotizacion'],0,1,'C',false);
    $this->SetXY(5,44);
    $this->Cell(50,5,$result['descri'],0,1,'C',false);
    $this->SetXY(55,44);
    $this->Cell(30,5,$result['fecha'],0,1,'C',false);
    $this->SetXY(112,39);
    $this->MultiCell(166,4,$result['obser'],0,'L',false);
    $this->SetXY(8,188);
    $this->Cell(20,0,'DNI :'.$result['empdni'],0,1,'L',false);
    $this->SetXY(5,189);
    $this->MultiCell(71,8,$result['nombre'],0,'C',false);
    $this->SetXY(79,188);
    $this->Cell(20,0,'DNI :'.$result['recdni'],0,1,'L',false);
    $this->SetXY(76,189);
    $this->MultiCell(71,8,$result['nomre'],0,'C',false);
    $this->SetXY(150,188);
    $this->Cell(20,0,'DNI :'.$result['insdni'],0,1,'L',false);
    $this->SetXY(144,189);
    $this->MultiCell(71,8,$result['nomins'],0,'C',false);
    $this->SetXY(220,188);
    $this->Cell(20,0,'DNI :'.$result['vbdni'],0,1,'L',false);
    $this->SetXY(220,189);
    $this->MultiCell(71,8,$result['nomvb'],0,'C',false);
  }
  $cn->close($query);
}

function FancyTable()
{
    // Colores, ancho de línea y fuente en negrita
    $this->SetFillColor(139,0,0);
    $this->SetTextColor(255);
    # $this->SetDrawColor(169,169,169);
    $this->SetLineWidth(.1);
    $this->SetFont('','B',8);
    // Cabecera
    $header = array('ITEM','CODIGO',utf8_decode('DESCRIPCIÓN'),'MEDIDA','UND','CANTIDAD');
    $w = array(18, 25, 100, 100, 18, 24);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],0,0,'C',true);
    $this->Ln();
    // Restauración de colores y fuentes
    $this->SetFillColor(255,255,210);
    $this->SetTextColor(0);
    $this->SetFont('Arial','',6.5);

}

function postbody()
{
  $this->SetFont('Arial','B',8);
	$this->SetXY(5,180);
	$this->Cell(71,5,'HECHO POR:',1,1,'C',false);
	$this->SetXY(5,185);
	$this->Cell(71,12,'',1,1,'C',false);
	$this->SetXY(76,180);
	$this->Cell(71,5,'RECIBIDO POR:',1,1,'C',false);
	$this->SetXY(76,185);
	$this->Cell(71,12,'',1,1,'C',false);
	$this->SetXY(147,180);
	$this->Cell(71,5,'INSPECCIONADO POR:',1,1,'C',false);
	$this->SetXY(147,185);
	$this->Cell(71,12,'',1,1,'C',false);
	$this->SetXY(218,180);
	$this->Cell(72,5,'V.B. JEFE ALMACEN:',1,1,'C',false);
	$this->SetXY(218,185);
	$this->Cell(72,12,'',1,1,'C',false);
}

function Footer()
{
	//Posicion a 1.,5 cm del final
	$this->SetXY(0,200);
	// Arial Italic 8
	$this->SetFont('Arial','I',8);
	$this->Cell(285,0,'Page '.$this->PageNo().'/{nb}',0,0,'C');
  $this->SetXY(0,205);
  $this->Cell(0,0,'Fecha Impresion: '.date("d-m-Y H:i:s"),0,1,'L',false);
}

}

// Creación del objeto de la clase heredada

$nro = $_GET['nro'];
$pdf = new PDF('L','mm','A4');
$pdf->SetAutoPageBreak(true,0);
$pdf->addprm($nro);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->cabnro();
$pdf->SetXY(5,50);
$pdf->FancyTable();
$pdf->SetWidths(array(18, 25, 100, 100, 18, 24));
$cn = new PostgreSQL();
$query = $cn->consulta("SELECT * FROM ALMACEN.SP_CONSULTARDETNOTAINGRESO('".$nro."')");
  if ($cn->num_rows($query)>0) {
    $i = 1;
    while($fila = $cn->ExecuteNomQuery($query)){
      $pdf->SetX(5);
      $pdf->Row(array($i++,$fila['materialesid'],$fila['matnom'], $fila['matmed'], $fila['matund'], $fila['cantidad']));
      }
    }
$cn->close($query);
$pdf->postbody();
$pdf->Output();


?>