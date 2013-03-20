<?
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


class PDF extends FPDF
{
//Cabecera de página
function Header($datos)
{
    //Datos estudiante
    $h=array(100,60,40);
    $this->Ln(1);
    
    foreach($datos as $row)
	{
		$this->Cell($h[0],6,$row[0],'',0,'L',$fill);
		$this->Cell($h[1],6,$row[1],'',0,'L',$fill);
		$this->Cell($h[2],6,$row[2],'',0,'L',$fill);
         $this->Ln();
    }
}

//Pie de página
function Footer($firma)
{

    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Times italic 8
    $this->SetFont('Times','I',8);
    //Número de página
    $this->Cell(0,10,$firma,0,0,'C');
}
}
?>