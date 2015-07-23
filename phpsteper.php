<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style>
		body {
			margin: 0;
		}
		.input {
			position: relative;
			width: 100%;
			box-shadow: 0px 3px 5px #888;
			padding: 1%;
			/*background-color: #eee;*/
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}
		.text {
			position: relative;
			width: 80%;
			height: 25px;
			box-shadow: 0px 11px 0px -10px #888;
			float: left;
			border: none;
			text-align: center;
		}
		.text:focus {
			outline-width: thin;
			outline-color: #eee;
		}
		.but {
			background-color: #aaefe5;
			overflow: hidden;
			text-align: center;
			padding: 0.3em;
			border-radius: 0.6em;
		}
		.but:hover {
			cursor: pointer;	
		}
		.a {
			color: blue;
			font-weight: bold;
		}
	</style>
</head>
<body>
	<script>

		var allData = <?php 

		$postdata = [];
		foreach ($_POST as $index => $value) {
				$postdata[$index] = $_POST[$index];
		}

		echo json_encode($postdata);

		?>

		function getXmlHttpRequest(){
			if (window.XMLHttpRequest){
				try{
					return new XMLHttpRequest();
				} 
				catch (e){}
			} 
			else if (window.ActiveXObject){
				try{
					return new ActiveXObject('Msxml2.XMLHTTP');
				} catch (e){}
				try{
					return new ActiveXObject('Microsoft.XMLHTTP');
				} 
				catch (e){}
			}
			return null;
		}

		function sendPOST (info) {
			var xmlO = getXmlHttpRequest();
			var url = "./phpsteper.php";
			var par = "test=sisi&text=paper&info="+info;
			xmlO.open("POST",url,true);
			xmlO.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			//xmlO.setRequestHeader("Content-length", par.length);
			//xmlO.setRequestHeader("Connection", "close");

			xmlO.onreadystatechange = function() {//Call a function when the state changes.
			    if(xmlO.readyState == 4 && xmlO.status == 200) {
			        console.log(xmlO.responseText);
			    }
			    else {
			    	console.log("xmlO.readyState="+xmlO.readyState);
			    	console.log("xmlO.status="+xmlO.status);
			    }
			}
			xmlO.send(par);
		}

		function sendData (arr) {

			for (var indexAllData in allData) {
				if (indexAllData.substr(0,6) === 'parent') {
					allData['parent'+toString(indexAllData.substr(7)+1)] = allData[indexAllData];
				}
			}

			if (arr['file']) {
				allData['parent0'] = allData['file'];
			}

			for ( var indexArr in arr) {
				allData[indexArr] = arr[indexArr];
			}

			var element = document.createElement('form');
			element.action = "phpsteper.php";
			element.method = "POST";
			for (var i in allData) {
				var input = element.appendChild(document.createElement('input'));
				input.name = i; 
				input.value = allData[i];
			} 

			element.submit();
		}	

		
	</script>
	<div class="input">
	<form id="form" action="phpsteper.php" method="POST">
		<input class="text" type="text" name="file"></input>
		<div class="but" onclick="javascript:this.parentNode.submit();">Show!</div>
	</form>
	</div>
	<p>
	<?php 
	ini_set('display_errors',true);
    error_reporting(E_ALL);
	define ('ROOT',dirname(__FILE__));

    //$type_statements = [];
   	//array_push($type_statements,[['red'],['gray','$catch_const','gray','$fill_const']]);
 
	$number_of_expressions = [0];
	$correspond_table['define'] = ['color: blue'];
	$correspond_table['error_reporting'] = ['color: blue'];
   	$correspond_table['include'] = ['color: red'];
   	$correspond_table['include_once'] = ['color: red'];
   	$correspond_table['require'] = ['color: red'];
   	$correspond_table['require_once'] = ['color: red'];
   	$correspond_table['comment'] = ['color: gray'];
   	$correspond_table['echo'] = ['color: green'];
   	$correspond_table['if'] = ['color: orange'];
   	if (!empty($_POST['file'])){
   		$file_name = $_POST['file'];
	   	$constants['__file____phpsteper'] = ROOT."/".$file_name;
   	}
   	$pattern = make_pattern();
   	$condition = [];

   	function define_phpsteper ($arr,$string) {
   		global $constants;
   		$arr['arguments'][0] = trim ($arr['arguments'][0],'\'\" ');
   		$arr['arguments'][0] = preg_replace("#^&quot\;|&quot\;$#","",$arr['arguments'][0]);
   		$constants[strtolower($arr['arguments'][0])."__phpsteper"] = $arr['arguments'][1];
//   		echo "name constant - ".strtolower($arr['arguments'][0])."__phpsteper"." which value is ".$constants[strtolower($arr['arguments'][0])."__phpsteper"];
   	}


   	function include_phpsteper ($arr,$string) {

   		//echo "<br> arr <br>";
   		//var_dump($arr);
   		//echo "<br> endarr <br>";
   		global $constants;
   		$data = [];

		//first argument from call function include
		$path_to_file = $arr['arguments'][0];	
		//resolve constant
		$path_to_file = change_constants($path_to_file);
		//result dirname
		$path_to_file = dirname_phpsteper($path_to_file);	
		//delete text comments
		$path_to_file = preg_replace('#\/\*(.|\R)*?\*\/#',"",$path_to_file);	
   		$path_to_file = trim($path_to_file,"(); ");

		//check variable in path	
		if (preg_match('#\$[^\s\R]*?#',$path_to_file,$m)) {
			echo "variable in argument <br>".$m[0]."preg_match = ".preg_match('#\$[^\s\R]*?#',$path_to_file,$m)."<br> arr <br>";
			var_dump($m);
			$link = '<a class="a" href="javascript:alert(\'variable in argument\');">'.$arr['arguments'][0].'</a>';
			return 'variable';

		} 

   		//for resolve concantenation
   		//devide path by . and put in array 
   		$path_to_file = explode(".",$path_to_file);

   		//check either . in quotation or not
   		//and connect to value if . in quotation
   		$countq = 0;
   		$count2q = 0;
   		foreach ($path_to_file as &$val) {
//	   			echo "<br> arguments <br> val = $val";
   			$countq += preg_match_all("/'/",$val); 
   			$count2q += preg_match_all("/\&quot;/",$val);
//	   			echo "<br> countq = $countq <br> count2q = $count2q <br>";
   			if (($countq & 1)||($count2q & 1)) {
   				$current = current($path_to_file);	
	   			$countq += preg_match_all("/'/",$current); 
	   			$count2q += preg_match_all("/\&quot;/",$current);   				
   				$val .= ".".$current;	
   				unset($path_to_file[key($path_to_file)]);
   			}
   		}
   		//delete all \ ' and " from start and finish value 
   		foreach ($path_to_file as &$val) {
   			$val = trim($val,"\' ");
   			$val = preg_replace("#^&quot\;|&quot\;$#","",$val);
   		}
   		// connect array into string
   		$path_to_file = implode(array_values($path_to_file));
   		$path_to_file = preg_replace("#".dirname(__FILE__)."/#","",$path_to_file);

   		$data['file'] = $path_to_file;
   		$data['constants'] = $constants;


		$json = json_encode($data);

		$link = '<a class="a" href=\'javascript:sendData('.$json.');\'>'.$arr['arguments'][0].'</a>';
		$pattern = "#".preg_quote($arr['arguments'][0],"#")."#";
	   	//echo "<br> pattern - ".$pattern." endpattern";
	   	//echo $string;
	   	//echo $link;
	   	$string = preg_replace($pattern,$link,$string);
	   	//echo $string;
		return $string;
   	}

   	function dirname_phpsteper($string) {
   		//echo "<br> $string";
   		preg_match("#dirname(\s|\R)*?\(([^\(\)]*?)\)#",$string,$match);
   		if ($match) {
	   		$string = preg_replace("#dirname(\s|\R)*?\(([^\(\)]*?)\)#",dirname($match[2]),$string);
   		}
   		//echo "<br> dirname ===".dirname($match[2])."<br>";
   		//var_dump($match);
   		//echo "<br> $string";
   		return $string;
   	}

   	function require_phpsteper ($arg,$string) {
   		$return = include_phpsteper ($arg,$string);
   		return $return;
   	}


	function require_once_phpsteper ($arg,$string) {
   		$return = include_phpsteper ($arg,$string);
   		return $return;
   	}

	function include_once_phpsteper ($arg,$string) {
   		$return = include_phpsteper ($arg,$string);
   		return $return;
   	}

   	function if_phpsteper ($arg,$string) {

   		echo "if finded";
   		var_dump($arg);
   		echo "<br /> <br /> string";
   		var_dump($string);
   		echo "<br /> <br />";

   		global $number_of_expressions;
   		global $pattern;
   		global $condition;
   		global $dataCheck;
   		// for work all data check
   		$reserve_dataCheck = $dataCheck;
   		// save condition with state if $condition 
   		array_push($number_of_expressions,0);
   		foreach ($arg['condition'] as $key_condition => $condition) {
	   		array_push($condition, [$condition,true]);
	   		$dataCheck = $arg['body'][$kay_condtition];
	   		$new_body = preg_replace_callback($pattern,"handler_expression", $arg['body'][$key_condition]);	
   		}
   		check();
   		array_pop($number_of_expressions);
   		$dataCheck = $reserve_dataCheck;
   		$string = preg_replace("#".preg_quote($arg['body'][0],"#")."#",$new_body,$string);
   		return $string;
   	}

   	function wtf ($file_name,$string) {
   		$myfile = fopen($file_name, "a") or die("Unable to open file!");
		fwrite($myfile, $string);
		fclose($myfile);	
   	}

   	function change_constants($string) {
   		global $constants;
   		$arrkey = [];
   		$arrval = [];
   		foreach ($constants as $key => $val) {
   			$key = preg_replace("#\_\_phpsteper$#","",$key);
   			$key = strtoupper($key);
   			$key = "#".$key."#";
   			array_push($arrkey,$key);	
   			array_push($arrval,$val);
   		}
   		$return = preg_replace($arrkey,$arrval,$string);
   		return $return;
   	}

   	function key_part ($correspond_table_item) {
   		global $correspond_table;
   		/*echo "<br> key_part <br> ";
   		var_dump($match);
   		echo "<br> end key_part <br>";*/
   		$key = key_def($correspond_table_item);
   		$correspond_table_item[0] = "<span style='".$correspond_table[$correspond_table_item[0]][0]."'>".$correspond_table_item[0]."</span>";
   		// echo $match[0];
   		return $correspond_table_item[0];
   	}

  	function key_def ($match = NULL) {

   		static $key;
   		//$echo = isset($match[0])?$match[0]:"empty".$match;
   		//echo "<br> key_def <br>";
   		//var_dump($match);
   		//echo "<br> end key_def <br>";	
   		if ($match === "") {
   			$match = NULL;
   			$key = "";
   		}
   		if ($match !== NULL) {
   		$key = $match[0];
   		}
   		return $key;
   	}

   	function parse_func ($string,$key) {

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
	   		$teststring1 = '\"DDDD';//"aaaaaa\"bbbbbbbb'cccccccccccc\"dddddddddd'eeeeeeeee\"ffffffff";
	   		echo "<br> ord(teststring) -".ord($teststring)." <br>";
	   		echo "<br> ord(teststring1) -".ord($teststring1)." <br>";
	   		echo "<br> teststring - $teststring <br>";
	   		$testcount = 0;
	   		echo "<br> testcount - $testcount <br>";
	   		$testcount += preg_match_all("/\&quot;/",$teststring);
	   		echo "<br> testcount - $testcount <br>";
	   		$teststring = preg_replace("/\&quot;/",'Q',$teststring);
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

	   	preg_match_all('/'.make_pattern_count_parentheses().'/',$string,$match);
   		if (isset($match[0])) {
   			$array['condition'] = [];
   			foreach ($match[0] as $v) {
		   		$v = trim($v,"; ");
		   		$v = preg_replace("#^\(|\)$#","",$v);
		   		array_push($array['condition'], $v);
   			}
   		}

	   	preg_match_all('/'.make_pattern_count_parentheses(100,"{").'/',$string,$match);
   		if (isset($match[0])) {
   			$array['body'] = [];
   			foreach ($match[0] as $v) {
		   		$v = trim($v,"; ");
		   		$v = preg_replace("#^\{|\}$#","",$v);
		   		array_push($array['body'], $v);
   			}
   		}

   		return $array;
   	}

/*   	function include_file ($match) {
   		$match[0] = preg_replace(make_pattern_count_parentheses(), "include_file_sub", $match[0]);	
   	}

   	function include_file_sub ($match) {
   		$match[0] = trim ($match[0],'()');
		$link = '<a class="a" href="phpsteper.php?file='.$path_to_file.$filllink.'">'.$match[0].'</a>';
   		$match[0] = $link;	
   	}
*/
   	function handler_expression ($match) {

   		global $correspond_table;
   		global $number_of_expressions;
   		global $dataCheck;


   		check($match[0]);	

/*		if ($number_of_expressions == 5) {
			var_dump($match);
		}
*/

		//delete expression equal ";"
		if (trim($match[0]) == ";") {
			return "";
		}

   		++$number_of_expressions[count($number_of_expressions)-1];

		$match[0] = htmlentities($match[0]);
		//defined which type of syntax expression and change from "if: else" to "if() {} elseif () {}";
		if (isset($match[1])) {
			$match[0] = preg_replace("#^([\s\S\R]*?(if|switch)[\s\R]*".make_pattern_count_parentheses("100","(")."[\s\R]*)\:#","$1 {",$match[0],1);
			$match[0] = preg_replace("#else[\s\R]*:#","}\r\nelse {",$match[0],1);
			$match[0] = preg_replace("#(endif|endswitch)#","} $1",$match[0],1);
		} 
		/*find keypart in space befor parentheses and seve in $ma;
*/		
		$count = 0;
		// assign $m all befor first parenthesis
   		preg_match("#^[^\(]*#",$match[0],$m);
   		// assign $ma key part if finded correspond in $correspond_table and $m 
   		/*echo 'make_pattern_arr ';
   		var_dump(make_pattern_arr($correspond_table));
   		echo "<br /> <br />";*/
		$ma = preg_replace_callback(make_pattern_arr($correspond_table),"key_part",$m[0],1,$count);
   		if (!$count) {
   			echo "handler_expression: no key part <br /> <br />";
   		}
   		// if keypart exists
   		else {
   			// replace all befor first parenthesis by key part
			$match[0] = preg_replace("#".preg_quote($m[0],"#")."#",$ma,$match[0]);
			// assign $key 	last defined key ( key defined in key_part function by key_def() function)
	   		$key = key_def();
	   		$arrfunc = parse_func ($match[0],$key);
	   		if (function_exists($key."_phpsteper")) {
	   			$func_name = $key."_phpsteper";
	   			//$arrfunc is all function's parts in arr. $arrfunc = [key,arguments,body];
	   			//$mathc[0] is string with expression
	  			$return = $func_name($arrfunc,$match[0]);
	   			if ($return) {
	   				$match[0] = $return;
	   			}

	   		}

   		}
   		//$match[0] = preg_replace("#^\h*\R#","\r\n .".$number_of_expressions[count($number_of_expressions)-1],$match[0],1,$count);
   		//if (!$count) {
		/*echo "number_of_expressions ";	
		var_dump($number_of_expressions);
		echo "<br />";
		var_dump($match[0]);
*/		// var_dump($match[1]);
		// var_dump($match[2]);
		echo "<br />";
		$number_of_expressions_string = "";
		foreach( $number_of_expressions as $val) {
			$number_of_expressions_string = "&nbsp;&nbsp;&nbsp;&nbsp;".$number_of_expressions_string;
			$number_of_expressions_string .= $val.",";
		}
		$match[0] = preg_replace("#^(\s)*#","$0 "."$number_of_expressions_string ",$match[0]);
   			// $match[0] = $number_of_expressions[count($number_of_expressions)-1]." ".$match[0];
   		// }
		return  $match[0];
   	}

	function make_pattern_arr ($arr) {
		$pattern_arr = [];
		foreach ($arr as $k => $v) {
			array_push($pattern_arr,"#\b".preg_quote($k,"#")."\b#");
		}
		//var_dump ($pattern_arr);
		return $pattern_arr;
	}

   	function make_pattern () {

   		$deep = 2;
   		$parenthesis = make_pattern_count_parentheses ($deep,"(");
   		$braces = make_pattern_count_parentheses ($deep,"{");
   		$pattern = "/";	
   		$pattern .= "([^\;\}]*?(if|switch)[\s\R]*";
   		$pattern .= $parenthesis;
   		$pattern .= "[\s]*:[\s\S]*?(endif|endswitch)[\s\R]*?\;)(\s)*|";
   		$pattern .= "(([^\;\(\)\{\}])*(";
   		$pattern .= $parenthesis;
   		$pattern .= ")+(([^\;\{\}]*\;)|(((\s|(\<br\ \/\>)|else\s*(if(\s)*$parenthesis(\s)*)?)*";
   		$pattern .= $braces;
   		$pattern .= ")+(\;?)))";
		$pattern .= ")(\s)*|";
		$pattern .= "[^\(\)]*\;(\s)*";
   		$pattern .= "/";
   		return $pattern;
   	}

   	function show_tag ($string) {

			$string = htmlentities($string);
			$string = nl2br ($string);
			return $string;
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

    $dataCheck = "";
    function check ($data = NULL) {

    	global $dataCheck; 
    	$count = 0;
    	if ($data == NULL) {
    	echo "<br> $data <br>";
		echo "<b><br /> dataCheck: <br /> $dataCheck <br /></b>";
    		$dataCheck = '';
    	}
    	else if (!$dataCheck){
			$dataCheck = $data;
    	}
		else {
		//echo "<br /> check "/*<br /> dataCheck(all): <br /><b> $dataCheck </b>*/."<br /> data: <br /> $data <br />";	
			$dataCheck = preg_replace("#".preg_quote($data,"#")."#","",$dataCheck,1,$count);	
			if (!$count) {
				echo "<br /> <b> count = 0 </b> <br />";
			}
		}

    }

    $collection_non_php_parts = [];

    function collect_non_php_parts ($match = NULL) {

    	global $collection_non_php_parts;
    	if ($match !== NULL) {
    		array_push($collection_non_php_parts,$match[0]);
    		return count($collection_non_php_parts);
    	}
    	else {
    		$collection_non_php_parts = [];
    		return "0";
    	}
    }

    function replace_non_php_parts ($match = NULL) {
    	$count = collect_non_php_parts($match);
    	$count = str_pad($count,6,'0',STR_PAD_LEFT);	
    	return "; --__".$count."__-- ";
    }

    function change_non_php($string,$htmlswitch) {

    	global $collection_non_php_parts;
    	if (!$htmlswitch) {
	    	replace_non_php_parts ();
    		$string = preg_replace_callback ("#(^[\s\S\R]*?\<\?php)|(\?\>[\s\S\R]*?\<\?php)|(\?\>[\s\S\R]*?$)#","replace_non_php_parts",$string);	
	    	//echo $string;
    	}
    	else {
    		$string = preg_replace_callback ("#\-\-\_\_\d{6}\_\_\-\-#", function ($match) { 
    			global $collection_non_php_parts; 
    			$index = trim($match[0],"-_"); 
    			return "<span style='color:darkslategray;'>".htmlentities($collection_non_php_parts[ltrim($index,"0")-1])."</span>"; 
    		},$string);
    	}
    	return $string;
    }

	$collection_any_comments = [];

    function collect_any_comments ($match = NULL) {

    	global $collection_any_comments;
    	if ($match !== NULL) {
    		array_push($collection_any_comments,$match[0]);
    		return count($collection_any_comments);
    	}
    	else {
    		$collection_any_comments = [];
    		return "0";
    	}
    }

    function replace_any_comments ($match = NULL) {
    	$count = collect_any_comments($match);
    	$count = str_pad($count,6,'0',STR_PAD_LEFT);	
    	return " --==".$count."==-- ";
    }

	function hide_any_comments ($string,$comments_switch) {
	    	global $collection_any_comments;
    	if (!$comments_switch) {
	    	replace_any_comments ();
    		$string = preg_replace_callback ("#(\/\*[\s\S\R]*?\*\/)|(\/\/[\s\S]*?\R)#","replace_any_comments",$string);	
	    	//echo $string;
    	}
    	else {
		$string = preg_replace_callback ("#\-\-\=\=\d{6}\=\=\-\-#", function ($match) { 
    			global $collection_any_comments; 
    			$index = trim($match[0],"=-"); 
    			return "<span style='color:slategray;'>".htmlentities($collection_any_comments[ltrim($index,"0")-1])."</span>"; 
    		},$string);
    	}
    	return $string;
		
	}

/*    function php_section ($match) {

		create regex for catch one expression
		global $pattern;
		echo $pattern;
		delete <php >
		$match[0] = preg_replace('/\&lt\;\?php|\?\&gt\;/',"",$match[0]);
		change all tag for show and change all new line to <br>
		echo $match[0];
		handler for one expression "handler_expression"
		check($match[0]);
		$match[0] = preg_replace_callback($pattern,"handler_expression", $match[0]);
		check();
		$match[0] = show_tag("<?php")."<br />".$match[0].show_tag(">");
		return $match[0];
    }*/



							var_dump($_POST);


	if ((count($_POST)>0)&&($file_name = $_POST['file'])) {
	    //show name presented file
	    echo '<br>'.ROOT."/".$file_name.'<br>';
		$file = fopen(ROOT."/".$file_name,"r");
		while (!feof($file)){
			$string = fread($file,filesize($file_name)+1);
			//non php text to -=000000=-
			$string = change_non_php($string,0);
			//comments to -_0000000_-
			$string = hide_any_comments($string,0);
			//$string = preg_replace_callback('/\&lt\;\?php(.|\R)*?(\?\&gt\;|$)/', "php_section", $string);
			echo $pattern;
			check($string);
			$string = preg_replace_callback($pattern,"handler_expression", $string);
			check();
			$string = change_non_php($string,1);
			$string = hide_any_comments($string,1);
			/* echo "<br /> collection_non_php_parts <br />";
			var_dump($collection_non_php_parts);
			echo " <br />";*/
			echo "result <br> ======================== <br> <br> <br>";
			echo nl2br($string);
		}
		fclose($file);

		
	};
	?>
	 </p>
</body>
</html>

