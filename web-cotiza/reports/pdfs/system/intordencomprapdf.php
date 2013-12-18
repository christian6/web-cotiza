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
    $algs = 'L';
      $w=$this->widths[$i];
      if ($w == 17 ) {
        $algs = 'R';
      }else if($w == 18 || $w == 10){
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
  $fec = '';
  $cn = new PostgreSQL();
  $query = $cn->consulta("SELECT c.nrocotizacion,c.lugent,d.docnom,p.nompag,m.nomdes,c.fecha::date,c.fecent FROM".
    " logistica.compras c INNER JOIN admin.moneda m ON m.monedaid=c.monedaid INNER JOIN admin.documentos d ON d.documentoid=c.documentoid".
    " INNER JOIN admin.fpago p ON p.pagoid=c.pagoid WHERE nrocompra LIKE '".$this->nro_."'");
  if ($cn->num_rows($query)>0) {
    while ($result =  $cn->ExecuteNomQuery($query)) {
      $this->SetXY(100,38);
      $this->cell(100,4,'Nro Cotizaion:         '.$result['nrocotizacion'],'LRT',0,'L',false);
      $this->SetXY(100,42);
      $this->Cell(100,4,'Lugar de Entrega:  ','RL',1,'LR',false);
      $this->SetXY(100,46);
      $this->Cell(100,4,utf8_decode($result['lugent']),'LR',1,'L',false);
      $this->SetXY(100,50);
      $this->cell(100,4,'Documento:             '.$result['docnom'],'LR',1,'L',false);
      $this->SetXY(100,54);
      $this->cell(100,4,'Forma Pago:           '.$result['nompag'],'RL',1,'L',false);
      $this->SetXY(100,58);
      $this->cell(100,4,'Moneda:                  '.$result['nomdes'],'LR',1,'L',false);
      $this->SetXY(100,62);
      $this->cell(100,4,'Fecha Entrega:        '.$result['fecent'],'LRB',0,'L',false);
      $this->mone = $result['nomdes'];
      $fec = $result['fecha'];
    }
  }
  $cn->close($query);
  $cn = new PostgreSQL();
	$query = $cn->consulta("
    SELECT p.rucproveedor,p.razonsocial,p.direccion,a.paisnom,d.deparnom,r.provnom,i.distnom,c.contacto
    FROM admin.proveedor p INNER JOIN logistica.compras c 
            ON c.rucproveedor=p.rucproveedor
            INNER JOIN admin.pais a
            ON p.paisid=a.paisid
            INNER JOIN admin.departamento d
            ON p.departamentoid=d.departamentoid
            INNER JOIN admin.provincia r
            ON p.provinciaid=r.provinciaid
            INNER JOIN admin.distrito i
            ON p.distritoid=i.distritoid
    WHERE a.paisid LIKE d.paisid AND d.departamentoid LIKE r.departamentoid AND r.provinciaid LIKE i.provinciaid AND p.rucproveedor LIKE '".$this->ruc_."' AND  c.nrocompra LIKE '".$this->nro_."'");
	if ($cn->num_rows($query)>0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
      $this->SetXY(10,38);
    	$this->cell(90,4,'RUC:                 '.$result['rucproveedor'],'LTR',0,'L',false);
      $this->SetXY(10,42);
    	$this->cell(90,4,'Razon Social:    '.$result['razonsocial'],'LR',1,'L',false);
			$this->SetXY(10,46);
      $this->cell(90,4,utf8_decode('Dirección:          '.$result['direccion']),'LR',1,'L',false);
      $this->SetXY(10,50);
			$this->cell(10,4,'Distrito:              '.$result['distnom'],'L',1,'L',false);
			$this->SetXY(55,50);
      $this->cell(10,4,'Provincia: '.$result['provnom'],0,1,'L',false);
			$this->SetXY(10,54);
      $this->cell(90,4,'Departamento:   '.$result['deparnom'],'L',1,'L',false);
			$this->SetXY(55,54);
      $this->cell(45,4,'Pais:      '.$result['paisnom'],'R',1,'L',false);
      $this->SetXY(10,58);
      $this->cell(90,4,'Contacto:           '.$result['contacto'],'LR',1,'L',false);
      $this->SetXY(10,62);
      $this->cell(90,4,'Fecha:               '.$fec,'LBR',1,'L',false);
		}
		$cn->close($query);
	}
  
  //-------------------------------------------------------------------------------------------
	$this->SetFont('Arial','B',16);
  $this->SetXY(80,25);
	$this->cell(160,0,'Nro Orden de Compra',0,0,'C',false);
  $this->SetXY(80,32);
  $this->cell(160,0,$this->nro_,0,2,'C',false);
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
    $w = array(10, 76, 36, 18, 18, 17, 17);
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
  $this->cell(40,0,number_format($sto,2,'.',','),0,0,'R',false);
  $this->SetX(130);
  $this->cell(40,8,'IGV 18%:',0,0,'R',false);
  $this->SetX(156);
  $this->cell(40,8,number_format($igv,2,'.',','),0,0,'R',false);
  $this->SetX(130);
  $this->cell(40,18,'Total :',0,0,'R',false);
  $this->SetX(156);
  $this->cell(40,18,number_format($tot,2,'.',','),0,1,'R',false);
  $this->Ln(0);
  $let = new CNumeroaLetra;
  $let->setNumero($tot);
  $let->setMoneda($this->mone);
  $this->cell(0,0,'SON: '.$let->letra(),0,1,'R',false);
  $this->Ln(4);
}
function factura()
{
  $this->SetFont('Arial','B',7);
  $this->cell(0,4,utf8_decode('Datos de Facturación'),1,1,'C',false);
  $this->cell(0,4,'Ruc:                 20428776110','LR',1,'L',false);
  $this->cell(0,4,'Facturar a:      ICR PERU S.A.','LR',1,'L',false);
  $this->cell(0,4,utf8_decode('Dirección:       Jr. Gral. José de San Martin Mz. E Lote 6 Huachipa - Lurigancho Lima'),'LR',1,'L',false);
  $this->cell(0,4,'Telefono:        51 1 371-0443','LBR',1,'L',false);
  $this->cell(0,8,'* Adjuntar copia de esta orden a la factura original',1,0,'L',false);
}
//Pie de Pagina
function Footer()
{
	//Posicion a 1.,5 cm del final
	$this->SetY(-15);
	// Arial Italic 8
	$this->SetFont('Arial','I',8);
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
  $this->SetX(5);
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
$pdf->SetWidths(array(10, 76, 36, 18, 18, 17, 17));
$cn = new PostgreSQL();
$sub = 0;
$query = $cn->consulta("SELECT * FROM logistica.spconsultardetcompra('".$nro."')");
  if ($cn->num_rows($query)>0) {
    $i = 1;
    while($fila = $cn->ExecuteNomQuery($query)){
      $pdf->Row(array($i++,utf8_decode($fila['matnom']),utf8_decode($fila['matmed']), $fila['matund'], $fila['cantidad'],number_format($fila['precio'],2,'.',','), number_format($fila['importe'],2,'.',',') ));
      $sub += $fila['importe'];
    }
      $cn->close($query);
    }
$pdf->fnline();
$pdf->tfoot($sub);
$pdf->fnline();
$pdf->factura();
$pdf->Output();
?>
