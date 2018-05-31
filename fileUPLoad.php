<!DOCTYPE html><html><head><meta charset="UTF-8">
<title>拖拉上傳</title><!--
===================='==============================
作者（Author）      :  張本微（Jzysin）
日期（Date） 1      :  民國 103/11/14 （2014）
日期（Date） 2      :  民國 104/08/13 （2015）
====================.============================== -->
<?php /*//////////////////////////////////////////////////*/ ?>

<script src="../JQuery.js" type="text/javascript"></script>


<?php /*//////////////////////////////////////////////////*/ ?>
</head><body>
<?php /*//////////////////////////////////////////////////*/ ?>
<div class="FileUPLoad">



<style type="text/css">
.blue {
	height: 42px;
	margin: 0;
	padding: 0 16px;
	background: #03a9f4;
	border: 0;
	border-bottom: 2px solid #b0bec5;
	border-radius: 2px 2px 0 0;
	font-size: 18px;
	line-height: 42px;
	color: #fff;
	font-weight: 400;
	text-align: center;
}
</style>

<input type="button" value="開始" class="blue" />

<style>
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

<div class="pika">
	<input type="file" multiple accept="image/*" />
</div>

<br />

<div id="fileDropBox">
	<div class="textarea">
		<textarea id="txt"></textarea>
	</div>
</div>


<script type="text/javascript">
	$('.textarea > textarea').on({
		focusin:function(){ $(this).parent().addClass('InputFocus');},
		focusout:function(){ $(this).parent().removeClass('InputFocus');}
	});

	var DDBox={};

	if (!window.FileReader){
		var message = '<p>HTML5 ' +
			'<a href="http://dev.w3.org/2006/webapi/FileAPI/" target="_blank">File API</a> ' +
			' 不被您的瀏覽器支援，請升級瀏覽器到最新版本。。</p>';
		document.querySelector('body').innerHTML = message;
	}else{
		// Set up the file drag and drop listeners:
		document.getElementById('fileDropBox').addEventListener('dragover', handleDragOver, false);
		document.getElementById('fileDropBox').addEventListener('drop', handleFileSelection, false);
	}

	function handleDragOver(evt){
		evt.stopPropagation();  // Do not allow the dragover event to bubble.
		evt.preventDefault(); // Prevent default dragover event behavior.
	}


	function handleFileSelection(evt){
		evt.stopPropagation(); // Do not allow the drop event to bubble.
		evt.preventDefault(); // Prevent default drop event behavior.

		// 獲取移到拖曳框的檔案列表
		var files = evt.dataTransfer.files;

		if (!files){
			msa.alert("<p>At least one selected file is invalid - do not select any folders.</p><p>Please reselect and try again.</p>");
			return;
		}

		// "files" 是選取檔案的數組。
		// 顯示檔案的屬性。
		var output = [];
		for (var i = 0, f; i < files.length; i++){
			try {
				f = files[i];
				output.push(f.name, f.type || 'unknown file type', ') - ', f.size, ' bytes, \nlast modified: ', f.lastModifiedDate);
				document.getElementById('txt').innerHTML = output.join(' ');
				} // try
			catch (fileError) {
				msa.alert( "<p>錯誤！檔案未被指定。</p><p>選擇資料夾會導致錯誤發生。</p>" ) ;
				console.log( "找到下列的錯誤 i = " + i + ": " + fileError ) ;
				// 傳送錯誤訊息到瀏覽器的 debugger。
				return;
			}
		}
	}
</script>

<script type="text/javascript">
function aa() {
	var file = document.getElementById('ff').files[0];
	//console.dir(file);
	if (file) {
		var msg = [];
		msg.push('檔名：' + file.name
			, '大小：' + file.size
			, '檔案類型：' + file.type
			, '修改日期：' + file.lastModifiedDate.toLocaleDateString()
			);
		document.getElementById('msg').innerHTML = msg.join("<"+"br"+">");

		//讀取檔案
		var fileReader = new FileReader();
		fileReader.onload = function(event){//讀取完後執行的動作
			//console.dir(event);
			//document.getElementById('ms').innerHTML = event.target.result;
			//alert( event.target.result.constructor + '\n' + event.target.length + '\n' + event.target.result.length )
			//document.getElementById('xx').src = event.target.result;
			var jEXIF = event.target.exifLoad(function(){
				alert('1 => ' + event.target.exif( 'PixelXDimension' ) + '\n' + event.target.result + '\n' + jEXIF );
			});
		}
		//fileReader.readAsDataURL(file);//讀取檔案內容,以DataURL格式回傳結果
		//fileReader.readAsBinary(file);
		fileReader.readAsArrayBuffer(file);
		//fileReader.readAsText(file,'UTF-8');
	}
}
</script>



</div>
<?php /*//////////////////////////////////////////////////*/ ?>
</body></html>
