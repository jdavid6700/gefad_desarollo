<?php
//Profesionales en Sistemas e Informatica
//ISC: Salvador Carrasco Flores

require('../fpdf.php');
 include("conexion.php");


	
	$conexion=conectar();
	  $sql = "select *from producto;";
   $result = mysql_query($sql,$conexion);
   $num = mysql_num_rows($result);  
  
   
class PDF extends FPDF
{
	function Header()
	{	
		global $title;
		global $title1;	
		global $title2;	
		global $title3;	
		global $title4;	
		//Arial bold 15
		$this->SetFont('Courier','B',25);
    	//LOGOTIPO
		$this->Image('logopsi.jpg',5,15,33);
		//Calculamos ancho y posición del título.
		$w=$this->GetStringWidth($title)+185;
		$w1=$this->GetStringWidth($title1)+16;
		$w2=$this->GetStringWidth($title2)+32;
		$w3=$this->GetStringWidth($title3)+64;
		$w4=$this->GetStringWidth($title4)+70;
		$this->SetX((210-$w)/2);
		$this->SetX((210-$w1)/2);
		//$this->SetX((210-$w2)/2);
		//Colores de los bordes, fondo y texto
		$this->SetDrawColor(250);
		$this->SetFillColor(240);
		$this->SetTextColor(0);
		//Ancho del borde (1 mm)
		$this->SetLineWidth(1);
		//Título
		$this->Cell($w,25,$title,0,1,'C',0);
		$this->SetFont('Arial','B',15);
		$this->Cell($w,0,$title1,0,1,'C',0);
		$this->Cell(210,10,$title2,0,1,'C',0);
		$this->SetFont('Arial','I',10);
		$this->Cell(210,0,$title3,0,1,'C',0);
		$this->Cell(210,10,$title4,0,1,'C',0);
		//Salto de línea
		$this->Ln(10);
	}
	function Footer()
	{
		//Posición a 1,5 cm del final
		$this->SetY(-55);
		//Arial itálica 8
		$this->SetFont('Arial','B',8);
		$this->Cell(0,0,'ATENTAMENTE:',0,0,'C');
    	$this->SetLineWidth(0.5);
    	$this->Line(77,260,139,260);
		$this->Ln(15);
    	$this->Cell(0,0,'ING. SALVADOR CARRASCO FLORES',0,1,'C');
		$this->Ln(10);
		$this->SetFont('Arial','I',8);
		//Color del texto en gris
		$this->SetTextColor(128);
		//Número de página
		$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
	}
}
$pdf=new PDF();
$title='P.S.I';
$title1='PROFESIONALES EN SISTEMAS E INFORMATICA';
$title2='';
$title3='Diseño de Sistemas de Información y Paginas WEB Dinamicas';
$title4='"Creatividad Diseño y Calidad"';
$pdf->SetTitle($title,$title1,$title2,$title3,$title4);
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->SetY(45);
$pdf->SetFont('Arial','B',10);
$pdf->SetLineWidth(1.2);$pdf->Line(20,55,200,55);
$pdf->SetLineWidth(1.2);$pdf->Line(20,80,200,80);
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(185,40,'    CLAVE                    DESCRIPCION             PRECIO UNIT.              CANTIDAD          IDCAT',0,0,'C',0);
$pdf->Ln(5);
$k=44;
while ($l =mysql_fetch_object($result) )
{  $k=$k+1;
 $pdf->SetFont('Arial','B',6.5);
 $pdf->Cell(40,$k,$l->clvp,0,0,'C',0);
  $pdf->Cell(24,$k,$l->descripcion,0,0,'C',0);
  $pdf->Cell(70,$k,$l->precio,0,0,'C',0);
  $pdf->Cell(-10,$k,$l->exist,0,0,'C',0);
  $pdf->Cell(60,$k,$l->idcat,0,0,'C',0);
  $pdf->Ln(4);
} 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,$k ,$num,0,0,'C',0);
 
$pdf->Output();
?>