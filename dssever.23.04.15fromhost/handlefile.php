<?php

ini_set('display_errors',true);
error_reporting(E_ALL);

	do {
		define('ROOT', dirname(__FILE__)."/");
		// ===================== attention for lib.php ROOT is /dssever directory
		$query = substr($_SERVER['HTTP_REFERER'], strlen($_SERVER['HTTP_ORIGIN']));
		require ROOT.'php/lib.php';
		require ROOT.'php/header.php';
		$query = check_url(1);
		print "<br> Server <pre>";
		var_dump(substr($_SERVER['HTTP_REFERER'], strlen($_SERVER['HTTP_ORIGIN'])));
		print "</pre> <br>";
		print "<br> handlefile.php query ==> $query";
		print "<br> handlefile.php post <pre>";
		print_r ($_POST);
		print "</pre> <br>";
		if ((isset($_POST['name'])&&($_POST['name'] === 'addBulk')) || ($ns = check_form('addBulk',1))) {
			if (!($name_file = check_upload ('/csv/i','','bulk'))){
				break;
			}
			file_put_contents(ROOT.$name_file,mb_convert_encoding(file_get_contents(ROOT.$name_file),'UTF-8','CP1251'));
			$handle = fopen($name_file,'r');
			if ($handle !== FALSE) {
		    	while (($data = fgetcsv($handle, 4000, ";",'"')) !== FALSE) {
		    		$all_data[] = $data;
		    	}
		    	fclose($handle);
			}
			$json = json_encode($all_data);
			print 'перед тем как положить файл $all_data <pre>';
			var_dump($all_data);
			print '</pre>';
			file_put_contents(ROOT."php/".$query."content.json",$json);
			unlink($name_file);
		}
		elseif ($ns = check_form('addImg')) {
			if (!($name_file = check_upload ('/[png|jpeg|jpg|svg|gif|tiff|bmp]/i','img/','img'))){
				break;
			}		
			change_content(nc($ns),0,$name_file);
		}
		elseif ($ns = check_form('changeHeader')) {

		//	print "<br>".$_POST['name'][12]."<br>".$_POST['elem1'];

			change_content(nc($ns),'1',$_POST['elem1']);
		}
		elseif ($ns = check_form('changeText')) {

		//	print "<br>".$_POST['name'][10]."<br>".$_POST['elem2'];

			change_content(nc($ns),'2',$_POST['elem2']);
		}
		elseif ($ns = check_form('addElem',1)) {
			print "<br> query -- $query";
			if($nss = check_form('checkbox',1,1)) {
				print "<br> nss --> $nss";
				print "<br> substr".substr(key($_POST),0,4);
				if (substr(key($_POST),0,4) !== 'elem') {
					$key = 'elem'.nb($nss);
					print "<br> key".$key;
					$post = array ($key => 0);
					$post = array_merge($post,$_POST);	
					$_POST = $post;
					reset ($_POST);
				} 
			};
			print "<br> post <pre>";
			var_dump($post);
			print "</pre> <br>";
			print " nb(ns) --> ".nb($ns)." substr(key(post),4) -->".substr(key($_POST),4)." current(post) --> ".current($_POST);
			change_content(nb($ns),substr(key($_POST),4),current($_POST));
		}
		elseif (($query)&&($ns = check_form('addBlock',1))) {
			$num_block = nb($ns);	
			array_pop($_POST);
			$add_block = 1;
			while ($arr_post = each($_POST)) {
				print "<br> add_block".$add_block;
				change_content($num_block,substr($arr_post[0],4)+0,$arr_post[1],$add_block);
				$add_block = 0;
			}
		}
		elseif ($ns = check_form('addBlock')) {
			if (!($name_file = check_upload ('/[png|jpeg|jpg|svg|gif|tiff|bmp]/i','img/','elem0'))){
				break;
			}		
			print $ns;
			change_content(nc($ns),0,$name_file,1);
			change_content(nc($ns),'1',$_POST['elem1']);
			change_content(nc($ns),'2',$_POST['elem2']);
		}
		elseif ($ns = check_form('addFinalBlock')) {
			if (!($name_file = check_upload ('/[png|jpeg|jpg|svg|gif|tiff|bmp]/i','img/','elem0'))){
				break;
			}		

			$num_obj = change_content(nc($ns),0,$name_file,2);
			change_content($num_obj,'1',$_POST['elem1']);
			change_content($num_obj,'2',$_POST['elem2']);
		}
		elseif (($query)&&($ns = check_form('addFinalBlock',1))) {
			$num_block = nb($ns);	
			array_pop($_POST);
			$add_block = 2;
			reset ($_POST);
			while ($arr_post = each($_POST)) {
				print "<br> add_block".$add_block;
				print "<pre>";
				var_dump($arr_post);
				print "</pre>";
				$num_block = change_content($num_block,substr($arr_post[0],4)+0,$arr_post[1],$add_block);
				print "<br> num_block $num_block";
				$add_block = 0;
			}
		}
		elseif ($ns = check_form('deleteBlock')) {
			change_content(nc($ns),0,0,3);
		}
		elseif ($ns = check_form('deleteBlock',1)) {
			change_content(nb($ns),0,0,3);
		}
		elseif ($ns = check_form('getBulk',1)) {
			$json = file_get_contents(ROOT."php/".$query."content.json");	
			$all_data = json_decode($json);
			$fhandle = fopen(ROOT."php/".$query.'schedule.csv', 'w');
			foreach ($all_data as $data) {
    			fputcsv($fhandle, $data,';','"');
			}
			fclose($fhandle);
			file_put_contents(ROOT."php/".$query."schedule.csv",mb_convert_encoding(file_get_contents(ROOT."php/".$query."schedule.csv"),'CP1251','UTF-8'));
			print "<script> location = 'php/$query"."schedule.csv' </script>";
		}
		else {
			print_r($_POST);
			print "<br><br>";
		}
		$upload_dir = '/home/u107847058/public_html/dssever/';
		//if ($_FILES[])
		if (isset($_POST['text'])) {
			print "<br> handlefile.php post[text]".$_POST['text'];
		}
		else {
			print "<br> handlefile.php post[text] isnt exists";
		}
		if ((isset($_FILES['x']))&&(isset($_FILES['x']['temp_name']))) {
			print "<br> handlefile.php files[x][temp_name]".$_FILES['x']['temp_name'];
		}
		else {
			print "<br> handlefile.php files[x][temp_name] isnt exists";
		}
			print "<br> <pre>";
			var_dump($_SERVER); 	
			print "</pre>"; 
			print_r($_POST);
			//require (ROOT.'php/'."create-content.php");
			break;
	} while (0);
	print "<script>location = \"$query"."a\" </script>";
	require ('php/footer.php');
?>