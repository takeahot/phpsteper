<?php

	function check_url ($script = 0) {
		global $query;
		if ($strpos = strpos($query,"?")) {
			$query = substr($query, 0,$strpos);
		}		
		if (substr($query, 0,1) === '/') {
			$query = substr($query, 1);
		};
		if (substr($query, 0,8) === 'dssever/') {
			$query = substr($query, 8);
		}
		if ((strlen($query)<2)&&(substr($query, -1)==='a')) {
			$query = '/'.$query;	
		};
		if (substr($query,-2)==='/a') {
			$query = substr($query, 0,-1);
			if (!$script) {
				print "<script>".file_get_contents(ROOT.'php/'.$query.'admin-script.js')."</script>";
			}
		};
		if (substr($query,-1) !== '/') {
			$query = $query.'/';
		}
		if (strlen($query) === 1) {
			$query =  '';
		}
		return $query;
	};


	function check_ext ($ext,$path) {
		if (preg_match($ext,pathinfo($path)['extension'])) {
			return 1;
		}
		print pathinfo($path)['extension'].'<br>'.$path;
		return 0;
	}

	function check_upload ($ext,$new_path,$name_var = '') {
		print "<br> lib.php check_upload _FILES <pre>";
		var_dump($_FILES);
		print "<br> </pre>";
		print "<br> lib.php check_upload name_var -->".$name_var;
		if ($_FILES[$name_var]['error'] == UPLOAD_ERR_OK) {
			$name_file = $_FILES[$name_var]['name'];
			if (check_ext($ext,ROOT.$name_file)) {
				move_uploaded_file($_FILES[$name_var]['tmp_name'],ROOT.$new_path.$name_file);
				print "<br> lib.php check_upload работа с файлом закончена.";
				return $name_file;
			}
			else {
				print "Не подходяшее рсширение файла, загрузите файл с расширением $ext";
				return 0;
			}
		}
		else {
			print "addBulk".$_FILES['bulk']['error']."<br> Попробуйте ещё разок, файл не прилетел. <br>";
			return 0;
		}
	}

	function change_content ($num_obj,$num_elem,$content,$add_content = 0) {
		global $query;
		print "<br>  path to content.json".ROOT.'php/'.$query.'content.json';
		$json = file_get_contents(ROOT.'php/'.$query.'content.json');
		$data = json_decode($json);
		switch ($add_content) {
			case 3:
				$data = array_assoc_del($data,$num_obj);
				break;
			case 2:
				$num_obj = count($data);
				print "<br> В массиве на данный момент $num_obj объектов";
			case 1:
				$data = array_assoc_add($data,$num_obj);
			case 0;
				print "<br> content".$content;
				print "<br> num_elem $num_elem";
				print "<br> num_obj $num_obj";
				print "<br> content".$content;
				if ($num_elem>0) {
					if (!array_key_exists($num_elem-1,$data[$num_obj])) {
						$data[$num_obj] = array_pad($data[$num_obj], $num_elem, '');
					}
				}
				$data[$num_obj][$num_elem] = $content;
				break;
		}
		print "<br> <br> дата перед закодированием <pre>";
		var_dump($data);
		print " </pre> <br> <br>";
		$json = json_encode($data);
		$file = file_put_contents(ROOT.'php/'.$query.'content.json', $json);
		print "file ===> $file";
		return $num_obj;
	};

	function check_form ($text,$last = 0,$checkbox = 0) {
		if (!$last) {
			if (isset($_POST['name'])) {
				$check_post = $_POST['name'];
			}
			else {
				print "<br> check_form Post[name] not exists";
				return 0;
			}
		}
		else {
			end ($_POST);
			$check_post = key($_POST);
			reset ($_POST);
		}
		print "<br> check_post ".$check_post." last --> ".$last." text --> ".$text;
		if(!preg_match('/\d+/',$check_post,$match,PREG_OFFSET_CAPTURE)) {
			return 0;
		};
		print "<br> match <pre>";
		var_dump ($match);
		print "</pre> <br>";
		$num_sim = $match[0][1];
		if (!$checkbox) {
			print "<br> lib.php check_form num_sim=".$num_sim;
			if (substr($check_post,0,$num_sim) === $text) {
				print '"<br>lib.php check_form num_sim='.$num_sim;
				return $num_sim;			
			};
		}
		else {
			$val_sim = $match[0][0];
			preg_match('/\d+/',$check_post,$match,PREG_OFFSET_CAPTURE,$num_sim+strlen($val_sim));
			print ("checkbox ==>".substr($check_post,$num_sim+strlen($val_sim),$match[0][1]-($num_sim+strlen($val_sim))));
			if (substr($check_post,$num_sim+strlen($val_sim),$match[0][1]-$num_sim-strlen($val_sim)) === $text) {
				return $match[0][1]; 
			}
		}
		return 0;
	}

	function nc ($num_sim) {
		return $_POST['name'][$num_sim];	
	}

	function nb ($num_sim) {
		end ($_POST);
		$check_post = key ($_POST);
		reset ($_POST);
		preg_match("/\d+/",$check_post,$match,PREG_OFFSET_CAPTURE,$num_sim);
		return $match[0][0];
	}

	function array_assoc_add ($arr,$pos) {
		$arr_1 = array_slice($arr, 0, $pos);
		$arr_2 = array_slice($arr,$pos);
		$arr_1[] = [];
		$arr = array_merge($arr_1,$arr_2);
		print "<br> array_assoc_add <pre>";
		var_dump($arr);
		print "</pre> <br>";
		return $arr;
	}

	function array_assoc_del ($arr,$pos) {
		$arr_1 = array_slice($arr, 0, $pos);
		$arr_2 = array_slice($arr,$pos);
		array_shift($arr_2);	
		$arr = array_merge($arr_1,$arr_2);
		print "<br> array_assoc_del <pre>";
		var_dump($arr);
		print "</pre> <br>";
		return $arr;	
	}

?>