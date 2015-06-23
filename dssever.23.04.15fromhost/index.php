<?php 
//print var_dump($_SERVER);

ini_set('display_errors',true);
error_reporting(E_ALL);

define('ROOT', dirname(__FILE__).'/');
define ('RURI',$_SERVER['REQUEST_URI']);
// ===================== attention for lib.php ROOT is /dssever directory
$query = RURI;
require ROOT."php/lib.php";
require ROOT.'php/header.php';
check_url ();
//print "<br> index.php query".$query;
if (file_exists(ROOT.'php/'.$query.'upcontent.php')) {
	require ROOT.'php/'.$query.'upcontent.php';
}

if (!$query) {
	print "<div class=\"content container main\">";
};

require ROOT.'php/'.'create-content.php';

if (!$query) {
	print "</div>";
}

if (file_exists(ROOT.'php/'.$query.'belowcontent.php')) {
	require ROOT.'php/'.$query.'belowcontent.php';
}
require ROOT.'php/footer.php';
//print ROOT."<br>".$query;
?>	
