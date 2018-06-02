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
            '.formBlock_formBtn_lableBtn, .formBlock_formBtn_pikaOver,'
            + ' .formBlock_dropArea, .formBlock_textArea',
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
}
);

