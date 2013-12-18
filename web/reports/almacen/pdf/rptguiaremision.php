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
	$this->Cell(74,8,'GUIA REMISION REMITENTE',0,1,'C',true);
	$this->SetXY(127,30);
	$this->SetTextColor(80,80,80);
	$this->Cell(0,8,''.$this->nro_,0,1,'C',false);
}

function cabdatos()
{
	# Declaracion de Variables
	$llegada = "";
	$rz = "";
	$ruc = "";
	$ftra = "";
	$placa = "";
	$mar = "";
	$lic = "";
	$tranom = "";
	$trruc = "";
	$meses = array("Enero", "Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

	# Consultado Datos
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT DISTINCT g.nroguia,g.puntollega,g.razonsocial,g.ruc,g.fectra,t.tranom,p.traruc,p.nropla,p.marca,c.conlic
							FROM almacen.guiaremision g INNER JOIN admin.transportista t
							ON g.traruc = t.traruc
							INNER JOIN admin.transporte p
							ON t.traruc = p.traruc
							INNER JOIN admin.conductor c
							ON t.traruc=c.traruc
							WHERE g.nroguia = '".$this->nro_."' AND g.esid LIKE '46'"
		);
	if ($cn->num_rows($query)>0) {
		$result = $cn->ExecuteNomQuery($query);
		$llegada = $result['puntollega'];
		$rz = $result['razonsocial'];
		$ruc = $result['ruc'];
		$ftra = $result['fectra'];
		$placa = $result['nropla'];
		$mar = $result['marca'];
		$lic = $result['conlic'];
		$tranom = $result['tranom'];
		$traruc = $result['traruc'];
	}
	$cn->close($query);
	# Punto de Salida
	$this->SetLineWidth(0);
	$this->SetXY(10,42);
	$this->Cell(95,17,'',1,1,'C',false);
	$this->SetXY(10,42);
	$this->SetFont('Arial','B',7);
	$this->SetTextColor(240,240,240);
	$this->SetFillColor(11,56,97);
	$this->Cell(23,5,'Punto de Partida',0,1,'C',true);
	$this->SetXY(15,48);
	$this->SetFont('Arial','',8);
	$this->SetTextColor(29,29,29);
	$this->MultiCell(70,4,'Jr. Gral. Jose de San Martin Mz. E Lote 6 Huachipa - Lurigancho Lima 15, Peru',0,'C',false);

	#Punto de Llegada
	$this->SetLineWidth(0);
	$this->SetXY(105,42);
	$this->Cell(95,17,'',1,1,'C',false);
	$this->SetXY(105,42);
	$this->SetFont('Arial','B',7);
	$this->SetTextColor(240,240,240);
	$this->SetFillColor(11,56,97);
	$this->Cell(23,5,'Punto de Llegada',0,1,'C',true);
	$this->SetXY(105,48);
	$this->SetFont('Arial','',8);
	$this->SetTextColor(29,29,29);
	$this->MultiCell(70,4,utf8_decode($llegada),0,'C',false);

	# Destinatario de Guia
	$this->SetLineWidth(0);
	$this->SetXY(10,59);
	$this->Cell(95,20,'',1,1,'C',false);
	$this->SetXY(10,59);
	$this->SetFont('Arial','B',7);
	$this->SetTextColor(240,240,240);
	$this->SetFillColor(11,56,97);
	$this->Cell(50,5,'Nombre o Razon Social del Destinatario',0,1,'C',true);
	$this->SetXY(15,66);
	$this->SetFont('Arial','',8);
	$this->SetTextColor(29,29,29);
	$this->Cell(80,4,utf8_decode($rz),0,1,'C',false);
	$this->SetXY(10,72);
	$this->Cell(60,4,utf8_decode('Número de RUC:  '.$ruc),0,1,'C',false); 

	# Fecha de Traslado
	$this->SetLineWidth(0);
	$this->SetXY(105,59);
	$this->Cell(95,20,'',1,1,'C',false);
	$this->SetXY(105,59);
	$this->SetFont('Arial','B',7);
	$this->SetTextColor(240,240,240);
	$this->SetFillColor(11,56,97);
	$this->Cell(40,5,'Fecha de Inicio de Traslado',0,1,'C',true);
	$this->SetXY(105,68);
	$this->SetFont('Arial','',8);
	$this->SetTextColor(29,29,29);
	$anio = substr($ftra, 0,4);
	$mes = substr($ftra, 5,7);
	$dia = substr($ftra, 8,10);
	//$this->Cell(0,4,$dia.' de '.$meses[$mes - 1].' del '.$anio,0,1,'C',false);
	$this->Cell(0,4,$ftra,0,1,'C',false);

	#Unidad de Transporte y Conductor
	$this->SetLineWidth(0);
	$this->SetXY(10,79);
	$this->Cell(95,18,'',1,1,'C',false);
	$this->SetXY(10,79);
	$this->SetFont('Arial','B',7);
	$this->SetTextColor(240,240,240);
	$this->SetFillColor(11,56,97);
	$this->Cell(45,5,'Unidad de Transporte y Conductor',0,1,'C',true);
	$this->SetXY(15,86);
	$this->SetFont('Arial','',8);
	$this->SetTextColor(29,29,29);
	$this->Cell(80,4,utf8_decode('Marca y Número de Placa: '.$mar.' '.$placa),0,1,'L',false);
	$this->SetXY(15,91);
	$this->Cell(50,4,utf8_decode('Nro Licencia: '.$lic),0,1,'L',false);
	//consultar datos  de transporte y conductor

	# Empresa de Transporte
	$this->SetLineWidth(0);
	$this->SetXY(105,79);
	$this->Cell(95,18,'',1,1,'C',false);
	$this->SetXY(105,79);
	$this->SetFont('Arial','B',7);
	$this->SetTextColor(240,240,240);
	$this->SetFillColor(11,56,97);
	$this->Cell(40,5,'Empresa de Transporte',0,1,'C',true);
	$this->SetXY(105,86);
	$this->SetFont('Arial','',8);
	$this->SetTextColor(29,29,29);
	$this->Cell(30,4,utf8_decode('Nombre o Razón Social: '.$tranom),0,1,'L',false);
	$this->SetXY(105,91);
	$this->Cell(30,4,utf8_decode('Número de RUC: '.$traruc),0,1,'L',false);
	//Consulta de Fecha de Traslado
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
	$this->SetFont('Arial','',16);
	$this->cell(0,0,'_____________________________________________________________',0,0,'C',false);
	$this->Ln(8);
}
function RowNiples($data)
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
		$this->MultiCell($w,4,$data[$i],'TB',$a,false);
		$this->SetXY($x+$w,$y);
	}
	$this->Ln($h);
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
$pdf->Ln(4);
$pdf->FancyTable();
$pdf->SetWidths(array(18, 25, 20, 75, 52));
$cn = new PostgreSQL();
$query = $cn->consulta("SELECT * FROM almacen.sp_consultardetguia('".$nro."')");
  if ($cn->num_rows($query)>0) {
    $i = 1;
    while($fila = $cn->ExecuteNomQuery($query)){
      $pdf->Row(array($i++,$fila['cantidad'],$fila['matund'], $fila['matnom'], $fila['matmed']));
      }
    }
$cn->close($query);
$pdf->Ln(-2);
$pdf->fnline();
$pdf->AliasNbPages();

// Obteniendo Nro de Pedido
$cn = new PostgreSQL();
$query = $cn->consulta("SELECT nropedido FROM almacen.guiaremision WHERE nroguia LIKE '".$nro."' AND esid LIKE '46' LIMIT 1 OFFSET 0;");
$npe = $cn->ExecuteNomQuery($query);
$npe = $npe[0];
$cn->close($query);

$query = $cn->consulta("SELECT n.materialesid,m.matnom,m.matmed,n.metrado,n.tipo FROM operaciones.niples n
						INNER JOIN admin.materiales m
						ON n.materialesid LIKE m.materialesid
						WHERE nropedido LIKE '".$npe."';");
if ($cn->num_rows($query) > 0) {
	$pdf->SetWidths(array(18,22,60,30,18,20,18));
	$i=1;
	$pdf->AddPage();
	$pdf->cabnro();
	$pdf->SetFillColor(255,255,210);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',6.5);
	$pdf->SetLineWidth(.1);
	$pdf->SetXY(10,50);
	while ($result = $cn->ExecuteNomQuery($query)) {
		$pdf->RowNiples(array($i++,$result['materialesid'],$result['matnom'],$result['matmed'],'x',$result['metrado'],$result['tipo']));
	}
}
$cn->close($query);
$pdf->Output();


?>