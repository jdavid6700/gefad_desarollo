<?php 
class getmicrotime { 
    var $iniciar;    
    function micro(){ 
      $micro_time = microtime(); 
      $micro_time = explode(" ",$micro_time); 
      $micro_time = $micro_time[1] + $micro_time[0]; 
    return $micro_time; 
    } 
    function getmicrotime(){ 
        $this->iniciar = $this->micro(); 
    return true; 
    } 
    function vertiempo(){ 
        $total_time = ($this->micro() - $this->iniciar); 
        $total_time = '<font color="#FF0000" face="Tahoma" size="1">Página generada en '.substr($total_time,0,4).' Segundos.</font>'; 
    return $total_time; 
    } 
}
/* 
* CONSTRUCTOR DE LA CLASE
* require_once('class.microtime.php');
* $tiempo = new getmicrotime;
* echo $tiempo->vertiempo();
*/ 
?> 