<?php
require_once("lib-php/ansi_lib.php");

$input=file_get_contents('CP437.ANS');

$option=['HTML_show_sauce'=>false];
echo ansi_TO_HTML($input,$option);

?>
