window.onload = function () {
	function typearr (ii,a,i) {
		i = i||0;
		var arr = [
			[
				'tr',
				'th',
				'',
				'pointer',
				[	
					['addBlock'+i],
					['Название:','text','elem0'],
					['Пн','checkbox','elem1'],
					['Вт','checkbox','elem2'],
					['Cр','checkbox','elem3'],
					['Чт','checkbox','elem4'],
					['Пт','checkbox','elem5'],
					['Сб','checkbox','elem6'],
					['Вс','checkbox','elem7'],
					['Время:','text','elem8'],
					['Доп. информация:','text','elem9']
				],
				[
					[c,0],
					[c,1,c,0],
					[c,1,c,1],
					[c,1,c,2],
					[c,1,c,3],
					[c,1,c,4],
					[c,1,c,5],
					[c,1,c,6],
					[c,2],
					[c,3]
				]
			]
		];
		return arr[ii][a];
	};

	var c = 'children';
	var submit = {};

	function fireElem (elem,nameEvent) {
		if (document.createEvent) {
			event = document.createEvent("HTMLEvents");
			event.initEvent(nameEvent, true, true);
		} 
		else {
			event = document.createEventObject();
			event.eventType = nameEvent;
		};

		event.eventName = nameEvent;

		if (document.createEvent) {
   			elem.dispatchEvent(event);
		}
		else {
   			elem.fireEvent("on" + event.eventType, event);
		}	
	}

	function d (obj,deep) {
		if (deep.length) {
			for (var i in deep) {
				obj = obj[deep[i]];
			}
		}
		else {
			obj = obj[deep];
		}
		return obj;
	}

	function schedule() {
		var tbody = document.getElementsByTagName('tbody');
		var colspan = [];
		tbody[0]&&(colspan = tbody[0].querySelectorAll('[colspan]'));
		for (var i = 0; colspan[i];i++) {
			colspan[i].colSpan = colspan[i].colSpan + 2;
		}
		var tr = document.getElementsByTagName('tr');
		for (var i = 0; tr[i]; i++) {
			tr[i].className = tr[i].className + ' ' + 'tr';
		}
	}

	schedule ();

	function Element () {


			for (var i = 0; arguments[i] !== undefined; i++) {
				this[this.arr[i]](arguments[i]);	
			}
		
	}		

		Element.prototype.arr = ['createElement','inner','addClass','placer','placerBefor','changeParentStyle','addEven'];

		Element.prototype.createElement = function (elem) {
			if (elem) {
				this.elem = document.createElement(elem);
				this.nodeName = elem;
			}
			else {
				console.log('createElement',elem);
			}
		}

		Element.prototype.inner = function (content) {
			if (content) {
			this.elem.innerHTML = content;
			}
			else {
				console.log('inner',content,this.elem.nodeName,this.elem.className);
			}
		}

		Element.prototype.addClass = function (cl) {
			if (cl) {
			this.elem.className = this.elem.className + ' ' + cl;	
			}
			else {
				console.log('addClass',cl,this.elem.nodeName,this.elem.className);
			}
		}

		Element.prototype.placer = function (place) {
			if (place) {
				place.insertBefore(this.elem,place.firstChild);
			}
			else {
				console.log('placer',place);
			}
		}

		Element.prototype.placerBefor = function (place) {
			if (place) {
				place.parentNode.insertBefore(this.elem,place);
			}
			else {
				console.log('placerBefor',place,this.elem.nodeName,this.elem.className);
			}
		}

		Element.prototype.changeParentStyle = function (x) {
			if (x) {
				if (this.elem.parentNode.style.position !== 'relative') {
					this.elem.parentNode.style.position = 'relative';
				}
			}
			else {
				console.log('changeParentStyle',x);
			}
		}

		Element.prototype.addAttr = function () {
			for (var i = 0; arguments[i]; i++) {
				this.elem.setAttribute(arguments[i][0],arguments[i][1]);	
			}
		}

		Element.prototype.addEven = function (menu) {
			if (menu) {
				this.elem.addEventListener('click',function (menu) {this.menu = new Menu(menu)}.bind(this.elem,menu),false);
			}
			else {
				console.log('addEven',menu);
			}
		}


	function Menu () {

		this.background = new Element('div','','menubackground',document.body);
		this.background.elem.addEventListener('click',function (e) {this.parentNode.removeChild(this); return false},false);

		this.menuArea = new Element ('div','','menuarea'); 
		this.background.elem.appendChild(this.menuArea.elem);
		this.placeElem = this.menuArea.elem;
		this.menuArea.elem.addEventListener('click',function (e) {e.stopPropagation();return false},false);

		this.length = 0;
		this.name = arguments[0][0];

		this.addForm = function (action,method,enctype,name) {
			this.form = new Element ('form');
			action = action||'/handlefile.php';
			switch (method) {
				case 1:
					method = 'GET';
					break;
				default:
					method = 'POST';
			}
			switch (enctype) {
				case 1:
					enctype = 'multipart/form-data';
					break;
				default:
					enctype = 'application/x-www-form-urlencoded';
			}
			var att = [['enctype',enctype],['action',action],['method',method]];
			this.form.addAttr(att[0],att[1],att[2]);
			this.menuArea.elem.appendChild(this.form.elem);
			this.placeElem = this.form.elem;
		};

		this.addInp = function (text,type,name,value) {
			var pre = 'pre'+this.length;
			var e = 'elem'+this.length;
			var p = 'p'+this.length;
			type = type||'text';
			name = name||(e);	 

			this[p] = new Element('p');
			this[pre] = new Element ('span',text);
			this[p].elem.appendChild(this[pre].elem);
			this[name] = new Element ('input');
			this[name].addAttr(['type',type],['name',name]);
			value&& (this[name].addAttr(['value',value]));
			this[p].elem.appendChild(this[name].elem);
			this.placeElem.appendChild(this[p].elem);
			this.length++;
		}

		this.con = function () {

			var file = 0;
			for (var i = 1; arguments[0][i]; i++) {
				(arguments[0][i][1]==='file') && (file=1);
			}
			this.addForm(0,0,file);

			for (var i = 1; arguments[0][i]; i++) {
				this.addInp(arguments[0][i][0],arguments[0][i][1],arguments[0][i][2],arguments[0][i][3]);
			}

			this.addInp ('','submit',this.name,'Отправить.');
			if (submit[this.name]) {
				fireElem(this[this.name].elem,'click');
			};
		}

		this.con.call(this,arguments[0]);

	}

	function AdminElement () {
		this.addElem = function (elem) {
			this.elem = elem;
		};
		Element.apply(this,arguments);
	}	

		AdminElement.prototype = Object.create(Element.prototype);
		AdminElement.prototype.constructor = AdminElement;
		AdminElement.prototype.arr = ['addElem','addClass','addEven'];


	var checkbox = '';
	var trContent = document.getElementsByClassName('tr');
	for (var i = 0; trContent[i];i++) {
		if (trContent[i].className === 'gray_cells tr') {
			for (var ii = 0; typearr(0,5)[ii];ii++) {
				if (typearr(0,5)[ii].length>2) {
					checkbox = 'checkbox'+ii;
				}
				else {
					chekcbox = '';
				}
				var v = new AdminElement(d(trContent[i],typearr(0,5)[ii]),typearr(0,3),[['addElem'+i+checkbox],typearr(0,4)[ii+1]]);	
			}
		} 
		else {
				var v = new AdminElement(d(trContent[i],typearr(0,5)[0]),typearr(0,3),[['addElem'+i],['Заголовок']]);	
		}
		//var img = new AdminElement(imgContent[i],'pointer',[['addImg'+i],['файл с изображением:','file','img']]);
		//var header = new  AdminElement(imgContent[i].nextElementSibling.firstElementChild,'pointer',[['changeHeader'+i],['Новый заголовок:','text','elem1']]);
		//var content = new AdminElement(imgContent[i].nextElementSibling.lastElementChild,'pointer',[['changeText'+i],['Новый текст:','text','elem2']]);
	}


	window.stl = new Element ('link','','',document.head);
	window.stl.addAttr(['rel','stylesheet'],['href','/css/admin.css']);

	var place = document.getElementsByClassName('footer')[0];
	if (!place) {
		place = document.body;
	}

	window.generalPlus = new Element('div','+','plus',place,'',1,[['addFinalBlock'+0],['Название:'],['Пн','checkbox'],['Вт','checkbox'],['Cр','checkbox'],['Чт','checkbox'],['Пт','checkbox'],['Сб','checkbox'],['Вс','checkbox'],['Время:'],['Доп. информация:']]);
	var fill = [['addBulk'+0],['Файл с данными:','file','bulk']];
	window.addBulk = new Element('div','Add bulk','addbulk plus',place,'','',fill);
	window.getBulk = new Element('div','Get bulk','getbulk plus',place,'','',[['getBulk0']]);
	submit.getBulk0 = 1;

	var ii = 0
	var places = [];
	places = document.getElementsByClassName(typearr(ii,0));
	for (var i = 0; places[i]; i++) {
		window['minus'+i] = new Element(typearr(ii,1),'-',typearr(ii,2)+' '+typearr(ii,3),places[i],'',!ii,[['deleteBlock'+i],['Удалить блок?','hidden']]);
		submit['deleteBlock'+i] = 1;
		window['plus'+i] = new Element(typearr(ii,1),'+',typearr(ii,3),places[i],'',!ii,typearr(ii,4,i));
	}

	function getXmlHttpRequest() {
		if (window.XMLHttpRequest) {
			try {
				return new XMLHttpRequest();
			} 
			catch (e){}
		} 
		else if (window.ActiveXObject) {
			try {
				return new ActiveXObject('Msxml2.XMLHTTP');
			} catch (e){}
			try {
				return new ActiveXObject('Microsoft.XMLHTTP');
			} 
			catch (e){}
		}
		return null;
	}


} 
