<?
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


class pie extends FPDF
{

//Pie de página
function Footer($firma)
{
    var_dump($firma);
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Times italic 8
    $this->SetFont('Times','I',8);
    //Número de página
    $this->Cell(0,10,$firma,0,0,'C');
}
}
?>