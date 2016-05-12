<?

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/pdf/fpdf.php");


class tablas extends FPDF
{
//Cargar los datos
function LoadData($file)
{
	//Leer las l�neas del fichero
	$lines=file($file);
	$data=array();
	foreach($lines as $line)
		$data[]=explode(';',chop($line));
	return $data;
}

//Tabla simple
function BasicTable($header,$data)
{
	//Cabecera
	foreach($header as $col)
		$this->Cell(40,7,$col,1);
	$this->Ln();
	//Datos
	foreach($data as $row)
	{
		foreach($row as $col)
			$this->Cell(40,6,$col,1);
		$this->Ln();
	}
}

//Una tabla m�s completa
function ImprovedTable($header,$data)
{
	//Anchuras de las columnas
    $w=array(30,100,10,10,10,25);
    //Cabeceras
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],0,0,'C');
	$this->Ln();
	//Datos
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'',0,'C');
		$this->Cell($w[1],6,$row[1],'',0,'C');
		$this->Cell($w[2],6,number_format($row[2]),'',0,'C');
		$this->Cell($w[3],6,number_format($row[3]),'',0,'C');
        $this->Cell($w[4],6,$row[4],'',0,'C');
		$this->Cell($w[5],6,$row[5],'',0,'R');
        $this->Ln();
	}
	//L�nea de cierre
	$this->Cell(array_sum($w),0,'','');
}

//Tabla coloreada
function FancyTable($header,$data,$datos)
{

	//Colores, ancho de l�nea y fuente en negrita
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('Arial','','10');


    //Datos estudiante
    $h=array(100,60,40);
    $this->Ln(5);
    
	//Restauraci�n de colores y fuentes
	$this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');

    
	foreach($datos as $row)
	{
		$this->Cell($h[0],6,$row[0],'',0,'L',$fill);
		$this->Cell($h[1],6,$row[1],'',0,'L',$fill);
		$this->Cell($h[2],6,$row[2],'',0,'L',$fill);
        $this->Ln();
    }
	//Cabecera
	$w=array(20,100,10,10,10,25);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],0,0,'C',1);
	$this->Ln(11);

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
        $this->Ln();
		$fill=!$fill;
        
        
	}
    $this->Ln(7);
    $this->SetFont('Arial','B','12');
    $this->Cell(70);
	$this->Cell('','','FIN CERTIFICADO DE NOTAS');

}

function Footer($firma='')
{
    //Times italic 8
    $this->SetFont('Times','I',11);
    //Posición: a 1,5 cm del final
    $this->SetY(275);
    //Se mueve la linea de la firma
    $this->Cell(120);
    //Firma
    $this->Cell('','',$firma);

}

function Header()
{
    /*
    $this->Image($configuracion["raiz_documento"].$configuracion["grafico"].'/pequeno_universidad.png',10,8,33);
    //Arial bold 15
    $this->SetFont('Arial','B',15);
    //Movernos a la derecha
    $this->Cell(80);
    //Título
    $this->Cell(30,10,'UNIVERSIDAD FRANCISCO JOSE DE CALDAS',1,0,'C');
    $this->Cell(30,10,'SECRETARIA ACADEMICA',1,0,'C');
    //Salto de línea
    $this->Ln(20);
    */

}

function firmasecretario($firma)
{

    $this->SetY(-150);
    $this->Cell(120);
    $this->Cell('','',$firma);

}

}

?>
