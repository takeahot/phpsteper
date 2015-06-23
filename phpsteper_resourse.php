<?php
define("DDDD,lkj",11);
$sick = "jl";
include( $sick );



?>
<?php
    //function parse_dirname ($inside)
    function parse_constant ($string,$path_current_file) {
    	preg_match_all('/\_*[A-Z0-9]+\_*/',$string,$match);
    	foreach ($match as $val) {
    		if (preg_match('/^\_{2}[A-Z0-9]+\_{2}/',$val[0]),$sysconst) {
    			if ($val[0] === '__FILE__') {
					$string = preg_replace('/__FILE__/', rtrim($path_current_file,'/'), $string);
					echo "<br>constant".$string;
					return $string;
    			}
    			else {
    				echo '---------------------------------------magic constant '.$sysconst[0].'----------------------------------------';
    			}
    		}
    		else 
    		{

    			echo 'constanta';
    		}
    	}
    }

    function parse_dirname ($string) {
			echo "<br>dirname".$string;
        //while we have "dirname()" in $string, $match assign dirname();
		while (preg_match('/dirname\([^()]*\)/',$string,$match)) {
            //$match assign parentheses's contain
			preg_match('/\([^()]*\)/',$match[0],$match);
			$m = $match[0];
			echo "<br>m".$m;
            //delete ()
			$m = trim($m,"() ");
			echo "<br>m".$m;
            //$match assign all befor last /
			preg_match('/([^\/]*\/)*/',$m,$match);
			$m = $match[0];
            //delet last /
			$m = trim($m,"/ ");
			$string = preg_replace('/dirname\([^()]*\)\s*\.*\s*\/*\s*/',$m,$string);
			$string = trim($string);
			echo "<br>dirname".$string;
		}
		return $string;
    }


   	function read_file ($file_path) {

   	} 
?>




