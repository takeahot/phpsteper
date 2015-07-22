<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>functionwaywiever</title>
</head>
<body>
<?php
function find_all_files_php () {

	function catch_dir_from_list (&$list) {
		// var_dump($list);
		$arr_dir = [];
		foreach ($list as $k => $v) {
			if (is_dir(ROOT."/".$v)) {
				unset($list[$k]);
				if ($v == "." || $v == ".." || substr($v,-2) == "/." || substr($v,-3) == "/..") {
					continue;
				}
				array_push($arr_dir,$v);
			}
		}
		return $arr_dir;
	}

	function parse_dir ($arr_files_and_dir) {
		$arr_dir = catch_dir_from_list($arr_files_and_dir);
		if ($arr_dir) {
			// var_dump($arr_dir);
			foreach ($arr_dir as $k => $v) {
				$arr_files_from_dir = scandir(ROOT."/".$v);
				// echo "$k from arr_dir =".$v."<br />";
				foreach ($arr_files_from_dir as $k1 => &$v1) {
					$v1 = $v."/".$v1;
				}
				// var_dump($arr_files_from_dir);
				$twoarg = parse_dir ($arr_files_from_dir);
				// echo "111";
				// var_dump($twoarg);
				$arr_files_and_dir = array_merge($arr_files_and_dir,$twoarg);
			}
		}
			// echo "return-----------";
			// var_dump($arr_files_and_dir);
			return $arr_files_and_dir;
	}

	define ("ROOT",dirname(__FILE__));
	$arr_files_and_dir_root = scandir(ROOT);
	$arr_files_and_dir_root = parse_dir ($arr_files_and_dir_root);
	$all_files_php = [];
	foreach ($arr_files_and_dir_root as $k => $v) {
		if (pathinfo($v,4) == "php") {
			if (basename($v) == basename(__FILE__) || $v == "lib.php") {
				continue;
			}
			array_push($all_files_php,$v);
		}
	}

	return $all_files_php;
}
    
	$collection_non_php_parts = [];
    function collect_non_php_parts ($match = null) {

    	global $collection_non_php_parts;
    	if ($match !== null) {
    		array_push($collection_non_php_parts,$match[0]);
    		return count($collection_non_php_parts);
    	}
    	else {
    		$collection_non_php_parts = [];
    		return "0";
    	}
    }

    function replace_non_php_parts ($match = null) {
    	$count = collect_non_php_parts($match);
    	$count = str_pad($count,6,'0',STR_PAD_LEFT);	
    	return "--__".$count."__--";
    }

    function change_non_php($string,$htmlswitch) {

    	global $collection_non_php_parts;
    	if (!$htmlswitch) {
	    	replace_non_php_parts ();
    		$string = preg_replace_callback ("#(^[\s\s\r]*?\<\?php)|(\?\>[\s\s\r]*?\<\?php)|(\?\>[\s\s\r]*?$)#","replace_non_php_parts",$string);	
	    	//echo $string;
    	}
    	else {
    		$string = preg_replace_callback ("#\-\-\_\_\d{6}\_\_\-\-#", function ($match) { 
    			global $collection_non_php_parts; 
    			$index = trim($match[0],"-_"); 
    			return $collection_non_php_parts[ltrim($index,"0")-1]; 
    		},$string);
    	}
    	return $string;
    }

	$collection_any_comments = [];

    function collect_any_comments ($match = null) {

    	global $collection_any_comments;
    	if ($match !== null) {
    		array_push($collection_any_comments,$match[0]);
    		return count($collection_any_comments);
    	}
    	else {
    		$collection_any_comments = [];
    		return "0";
    	}
    }

    function replace_any_comments ($match = null) {
    	$count = collect_any_comments($match);
    	$count = str_pad($count,6,'0',STR_PAD_LEFT);	
    	return " --==".$count."==-- ";
    }

	function hide_any_comments ($string,$comments_switch) {
	    	global $collection_any_comments;
    	if (!$comments_switch) {
	    	replace_any_comments ();
    		$string = preg_replace_callback ("#(\/\*[\s\s\r]*?\*\/)|(\/\/[\s\s]*?\r)#","replace_any_comments",$string);	
	    	//echo $string;
    	}
    	else {
		$string = preg_replace_callback ("#\-\-\=\=\d{6}\=\=\-\-#", function ($match) { 
    			global $collection_any_comments; 
    			$index = trim($match[0],"=-"); 
    			return $collection_any_comments[ltrim($index,"0")-1]; 
    		},$string);
    	}
    	return $string;
		
	}

   	function make_pattern () {

   		$deep = 100;
   		$pattern = "/";	
   		$pattern .= "function\s+?\S*?\s*?";
   		$pattern .= make_pattern_count_parentheses ($deep,"(");
   		$pattern .= "\s*?";
   		$pattern .= make_pattern_count_parentheses ($deep,"{");
   		$pattern .= "/";
   		return $pattern;
   	}

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
    	//don't have anything befor figure parentheses
    	$startpat = "((\\".$o.")";
    	// $endpat = ")*(})(;?)";
    	$endpat = "([^\\".$o."\\".$c."]*)(\\".$c."))";
		/**
		* $startpat .= "(({)([^{}]*";
		* $endpat = ")(}))*([^{}]*)".$endpat; 
		*/	
    	for ($i = 0; $i < $count; $i++) {
    		$startpat .="(([^\\".$o."\\".$c."]*)(\\".$o.")";
    		$endpat = "([^\\".$o."\\".$c."]*)(\\".$c."))*".$endpat;
    	}
    	return $startpat.$endpat;
    }

   	function parse_func ($string) {

   		$array= [];
   		
/*   		if ($key !== "") {
	   		$array['key'] = $key;
   		}*/

   		preg_match('#^[^\(]*#',$string,$array['key']);

   		preg_match('/'.make_pattern_count_parentheses().'/',$string,$argstr);
   		if (isset($argstr[0])) {
	   		$argstr = trim($argstr[0],"; ");
	   		$argstr = preg_replace("#^\(|\)$#","",$argstr);
	   		$argarr = explode(",",$argstr);

	   		/*

	   		$teststring = $argarr[0];
	   		$teststring1 = '\"dddd';//"aaaaaa\"bbbbbbbb'cccccccccccc\"dddddddddd'eeeeeeeee\"ffffffff";
	   		echo "<br> ord(teststring) -".ord($teststring)." <br>";
	   		echo "<br> ord(teststring1) -".ord($teststring1)." <br>";
	   		echo "<br> teststring - $teststring <br>";
	   		$testcount = 0;
	   		echo "<br> testcount - $testcount <br>";
	   		$testcount += preg_match_all("/\&quot;/",$teststring);
	   		echo "<br> testcount - $testcount <br>";
	   		$teststring = preg_replace("/\&quot;/",'q',$teststring);
	   		echo "<br> teststring - $teststring <br>";
			*/

	   		$countq = 0;
	   		$count2q = 0;
	   		foreach ($argarr as &$val) {
//	   			echo "<br> arguments <br> val = $val";
	   			$pat2q = "/\&quot;/";
	   			$countq += preg_match_all("/'/",$val); 
	   			$count2q += preg_match_all($pat2q,$val);
//	   			echo "<br> countq = $countq <br> count2q = $count2q <br>";
	   			if (($countq & 1)||($count2q & 1)) {
	   				$current = current($argarr);	
		   			$countq += preg_match_all("/'/",$current); 
		   			$count2q += preg_match_all($pat2q,$current);   				
	   				$val .= ",".$current;	
	   				unset($argarr[key($argarr)]);
	   			}
	   		}
	   		$array['arguments'] = array_values($argarr);
   		}

	   		preg_match('/'.make_pattern_count_parentheses(100,"{").'/',$string,$match);
   		if (isset($match[0])) {
	   		$array['body'] = $match[0];
   		}

   		return $array;
   	}

	
   	function handler_expression ($match) {
   		// echo htmlentities($match[0]);
   		global $file_name;
   		$arrfunc = parse_func ($match[0]);
   		$change_part = preg_replace("/^\{|\}$/","",$arrfunc['body']);
   		if ($file_name == "wp-includes/media-template.php" || $file_name == "wp-includes/query.php" || $file_name == "wp-includes/ID3/module.audio-video.asf.php" || $file_name == "wp-includes/ID3/module.audio-video.quicktime.php" || $file_name == "wp-includes/ID3/module.audio-video.riff.php" || $file_name == "wp-includes/ID3/module.audio.mp3.php" || $file_name == "wp-includes/ID3/module.tag.id3v2.php" || $file_name == "wp-includes/SimplePie/Item.php") {
   			echo nl2br(htmlentities($match[0]));
   			echo "---------------------------------------------------";
   			echo $file_name;
   			echo "---------------------------------------------------";
		}
		else {

   		$match[0] = preg_replace("/".preg_quote($change_part,"/")."/","function_write(__function__,__file__,1);\r\n$0\r\nfunction_write(__function__,__file__)",$match[0]);
		}


		return  $match[0];
   	}


function function_includer ($file) {
   	
	
 	file_put_contents(ROOT."/output", "",LOCK_EX);
	$startstring = "<?php include_once(\"".ROOT."/lib.php\") ?>";
	$string = file_get_contents(ROOT."/".$file);	
//	echo "1.".substr($string,0,strlen($startstring)) ;
//	echo "2.".$startstring;
	if (substr($string,0,strlen($startstring)) !== $startstring) {
//		$string = $startstring.$string;	
		$string = change_non_php($string,0);
		$string = hide_any_comments($string,0);
	  	$pattern = make_pattern();
	  	// echo $pattern;
		$string = preg_replace_callback($pattern,"handler_expression", $string);
		$string = change_non_php($string,1);
		$string = hide_any_comments($string,1);
	}
//	file_put_contents(ROOT."/".$file,$string, LOCK_EX);

}

ini_set('display_errors',true);
error_reporting(E_ALL);
$file_names = find_all_files_php();
$file_name = "";
foreach ($file_names as $v) {
	echo $v."<br />";
	$file_name = $v;
	function_includer($v);
}

?>
</body>
</html>