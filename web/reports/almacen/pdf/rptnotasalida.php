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
      }else if($w == 18 || $w == 25 || $w == 20){
        $algs = 'C';
      }
      $a=isset($this->aligns[$i]) ? $this->aligns[$i] : $algs;
      $x=$this->GetX();
      $y=$this->GetY();
      //$this->Rect($x,$y,$w,$h);
      $this->MultiCell($w,4,$data[$i],'LR',$a,false);
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

function cabnro()
{
	$this->SetXY(126,10);
	$this->SetLineWidth(.6);
	$this->Cell(74.5,30,'',1,1,'C',false);
	$this->SetFont('Arial','B',15);
	$this->SetTextColor(80,80,80);
	$this->SetXY(128,14);
	$this->Cell(0,4,'R.U.C. Nro 20428776110',0,1,'C',false);
	$this->SetXY(126.5,21);
	#$this->SetDrawColor(245,169,169);
    $this->SetFillColor(180,0,0);
    $this->SetTextColor(255,255,255);
	$this->Cell(74,8,'NOTA DE SALIDA',0,1,'C',true);
	$this->SetXY(127,30);
	$this->SetTextColor(80,80,80);
	$this->Cell(0,8,''.$this->nro_,0,1,'C',false);
}

function cabdatos()
{
	# Declaracion de Variables
	$destino = "";
	$rz = "";
	$proyectoid = "";
	$ftra = "";

	$meses = array("Enero", "Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

	# Consultado Datos
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT DISTINCT n.nronsalida,p.nropedido,p.proyectoid,y.descripcion,n.fecsal,n.destino
							FROM almacen.notasalida n INNER JOIN almacen.pedido p
							ON n.nropedido = p.nropedido
							INNER JOIN ventas.proyectos y
							ON p.proyectoid = y.proyectoid
							WHERE n.nronsalida = '".$this->nro_."'"
		);
	if ($cn->num_rows($query)>0) {
		$result = $cn->ExecuteNomQuery($query);
		$destino = $result['destino'];
		$rz = $result['descripcion'];
		$proyectoid = $result['proyectoid'];
		$ftra = $result['fecsal'];
	}
	$cn->close($query);
	# Datos Generales
	#cuadro izquierdo
	$this->SetLineWidth(0);
	$this->SetXY(10,42);
	$this->Cell(95,16,'',1,1,'C',false);
	# datos
	$this->SetXY(12,46);
	$this->SetFont('Arial','B',8);
	$this->SetTextColor(2,2,2);
	$this->SetFillColor(255,255,255);
	$this->Cell(20,0,'Codigo Proyecto :',0,0,'L',false);
	$this->SetFont('Arial','',8);
	$this->SetTextColor(29,29,29);
	$this->SetXY(42,46);
	$this->Cell(20,0,utf8_decode($proyectoid),0,1,'L',false);
	# Nombre de Proyecto
	$this->SetXY(12,52);
	$this->SetFont('Arial','B',8);
	$this->Cell(20,0,'Nombre Proyecto : ',0,0,'L',false);
	$this->SetXY(42,52);
	$this->SetFont('Arial','',8);
	$this->MultiCell(30,0,utf8_decode($rz),0,'L',false);
	# Cuadro Derecho
	$this->SetLineWidth(0);
	$this->SetXY(105,42);
	$this->Cell(95,16,'',1,1,'C',false);
	# datos
	$this->SetXY(108,46);
	$this->SetFont('Arial','B',8);
	$this->Cell(20,0,'Destino :',0,0,'L',false);

	$this->SetXY(124,46);
	$this->SetFont('Arial','',8);
	$this->MultiCell(70,0,utf8_decode($destino),0,'L',false);
	# Fecha de Traslado
	$this->SetXY(108,52);
	$this->SetFont('Arial','B',8);
	$this->Cell(20,0,'Fecha de Traslado : ',0,1,'L',false);
	$this->SetXY(138,52);
	$this->SetFont('Arial','',8);
	$anio = substr($ftra, 0,4);
	$mes = substr($ftra, 5,7);
	$dia = substr($ftra, 8,10);
	$this->MultiCell(70,0,$dia.' de '.$meses[$mes - 1].' del '.$anio,0,'L',false);
	//
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
    $header = array('Item','Cantidad','Und','Descripcion','Medida');
    $w = array(18, 25, 20, 75, 52);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Restauración de colores y fuentes
    $this->SetFillColor(255,255,210);
    $this->SetTextColor(0);
    $this->SetFont('Arial','',6.5);

}

function fnline()
{
	$this->SetLineWidth(0);
	$this->Cell(190,0,'',1,1,'C',false);
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

// Creación del objeto de la clase heredada

$nro = $_GET['nro'];

$pdf = new PDF();
$pdf->addprm($nro);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->cabnro();
$pdf->cabdatos();
$pdf->Ln(8);
$pdf->FancyTable();
$pdf->SetWidths(array(18, 25, 20, 75, 52));
$cn = new PostgreSQL();
$query = $cn->consulta("SELECT * FROM almacen.sp_consultardetnsalida('".$nro."')");
  if ($cn->num_rows($query)>0) {
    $i = 1;
    while($fila = $cn->ExecuteNomQuery($query)){
      $pdf->Row(array($i++,$fila['cantidad'],$fila['matund'], $fila['matnom'], $fila['matmed']));
      }
    }
$cn->close($query);
//$pdf->Ln(-2);
$pdf->fnline();
$pdf->Output();


?>