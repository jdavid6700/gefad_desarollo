<?php
$cod_consul = "SELECT NPF_CRA_COD,
  	          	   	  to_number(TO_CHAR(NPF_IPAR1, 'yyyymmdd')),
					  to_number(TO_CHAR(NPF_FPAR1, 'yyyymmdd')),
  	          	   	  to_number(TO_CHAR(NPF_IPAR2, 'yyyymmdd')), 
					  to_number(TO_CHAR(NPF_FPAR2, 'yyyymmdd')),
  	          	   	  to_number(TO_CHAR(NPF_IPAR3, 'yyyymmdd')), 
					  to_number(TO_CHAR(NPF_FPAR3, 'yyyymmdd')),
  	           	   	  to_number(TO_CHAR(NPF_IPAR4, 'yyyymmdd')), 
					  to_number(TO_CHAR(NPF_FPAR4, 'yyyymmdd')),
  	          	   	  to_number(TO_CHAR(NPF_IPAR5, 'yyyymmdd')), 
					  to_number(TO_CHAR(NPF_FPAR5, 'yyyymmdd')),
  	          	   	  to_number(TO_CHAR(NPF_ILAB, 'yyyymmdd')), 
					  to_number(TO_CHAR(NPF_FLAB, 'yyyymmdd')),
  	          	   	  to_number(TO_CHAR(NPF_IEXA, 'yyyymmdd')),  
					  to_number(TO_CHAR(NPF_FEXA, 'yyyymmdd')),
  	          	   	  to_number(TO_CHAR(NPF_IHAB, 'yyyymmdd')),
					  to_number(TO_CHAR(NPF_FHAB, 'yyyymmdd'))
    	   	  	 FROM acnotparfec
        	 	WHERE NPF_CRA_COD =".$_GET['cra']."
          	      AND NPF_ESTADO = 'A'";
?>