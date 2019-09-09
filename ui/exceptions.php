<?php
class UIExceptionFactory{
    public function throwIfNot($condition,$message){
        if(!$condition){
            throw new Exception($message);
        }
    }
    public function throwIfNotReader($reader){
        $this->throwIfNot(is_a($reader,'ReaderForValuesStoredInArray'),"expected a reader");
    }
}