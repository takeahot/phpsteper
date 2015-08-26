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
		debug (__FUNCTION__);
		//$arr['arguments'][0] = hide_any_quotation($arr['arguments'][0],1);
		// echo "<br /> arr['arguments'][0] - --".$arr['arguments'][0]."--";
		$name_of_constant = trim ($arr['arguments'][0],'\'\" ');
		$name_of_constant = preg_replace("#^&quot\;|&quot\;$#","",$name_of_constant);
		// echo "arr['arguments'][1] - --".$arr['arguments'][1]."--";
		// $arr['arguments'][1] = hide_any_quotation($arr['arguments'][1],1);
		// echo "arr['arguments'][1] - --".$arr['arguments'][1]."--";
		// $constant_val = change_constants(trim ($arr['arguments'][1]," "));
		// echo "change_constants - --".$constant_val."--";
		// $constants[strtolower($arr['arguments'][0])."__phpsteper"] = $constant_val;
		return [define($name_of_constant."__PHPSTEPER",trim ($arr['arguments'][1]," "))];
//   		echo "name constant - ".strtolower($arr['arguments'][0])."__phpsteper"." which value is ".$constants[strtolower($arr['arguments'][0])."__phpsteper"];
	}


	function include_phpsteper ($func_parts,$once = 0) {
		debug (__FUNCTION__);
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
				$name_core_file_for_include,
				$include_deep_parse,
				$is_library,
				$included_files,
				$file_name;

		if ($include_deep_parse === NULL) {
			$include_deep_parse = 0;
		}

		foreach ($var_name_for_pass as $var_name) {
			global $$var_name;
		}
		$data = [];
		// echo "include_phpsteper hide_any_quotation open <br />";
		//first argument from call function include
		$path_to_file = $func_parts['arguments'][0];	
		//resolve constant
		$path_to_file = change_constants($path_to_file);
		//result dirname
		$path_to_file = dirname_phpsteper($path_to_file);	
		//delete text comments
		$path_to_file = preg_replace('#\/\*(.|\R)*?\*\/#',"",$path_to_file);	
		$path_to_file = trim($path_to_file,"(); ");

		//check variable in path	
		if (preg_match('#\$[^\s]*#',$path_to_file,$m)) {
			// echo "variable in argument <br>".$m[0]."<br /> preg_match = ".preg_match('#\$[^\s]*#',$path_to_file,$m)."<br> arr <br>";
			// var_dump($arr);
			$link = '<a class="a" href="javascript:alert(\'variable in argument\');">'.$arr['arguments'][0].'</a>';
			$local_pattern = "#".preg_quote($arr['arguments'][0],"#")."#";
			$return['pattern'] = $local_pattern;
			$return['inclusion'] = $link;
			return $return;

		} 

		//for resolve concantenation
		//devide path by . and put in array 
		// $path_to_file = explode(".",$path_to_file);

		//check either . in quotation or not
		//and connect to value if . in quotation
		// $countq = 0;
		// $count2q = 0;
		// foreach ($path_to_file as &$val) {
//	   			echo "<br> arguments <br> val = $val";
			// $countq += preg_match_all("/'/",$val); 
			// $count2q += preg_match_all("/\&quot;/",$val);
//	   			echo "<br> countq = $countq <br> count2q = $count2q <br>";
			// if (($countq & 1)||($count2q & 1)) {
				// $current = current($path_to_file);	
				// $countq += preg_match_all("/'/",$current); 
				// $count2q += preg_match_all("/\&quot;/",$current);   				
				// $val .= ".".$current;	
				// unset($path_to_file[key($path_to_file)]);
			// }
		// }
		//delete all \ ' and " from start and finish value 
		// foreach ($path_to_file as &$val) {
			// $val = trim($val,"\' ");
			// $val = preg_replace("#^&quot\;|&quot\;$#","",$val);
		// }
		// connect array into string
		// $path_to_file = implode(array_values($path_to_file));
		$path_to_file = preg_replace_callback ("#\"[^\"]*\"|\'[^\']*\'#","replace_any_quotation",$path_to_file );
		$path_to_file = preg_replace("#\.#","",$path_to_file);
		$path_to_file = hide_any_quotation($path_to_file,1);
		$path_to_file = preg_replace("#".dirname(__FILE__)."/#","",$path_to_file);
		if ($once) {
			foreach ($included_files as $val) {
				if ($path_to_file === $val) {
					$return['pattern'] = "";
					$return['inclusion'] = "";
					return $return;
				}
			}
		}
		array_push($included_files,$path_to_file);

		// if ($name_core_file_for_include == $file_name) {
		if ($break_parse_file < 10) {
			$include_deep_parse++;
			$clone_break_parse_file = $break_parse_file;
			$var_for_cash = ['collection_any_comments','number_of_expressions','file_name','dataCheck','collection_non_php_parts','collection_any_quotation','is_library'];
			foreach ($var_for_cash as $name_of_var) {
				$new_name =  $name_of_var.$clone_break_parse_file;
				$cash[$new_name] = $$name_of_var;  
			}		
			$cash['constants']['__file____phpsteper'] = $constants['__file____phpsteper'];
			$new_name = "_POST".$clone_break_parse_file;
			$cash[$new_name] = $_POST;
			$dataCheck = "";
			$collection_any_quotation = [];
			// echo "include_phpsteper collection non php parts befor initialisation count -- ".count($collection_non_php_parts)."<br />";
			$collection_non_php_parts = [];
			// echo "include_phpsteper collection non php parts arter initialisation count -- ".count($collection_non_php_parts)."<br />";
			$collection_any_comments = [];
			$number_of_expressions = [0];
			$is_library = TRUE;
			$file_name = $path_to_file; 
			// echo "include_phpsteper code new_name = $new_name <br />";
			// echo "include_phpsteper file_name $file_name <br />";
			$constatns["__file____phpsteper"] = ROOT."/".$file_name;
			$_POST['file'] = $path_to_file;
			$_POST['condition'] = $condition;
			$_POST['constants'] = $constants;
			$_POST['functions'] = $functions;
			$clone_condition = $condition;
			$clone_constants = get_defined_constants(TRUE)['user'];
			$clone_functions = $functions;
			// echo "include_phpsteper -befor call parse_file <br />";
			// echo "file_name is $file_name <br />";
			// var_dump_html($_POST);
			// echo "include_phpsteper == number_of_expressions -- ".$number_of_expressions[0]." count(functions) -- ".count($functions)."<br />";
			// echo "include 1";
			// var_dump_html($functions);
			// echo "<br />";
			$not_return = 0;
			if (file_exists(ROOT."/".$path_to_file)) {
				// echo ("parse_file -- ".ROOT."/".$file_for_parse.'<br>');
				parse_file ($path_to_file,FALSE);
			} else {
				$not_return = 1;
			}		
			$clone_is_library = $is_library;
			// echo "include 2";
			// echo "<br />";
			// echo "include_phpsteper collection non php parts after parse_file count -- ".count($collection_non_php_parts)."<br />";
			foreach ($var_for_cash as $name_of_var) {
				$new_name =  $name_of_var.$clone_break_parse_file;
				$$name_of_var = $cash[$new_name]; 
			}
			$is_library = ($number_of_expressions[0] === count($functions)? TRUE : FALSE );
/*			if ($is_library) {
				echo "$file_name is library <br />";
			} else {
				echo "$file_name isn't library <br />";
			}*/
			// echo "indlude_phpsteper after parse_file == number_of expression -- ".$number_of_expressions[0]." count(functions) -- ".count($functions)."<br />";
			// echo "include_phpsteper exit to file_name $file_name <br />";
			$constants['__file____phpsteper'] = $cash['constants']['__file____phpsteper'];
			$new_name = "_POST".$clone_break_parse_file;
			// echo "include_phpsteper decode new_name = $new_name <br />";
			$_POST = $cash[$new_name]; 
			$include_deep_parse--;
		}
		$arr_for_extra = ['path_to_file','clone_constants','clone_functions','clone_condition','clone_is_library'];
		foreach ($arr_for_extra as $val) {
			$extra[$val] = $$val;
		}

		if (isset($clone_constants)) {
			foreach($var_name_for_pass as $var_name) {
				$var_name = "clone_".$var_name;
				unset($$var_name);			
			}	
		}

		if (!$not_return) {
			return [TRUE,$extra];
		}
	}

	function include_phpsteper_show($func_parts,$extra) {

		debug (__FUNCTION__);
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
				$name_core_file_for_include,
				$include_deep_parse,
				$is_library,
				$included_files,
				$file_name;

		foreach ($extra as $key => $val) {
			$$key = $val;
		}

		$data['file'] = $path_to_file;
		foreach ($var_name_for_pass as $var_name) {
			$poperty_name = $var_name;
			if (isset($clone_constants)) {
				$var_name = "clone_".$var_name;				
			}
			if ($$var_name !== NULL) {
				$data[$poperty_name] = $$var_name;
			}
		}

		// echo "319 <br />";
		// var_dump_html($functions);

		$json = json_encode($data,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_FORCE_OBJECT);
		// echo "include_phpsteper ".$arr['arguments'][0]." <br />";
		// var_dump_html($json);
		$text_link = $func_parts['arguments'][0];
		if (isset($clone_is_library)&&$clone_is_library) {
			$text_link = "<i>".$text_link."</i>";
		}
		$link = '<a class="a" href=\'javascript:sendData('.$json.');\'>'.$text_link.'</a>';
		// echo (show_tag($link));
		$local_pattern = "#".preg_quote($func_parts['arguments'][0],"#")."#";
		//echo "<br> pattern - ".$pattern." endpattern";
		//echo $string;
		//echo $link;
		$return['pattern'] = $local_pattern;
		$return['inclusion'] = $link;
		//echo $string;
		return $return;
	}

	function dirname_phpsteper($string) {
		debug (__FUNCTION__);
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
		debug (__FUNCTION__);
		$return = include_phpsteper ($arg);
		return $return;
	}


	function require_once_phpsteper ($arg) {
		debug (__FUNCTION__);
		$return = include_phpsteper ($arg,1);
		return $return;
	}

	function include_once_phpsteper ($arg) {
		debug (__FUNCTION__);
		$return = include_phpsteper ($arg,1);
		return $return;
	}

	function if_phpsteper ($arg) {
		debug (__FUNCTION__);

		/*echo "if finded";
		var_dump($arg);
		echo "<br /> <br /> string";
		var_dump($string);
		echo "<br /> <br />";
*/
		global $number_of_expressions;
		global $pattern_alt;
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
				handler_expression($arg['condition'][$key_body],0,'condition');
				array_push($condition, array(addslashes(hide_any_quotation($arg['condition'][$key_body],1)),$file_name,true));
			}
			$dataCheck = $body;
			$new_body = preg_replace_callback($pattern_alt,"handler_expression", $body);	
			check();
			array_push($return['pattern'],"#".preg_quote($body,"#")."#");
			array_push($return['inclusion'],$new_body."\r\n");
			$condition[count($condition)-1][2] = false;
		}
		array_pop($number_of_expressions);
		$dataCheck = $reserve_dataCheck;
		return $return;
	}

	function for_parse_function () {

		debug (__FUNCTION__);
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
				$name_core_file_for_include,
				$include_deep_parse,
				$file_name;	

				$var_for_cash = ['collection_any_comments','number_of_expressions','file_name','dataCheck','collection_non_php_parts','collection_any_quotation'];
		foreach ($var_for_cash as $name_of_var) {
			$new_name =  $name_of_var.$clone_break_parse_file;
			$cash[$new_name] = $$name_of_var;  
		}		
		$cash['constants']['__file____phpsteper'] = $constants['__file____phpsteper'];
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
		// echo "include_phpsteper == number_of_expressions -- ".$number_of_expressions[0]." count(functions) -- ".count($functions)."<br />";
		parse_file ($path_to_file,FALSE);
		$is_library = ($number_of_expressions[0] == count($functions)? TRUE : FALSE );
/*			if ($is_library) {
			echo "$file_name is library <br />";
		} else {
			echo "$file_name isn't library <br />";
		}*/
		// echo "indlude_phpsteper after parse_file == number_of expression -- ".$number_of_expressions[0]." count(functions) -- ".count($functions)."<br />";
		foreach ($var_for_cash as $name_of_var) {
			$new_name =  $name_of_var.$clone_break_parse_file;
			$$name_of_var = $cash[$new_name]; 
		}
		$constants['__file____phpsteper'] = $cash['constants']['__file____phpsteper'];
		$new_name = "_POST".$clone_break_parse_file;
		$_POST = $cash[$new_name]; 
	}

	function isset_phpsteper ($var) {
		global $variable;
		if (isset($variable[$var])) {
			return TRUE;
		};
	}

	function function_phpsteper ($arg) {
		debug (__FUNCTION__);
		// var_dump_html($arg);
		global $functions,$file_name,$pattern_alt,$dataCheck,$number_of_expressions; 

		if ($functions === NULL) {
			$functions = [];
		}

		$return['pattern'] = [];
		$return['inclusion'] = [];

		// echo "function_phpsteper arg['key'][0] =".$arg['key'];
		preg_match("#function[\s]+([^\(]*)#",$arg['key'],$function_name);
		$arguments = $arg['arguments'];

		foreach ($arguments as &$val) {
			// echo "function_phpsteper function name0 = ".$function_name[0];
			// var_dump_html ($function_name);
			// echo "function phpsteper val = $val file_name = $file_name <br /> function_name0 = ".$function_name[1]."<br />";
			$val = hide_any_quotation($val,1);
		}
		$body = $arg['body'];
		foreach ($body as &$val) {
			// echo "function function val = $val <br />";
			$val = hide_any_quotation($val,1,1);
			$val = hide_any_comments($val,1,1); 
			$val = change_non_php($val,1,1);
			// echo "function function 22 val = $val <br />";
		}
		array_push($functions,[$function_name[1],$arguments,$body,$file_name]);
		$function_name_for_phpsteper = $function_name[1]."_phpsteper";
		$$function_name_for_phpsteper = 
		$new_body = preg_replace_callback($pattern_alt,function ($match) {
			$match[0] = preg_replace("#^\s*#","$0 &nbsp;&nbsp;&nbsp;&nbsp;",$match[0]);
			return $match[0];
					},$arg['body'][0]);	
		array_push($return['pattern'],"#".preg_quote($arg['body'][0],"#")."#");
		array_push($return['inclusion'],$new_body);
		// echo "function_phpsteper";
		// var_dump_html($return);
		return $return;

	}

	function wtf ($file_for_write,$string) {
		debug (__FUNCTION__);
		$myfile = fopen($file_for_write, "a") or die("Unable to open file!");
		fwrite($myfile, $string);
		fclose($myfile);	
	}

	function change_constants($string) {
		debug (__FUNCTION__);
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
		debug (__FUNCTION__);
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
		debug (__FUNCTION__);

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
		debug (__FUNCTION__);

		$array= [];
		
/*   		if ($key !== "") {
			$array['key'] = $key;
		}*/
		preg_match('#^[^\(]*#',$string,$array['key']);
		$array['key'] = hide_any_quotation($array['key'][0],1);	

		$array['arguments'][0] = "";
		$arr_patt_func_non_parentheses = ["echo","include","include_once","require","require_once","die"];
		foreach ($arr_patt_func_non_parentheses as $val) {
			if ($key == $val) {
				$string_check = preg_replace("#\s*".make_pattern_count_parentheses()."*\s*#","",$string);
				if (!preg_match("#".$val.";*$#",$string_check)) {
					preg_match("#".$val."([\s\S]*);*#",$string,$match);
					echo "678 if without parenthesis <br />";
					$array['arguments'][0] = hide_any_quotation($match[1],1); 
				}	
			}
		}

		if ($array['arguments'][0] === "") {
			preg_match('/'.make_pattern_count_parentheses().'/',$string,$argstr);
			if (isset($argstr[0])) {
				$argstr[0] = preg_replace("#^\(|\)$#","",$argstr[0]);
				$argarr = explode(",",$argstr[0]);
				$array['arguments'] = $argarr;
				$array['arguments'] = hide_any_quotation_arr ($array['arguments'],1);	
			}
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
			// echo $string."<br />";
			$exists_braces = preg_match('/'.make_pattern_count_parentheses(100,"{").'/',$string,$braces);
			if ($exists_braces) {
				$pos_braces = strpos($string,$braces[0]);
			}
			if ($exists_parentheses||$exists_braces) {
				if ($pos_parentheses < $pos_braces) {
					$string = preg_replace("#".preg_quote($parentheses[0],"#")."#","",$string,1);
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

	function check_pattern ($pattern_for_check,$string_for_check) {
		debug (__FUNCTION__);
		$pattern_for_check = trim($pattern_for_check,"#");	
		$pattern_for_check = stripslashes($pattern_for_check);
		$string_for_check = stripslashes($string_for_check);
		$start = 0;
		$length = strlen($pattern_for_check);
		echo "check pattern -- 11length - $length <br />";
		var_dump_html($pattern_for_check);
		var_dump_html($string_for_check);
		$length_befor = 0;
		$i = 0;
		$limit = 100;
		$length_last_lose = $length;
		while (($length !== $length_befor)&&($i<$limit)) {
			$i++;
			echo "check pattern -- length - $length <br />";
			$length_befor = $length;
			if (/*preg_match("#".substr($pattern_for_check,$start,$length)."#",$string_for_check)*/strpos($string_for_check,substr($pattern_for_check,$start,$length)) !== FALSE) {
				$length = $length+(($length_last_lose-$length)-(($length_last_lose-$length)%2))/2;
			} else {
				$length_last_lose = $length;
				$length = $length-($length-($length%2))/2;
			}
			if ($i == ($limit-1)) {
				echo "<b> perebor </b>";
			}
		}
		echo "check_pattern -- length - $length <br /> part of pattern - ".substr($pattern_for_check,0,$length)."<br />";
		// $pattern_for_check = stripslashes($pattern_for_check);
		// $string_for_check = preg_quote($string_for_check,"#");
		$begin = strpos($string_for_check,substr($pattern_for_check,0,$length-1));
		if ($begin === FALSE) {
			echo 'begin is false <br />';
		}
//		echo "diff letter from pattern --".substr($pattern_for_check,0,10)."++ from string --".substr($string_for_check,$begin,10)."++ <br />";
		echo "diff letter from pattern --|".substr($pattern_for_check,$length,15)."|++ from string --|".substr($string_for_check,$begin+$length,15)."|++ <br />";
	}	

	function change_frag ($pattern_for_frag,$inclusion,$string) {
		debug (__FUNCTION__);

		global $number_of_expressions;
		if (is_array($pattern_for_frag)) {
			foreach ($pattern_for_frag as $k => &$val) {
				$val = htmlentities($val);
			}
		}
		else {
			$pattern_for_frag = htmlentities($pattern_for_frag);
		}
		// echo "change_frag -- check pattern befor and after htmlentities";
		// check_pattern($xxx[0],$pattern_for_frag[0]);
		$string = hide_any_quotation($string,1);
		$string = htmlentities($string);
		$return = preg_replace($pattern_for_frag, $inclusion, $string,-1,$count);
		// if ($number_of_expressions[0] < 2) {
			// echo "change_frag -- count - $count <br /> ";
			// var_dump_html($string);
			// var_dump_html($pattern_for_frag);
			// check_pattern ($pattern_for_frag[0],$string);
		// }
		return $return;
	}

	function handler_expression ($match,$echo = 1,$type = "") {
		debug (__FUNCTION__);

		global $correspond_table,$file_name,$name_core_file_for_include,$is_library,$include_deep_parse;
		global $number_of_expressions;
		global $dataCheck;

	
		$return_expression = "";
/*		if (preg_match("#WP_CONTENT_DIR#",$match[0])) {
			echo "handler_experssion !!!!!! $file_name !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1finded WP_CONTENT_DIR <br />";
		};*/
		echo "match".$match[0]."<br />";
		echo "file_name".$file_name."<br />";
		check($match[0]);	


		//delete expression equal ";" or contain only \s sings
		if ((trim($match[0]) == ";")||(trim($match[0]) == "")) {
			echo "831 <br />";
			return "";
		}

		// if ($number_of_expressions == [1]) {
/*			echo "<br /> handler_expression - ";
			var_dump($number_of_expressions);
			echo " <br />"; */
/*			if ($number_of_expressions[0] == 2) {
				var_dump_html($match[0]);
			}*/
		// }
		if (!isset($included_deep_parse)) {
			$included_deep_parse = 0;
		}
		if ($include_deep_parse < 1) {
			echo ("752 ".$match[0]."<br /> <br />");



			++$number_of_expressions[count($number_of_expressions)-1];
			//var_dump($number_of_expressions);



			//=== Check type of expression;
			$deep = 10;
			$parenthesis = make_pattern_count_parentheses ($deep,"(");
			$braces = make_pattern_count_parentheses ($deep,"{");
			$check_type = preg_replace("#\-\-\_\_\d{6}\_\_\-\-|\-\-\=\=\d{6}\=\=\-\-#","",$match[0]);
			$check_type = trim($check_type);
			echo "857 <br />";
			if (preg_match("#^\!#",$check_type,$not)) {
				handler_expression(ltrim($match[0],"!"),1,'not');
			}
			echo "861 <br />";
			if (preg_match("#^\\$\w+#",$check_type,$variable)) {
				echo $variable[0];		
			}
			echo "865 <br />";
			if (preg_match("#^\w+#",$check_type,$word)) {
				$key = $word[0];
				$key_inclusion = "<span style='".$correspond_table[$key][0]."'>".$key."</span>";
				if ($key !== "function") {
					$is_library = FALSE;
				}
				$arrfunc = parse_func ($match[0],$key);
				if (function_exists($key."_phpsteper")) {
					$func_name = $key."_phpsteper";
					$return_all = $func_name($arrfunc);
					// echo "function handler_expression key._phpsteper return";
					// var_dump_html($return);
					// echo "<br />";
				}
					//$arrfunc is all function's parts in arr. $arrfunc = [key,arguments,body];
					//$mathc[0] is string with expression
				if (function_exists($key."_phpsteper_show")) {
					$func_name = $key."_phpsteper_show";
					$return = $func_name($arrfunc,$return_all[1]);
					if (($return)&&($return['pattern'] !== "")) {
						// if ($number_of_expressions[0] < 2) {
							// echo "handler_expression -- get return from func_name";
							// var_dump_html($return);
							// echo "<br />";
						// }

					$match[0] = change_frag($return['pattern'],$return['inclusion'],$match[0]);
					} else 
					{
						$match[0] = htmlentities($match[0]);
					}
				} else 
				{
						$match[0] = htmlentities($match[0]);
				}
			}
			$match[0] = preg_replace("#".preg_quote($key,"#")."#",$key_inclusion,$match[0],1);	
			echo "903 <br />";
			if (preg_match("#\\$*\w+\s*".$parenthesis."\s*".$braces."#",$match[0],$function)) {
				echo $function[0]."<br />";
			}
			//part for key() {} type of expression;;

		}


		/*find keypart in space befor parentheses and seve in $ma;
*/		
		// echo $match[0];
		// $count = 0;
		// assign $m all befor first parenthesis
		// preg_match("#^[^\(]*#",$match[0],$m);
		// assign $ma key part if finded correspond in $correspond_table and $m 
		/*echo 'make_pattern_arr ';
		var_dump(make_pattern_arr($correspond_table));
		echo "<br /> <br />";*/
		// $ma = preg_replace_callback(make_pattern_arr($correspond_table),"key_part",$m[0],1,$count);
		// if (!$count) {
			// $match[0] = htmlentities($match[0]);
			// $is_library = FALSE;
			// echo "handler_expression: no key part <br /> match[0] = ".$match[0]."<br />";
		// }
		// if keypart exists
		// else {
			// replace all befor first parenthesis by key part
			
			// assign $key 	last defined key ( key defined in key_part function by key_def() function)
			// $key = key_def();
			// if ($key !== "function") {
				// $is_library = FALSE;
			// }


			// $arrfunc = parse_func ($match[0],$key);
			// echo "handler_expression <br />";
			// var_dump_html($arrfunc);
			// if ($name_core_file_for_include != $file_name) {
/*			if ($file_name == "wp-includes/pomo/mo.php" ) {
				echo $number_of_expressions[count($number_of_expressions)-1]."   ";
				echo "handler_expression -- $key <br />";
			}*/
/*			if ($number_of_expressions[0] == 2) {
				var_dump_html($match[0]);
				echo "<br />";
			}*/
			// if (function_exists($key."_phpsteper")) {
				// $func_name = $key."_phpsteper";
				//$arrfunc is all function's parts in arr. $arrfunc = [key,arguments,body];
				//$mathc[0] is string with expression
				// $return = $func_name($arrfunc);
				// if (($return)&&($return['pattern'] !== "")) {
					// if ($number_of_expressions[0] < 2) {
						// echo "handler_expression -- get return from func_name";
						// var_dump_html($return);
						// echo "<br />";
					// }

					// $match[0] = change_frag($return['pattern'],$return['inclusion'],$match[0]);
				// } else 
				// {
					// $match[0] = htmlentities($match[0]);
				// }

			// } else {

					// $match[0] = htmlentities($match[0]);
			// }
			// $match[0] = preg_replace(htmlentities("#".preg_quote($m[0],"#")."#"),$ma,$match[0]);

		// }
		//$match[0] = preg_replace("#^\h*\R#","\r\n .".$number_of_expressions[count($number_of_expressions)-1],$match[0],1,$count);
		//if (!$count) {
		/*echo "number_of_expressions ";	
		var_dump($number_of_expressions);
		echo "<br />";
		var_dump($match[0]);
*/		// var_dump($match[1]);
		// var_dump($match[2]);
		//echo "<br />";
		$number_of_expressions_string = "";
		foreach( $number_of_expressions as $val) {
			$number_of_expressions_string = "&nbsp;&nbsp;&nbsp;&nbsp;".$number_of_expressions_string;
			$number_of_expressions_string .= $val.",";
		}
		$match[0] = preg_replace("#^(\s)*#","$0 "."$number_of_expressions_string ",$match[0]);
			// $match[0] = $number_of_expressions[count($number_of_expressions)-1]." ".$match[0];
		// }
		// echo "handler_expression == ".$number_of_expressions[0]."  <br />";
		echo "function handler_expression end <br />";
		echo $match[0]."<br />";
		if ($echo) {
			return  $match[0];
		}
	}

	function make_pattern_arr ($arr) {
		debug (__FUNCTION__);
		$pattern_arr = [];
		foreach ($arr as $k => $v) {
			array_push($pattern_arr,"#\b".preg_quote($k,"#")."\b#");
		}
		//var_dump ($pattern_arr);
		return $pattern_arr;
	}

	function make_pattern () {
		debug (__FUNCTION__);

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
		debug (__FUNCTION__);

			$string = htmlentities($string);
			$string = nl2br ($string);
			return $string;
	}

	function make_pattern_count_parentheses ($count = 100,$parentheses = "(") {
		debug (__FUNCTION__);
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
		debug (__FUNCTION__);


		global $dataCheck; 
		$count = 0;
		if ($data == NULL) {
			$data_ = preg_replace("#\s*#","",$data);
			$dataCheck_ = preg_replace("#\s*#","",$dataCheck);
			if (($data_ !== "")||($dataCheck_ !== "")){
				echo "<br> $data";
				echo "<b><br /> dataCheck: $dataCheck </b>";
			}
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
		debug (__FUNCTION__);

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
		debug (__FUNCTION__);
		$count = collect_non_php_parts($match);
		$count = str_pad($count,6,'0',STR_PAD_LEFT);	
		return "; --__".$count."__-- ";
	}

	function change_non_php($string,$htmlswitch = 0,$without_color = 0) {
		debug (__FUNCTION__);

		global $collection_non_php_parts;
		if (!$htmlswitch) {
			replace_non_php_parts ();
			$string = preg_replace_callback ("#(^[\s\S\R]*?\<\?php)|(\?\>[\s\S\R]*?\<\?php)|(\?\>[\s\S\R]*?$)#","replace_non_php_parts",$string);	
			//echo $string;
		}
		else {
/*			while(1) {
				if (preg_match("#\-\-\_\_\d{6}\_\_\-\-#",$string,$m,PREG_OFFSET_CAPTURE,$m[0][1]+1)) {
					echo show_tag(substr($string,0,$m[0][1]+strlen($m[0][0])))."<br />";
					echo "<b> big break <br /> line <br /> -------------------------------------------------------------------------</b><br />";
					// var_dump_html($match);
				}else {
					break;
				};
			}*/
			if ($without_color) {
				$string = preg_replace_callback ("#\-\-\_\_\d{6}\_\_\-\-#", function ($match) { 
					global $collection_non_php_parts,$file_name; 
					$index = trim($match[0],"-_"); 
					// echo $match[0]." -- change_non_php $file_name ".ltrim($index,"0")."<br />";
					return $collection_non_php_parts[ltrim($index,"0")-1]; 
				},$string);
			} else {
				$string = preg_replace_callback ("#\-\-\_\_\d{6}\_\_\-\-#", function ($match) { 
					global $collection_non_php_parts,$file_name; 
					$index = trim($match[0],"-_"); 
					// echo $match[0]." -- change_non_php $file_name ".ltrim($index,"0")."<br />";
					return "<span style='color:darkslategray;'>".htmlentities($collection_non_php_parts[ltrim($index,"0")-1])."</span>"; 
				},$string);
			}
		}
		return $string;
	}

	function collect_any_comments ($match = NULL) {
		debug (__FUNCTION__);

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
		debug (__FUNCTION__);
		//echo "COMMENTS <br /> $match[0] <br />";
		if (preg_match("#^(\'|\")[\s\S]*?(\'|\")$#",$match[0]) ) {
			return $match[0];
		} else {
		$count = collect_any_comments($match);
		$count = str_pad($count,6,'0',STR_PAD_LEFT);	
		return " --==".$count."==-- ";
		}
	}

	function hide_any_comments ($string,$comments_switch = 0,$without_color = 0) {
		debug (__FUNCTION__);
			global $collection_any_comments;
		if (!$comments_switch) {
			replace_any_comments ();
			$string = preg_replace_callback ("#(\/\*[\S\s]*?\*\/)|(\/\/[\S\s]*?\R)|\"[^\"]*\"|\'[^\']*\'#","replace_any_comments",$string);	
			//echo $string;
		}
		else {
			if ($without_color) {
				$string = preg_replace_callback ("#\-\-\=\=\d{6}\=\=\-\-#", function ($match) { 
					global $collection_any_comments;
					$index = trim($match[0],"=-"); 
					return $collection_any_comments[ltrim($index,"0")-1]; 
				},$string);
			} else 
			{
				$string = preg_replace_callback ("#\-\-\=\=\d{6}\=\=\-\-#", function ($match) { 
					global $collection_any_comments;
					$index = trim($match[0],"=-"); 
					return "<span style='color:slategray;'>".htmlentities($collection_any_comments[ltrim($index,"0")-1])."</span>"; 
				},$string);

			}
		}
		return $string;
		
	}

	function collect_any_quotation ($match = NULL) {
		debug (__FUNCTION__);

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
		debug (__FUNCTION__);
		//echo "<br /> ".$match[0]."<br />";
		$count = collect_any_quotation($match);
		$count = str_pad($count,6,'0',STR_PAD_LEFT);	
		return "--++".$count."++--";
	}

	function hide_any_quotation ($string,$quotation_switch = 0,$without_color = 0) {
		debug (__FUNCTION__);
			global $collection_any_quotation;
			// echo "hide_any_quotation $string <b> end </b><br />";
		if (!$quotation_switch) {
			echo "1258 string $string for code<br />";
			replace_any_quotation ();
			$string = preg_replace_callback ("#\"[^\"]*\"|\'[^\']*\'#","replace_any_quotation",$string);	
			//echo $string;
		}
		else {
			// echo "hide_any_quotation 1 $string <br />";
			if ($without_color){
				echo "1266 string $string for encode without color <br />";
				$string = preg_replace_callback ("#\-\-\+\+\d{6}\+\+\-\-#", function ($match) { 
					global $collection_any_quotation; 
					$index = trim($match[0],"+-"); 
					return $collection_any_quotation[ltrim($index,"0")-1]; 
				},$string);
			} else {
				echo "1266 string $string for encode color <br />";
				$string = preg_replace_callback ("#\-\-\+\+\d{6}\+\+\-\-#", function ($match) { 
					global $collection_any_quotation; 
					$index = trim($match[0],"+-"); 
					return htmlentities($collection_any_quotation[ltrim($index,"0")-1]); 
				},$string);
			}
		}
		return $string;
		
	}

	function hide_any_quotation_arr ($arr_str,$quotation_switch) { 
		debug (__FUNCTION__);
		foreach ($arr_str as &$val) {
			$val = hide_any_quotation ($val,$quotation_switch);
		}
		return $arr_str;
	}

	function html_formating ($string) {
		debug (__FUNCTION__);
		$string = str_replace(" ","&nbsp;&nbsp;",$string);
		$string = nl2br($string);
		return $string;
	}

	function var_dump_html ($var) {
		debug (__FUNCTION__);
		ob_start("html_formating");
		var_dump($var);
		ob_end_flush();
	}

	function pass_data ($name_data) {
		debug (__FUNCTION__);

		global $_POST;
		if (isset($_POST[$name_data])&&$_POST[$name_data] !== "" ){
			$_POST[$name_data] = json_decode($_POST[$name_data],JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_FORCE_OBJECT);
			return $_POST[$name_data];
		} 
	}
	
	function correct_if ($string) {
		debug (__FUNCTION__);
		$deep = 10;
		$parenthesis = make_pattern_count_parentheses ($deep,"(");
		$string = preg_replace("#((if|switch|while|for|foreach)\s*".$parenthesis."\s*)\:#","$1 {",$string) ;
		$string = preg_replace("#end(if|switch|while|for|foreach)\s*\;#","}",$string);
		$string = preg_replace("#(else)\s*\:#","} $1 \r\n {",$string);
		$string = preg_replace("#([^}\s])\s*((elseif|else\s*if)\s*".$parenthesis.")\s*\:#","$1 } $2 \r\n { ",$string);
		return $string;
	}

	function parse_file ($file_for_parse,$echo = TRUE) {
		debug (__FUNCTION__);

		global $pattern_alt,$break_parse_file,$file_name,$collection_non_php_parts;
		$break_parse_file++;
		//show name presented file
		if (file_exists(ROOT."/".$file_for_parse)) {
			// echo ("parse_file -- ".ROOT."/".$file_for_parse.'<br>');
		} else {
			 die (" file not exist $file_for_parse ");
		}
		$file = fopen(ROOT."/".$file_for_parse,"r");
		while (!feof($file)){
			$string = fread($file,filesize($file_for_parse)+1);
			//non php text to -=000000=-
			$string = change_non_php($string,0);
			//comments to -_000000_-
			$string = hide_any_comments($string,0);
			//$string = preg_replace_callback('/\&lt\;\?php(.|\R)*?(\?\&gt\;|$)/', "php_section", $string);
			// echo $pattern_alt;
			// echo "parse_file hide_any_quotation open <br />";
			$string = hide_any_quotation($string,0);
			$string = correct_if($string);
			check($string);
			$string = preg_replace_callback($pattern_alt,"handler_expression", $string);
			check();
			// echo "parse_file hide_any_quotation close <br />";
			if ($echo) {
			echo "1353 string $string <br />";
			$string = hide_any_quotation($string,1);
			// echo "parse_file ------------------ file_name = $file_name, count(collection_non_php_parts) =".count($collection_non_php_parts)."<br />";
			$string = change_non_php($string,1);
			$string = hide_any_comments($string,1);
			/* echo "<br /> collection_non_php_parts <br />";
			var_dump($collection_non_php_parts);
			echo " <br />";*/
				echo "result <br> ======================== <br> <br> <br>";
				echo nl2br($string);
			}
		}
		fclose($file);
	}

	function main () {
		// phpinfo();
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
				$name_core_file_for_include,
				$pattern_alt,
				$is_library,
				$debug,
				$included_files,
				$variables,
				$variables_info,
				$file_name;

		$debug = 1;

		debug (__FUNCTION__);

		$dataCheck = "";
		$collection_non_php_parts = [];
		$collection_any_comments = [];
		$variables = [];
		$variables_info = [];
		$number_of_expressions = [0];
		$break_parse_file = 0;
		$is_library = TRUE;

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
		$included_files = [$file_name];
		$name_core_file_for_include = $file_name;
		var_dump_html($_POST);
		echo "<br />";
						

//alternative pattern
			$deep = 10;
			$parenthesis = make_pattern_count_parentheses ($deep,"(");
			$braces = make_pattern_count_parentheses ($deep,"{");
			$pattern_alt = "#([^\(\{\;]*".$parenthesis.")*[^\{\;]*".$braces."*(\s*(else|else\s*if)\s*(".$parenthesis."\s*)*".$braces.")*\;?#";

		if ((count($_POST)>0)&&($file_name = $_POST['file'])) {
			parse_file($file_name);
		};
	}

	function debug ($function_name) {
		global $debug;
		if ($debug) {
			echo $function_name."<br />";
		}
	}

	main();
	?>
	 </p>
</body>
</html>
