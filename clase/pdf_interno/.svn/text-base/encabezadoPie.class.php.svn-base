<?
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


class encabezadoPie extends FPDF
{
//Cabecera de página
function Header()
{
    
    //Logo
    $this->Image($this->configuracion["raiz_documento"].$this->configuracion["grafico"]."/logoUniversidad.png",10,8,25);
    //Times bold 13
    $this->SetFont('Times','B',13);
    //Movernos a la derecha
    $this->Cell(80);
    //Título
    $this->Cell(30,10,'UNIVERSIDAD DISTRITAL FRANCISCO JOSE DE CALDAS',0,0,'C');
    //Salto de línea
    $this->Ln(5);
    //Titulo
    $this->Cell(80);
    $this->Cell(20,10,'VICERRECTORIA',0,0,'C');
    //Salto de línea
    $this->Ln(5);
    //Titulo
    $this->Cell(80);
    $this->Cell(20,10,'REGISTRO ACADEMICO',0,0,'C');
    //Salto de línea
    $this->Ln(20);
}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Times italic 8
    $this->SetFont('Times','I',8);
    //Número de página
    $this->Cell(0,10,'Oficina Asesora de Sistemas'." ".$this->PageNo().'/{nb}',0,0,'C');
}
}
?>