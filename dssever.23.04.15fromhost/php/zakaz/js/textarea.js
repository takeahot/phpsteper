window.onload = function () {

	var clearText = function () {
		if (document.getElementById('textarea').value == 'Здесь вы можете написать удобное для вас время звонка, язык, который вам хочется учить, уровень знания языка, удобное для вас место расположения школы.') {
			var elem = document.getElementById('textarea');
			elem.value = ''; 
			elem.style.color = 'gray'; 
		}
	}

	var putInfo = function () {
		info.push(document.URL.substr(pos+1,length));
		info[info.length - 1] = info[info.length -1].substr(info[info.length -1].search("=")+1);
		info[info.length - 1] = decodeURIComponent(info[info.length -1]);
		pos += length + 1;
	}

	var textarea = document.getElementsByTagName('textarea')[0];
	var pos = 0;
	var end = 0;
	var info = []; 
	var length;
	if ((pos = document.URL.search("\\?")) === -1) {
		textarea.addEventListener('focus',clearText,false);
	}
	else {
		while ((length = document.URL.substring(pos+1).search("&")) !== -1 ){
		putInfo();	
		}	
		length = document.URL.substring(pos+1).length;
		putInfo();
		textarea.value='Мне бы хотелось записаться на занятия '+info[0]+', которые будут проходить '+info[1]+'. Дополнительная информация: '+info[2];	
			textarea.style.color = 'gray'; 
	}
}