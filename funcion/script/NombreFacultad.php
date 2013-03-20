<?PHP
$QryFac = "SELECT unique(cra_dep_cod), dep_nombre
	FROM accra, gedep
	WHERE dep_cod = cra_dep_cod
	AND cra_dep_cod = $depcod";
?>