<?php
class ButtonFactory{
    public function submit(){
        return new SubmitInput();
    }
}