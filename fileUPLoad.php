<!DOCTYPE html><html><head><meta charset="UTF-8">
<title>拖拉上傳</title><!--
===================='==============================
作者（Author）      :  張本微（Jzysin）
日期（Date） 1      :  民國 103/11/14 （2014）
日期（Date） 2      :  民國 104/08/13 （2015）
====================.============================== -->
<?php /*//////////////////////////////////////////////////*/ ?>


<?php /*//////////////////////////////////////////////////*/ ?>
</head><body>
<?php /*//////////////////////////////////////////////////*/ ?>
<div class="FileUPLoad">



<style type="text/css">
.blue {
	display: block;
	width: 200px;
	height: 42px;
	margin: 0;
	background: #03a9f4;
	border: 1px dashed gray;
	border-radius: 7px;
	font-size: 18px;
	line-height: 42px;
	color: #fff;
	font-weight: 400;
	text-align: center;
}
.pika {
	width: 200px;
	height: 100px;
	background: url("pika.gif") no-repeat 0 0 transparent;
	background-size: 208px 109px;
	border: 1px dashed gray;
	border-radius: 7px;
}
.pika > input {
	width: 100%;
	height: 100%;
	opacity: 0;
	cursor: pointer;
}

#fileDropBox {
	width: 500px;
	height: 150px;
	position: relative;
}
.css_input {
	display: block;
	width: 360px;
	height: 22px;
	padding: 5px 7px;
	font-size: 14px;
	line-height: 22px;
	color: #555;
	background: #fff;
	border: 1px solid #ccc;
	border-radius: 4px;
	transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.css_input:focus {
	border-color: #66afe9;
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
	outline: 0;
}
#fileDropBox > .textarea {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0px;
}
.textarea {
	padding: 4px;
	border: 1px solid #A9A9A9;
	border-radius: 4px;
	transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.textarea.InputFocus {
	border-color: #66afe9;
	box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
}

#fileDropBox > .textarea > textarea {
	width: 100%;
	height: 100%;
	padding: 0;
	border: 0;
	outline: 0;
    resize: none;
}
</style>

<label for="selectFile" class="blue">選擇圖片</label>
<div class="pika">
	<input type="file" id="selectFile" multiple accept="image/*" />
</div>

<br />

<div id="fileDropBox">
	<div class="textarea">
		<textarea></textarea>
	</div>
</div>


<script type="text/javascript">
	var helTextarea = document.querySelector('.textarea > textarea');

	void function () {
		function textareaFocusToggle() {
			var helParent = this.parentNode;
			if (this === document.activeElement) {
				helParent.classList.add('InputFocus');
			} else {
				helParent.classList.remove('InputFocus');
			};
		}

		helTextarea.addEventListener('focusin', textareaFocusToggle, false);
		helTextarea.addEventListener('focusout', textareaFocusToggle, false);
	}();

	function msgText(msg) {
		switch (typeof msg) {
			case 'string':
				msgText.add(msg);
				break;
			case 'object':
				if (msg.constructor !== Array) break;
				for (let val of msg) msgText.add(val);
				break;
		}

		return msgText;
	}

	msgText._text = '';
	msgText._helTextarea = helTextarea;
	msgText.add = function (msg) {
		var txt = this._text;
		this._text = (!txt ? '' : txt + '\n') + msg
	};
	msgText.clear = function (msg) {
		this._helTextarea.innerText = this._text = '';
		if (!!msg) msgText(msg);
		return this;
	};
	msgText.output = function () {
		var txt = this._text;
		if (!!txt) {
			this._helTextarea.innerText = txt;
			this._text = '';
		}
		return this;
	};

	if (!window.FileReader){
		var message = '<p>HTML5' +
			' <a href="http://dev.w3.org/2006/webapi/FileAPI/" target="_blank">File API</a>' +
			' 不被您的瀏覽器支援，請升級瀏覽器到最新版本。</p>';
		document.querySelector('body').innerHTML = message;
	}else{
		document.getElementById('fileDropBox').addEventListener('dragover', handleDragOver, false);
		document.getElementById('fileDropBox').addEventListener('drop', handleFileSelection, false);
	}

	function handleDragOver(evt){
		// 取消冒泡
		evt.stopPropagation();
		// 防止預設動作
		evt.preventDefault();
	}

	function handleFileSelection(evt){
		evt.stopPropagation();
		evt.preventDefault();

		// 獲取移到拖曳框的檔案列表
		var files = evt.dataTransfer.files;

		if (!files){
			msgText.clear('<p>至少有一個選定的文件無效 - 請勿選擇任何文件夾。</p><p>請重新選擇。</p>').output();
			return;
		}

		// "files" 是選取檔案的數組。
		// 顯示檔案的屬性。
		msgText.clear();
		for (let val of files){
			try {
				msgText([
					val.name + ' (' + (val.type || 'unknown file type') + ') - ' + val.size + ' bytes',
					'last modified: ' + val.lastModifiedDate
				]);
			}
			catch (err) {
				msgText.clear('<p>錯誤！檔案未被指定。</p><p>選擇資料夾會導致錯誤發生。</p>');
				console.log('文件清單讀值錯誤', val.name, fileError);
				break;
			}
		}
		msgText.output();
	}

	document.querySelector('.pika > input').addEventListener('change', selectFile, false);

	function selectFile() {
		var file = this.files[0];
		//console.dir(file);
		msgText.clear();
		if (file) {
			msgText([
				'檔名：' + file.name,
				'大小：' + file.size,
				'檔案類型：' + file.type,
				'修改日期：' + file.lastModifiedDate.toLocaleDateString(),
			]);
		}
	}
</script>



</div>
<?php /*//////////////////////////////////////////////////*/ ?>
</body></html>
