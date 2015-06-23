<?php
class ObjectContent {
	
	public $content_arr = [];
	public $code = '';
	public $templatearr = [];
	public $color = ['#FFF592','#91CCFD','#C4FF8D','#FFA5A5','#A2FFCA'];

	public function cre ($ntmpl = 0,$count) {
		foreach($this->content_arr as $k => $v) {
			if ($ntmpl === 2) {
				if (($k>0)&&($k<8)) {
					if ($v) {
						$v = 'class="active"';
					}
					else {
						$v = '';
					}
				}
			}
			$name_var = "cont".$k;
			$$name_var = $v;
		}
		$colori = $count;
		$countcolor = count($this->color);
		while ($colori >= $countcolor) {
			$colori -= $countcolor;	
		}
		
		$this->templatearr[] = "<div class='infoblock elem$count'>
			<div class='img' style='background-image: url(/img/$cont0)'></div>
			<div class='info' style=\" background-color: ".$this->color[$colori].";\">
				<h2>$cont1</h2>
				<span>$cont2</span>
			</div>
		</div>" ;
		$this->templatearr[] = "<tr>
			<th colspan=\"6\">
				$cont0 
			</th>
		</tr>";
		if (isset($cont3)) {
			$this->templatearr[] = "<tr class=\"gray_cells\">
				<th>
					$cont0 
				</th>
				<th class=\"days\">
					<span $cont1>
						Пн
					</span>
					<span $cont2>
						Вт
					</span>
					<span $cont3>
						Ср
					</span>
					<span $cont4>
						Чт
					</span>
					<span $cont5>
						Пт
					</span>
					<span $cont6>
						Сб
					</span>
					<span $cont7>
						Вс
					</span>
				</th>
				<td>
					$cont8
				</td>
				<th>
					$cont9
				</th>
				<td>
					<a href=\"\zakaz?a=$cont0"."&b=$cont8"."&c=$cont9\"  rel=\"nofollow\" class=\"link_button little_button link_mark facebox\">
						Записаться
					</a>
				</td>
			</tr>";
}
$this->code = $this->templatearr[$ntmpl];	

}

function __construct ( $content_array,$ntmpl,$count) {
	$this->content_arr = $content_array;
	$this->cre($ntmpl,$count);
	print $this->code;
}
};
if (file_exists(ROOT."php/".$query."content.json")) {

	$file = file(ROOT."php/".$query."content.json")[0];
	$content = json_decode($file);
	$count = 0;
	foreach ($content as $arr) {
		$count_arr = count($arr);
		if ($count_arr === 3) {
			$ntmpl = 0;
		}
		elseif ($count_arr === 10) {
			$sum = '';
			for ($arri = 1; $arri < $count_arr; $arri++) {
				$sum = $sum.$arr[$arri];
			}
			if ($sum) {
				$ntmpl = 2;
			}
			else
			{
				$ntmpl = 1;
			}
		}
		$object = new ObjectContent($arr,$ntmpl,$count++);
	}

}
?>
