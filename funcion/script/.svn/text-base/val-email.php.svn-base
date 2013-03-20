<?PHP
$mail_correcto = 0;
if((strlen($_POST["mail"]) >= 6) && (substr_count($_POST["mail"],"@") == 1) && (substr($_POST["mail"],0,1) != "@") && (substr($_POST["mail"],strlen($_POST["mail"])-1,1) != "@")) { 
   if((!strstr($_POST["mail"],"'")) && (!strstr($_POST["mail"],"\"")) && (!strstr($_POST["mail"],"\\")) && (!strstr($_POST["mail"],"\$")) && (!strstr($_POST["mail"]," "))) { 
      if(substr_count($_POST["mail"],".")>= 1){ 
         $term_dom = substr(strrchr ($_POST["mail"], '.'),1); 
         if(strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){ 
            $antes_dom = substr($_POST["mail"],0,strlen($_POST["mail"]) - strlen($term_dom) - 1); 
            $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 
            if($caracter_ult != "@" && $caracter_ult != "."){ 
               $mail_correcto = 1; 
            } 
         } 
      } 
   } 
}
?>