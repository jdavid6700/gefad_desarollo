<?PHP
function genera_clave($num){ 
    $voc = array ("a","e","i","o","u"); 
    $con = array ("b","c","d","f","g","h","j","k","l","m","n","p","q","r","s","t","v","w","x","y","z");
	$esp = array ("@","/","_","%");
	$nro = array ("0","1","2","3","4","5","6","7","8","9");
    $clave = "";
    $vc  = mt_rand(0,1);
    for($n=0; $n<$num; $n++){ 
        if($vc==1){
           $vc=0; 
           $clave .= $con[mt_rand(0,count($con)-1)]; 
        } 
        $clave .= $voc[mt_rand(0,count($voc)-1)];
		//$clave .= $esp[mt_rand(0,count($esp)-2)]; 
        $clave .= $con[mt_rand(0,count($con)-1)];
		//$clave .= $nro[mt_rand(0,count($nro)-2)]; 
    }
    $clave = ereg_replace ("q","qu",$clave); 
    $clave = ereg_replace ("quu","que",$clave); 
    $clave = ereg_replace ("yi","ya",$clave); 
    $clave = ereg_replace ("iy","ay",$clave); 
    $clave = substr($clave,0,$num); 
    return $clave; 
}
?>