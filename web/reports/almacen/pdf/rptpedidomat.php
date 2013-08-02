<?php
include("../../../datos/postgresHelper.php");
include("../../../modules/CNumeroaLetra.php");
require("../../../modules/fpdf.php");

$nro = $_GET['nro'];


class PDF extends FPDF
{
	var $nro_ = "";
  var $mone = "";
	
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
    $this->Image('../../../resource/icrlogo.png',10,8,0,0,'PNG');
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
  
  $proid = "";
  $subid = "";
  $secid = "";

  $this->SetFont('Arial','B',10);
  $this->SetXY(150,10);
  $this->Cell(50,0,'Materiales',0,0,'C',false);

	$this->SetFont('Arial','',8);
  $cn = new PostgreSQL();
	$query = $cn->consulta("SELECT DISTINCT p.proyectoid,p.subproyectoid,p.sector,o.descripcion as nompro,o.direccion,a.paisnom,d.deparnom,r.provnom,i.distnom,e.empdni,e.empnom ||', '|| e.empape as emp,p.fecha::date,p.fecent,c.nombre as cliente FROM ".
                        "almacen.pedido p INNER JOIN admin.empleados e ".
                        "ON p.empdni=e.empdni ".
                        "INNER JOIN ventas.proyectos o ".
                        "ON p.proyectoid=o.proyectoid ".
                        "INNER JOIN admin.clientes c ".
                        "ON o.ruccliente=c.ruccliente ".
                        "INNER JOIN admin.pais a ".
                        "ON o.paisid=a.paisid ".
                        "INNER JOIN admin.departamento d ".
                        "ON o.departamentoid=d.departamentoid ".
                        "INNER JOIN admin.provincia r ".
                        "ON o.provinciaid=r.provinciaid ".
                        "INNER JOIN admin.distrito i ".
                        "ON o.distritoid=i.distritoid ".
                        "WHERE a.paisid LIKE d.paisid AND d.departamentoid LIKE r.departamentoid AND r.provinciaid LIKE i.provinciaid AND p.nropedido LIKE '".$this->nro_."' ".
                        "LIMIT 1 OFFSET 0");

  $this->SetXY(15,40);
  $this->cell(150,0,'Cliente: ',0,0,'L',false);
  $this->SetXY(15,44);
  $this->cell(150,0,'Proyecto: ',0,1,'L',false);
  $this->SetXY(15,48);
  $this->cell(150,0,'Sub-Proyecto: ',0,1,'L',false);
  $this->SetXY(15,52);
  $this->cell(150,0,'Sector: ',0,1,'L',false);
  $this->SetXY(15,56);
  $this->cell(150,0,'Descripcion: ',0,1,'L',false);
  $this->SetXY(15,60);
  $this->cell(150,0,'Fecha: ',0,1,'L',false);
  $this->SetXY(15,64);
  $this->cell(150,0,'Pedido por: ',0,1,'L',false);
  // Otra parte
  $this->SetXY(100,40);
  $this->cell(150,0,'Direccion: ',0,1,'L',false);
  $this->SetXY(100,44);
  $this->cell(150,0,'Distrito:',0,1,'L',false);
  $this->SetXY(100,48);
  $this->cell(150,0,'Provincia:',0,1,'L',false);
  $this->SetXY(100,52);
  $this->cell(150,0,'Departamento:',0,1,'L',false);
  $this->SetXY(100,56);
  $this->cell(150,0,'Pais: ',0,0,'L',false);
  $this->SetXY(100,60);
  $this->cell(150,0,'Fecha Entrega:',0,1,'L',false);

	if ($cn->num_rows($query)>0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
      $this->SetXY(40,40);
    	$this->cell(150,0,$result['cliente'],0,0,'L',false);
      $this->SetXY(40,44);
    	$this->cell(150,0,$result['nompro'],0,1,'L',false);
      //$this->SetXY(40,48);
			/*$this->cell(150,0,$result[''],0,1,'L',false);
      $this->SetXY(40,52);
			$this->cell(150,0,$result[''],0,1,'L',false);
      $this->SetXY(40,56);
			$this->cell(150,0,$result[''],0,1,'L',false);*/
      $this->SetXY(40,60);
      $this->cell(150,0,$result['fecha'],0,1,'L',false);
      $this->SetXY(40,64);
      $this->cell(150,0,$result['emp'],0,1,'L',false);
      //
      $this->SetXY(125,40);
			$this->cell(150,0,$result['direccion'],0,1,'L',false);
      $this->SetXY(125,44);
      $this->cell(150,0,$result['distnom'],0,1,'L',false);
      $this->SetXY(125,48);
      $this->cell(150,0,$result['provnom'],0,1,'L',false);
      $this->SetXY(125,52);
      $this->cell(150,0,$result['deparnom'],0,1,'L',false);
      $this->SetXY(125,56);
			$this->cell(150,0,$result['paisnom'],0,0,'L',false);
      $this->SetXY(125,60);
      $this->cell(150,0,$result['fecent'],0,0,'L',false);
      $subid = $result['subproyectoid'];
      $secid = $result['sector'];
      $proid = $result['proyectoid'];
		}
		$cn->close($query);
	}
  $cn->close($query);

  $cn = new PostgreSQL();
  $query = $cn->consulta("SELECT subproyecto FROM ventas.subproyectos WHERE subproyectoid = '".$subid."'");
  if ($cn->num_rows($query)>0) {
    $result = $cn->ExecuteNomQuery($query);
    $this->SetXY(40,48);
    $this->cell(150,0,$result['subproyecto'],0,1,'L',false);
  }
  $cn->close($query);

  $cn = new PostgreSQL();
  $query = $cn->consulta("SELECT sector,descripcion FROM ventas.sectores WHERE proyectoid LIKE '".$proid."' AND subproyectoid LIKE '".$subid."' AND sector LIKE '".$secid."'");
  if ($cn->num_rows($query)>0) {
    $result = $cn->ExecuteNomQuery($query);
    $this->SetXY(40,52);
    $this->cell(150,0,$result['sector'],0,1,'L',false);
    $this->SetXY(40,56);
    $this->cell(150,0,$result['descripcion'],0,1,'L',false);
  }
  $cn->close($query);


  //-------------------------------------------------------------------------------------------
	$this->SetFont('Arial','B',16);
  $this->SetXY(80,25);
	$this->cell(150,0,'Nro Pedido al Almacen',0,0,'C',false);
  $this->SetXY(80,32);
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
    $header = array('Item','Codigo','Descripcion','Medida','UND','Cantidad');
    $w = array(18, 22, 70, 45, 18, 17);
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
$pdf->addprm($nro);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Cab();
$pdf->SetY(68);
$pdf->fnline();
$pdf->SetY(73);
$pdf->FancyTable();
$pdf->SetWidths(array(18,22, 70, 45, 18, 17));
$cn = new PostgreSQL();
$query = $cn->consulta("SELECT * FROM almacen.spconsultardetpedidomat('".$nro."')");
  if ($cn->num_rows($query)>0) {
    $i = 1;
    while($fila = $cn->ExecuteNomQuery($query)){
      $pdf->Row(array($i++,$fila['materialesid'],$fila['matnom'], $fila['matmed'], $fila['matund'], $fila['cantidad']));
      }
      $cn->close($query);
    }
$pdf->fnline();
$pdf->Output();
?>