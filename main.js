'use strict';

void function (fnReadyAction) {
    var idxState;
    var stateList = ['loading', 'interactive', 'complete'];

    idxState = stateList.indexOf(document.readyState);

    if (idxState > 0) {
        fnReadyAction();
        return;
    }

    document.addEventListener('DOMContentLoaded', function tmp(evt) {
        document.removeEventListener('DOMContentLoaded', tmp, false);
        fnReadyAction();
    }, false);
}(
function () {
    let helTextarea = document.querySelector('.formBlock_textArea > textarea');

    void function () {
        function designateSubElementListener(strSelectors, fnListener) {
            return function (evt) {
                var idx, len, val;
                var qsAll = this.querySelectorAll(strSelectors);
                var designateSubHelList = Array.from(qsAll);
                var pathList = evt.path;

                for (idx = 0, len = pathList.length; idx < len ; idx++) {
                    val = pathList[idx];

                    if (val === this || evt.cancelBubble) break;
                    if (!!~designateSubHelList.indexOf(val)) fnListener.call(val, evt);
                }
            };
        }

        let helFromBlock = document.querySelector('.formBlock');
        let listener = designateSubElementListener(
            '.formBlock_pushTool_lableBtn,'
            + '.formBlock_pushTool_pikaOver,'
            + '.formBlock_pushTool_dropArea,'
            + '.formBlock_textArea',
            function (evt) {
                switch (evt.type) {
                    case 'dragenter':
                        this.classList.add('esFocus');
                        break;
                    case 'dragleave':
                        this.classList.remove('esFocus');
                        break;
                }
            }
        );

        helFromBlock.addEventListener('dragenter', listener, false);
        helFromBlock.addEventListener('dragleave', listener, false);
    }();

    void function () {
        function textareaFocusToggle() {
            let helParent = this.parentNode;

            if (this === document.activeElement) {
                helParent.classList.add('esFocus');
            } else {
                helParent.classList.remove('esFocus');
            };
        }

        helTextarea.addEventListener('focusin', textareaFocusToggle, false);
        helTextarea.addEventListener('focusout', textareaFocusToggle, false);
    }();

    let msgText = function () {
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
            let txt = this._text;
            this._text = (!txt ? '' : txt + '\n') + msg
        };
        msgText.clear = function (msg) {
            this._helTextarea.innerText = this._text = '';
            if (!!msg) msgText(msg);
            return this;
        };
        msgText.output = function () {
            let txt = this._text;
            if (!!txt) {
                this._helTextarea.innerText = txt;
                this._text = '';
            }
            return this;
        };

        return msgText;
    }();

    let helPushTool_pikaOver = document.getElementById('inputSelectFile');
    let helPushTool_dropArea = document.querySelector('.formBlock_pushTool_dropArea');
    let helPreviewPhoto = document.querySelector('.previewBlock_showPhoto');

    let selectFile = function () {
        function selectFile() {
            this._imgList = [];
            this._noHandleList = [];
            this.listener = selectFile.listener.bind(this);
            this.previewPhoto = selectFile.previewPhoto.bind(this);
        }

        selectFile.listener = function (evt) {
            let idx, len, val;
            let files;

            if (evt.target instanceof HTMLInputElement) {
                // input 標籤物件所選取的文件
                files = evt.target;
            } else {
                // 拖拉圖片至瀏覽器預設動作是開啟本地文件
                evt.preventDefault();
                // 拖拉物件由 DataTransfer 物件中取得檔案物件
                files = evt.dataTransfer;
            }
            files = files.files;

            this.clear();

            // 不確定會不會有此種情況
            if (!files || !files.length) {
                this.msgText('至少選定一個有效文件。 請重新選擇。').output();
                return;
            }

            // "files" 是被選取檔案的類數組。
            // files instanceof Array === false
            for (idx = 0, len = files.length; idx < len ; idx++) {
                val = files[idx];

                if (files[idx].type.indexOf('image/') !== 0) {
                    this._noHandleList.push(val);
                } else {
                    this._imgList.push(val);
                }
            }

            this.readFile();
        };

        selectFile.previewPhoto = function (evt) {
            let base64Img = evt.target.result;
            let helImg = document.createElement('img');

            helImg.src = base64Img;

            this._helPreviewPhoto.appendChild(helImg);
        };

        selectFile.prototype.msgText = msgText;
        selectFile.prototype._helPreviewPhoto = helPreviewPhoto;

        selectFile.prototype.clear = function () {
            this._noHandleList.length = 0;
            this._imgList.length = 0;
            this.msgText.clear();
            this._helPreviewPhoto.textContent = "";
        };

        selectFile.prototype._shortTxt = function (txt) {
            if (txt.length < 20) return txt;

            let ansTxt = '';
            let txtList = [];
            let idx, len, val;

            idx = 0;
            for (val of txt) {
                if (idx > 7) {
                    txtList.push(val);
                } else {
                    ansTxt += val;
                    idx++;
                }
            }
            ansTxt += '...';
            for (len = txtList.length, idx = len - 9; idx < len ; idx++) {
                ansTxt += txtList[idx];
            }

            return ansTxt;
        };

        selectFile.prototype.readFile = function () {
            let idx, len, val;
            let noHandleList = this._noHandleList;
            let imgList = this._imgList;
            let msgText = this.msgText;
            let shortTxt = this._shortTxt;

            if (!!noHandleList.length) {
                msgText('文件類型與 "image/*" 不相符：')

                for (idx = 0, len = noHandleList.length; idx < len ; idx++) {
                    val = noHandleList[idx];
                    msgText(
                        '  ' + idx + '. ' + shortTxt(val.name)
                        + ' (' + (val.type || 'unknown file type') + ')'
                    );
                }

                msgText('\n -------\n');
            }

            if (!!imgList.length) {
                for (idx = 0, len = imgList.length; idx < len ; idx++) {
                    val = imgList[idx];

                    msgText([
                        shortTxt(val.name)
                            + ' (' + (val.type || 'unknown file type') + ') - '
                            + val.size + ' bytes',
                        'last modified: ' + val.lastModifiedDate,
                        '\n -------\n',
                    ]);

                    // 預覽圖片
                    let insFileReader = new FileReader();
                    insFileReader.onload = this.previewPhoto;
                    insFileReader.readAsDataURL(val);
                }
            }

            msgText.output();
        };

        return new selectFile();
    }();

    helPushTool_pikaOver.addEventListener('change', selectFile.listener, false);
    helPushTool_dropArea.addEventListener('drop', selectFile.listener, false);
    helPushTool_dropArea.addEventListener('dragover', function (evt) {
        // drop、dragover 都必須要加！ bug？
        evt.preventDefault();
    }, false);
}
);

