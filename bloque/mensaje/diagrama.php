<?   include_once("sql.class.php");
// crea objeto y se conecta a la base de datos
$this->sql=new sql_mensaje();
$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();



$this->dicc_sql=$this->sql->cadena_sql($configuracion,"buscar_id",$_REQUEST['id_diccionario']);
//echo "<br>".$this->dicc_sql;

$this->dicc=$acceso_db->ejecutarAcceso($this->dicc_sql,"busqueda");
//echo "id_dic ".$_REQUEST['id_diccionario'];
?>
<table width="100%" align="center" border="0" cellpadding="5"
	cellspacing="0">
	<tbody>
		<tr>
			<td>
				<table width="100%" border="0" align="center" cellpadding="5 px"
					cellspacing="1px">
					<tr class="texto_subtitulo">
						<td><?
						echo "Diccionario de Datos <b>".$this->dicc[0][2]."</B>";
						?>
							<hr class="hr_subtitulo">
						</td>
					</tr>
				</table>
	
	</tbody>
</table>

<div id="contenedor">
	<?
	$var[0]=$_REQUEST['id_diccionario'];
	$var[1]=1;
	$this->tabla_sql=$this->sql->cadena_sql($configuracion,"buscar_obj",$var);
	// echo "<br>".$this->tabla_sql;
	$this->tabla=$acceso_db->ejecutarAcceso($this->tabla_sql,"busqueda");
	$tablas=count($this->tabla);
	// echo $tablas;
	$registro=array();
	for($aux=0;$aux<$tablas;$aux++)
		 	{?>
	<div class="tabla">
		<div class="titulo">
			<p>
				<? echo $this->tabla[$aux][1];?>
			</p>
		</div>
		<div class="desc">
			<? echo $this->tabla[$aux][2];?>
		</div>
		<div class="desc">
			<br>Campos:
			<hr class="hr_subtitulo">
		</div>

		<?

		$this->obj_sql=$this->sql->cadena_sql($configuracion,"select_obj",$this->tabla[$aux][0]);
		// echo "<br>".$this->obj_sql;
		$this->campo=$acceso_db->ejecutarAcceso($this->obj_sql,"busqueda");
		$campos=count($this->campo);
		for($cont=0;$cont<$campos;$cont++)
		{?>
		<div class="campo">
			<?
			echo $this->campo[$cont][1]." : ".$this->campo[$cont][2];
			jerarquia($configuracion,$registro,$this->campo[$cont][0],1);
			?>

		</div>
		<?
		}

		?>
	</div>
	<?
		 	}
		 	?>
</div>

<?	

//funcion que busca la jerraquia de los objetos padre
function jerarquia($configuracion,$registro,$id_obj,$cont)
{
	include_once("sql.class.php");
	$sql=new sql_mensaje();
	$acceso_db=new dbms($configuracion);
	$cadena_sql=$sql->cadena_sql($configuracion,"select_obj",$id_obj);
	//echo "<br>".$cadena_sql;
	$obj=$acceso_db->ejecutarAcceso($cadena_sql,"busqueda");
	//si encuentra que tiene un objeto padre lo va asignando a la avariable registro

	if($obj==true)
	{
		$registro[$cont]['id']=$obj[0][0];
		$registro[$cont]['nombre']=$obj[0][1];
		$registro[$cont]['tipo_dato']=$obj[0][2];
		$registro[$cont]['nivel']=$obj[0][3];
		$cont=$cont+1;
		jerarquia($configuracion,$registro,$obj[0][0],$cont);
	}
	else	{
		//identifica el numero de campos e imprime los botones de la jerarquia de objetos
		$totalRegistro=count($registro);

		for($boton=$totalRegistro;$boton>0;$boton--)
		{ ?>
<div class="campo">
	<?
	for($aux=1;$aux<=$registro[$boton]['nivel'];$aux++)
	{
		echo ".";
	}
	echo $registro[$boton]['nombre']." : ".$registro[$boton]['tipo_dato'];
	?>
</div>
<?
	
		}

		}
		unset($registro);

}
?>

