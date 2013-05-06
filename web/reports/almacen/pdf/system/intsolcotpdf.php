<?php
include("../../../datos/postgresHelper.php");
include("../../../modules/CNumeroaLetra.php");
require("../../../modules/fpdf.php");

$ruc = $_GET['ruc'];
$nro = $_GET['nro'];


class PDF extends FPDF
{
	var $ruc_ = "";
	var $nro_ = "";
  var $mone = "";
	
	var $widths;
	var $aligns;

function addprm($r,$n){
	$this->ruc_ = $r;
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
      }else if($w == 18){
        $algs = 'C';
      }
      $a=isset($this->aligns[$i]) ? $this->aligns[$i] : $algs;
      $x=$this->GetX();
      $y=$this->GetY();
      $this->Rect($x,$y,$w,$h);
      $this->MultiCell($w,4,$data[$i],0,$a);
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

// Cabecera de página
function Header()
{
    // Logo
    $this->Image('../../../source/icrlogo.png',10,8,0,0,'PNG');
    // Font type
    $this->SetFont('Arial','B',7);
    // Mover a la derecha
    $this->cell(80);
    // Titulo
    $this->cell(30,10,'Jr. Gral. Jose de San Martin Mz. E Lote 6 Huachipa - Lurigancho Lima 15, Peru',0,0,'C',false);
    $this->cell(-40,18,'Central Telefonica: (511) 371-0443',0,2,'C',false);
    $this->cell(-40,-12,'E-mail: logistica@icrperusa.com',0,2,'C',false);
    $this->Ln(20);
}

function Cab(){
	$this->SetFont('Arial','',8);
  $cn = new PostgreSQL();
	$query = $cn->consulta("SELECT rucproveedor,razonsocial,direccion,distrito,provincia,departamento,pais FROM admin.proveedor WHERE rucproveedor LIKE '".$this->ruc_."'");
	if ($cn->num_rows($query)>0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
    	$this->cell(30,10,'RUC:                '.$result['rucproveedor'],0,0,'L',false);
    	$this->cell(49,18,'Razon Social:    '.$result['razonsocial'],0,1,'R',false);
			$this->cell(-4,-9,'Direccion:         '.$result['direccion'],0,1,'L',false);
			$this->cell(-4,18,'Distrito:             '.$result['distrito'],0,1,'L',false);
			$this->cell(-4,-9,'Provincia:         '.$result['provincia'],0,1,'L',false);
			$this->cell(-4,18,'Departamento: '.$result['departamento'],0,1,'L',false);
			$this->cell(-4,-9,'Pais:                 '.$result['pais'],0,2,'L',false);
		}
		$cn->close($query);
	}
  $cn = new PostgreSQL();
  $query = $cn->consulta("SELECT c.tentr,c.contacto,c.fval,m.nomdes FROM logistica.cotizacioncli c INNER JOIN admin.moneda m ON m.monedaid=c.monedaid WHERE nrocotizacion LIKE '".$this->nro_."' AND rucproveedor LIKE '".$this->ruc_."'");
  if ($cn->num_rows($query)>0) {
    while ($result =  $cn->ExecuteNomQuery($query)) {
      $this->SetXY(130,50);
      $this->cell(150,0,'Tiempo de Entrega: '.$result['tentr'].' dias.',0,1,'L',false);
      $this->SetXY(130,55);
      $this->cell(150,0,'Contacto: '.$result['contacto'],0,1,'L',false);
      $this->SetXY(130,60);
      $this->cell(150,0,'Validez de la Oferta: '.$result['fval'],0,1,'L',false);
      $this->SetXY(130,65);
      $this->cell(130,0,'Moneda: '.$result['nomdes'],0,0,'L',false);
      $this->mone = $result['nomdes'];
    }
  }
  $cn->close($query);
  //-------------------------------------------------------------------------------------------
	$this->SetFont('Arial','B',16);
  $this->SetXY(80,30);
	$this->cell(150,0,'Nro Solicitud de Cotizacion',0,0,'C',false);
  $this->SetXY(80,38);
  $this->cell(150,0,$this->nro_,0,2,'C',false);
}

function fnline()
{
  $this->SetFont('Arial','B',16);
  $this->cell(0,0,'______________________________________________________________',0,2,'C',false);
  $this->Ln(8);
}

//Body Pag
function FancyTable()
{
    // Colores, ancho de línea y fuente en negrita
    $this->SetFillColor(139,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(169,169,169);
    $this->SetLineWidth(.1);
    $this->SetFont('','B',8);
    // Cabecera
    $header = array('Item','Descripcion','Medida','UND','Cantidad','Precio','Importe');
    $w = array(22, 70, 30, 18, 17, 17, 17);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Restauración de colores y fuentes
    $this->SetFillColor(255,255,210);
    $this->SetTextColor(0);
    $this->SetFont('','',6.5);

}
function tfoot($sto)
{
  $igv = 0;
  $tot = 0;
  /// Recuerda Consultar el IGV de la Base de Datos
  $igv = ($sto * 0.18);
  $tot = $sto+$igv;
  $this->SetFont('Arial','B',8.5);
  $this->SetX(130);
  $this->cell(40,0,'Sub-Total :',0,1,'R',false);
  $this->SetX(156);
  $this->cell(40,0,$sto,0,0,'R',false);
  $this->SetX(130);
  $this->cell(40,8,'IGV :',0,0,'R',false);
  $this->SetX(156);
  $this->cell(40,8,$igv,0,0,'R',false);
  $this->SetX(130);
  $this->cell(40,18,'Total :',0,0,'R',false);
  $this->SetX(156);
  $this->cell(40,18,$tot,0,1,'R',false);
  $this->Ln(0);
  $let = new CNumeroaLetra;
  $let->setNumero($tot);
  $let->setMoneda($this->mone);
  $this->cell(0,0,'SON: '.$let->letra(),0,1,'R',false);
  $this->Ln(4);
}
//Pie de Pagina
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
$pdf = new PDF();
$pdf->addprm($ruc,$nro);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Cab();
$pdf->SetY(68);
$pdf->fnline();
$pdf->SetY(73);
$pdf->FancyTable();
$pdf->SetWidths(array(22, 70, 30, 18, 17, 17, 17));
$cn = new PostgreSQL();
$sub = 0;
$query = $cn->consulta("SELECT * FROM logistica.spcondetcotizapro('".$nro."','".$ruc."')");
  if ($cn->num_rows($query)>0) {
    $i = 1;
    while($fila = $cn->ExecuteNomQuery($query)){
      $pdf->Row(array($fila['materialesid'],$fila['matnom'], $fila['matmed'], $fila['matund'], $fila['cantidad'],$fila['precio'],$fila['importe']));
      $sub += $fila['importe'];
      }
      $cn->close($query);
    }
$pdf->fnline();
$pdf->tfoot($sub);
$pdf->fnline();
$pdf->Output();
?>
