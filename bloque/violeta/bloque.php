<?php

if (!isset($_POST['action'])) {
    //echo 'HTML';
    include_once($configuracion["raiz_documento"] . $configuracion["bloques"] . "/login/html.php");
} else {
    //echo 'ACTION';
    include_once($configuracion["raiz_documento"] . $configuracion["bloques"] . "/login/action.php");
}
?>