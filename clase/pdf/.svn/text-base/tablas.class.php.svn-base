<?

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/pdf/fpdf.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/pdf/encabezadoPie.class.php");


class tablas extends FPDF
{
function Header($datos)
	{
        $this->SetFillColor(0,0,0);
	$this->SetTextColor(255);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(0);
	$this->SetFont('Arial','','8');

        //Datos estudiante
        $h=array(120,50,40);
        $this->Ln(-4);
	//Restauraci�n de colores y fuentes
	$this->SetFillColor(255,255,255);
	$this->SetTextColor(0);
	$this->SetFont('');

	for($i=0;$i<=1;$i++)
	{
                $this->Cell(5);
		$this->Cell(130,6,$datos[$i][0],'',0,'L',$fill);
		$this->Cell(55,6,$datos[$i][1],'',0,'L',$fill);
		$this->Cell(40,6,$datos[$i][2],'',0,'L',$fill);
                $this->Ln(4.5);
        }
	}

function FancyTable($header,$data,$datos, $firma)
{
        $this->Header($datos);
	
	//Cabecera
	$w=array(20,111,10,10,15,25);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],0,0,'C',1);
	$this->Ln(7);

	//Restauraci�n de colores y fuentes
	$this->SetFillColor(255,255,255);
	$this->SetTextColor(0);
	$this->SetFont('');
	//Datos
	$fill=0;
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'',0,'L',$fill);
		$this->Cell($w[1],6,$row[1],'',0,'L',$fill);
		$this->Cell($w[2],6,$row[2],'',0,'C',$fill);
		$this->Cell($w[3],6,$row[3],'',0,'C',$fill);
                $this->Cell($w[4],6,$row[4],'',0,'C',$fill);
		$this->Cell($w[5],6,$row[5],'',0,'L',$fill);
                $this->Ln(5);
		$fill=!$fill;
        
	}
    $this->Ln(7);
    $this->SetFont('Arial','B','8');
    $this->Cell(45);
	$this->Cell('','','FIN CERTIFICADO DE NOTAS');
    $this->Ln(5);
    $this->Cell(45);
    $this->Cell('','','Las notas van de 1.5 a 5.0');
    $this->Ln(5);
    $this->Cell(45);
    $this->Cell('','','Nota Minima Aprobatoria: 3.0');

    $this->Footer($firma);

}

function Footer($firma='')
{       
        $this->SetXY(140, 275);

        $this->SetFont('Arial','I',10);
        $this->SetTextColor(0);
        $this->Cell('' ,'' ,$firma,0,1,'C');
        	
    }
}

?>
