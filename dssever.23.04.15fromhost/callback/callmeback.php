<? // WR-CalMeBack v 1.2  //  26.09.09 г.  //  Miha-ingener@yandex.ru

error_reporting (E_ALL);


// --------------------------- конфигурирование -------------------------- //

$adminemail="info@dssever.ru";  // емайл админа - кому отсылать
$date=date("d.m.y"); // число.месяц.год
$time=date("H:i",time()+(60*60*3)); // часы:минуты:секунды
$backurl="http://www.dssever.ru";  // На какую страничку переходит после отправки письма
$back="<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'><meta http-equiv='Content-Language' content='ru'></head><body><center>Вернитесь <a href='javascript:history.back(1)'><B>назад</B></a>"; // Удобная строка
// ---------------------------------------------------------------------- //

function replacer ($text) { // ФУНКЦИЯ очистки кода
$text=str_replace("&#032;",' ',$text);
$text=str_replace(">",'&gt;',$text);
$text=str_replace("<",'&lt;',$text);
$text=str_replace("\"",'&quot;',$text);
$text=preg_replace("/\n\n/",'<p>',$text);
$text=preg_replace("/\n/",'<br>',$text);
$text=preg_replace("/\\\$/",'&#036;',$text);
$text=preg_replace("/\r/",'',$text);
$text=preg_replace("/\\\/",'&#092;',$text);
$text=str_replace("\r\n","<br> ",$text);
$text=str_replace("\n\n",'<p>',$text);
$text=str_replace("\n",'<br> ',$text);
$text=str_replace("\t",'',$text);
$text=str_replace("\r",'',$text);
$text=str_replace('   ',' ',$text);
do {$text=str_replace("<br><br><br>","<br>",$text);} while (preg_match("/<br><br><br><br>/i",$text));
do {$text=str_replace("   "," ",$text);} while (preg_match("/   /i",$text));
$text=str_replace("\t",' ',$text);
$text=str_replace("\r",' ',$text);
$text=str_replace('   ',' ',$text);
$text=rtrim($text);
return $text; }


if (isset($_POST['name'])) {

if (isset($_POST['ph1'])) {$ph1=replacer($_POST['ph1']);} else {$ph1="";} // Код страны
if (isset($_POST['ph2'])) {$ph2=replacer($_POST['ph2']);} else {$ph2="";} // Код города
if (isset($_POST['ph3'])) {$ph3=replacer($_POST['ph3']);} else {$ph3="";} // Номер
if (isset($_POST['ph4'])) {$ph4=replacer($_POST['ph4']);} else {$ph4="";} // Добавочный

//if ((!ctype_digit($ph1)) or (strlen($ph1)>10)) {exit("<B>$back. Номер телефона может содержать только цифры</B>");}
if ((!ctype_digit($ph2)) or (strlen($ph2)>15)) {exit("<B>$back. Номер телефона может содержать только цифры</B>");}
if ((!ctype_digit($ph3)) or (strlen($ph3)>10)) {exit("<B>$back. Номер телефона может содержать только цифры</B>");}
//if ((!ctype_digit($ph4)) or (strlen($ph4)>10)) {exit("<B>$back. Номер телефона может содержать только цифры</B>");}

if (isset($_POST['timer'])) {$timer=replacer($_POST['timer']);} else {$timer="";} // Желаемое время звонка
//if ($timer=="") {exit("<center>Вернитесь <a href='javascript:history.back(1)'><B>назад</B></a>. Вы не указали желаемое время звонка.");}

if (isset($_POST['name'])) {$name=replacer($_POST['name']);} else {$name="";} // Имя контактного лица
if (isset($_POST['subj'])) {$subj=replacer($_POST['subj']);} else {$subj="";} // Тема звонка
if (isset($_POST['info'])) {$info=replacer($_POST['info']);} else {$info="";} // Дополнительная информация

if (strlen($name)<1 or strlen($name)>40) {exit("<B>$back. Вы не ввели имя, или ввели слишком длинное имя</B>");}
//if (strlen($subj)<1 or strlen($subj)>40) {exit("<B>$back. Вы не указали тему звонка или ввели слишком длинную тему</B>");}
if (strlen($info)<1 or strlen($info)>1000) {exit("<B>$back. Вы не указали дополнительную информацию, или ввели слишком много доп. информации</B>");}


// отправка админу сообщения
$headers=null; // Настройки для отправки писем
$headers.="Content-Type: text/html; charset=utf-8\r\n";
$headers.="From: Администратор <".$adminemail.">\r\n";
$headers.="X-Mailer: PHP/".phpversion()."\r\n";

$host=$_SERVER["HTTP_HOST"]; $self=$_SERVER["PHP_SELF"];
$cmburl="$backurl";
//$cmburl="http://$host$self";
//$cmburl=str_replace("callmeback.php", "$backurl", $cmburl);

// Собираем всю информацию в теле письма
$allmsg="<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'></head>
<body><BR><BR><center>
<table width=600><tr><td height='25' bgcolor='#71B8FF' align=center>
<font color=white>Клиент хочет <B>чтобы ему позвонили!</B></font></td></tr></table><br>

<table border=0 cellpadding=0 cellspacing=0 width=600 bgcolor=#71B8FF><tr><td width=964>
<table border=0 cellpadding=3 cellspacing=1 width='100%'>

<tr bgcolor='#D9ECFF' height=24><td width=30%>Имя контактного лица</td><td width=70%>$name</td></tr>
<tr bgcolor='#FFFFFF'><td>Номер телефона</td><td>

<table border=0 cellpadding=0 cellspacing=0><TR><TD>Код страны</TD><TD>Код города</TD><TD>Номер</TD><TD>Добавочный</TD></TR>
<TR align=center><TD><B>$ph1</B></TD><TD><B>$ph2</B></TD><TD><B>$ph3</B></TD><TD><B>$ph4</B></TD></TR></TABLE>
</td></tr>

<tr bgcolor='#D9ECFF' height=24><td>Дополнительная информация</td><td>$info</td></tr>
<tr bgcolor='#FFFFFF' height=24><td>Дата подачи заявки:</td><td>$time - $date</td></tr>

</table></td></tr></table><br>

<table width=600><tr><td height=25 bgcolor='#71B8FF' align='center'>
<a href='$cmburl'><font color='white'>Отправлено со страницы: $cmburl</font></a></td></tr></table>
<BR><BR><BR>
</body></html>";

// Отправляем письмо майлеру на съедение если разрешена отправка ;-)
mail("$adminemail", "$date $time Заказ обратного звонка от \"$name\"", $allmsg, $headers);
mail("mirtrudmaynow@gmail.com", "$date $time Заказ обратного звонка от \"$name\"", $allmsg, $headers);
mail("pavelkorn2000@yandex.ru", "$date $time Заказ обратного звонка от \"$name\"", $allmsg, $headers);

print "
<!DOCTYPE html>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<title>Запрос отправлен!</title>
<script language='Javascript'><!--
function reload() {location = \"$backurl\"}; setTimeout('reload()', 2000);
//-->
</script>
</head>
<body>
	<div style=\"margin:auto; text-align: center; margin-top: 50px; \">
		<div class=\"message\" style=\"display: inline; padding: 1%; color: white; font-size: 150%; border-radius: 5px;	background-color: LimeGreen;\">
			Ваш запрос отправлен.
		</div>
	</div>
</body>
</html>";

exit;

}
else {exit;}

?>
