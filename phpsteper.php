<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $_POST['file']; ?></title>
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

			var parents = {};
			for (var indexAllData in allData) {
				if (indexAllData.substr(0,6) === 'parent') {
					parents[Number(indexAllData.substr(6))+1] = allData[indexAllData];
					delete allData[indexAllData];
				}
			}

			if (arr['file']) {
				allData['parent0'] = allData['file'];
			}

			for (var i in parents) {
			for (var i in parents) {
				allData["parent"+i] = parents[i];
			}

				allData["parent"+i] = parents[i];
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
				if (typeof allData[i] === 'string' || allData[i] instanceof String){
					input.value = allData[i];
				} else 
				{
					input.value = JSON.stringify(allData[i]);
				}
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


	function define_phpsteper ($arr) {
		global $constants;
		//$arr['arguments'][0] = hide_any_quotation($arr['arguments'][0],1);
		// echo "<br /> arr['arguments'][0] - --".$arr['arguments'][0]."--";
		$arr['arguments'][0] = trim ($arr['arguments'][0],'\'\" ');
		$arr['arguments'][0] = preg_replace("#^&quot\;|&quot\;$#","",$arr['arguments'][0]);
		// echo "arr['arguments'][1] - --".$arr['arguments'][1]."--";
		// $arr['arguments'][1] = hide_any_quotation($arr['arguments'][1],1);
		// echo "arr['arguments'][1] - --".$arr['arguments'][1]."--";
		$constant_val = change_constants(trim ($arr['arguments'][1]," "));
		// echo "change_constants - --".$constant_val."--";
		$constants[strtolower($arr['arguments'][0])."__phpsteper"] = $constant_val;
//   		echo "name constant - ".strtolower($arr['arguments'][0])."__phpsteper"." which value is ".$constants[strtolower($arr['arguments'][0])."__phpsteper"];
	}


	function include_phpsteper ($arr) {

		//echo "<br> arr <br>";
		//var_dump($arr);
		//echo "<br> endarr <br>";
		global $number_of_expressions,
				$correspond_table,
				$pattern,
				$var_name_for_pass,
				$constants,
				$break_parse_file,
				$collection_any_comments,
				$dataCheck,
				$collection_non_php_parts,
				$condition,
				$functions,
				$collection_any_quotation,
				$file_name;

		foreach ($var_name_for_pass as $var_name) {
			global $$var_name;
		}
		$data = [];

		$arr['arguments'][0] = hide_any_quotation($arr['arguments'][0],1);
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
		if (preg_match('#\$[^\s]*#',$path_to_file,$m)) {
			echo "variable in argument <br>".$m[0]."<br /> preg_match = ".preg_match('#\$[^\s]*#',$path_to_file,$m)."<br> arr <br>";
			var_dump($arr);
			$link = '<a class="a" href="javascript:alert(\'variable in argument\');">'.$arr['arguments'][0].'</a>';
			$local_pattern = "#".preg_quote($arr['arguments'][0],"#")."#";
			$return['pattern'] = $local_pattern;
			$return['inclusion'] = $link;
			return $return;

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
		foreach ($var_name_for_pass as $var_name) {
			if ($$var_name !== NULL) {
				$data[$var_name] = $$var_name;
			}
		}

		$json = json_encode($data,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_FORCE_OBJECT);

		$link = '<a class="a" href=\'javascript:sendData('.$json.');\'>'.$arr['arguments'][0].'</a>';
		$local_pattern = "#".preg_quote($arr['arguments'][0],"#")."#";
		//echo "<br> pattern - ".$pattern." endpattern";
		//echo $string;
		//echo $link;
		$return['pattern'] = $local_pattern;
		$return['inclusion'] = $link;
		//echo $string;
		if ($break_parse_file < 2) {

			$clone_break_parse_file = $break_parse_file;
			$var_for_cash = ['collection_any_comments','number_of_expressions','constants','file_name','dataCheck','collection_non_php_parts','condition','functions','collection_any_quotation'];
			foreach ($var_for_cash as $name_of_var) {
				$new_name =  $name_of_var.$clone_break_parse_file;
				$cash[$new_name] = $$name_of_var;  
			}		
			$new_name = "_POST".$clone_break_parse_file;
			$cash[$new_name] = $_POST;
			$dataCheck = "";
			$collection_any_quotation = [];
			$collection_non_php_parts = [];
			$collection_any_comments = [];
			$number_of_expressions = [0];
			$file_name = $path_to_file; 
			$constants['__file____phpsteper'] = ROOT."/".$file_name;
			$_POST['file'] = $path_to_file;
			$_POST['condition'] = $condition;
			$_POST['constants'] = $constants;
			$_POST['functions'] = $functions;
			// echo "include_phpsteper -befor call parse_file <br />";
			// echo "file_name is $file_name <br />";
			// var_dump_html($_POST);
			parse_file ($path_to_file,FALSE);
			foreach ($var_for_cash as $name_of_var) {
				$new_name =  $name_of_var.$clone_break_parse_file;
				$$name_of_var = $cash[$new_name]; 
			}
			$new_name = "_POST".$clone_break_parse_file;
			$_POST = $cash[$new_name]; 
			// $is_library = ($number_of_expressions[0] == count($functions)? TRUE : FALSE );
			// echo "is_library======================================================= $is_library <br />";
		}
		return $return;
	}

	function dirname_phpsteper($string) {
		//echo "<br> $string";
		$string = hide_any_quotation($string,1);
		preg_match("#dirname(\s|\R)*?\(([^\(\)]*?)\)#",$string,$match);
		if ($match) {
			$string = preg_replace("#dirname(\s|\R)*?\(([^\(\)]*?)\)#",dirname($match[2]),$string);
		}
		//echo "<br> dirname ===".dirname($match[2])."<br>";
		//var_dump($match);
		//echo "<br> $string";
		return $string;
	}

	function require_phpsteper ($arg) {
		$return = include_phpsteper ($arg);
		return $return;
	}


	function require_once_phpsteper ($arg) {
		$return = include_phpsteper ($arg);
		return $return;
	}

	function include_once_phpsteper ($arg) {
		$return = include_phpsteper ($arg);
		return $return;
	}

	function if_phpsteper ($arg) {

		/*echo "if finded";
		var_dump($arg);
		echo "<br /> <br /> string";
		var_dump($string);
		echo "<br /> <br />";
*/
		global $number_of_expressions;
		global $pattern;
		global $condition;
		global $dataCheck;
		global $file_name;
		$return = ['pattern','inclusion'];
		$return['pattern'] = []; 
		$return['inclusion'] = [];

		if ($condition === NULL) {
			$condition = [];
		}

		// for work all data check
		$reserve_dataCheck = $dataCheck;
		// save condition with state if $condition 
		array_push($number_of_expressions,0);
		foreach ($arg['body'] as $key_body => $body) {
			if (isset($arg['condition'][$key_body])) {
				array_push($condition, array(addslashes(hide_any_quotation($arg['condition'][$key_body],1)),$file_name,true));
			}
			$dataCheck = $body;
			$new_body = preg_replace_callback($pattern,"handler_expression", $body);	
			check();
			array_push($return['pattern'],"#".preg_quote($body,"#")."#");
			array_push($return['inclusion'],$new_body);
			$condition[count($condition)-1][2] = false;
		}
		array_pop($number_of_expressions);
		$dataCheck = $reserve_dataCheck;
		return $return;
	}

	function function_phpsteper ($arg) {
		global $functions,$file_name; 
		if ($functions === NULL) {
			$functions = [];
		}
		preg_match("#function[\s]+([\s\S]*?)[^\(]#",$arg['key'][0],$function_name);
		array_push($functions,[$function_name[1],$arg['arguments'],$arg['body'],$file_name]);
		$return['pattern'] = '#xxxxx#';
		$return['inclusion'] = '#xxxxx#';
		return $return;
	}

	function wtf ($file_for_write,$string) {
		$myfile = fopen($file_for_write, "a") or die("Unable to open file!");
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
			$key = "#".preg_quote($key,"#")."#";
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
		$array['key'] = hide_any_quotation($array['key'],1);	

		preg_match('/'.make_pattern_count_parentheses().'/',$string,$argstr);
		if (isset($argstr[0])) {
			$argstr = trim($argstr[0],"; ");
			$argstr = preg_replace("#^\(|\)$#","",$argstr);
			$argarr = explode(",",$argstr);

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
			$array['arguments'] = hide_any_quotation($array['arguments'],1);	
		}

		$array['condition'] = [];
		$array['body'] = [];
		do {
			$pos_parentheses = strlen($string);
			$pos_braces = strlen($string);
			$exists_parentheses = preg_match("#".make_pattern_count_parentheses()."#",$string,$parentheses); 
			if ($exists_parentheses) {
				$pos_parentheses = strpos($string,$parentheses[0]);
			}
			$exists_braces = preg_match('/'.make_pattern_count_parentheses(100,"{").'/',$string,$braces);
			if ($exists_braces) {
				$pos_braces = strpos($string,$braces[0]);
			}
			if ($exists_parentheses||$exists_braces) {
				if ($pos_parentheses < $pos_braces) {
					$string = preg_replace("#".preg_quote($parentheses[0],"#")."#","",$string);
					$parentheses[0] = trim($parentheses[0],"; ");
					$parentheses[0] = preg_replace("#^\(|\)$#","",$parentheses[0]);
					array_push($array['condition'],hide_any_quotation($parentheses[0],1));
				} else {
					$string = preg_replace("#".preg_quote($braces[0],"#")."#","",$string,1);
					$braces[0] = trim($braces[0],"; ");
					$braces[0] = preg_replace("#^\{|\}$#","",$braces[0]);
					array_push($array['body'],hide_any_quotation($braces[0],1));
				}
			} else {
				break;
			}
		} while (1);

/*   		echo "<br /> parse_func ";
		var_dump($array);*/
		return $array;
	}

	function change_frag ($pattern_for_frag,$inclusion,$string) {
		if (is_array($pattern_for_frag)) {
			foreach ($pattern_for_frag as $k => &$val) {
				$val = htmlentities($val);
			}
		}
		else {
			$pattern_for_frag = htmlentities($pattern_for_frag);
		}
		$string = hide_any_quotation($string,1);
		$string = htmlentities($string);
		$return = preg_replace($pattern_for_frag, $inclusion, $string,-1,$count);
		return $return;
	}

	function handler_expression ($match) {

		global $correspond_table;
		global $number_of_expressions;
		global $dataCheck;
		$return_expression = "";


		check($match[0]);	

		// if ($number_of_expressions == [1]) {
/*			echo "<br /> handler_expressions - ";
			var_dump($number_of_expressions);
			echo " <br />";
			var_dump($match[0]);*/
		// }


		//delete expression equal ";"
		if (trim($match[0]) == ";") {
			return "";
		}

		++$number_of_expressions[count($number_of_expressions)-1];
		//var_dump($number_of_expressions);

		//defined which type of syntax expression and change from "if: else" to "if() {} elseif () {}";
		if (isset($match[1])) {
			$match[0] = preg_replace("#^([\s\S]*?(if|switch)\s*".make_pattern_count_parentheses("100","(")."\s*)\:#","$1 {",$match[0],1);
			$match[0] = preg_replace("#else\s*:#","}\r\nelse {",$match[0],1);
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
			//echo "handler_expression: no key part <br /> <br />";
			$match[0] = htmlentities($match[0]);
		}
		// if keypart exists
		else {
			// replace all befor first parenthesis by key part
			
			// assign $key 	last defined key ( key defined in key_part function by key_def() function)
			$key = key_def();
			$arrfunc = parse_func ($match[0],$key);
			if (function_exists($key."_phpsteper")) {
				$func_name = $key."_phpsteper";
				//$arrfunc is all function's parts in arr. $arrfunc = [key,arguments,body];
				//$mathc[0] is string with expression
				$return = $func_name($arrfunc);
				if ($return) {
					$match[0] = change_frag($return['pattern'],$return['inclusion'],$match[0]);
				} else 
				{
					$match[0] = htmlentities($match[0]);
				}

			} else {

					$match[0] = htmlentities($match[0]);
			}
			$match[0] = preg_replace(htmlentities("#".preg_quote($m[0],"#")."#"),$ma,$match[0]);

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

		$deep = 10;
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
		//echo "COMMENTS <br /> $match[0] <br />";
		if (preg_match("#^(\'|\")[\s\S]*?(\'|\")$#",$match[0]) ) {
			return $match[0];
		} else {
		$count = collect_any_comments($match);
		$count = str_pad($count,6,'0',STR_PAD_LEFT);	
		return " --==".$count."==-- ";
		}
	}

	function hide_any_comments ($string,$comments_switch) {
			global $collection_any_comments,$break_parse_file;
		if (!$comments_switch) {
			replace_any_comments ();
			$string = preg_replace_callback ("#(\/\*[\S\s]*?\*\/)|(\/\/[\S\s]*?\R)|\"[^\"]*\"|\'[^\']*\'#","replace_any_comments",$string);	
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

	function collect_any_quotation ($match = NULL) {

		global $collection_any_quotation;
		if ($match !== NULL) {
			array_push($collection_any_quotation,$match[0]);
			return count($collection_any_quotation);
		}
		else {
			$collection_any_quotation = [];
			return "0";
		}
	}

	function replace_any_quotation ($match = NULL) {
		//echo "<br /> ".$match[0]."<br />";
		$count = collect_any_quotation($match);
		$count = str_pad($count,6,'0',STR_PAD_LEFT);	
		return "--++".$count."++--";
	}

	function hide_any_quotation ($string,$quotation_switch) {
			global $collection_any_quotation;
		if (!$quotation_switch) {
			replace_any_quotation ();
			$string = preg_replace_callback ("#\"[^\"]*\"|\'[^\']*\'#","replace_any_quotation",$string);	
			//echo $string;
		}
		else {
			$string = preg_replace_callback ("#\-\-\+\+\d{6}\+\+\-\-#", function ($match) { 
				global $collection_any_quotation; 
				$index = trim($match[0],"+-"); 
				return htmlentities($collection_any_quotation[ltrim($index,"0")-1]); 
			},$string);
		}
		return $string;
		
	}

	function html_formating ($string) {
		$string = str_replace(" ","&nbsp;&nbsp;",$string);
		$string = nl2br($string);
		return $string;
	}

	function var_dump_html ($var) {
		ob_start("html_formating");
		var_dump($var);
		ob_end_flush();
	}

	function pass_data ($name_data) {

		global $_POST;
		if (isset($_POST[$name_data])&&$_POST[$name_data] !== "" ){
			$_POST[$name_data] = json_decode($_POST[$name_data],JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_FORCE_OBJECT);
			return $_POST[$name_data];
		} 
	}

	function parse_file ($file_for_parse,$echo = TRUE) {

		global $pattern,$break_parse_file;
		$break_parse_file++;
		//show name presented file
		echo '<br>'.ROOT."/".$file_for_parse.'<br>';
		$file = fopen(ROOT."/".$file_for_parse,"r");
		while (!feof($file)){
			$string = fread($file,filesize($file_for_parse)+1);
			//non php text to -=000000=-
			$string = change_non_php($string,0);
			//comments to -_000000_-
			$string = hide_any_comments($string,0);
			//$string = preg_replace_callback('/\&lt\;\?php(.|\R)*?(\?\&gt\;|$)/', "php_section", $string);
			// echo $pattern;
			$string = hide_any_quotation($string,0);
			check($string);
			$string = preg_replace_callback($pattern,"handler_expression", $string);
			check();
			$string = hide_any_quotation($string,1);
			$string = change_non_php($string,1);
			$string = hide_any_comments($string,1);
			/* echo "<br /> collection_non_php_parts <br />";
			var_dump($collection_non_php_parts);
			echo " <br />";*/
			if ($echo) {
				echo "result <br> ======================== <br> <br> <br>";
				echo nl2br($string);
			}
		}
		fclose($file);
	}

	function main () {
		ini_set('display_errors',true);
		error_reporting(E_ALL);
		define ('ROOT',dirname(__FILE__));

		//$type_statements = [];
		//array_push($type_statements,[['red'],['gray','$catch_const','gray','$fill_const']]);
	 
		global $number_of_expressions,
				$correspond_table,
				$pattern,
				$var_name_for_pass,
				$constants,
				$break_parse_file,
				$collection_any_comments,
				$dataCheck,
				$collection_non_php_parts,
				$condition,
				$functions,
				$collection_any_quotation,
				$file_name;

		$dataCheck = "";
		$collection_non_php_parts = [];
		$collection_any_comments = [];
		$number_of_expressions = [0];
		$break_parse_file = 0;

		$correspond_table['define'] = ['color: blue'];
		$correspond_table['error_reporting'] = ['color: blue'];
		$correspond_table['include'] = ['color: red'];
		$correspond_table['include_once'] = ['color: red'];
		$correspond_table['require'] = ['color: red'];
		$correspond_table['require_once'] = ['color: red'];
		$correspond_table['comment'] = ['color: gray'];
		$correspond_table['echo'] = ['color: green'];
		$correspond_table['if'] = ['color: orange'];
		$correspond_table['function'] = ['color: darkorchid'];
		$pattern = make_pattern();
		$var_name_for_pass = ['condition','functions','constants'];
		foreach ($var_name_for_pass as $var_name) {
			$$var_name = pass_data($var_name);
		} 	
		if (!empty($_POST['file'])){
			$file_name = $_POST['file'];
			$constants['__file____phpsteper'] = ROOT."/".$file_name;
		}
		var_dump_html($_POST);
		echo "<br />";
						

		if ((count($_POST)>0)&&($file_name = $_POST['file'])) {
			parse_file($file_name);
		};
	}

	main();
	?>
	 </p>
</body>
</html>

