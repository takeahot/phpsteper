<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>xx</title>
</head>
<body>
<?php 
	ini_set('display_errors',true);
    error_reporting(E_ALL);
	define ('ROOT',dirname(__FILE__)); 

    function make_pattern_count_parentheses ($count = 100,$parentheses = "(") {
    	switch ($parentheses) {

    		case '(':
    			$o = "(";
    			$c = ")";
    			break;

    		case "[":
    			$o = "[";
    			$c = "]";	
    			break;

    		case "{":
    			$o = "{";
    			$c = "}";
    			break;

    		default:
    			echo "net takih skobok";
    			break;
    	}
    	/**
    	* for explantation suposes {} parentheses
    	* when
    	* $startpart = "([^;{}]*)({)([^{}]*";
    	*/
    	if ($o === "{") {
        	$startpat = "(\\".$o.")([^\\".$o."\\".$c."]*";
      	} 
      	else {
      		$startpat = "([^\;\\".$o."\\".$c."]*)(\\".$o.")([^\\".$o."\\".$c."]*";
      	}
    	// $endpat = ")*(})(;?)";
    	$endpat = ")*(\\".$c.")(\;?)";
		/**
		* $startpat .= "(({)([^{}]*";
		* $endpat = ")(}))*([^{}]*)".$endpat; 
		*/
    	for ($i = 0; $i < $count; $i++) {
    		$startpat .="((\\".$o.")([^\\".$o."\\".$c."]*";
    		$endpat = ")(\\".$c."))*([^\\".$o."\\".$c."]*)".$endpat;
    	}
    	return $startpat.$endpat;
    }

  	function make_pattern () {

   		$pattern = "/";	
   		$pattern .= "(";
   		$pattern .= make_pattern_count_parentheses (1,"(");
   		$pattern .= "(";
   		$pattern .= make_pattern_count_parentheses (1,"{");
   		$pattern .= ")*";
		$pattern .= ")|[^\(\)]*\;";
   		$pattern .= "/";
   		return $pattern;
   	}

   	function show_tag ($string) {

			$string = htmlentities($string);
			$string = nl2br ($string);
			return $string;
   	}

		$file = fopen(ROOT."/"."xxx.php","r");
		echo ROOT."/"."xxx.php"; 
		while (!feof($file)){
			$string = fread($file,8000);
			$string = show_tag ($string);
    		$pat = make_pattern();
    		echo $pat;
			preg_match($pat,$string,$match);
			var_dump($match);
			echo "result <br> ======================== <br> <br> <br>";
			echo $string;
    }
		fclose($file);
 ?>	
</body>
</html>
