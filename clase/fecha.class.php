<?php
/*
 ############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por: Teleinformatics Technology Group                      #
#    Paulo Cesar Coronado 2004 - 2006                                      #
#    paulo_cesar@ttg.com                                                   #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
?>
<?
/****************************************************************************************************************
 * @name          fecha.class.php
* @author        Luis Fernando Torres R.
* @revision      Última revisión 2 de febrero de 2009
*******************************************************************************************************************
* @subpackage
* @package	clase
* @copyright
* @version      0.2
* @author      	Luis Fernando Torres R.
Jairo Lavado
* @link		http://acreditacion.udistrital.edu.co
* @description  clase para seleccionar la fecha.
*
*****************************************************************************************************************/
?>
<?

class fecha{

	var $fecha;
	var $fechadia;
	var $fechames;
	var $fechaanno;
	var $fechahora;
	var $fechaminutos;
	var $ampm=array(1=> 'AM', 'PM');
	var $meses=array(1=> 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	var $dias=array(1=> '31', '28', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31');


	function dia($tab,$dia)
	{
		$this->fecha=time();
		$this->fechadia=date('d',$this->fecha);
		$mes=$this->mes=date('m',$this->fecha);
		$anno=date('Y',$this->fecha);
		if($mes<10)
		{
			$mes=$mes[1];
		}
		$max=$this->dias[$mes];
		if ($mes==2 && $anno % 4 == 0)
		{
			$max=$max+1;
		}

		echo "<select name='dia' maxlength='2' tabindex='<? echo $tab++ ?>' >";
			
		for ($n=1;$n<=$max;$n++) {
			echo "<option value=$n";
			if ($dia!=0)
			{
				if ($dia==$n){
					echo " selected";
				}
			}
			else	{
				if ($this->fechadia==$n)
				{
					echo " selected";
				}
			}
			echo ">".$n;
			echo '</option>';
		}
			
		echo "</select>";
	}


	function mes($tab,$mes)
	{
		$this->fecha=time();
		$this->fechames=date('m',$this->fecha);
		echo "<select name='mes' maxlength='2' tabindex='<? echo $tab++ ?>' >";
			
		for ($n=1;$n<=12;$n++) {
			echo "<option value=$n";
			if ($mes!=0)
			{
				if ($mes==$n){
					echo " selected";
				}
			}
			else	{
				if ($this->fechames==$n)
				{
					echo " selected";
				}
			}
			echo "> {$this->meses[$n]}";
			echo '</option>';
		}
			
		echo "</select>";
	}
		
		
	function anno($tab,$inicio,$fin,$anno)
	{

		$this->fecha=time();
		$this->fechaanno=date('Y',$this->fecha);
		echo "<select name='anno' maxlength='2' tabindex='<? echo $tab++ ?>' >";
			
		for ($n=$inicio;$n<=($this->fechaanno+5);$n++) {
			echo "<option value=$n";
			if ($anno!=0)
			{
				if ($anno==$n){
					echo " selected";
				}
			}
			else	{
				if ($this->fechaanno==$n)
				{
					echo " selected";
				}
			}
			echo "> $n";
			echo '</option>';
		}
			
		echo "</select>";
	}
		
	function fecha_completa($tab,$inicio,$final,$registro){

		if($registro)
		{
			$this->anno($tab,$inicio,$final,date('Y',$registro));
			$this->mes($tab,date('m',$registro));
			$this->dia($tab,date('d',$registro));
		}
		else	{$this->anno($tab,$inicio,$final,0);
		$this->mes($tab,0);
		$this->dia($tab,0);
		}
	}

	/* funciones para manejar la hora de la fecha
	 function hora($tab)
		{
	$this->fecha=time();
	$this->fechahora=date('h',$this->fecha);
	echo "<select name='horas' maxlength='2' tabindex='<? echo $tab++ ?>' >";
		
	for ($n=0;$n<=23;$n++) {
				echo "<option value=$n";
	if ($this->fechahora==$n){
				echo ' selected';
	}
	echo "> $n";
	echo '</option>';
	}
		
	echo "</select>";
	}


	function minutos($tab)
	{
	$this->fecha=time();
	$this->fechaminutos=date('i',$this->fecha);
	echo "<select name='minutos' maxlength='2' tabindex='<? echo $tab++ ?>' >";
		
	for ($n=0;$n<=59;$n++) {
				echo "<option value=$n";
	if ($this->fechaminutos==$n){
				echo ' selected';
	}
	echo "> $n";
	echo '</option>';
	}
		
	echo "</select>";
	}

	function ampm($tab)
	{
	$this->fecha=time();
	$this->fechaampm=date('A',$this->fecha);
	echo "<select name='ampm' maxlength='2' tabindex='<? echo $tab++ ?>' >";
		
	for ($n=1;$n<=2;$n++) {
				echo "<option value={$this->ampm[$n]}";
	if ($this->fechaampm==$this->ampm[$n]){
				echo ' selected';
	}
	echo "> {$this->ampm[$n]}";
	echo '</option>';
	}
		
	echo "</select>";
	}
	*/

}
?>
