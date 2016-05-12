<?PHP
include_once("../clase/multiConexion.class.php");

class Nombres extends multiConexion{


	function rescataNombre($codigo,$opcion){

		include_once("../clase/multiConexion.class.php");
	
		$esta_configuracion=new config();
		$configuracion=$esta_configuracion->variable("../"); 


		$conexion=new multiConexion();
		$accesoOracle=$conexion->estableceConexion($_SESSION['usuario_nivel']);

		//echo "id".$identificador;

		if(isset($codigo)){

			switch($opcion){
				case "NombreTipoIns": // 17 TIPO DE INSCRIPCION
					$cadena_sql="SELECT ti_nombre FROM actipins WHERE ti_cod = $codigo AND ti_estado = 'A' ORDER BY ti_nombre";
				break;

				case "NombreEstrato":// 16 NOMBRE DEL ESTRATO
					$cadena_sql="SELECT str_nombre FROM acestrato,acasperiadm WHERE ape_ano = str_ape_ano
							AND ape_per = str_ape_per
							AND ape_estado = 'X'
							AND str_estado = 'A'
							AND str_nro = $codigo";
				break;
				case "NombreLocalidad": // 15 NOMBRE DEL LOCALIDAD
					$cadena_sql="SELECT loc_nombre
							FROM aclocalidad,acasperiadm
							WHERE ape_ano = loc_ape_ano
							AND ape_per = loc_ape_per
							AND ape_estado = 'X'
							AND loc_estado = 'A'
							AND loc_nro = $codigo";
				break;
				case "NombreMunicipio": // 14 NOMBRE DEL MUNICIPIO
					$cadena_sql="SELECT mun_nombre FROM mntge.gemunicipio WHERE mun_cod = $codigo AND mun_estado = 'A'";
				break;
				case "NombreDepartamento": // 13 NOMBRE DEL DEPARTAMENTO
					$cadena_sql="SELECT dep_nombre FROM mntge.gedepartamento WHERE dep_cod = $codigo AND dep_estado = 'A'";
				break;
				case "PuntosMin": // 12 PUNTAJE MINIMO DE INSCRIPCION POR CARRERA
					$cadena_sql="SELECT ATM_PTOS 
											   FROM ACASPTOSMIN,ACASPERIADM
											  WHERE APE_ANO = ATM_APE_ANO
											    AND APE_PER = ATM_APE_PER
											    AND ATM_CRA_COD = $codigo
											    AND APE_ESTADO = 'A'
											    AND ATM_TIP_ICFES = 'N'
											    AND ATM_ESTADO = 'A'";
				break;
				case "MediPublicidad": // 11 MEDIOS DE PUBLICIDAD
					$cadena_sql="SELECT med_nombre FROM acmedio WHERE med_cod = '$codigo' AND med_estado = 'A'";
				break;
				case "NombreCiudad": // 10 NOMBRE DEL LUGAR
					$cadena_sql="SELECT LUG_NOMBRE FROM GELUGAR WHERE LUG_COD = '$codigo' AND LUG_ESTADO = 'A'";
				break;
				case "NombreFacultadCraCod": // 9 NOMBRE DE LA FACULTAD A QUE PERTENECE LA CARRERA
					$cadena_sql="SELECT DEP_NOMBRE FROM GEDEP,ACCRA WHERE DEP_COD = CRA_DEP_COD
							AND DEP_ESTADO = 'A'
							AND CRA_ESTADO = 'A'
							AND CRA_COD = '$codigo'";
				break;
				case "NombreEstado": // 8 NOMBRE DEL ESTADO
					$cadena_sql="SELECT ESTADO_NOMBRE FROM ACESTADO WHERE ESTADO_COD = '$codigo'";
				break;
				case "NombreFacultad": // 7  NOMBRE DE LA FACULTAD
					$cadena_sql="SELECT DEP_NOMBRE FROM GEDEP WHERE DEP_COD = $codigo AND DEP_ESTADO = 'A'";
				break;
				case "NombreSede": // 6 NOMBRE DE LA SEDE
					$cadena_sql="SELECT sed_nombre FROM gesede WHERE sed_cod = $codigo AND sed_estado = 'A'";
				break;
				case "NombreCarrera": // 5 NOMBRE DE LA CARRERA
					$cadena_sql="SELECT cra_nombre FROM accra WHERE cra_cod = $codigo AND cra_estado = 'A'";
				break;
				case "NombreAsignatura":  // 4 NOMBRE DE LA ASIGNATURA
					$cadena_sql="SELECT asi_nombre FROM acasi WHERE asi_cod = $codigo AND asi_estado = 'A'";
				break;
				case "NombreEstudiante": // 3 NOMBRE DEL ESTUDIANTE
					$cadena_sql="SELECT est_nombre FROM acest WHERE est_cod = $codigo AND est_estado_est IN('A','B','H','J','L','V')";
				break;
				case "NombreFuncionario": // 2 NOMBRE DEL FUNCIONARIO
					$cadena_sql="SELECT emp_nombre FROM mntpe.peemp WHERE emp_nro_iden = $cedula AND emp_estado_e = 'A'";
				break;
				case "NombreDocente": // 1 NOMBRE DEL DOCENTE
					$cadena_sql="SELECT LTRIM(doc_nombre||'  '||doc_apellido) FROM acdocente WHERE doc_nro_iden = $codigo AND doc_estado = 'A'";
				break;



			}

		$registro=$conexion->ejecutarSQL($configuracion,$accesoOracle,$cadena_sql,"busqueda");
		
		return  $registro[0][0];
			
		}		

	}


}

?>