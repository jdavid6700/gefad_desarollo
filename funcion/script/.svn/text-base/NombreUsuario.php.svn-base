<?PHP
if($nivel == 51){
   $QryNombre = "SELECT fua_invierte_nombre(est_nombre), NVL(eot_email, 'Actualize sus datos, agregue un email.'),est_cra_cod
   		FROM acest,acestotr
		WHERE est_cod = $usuario
		AND est_cod = eot_cod";
		
	$registro1 =$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryNombre,"busqueda");
	$Nombre =  $registro1[0][0];
	$Email  = $registro1[0][1];
	$_SESSION['carrera'] = $RowNombre[0][2];
}
elseif($nivel == 4 || $nivel == 16 || $nivel == 30){
       $QryNombre ="SELECT TRIM(doc_nombre||'  '||doc_apellido),doc_email
		FROM acdocente
		WHERE doc_nro_iden = $usuario
		AND doc_estado = 'A'";
		
		$registro2 =$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryNombre,"busqueda");
		$Nombre = $registro2[0][0];
		$Email  = $registro2[0][1];
}
elseif($nivel == 24 || $nivel == 32 || $nivel == 34){
	   $QryNombre = "SELECT fua_invierte_nombre(emp_nombre),emp_nro_iden, emp_cod
			FROM mntpe.peemp
			WHERE emp_nro_iden = $usuario
			AND emp_estado_e != 'R'";
											   
	   $QryCargo = "SELECT emp_car_cod,car_nombre
			FROM mntpe.pecargo,mntpe.peemp
			WHERE car_cod = emp_car_cod
			AND emp_nro_iden = $usuario
			AND emp_estado_e != 'R'
			AND car_estado = 'A'";
		
		$registro3=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryNombre,"busqueda");
		$Nombre = $registro3[0][0];
		$_SESSION["fun_cod"] = $registro3[0][2];

		$registro4=$conexion->ejecutarSQL($configuracion,$accesoOracle,$QryCargo,"busqueda");
				
		if(($registro4[0][0] == 186) || ($registro4[0][0] == 200))
		   $Cargo = $registro4[0][1];
		else
			$Cargo = 'FUNCIONARIO';
		}
?>