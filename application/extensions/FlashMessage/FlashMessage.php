<?php
class FlashMessage extends CWidget {
    public function run() {
        if (!empty(App()->session['flashmessage']) && Yii::app()->session['flashmessage'] != '')
        {
            $message = App()->session['flashmessage'];
            unset(App()->session['flashmessage']);
            $this->render('message', compact('message'));
        }
    }
}