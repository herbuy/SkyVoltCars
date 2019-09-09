<?php

class InvalidInputException extends Exception{
}
class ThrowInvalidInputException{
    public static function given($boolean, $message){
        if($boolean){
            throw new InvalidInputException($message);
        }
    }
    public static function ifNot($boolean,$message){
        self::given(!$boolean,$message);
    }
}

abstract class VariableDefinition{

    private $value;
    private $can_be_null = false;
    
    public function getValue(){
        return $this->value;
    }
    public function __toString()
    {
        return $this->getValue();
    }

    public function __construct($can_be_null=false)
    {
        //input
        $this->can_be_null = $can_be_null;
        
        //processing        
        $value = $this->resolveValue(); 
        
        if(is_array($value)){
            if(!$this->should_be_array()){
                throw new Exception("multiple input not accepted for ".$this->getDisplayName());
            }
            else{
                $count = count($value);
                if($count > $this->max_number_of_items_if_array()){
                    throw new Exception(sprintf(
                        "expected not more than %s items for %s. %s provided",
                            $this->max_number_of_items_if_array(), $this->getDisplayName(),
                        $count)
                    );
                }
                else{
                    $this->value = array();
                    for($index = 0; $index < $count;$index++){
                        $this->value[] = $this->getValidatedValue($value[$index]);
                    }
                }
            }
                        
        }
        else{
            if($this->should_be_array()){
                throw new Exception("multiple input expected for ".$this->getDisplayName());
            }
            else{
                $this->value = $this->getValidatedValue($value);
            }
            
        }
       
    }    
    public function is_array(){
        return is_array($this->value);
    }
    protected function should_be_array(){
        return false;
    }
    protected function max_number_of_items_if_array(){
        return 10;
    }
    private function getValidatedValue($value)
    {
        $value = $this->sanitizeValue($value);
        if (!$this->valueEmpty($value)) {
            $this->validateValue($value);
        } else {
            ThrowInvalidInputException::ifNot($this->canBeNull(), $this->canNotBeNullMessage());
            $value = $this->defaultValue();
        }
        return $value;
    }
    private function resolveValue(){        
        $value = array_key_exists($this->getName(),$_REQUEST)? $_REQUEST[$this->getName()] : "";
        
        $value = is_array($value) ? $value : trim($value);
        
        return $value;
    }
    protected abstract function validateValue($value);
          
    

    /** @return string */
    abstract public function getName();

    protected function getDisplayName(){
        return str_replace("_"," ",$this->getName());
    }

    
    /** @return bool */
    protected function canBeNull()
    {
        return $this->can_be_null;
    }
    /** @return string */
    abstract protected function defaultValue();

    /**
     * @return string
     */
    private function canNotBeNullMessage()
    {
        return sprintf("%s can not be empty", $this->getDisplayName());
    }

 
    /**
     * @param $value
     * @return bool
     */
    private function valueEmpty($value)
    {
        return $value == "";
    }

    protected function sanitizeValue($value)
    {
        return $value;
    }

   
}


abstract class EnumVariable extends VariableDefinition{
    protected function validateValue($value)
    {
        $acceptable_values_array = array_flip($this->getArrayOfAcceptableValues());
        ThrowInvalidInputException::ifNot(
            array_key_exists($value,$acceptable_values_array),"invalid value for ".$this->getDisplayName());
    }
    abstract protected function getArrayOfAcceptableValues();
}
abstract class CommaSeparatedEnum extends EnumVariable{
    protected function validateValue($value)
    {        
        //break it into comma separated value
        $sub_parts = explode(",",$value);
        //validate each value against the enumeration
        foreach ($sub_parts as $sub_item){
            parent::validateValue($sub_item);
        }
    }
    public function getValueAsArray()
    {
        return explode(",",$this->getValue());        
    }
}

abstract class NonEnumVariable extends VariableDefinition{
    protected function validateValue($value){
        ThrowInvalidInputException::ifNot(strlen($value) >= $this->minLengthInChars(), $this->tooShortMessage());
        ThrowInvalidInputException::ifNot(strlen($value) <= $this->maxLengthInChars(), $this->exceededLengthMessage());
        ThrowInvalidInputException::ifNot($this->matches($value), $this->invalidDataTypeMessage());
        
    }

    /** @return bool */
    protected function matches($value){
        return preg_match(sprintf("/^(%s)$/i",$this->getPattern()),$value);
    }
    abstract protected function getPattern();
    /** @return int */
    abstract protected function maxLengthInChars();
    
    protected function minLengthInChars()
    {
        return 0;
    }

    protected function exceededLengthMessage()
    {
        return sprintf("%s too long",$this->getDisplayName());
    }

    protected function tooShortMessage()
    {
        return sprintf("%s too short",$this->getDisplayName());
    }

    private function invalidDataTypeMessage()
    {
        return sprintf("%s incorrect", $this->getDisplayName());
    }

}

abstract  class TextDefinition extends NonEnumVariable{
    protected function getPattern()
    {
        return "[\w\W\s\S]+";
    }
    
    protected function maxLengthInChars()
    {
        return 160;
    }

    protected function defaultValue()
    {
        return "";
    }
}
abstract class IntegerInputDefinition extends NonEnumVariable{
    protected function defaultValue()
    {
        return 0;
    }
    protected function sanitizeValue($value)
    {
        //allow for commas and spaces in values to group them
        $value = trim($value);
        $value = str_replace(",","",$value);
        $value = preg_replace("/\s+/i","",$value);
        return $value;
    }

    protected function exceededLengthMessage()
    {
        return sprintf("%s too big",$this->getDisplayName());
    }

    protected function tooShortMessage()
    {
        return sprintf("%s too small",$this->getDisplayName());
    }
   
}

abstract class BigIntValueDefinition extends IntegerInputDefinition{
        
    protected function maxLengthInChars()
    {
        return 20;
    }
    protected function defaultValue()
    {
        return 0;
    }
    protected function canBeNull()
    {
        return false;
    }
    protected function getPattern(){        
        return "\d{1,20}";
    }
}
abstract class BigIntSignedValueDefinition extends IntegerInputDefinition{

    protected function maxLengthInChars()
    {
        return 20;
    }
    protected function defaultValue()
    {
        return 0;
    }
    protected function canBeNull()
    {
        return false;
    }
    protected function canBeNegative()
    {
        return true;
    }
    protected function getPattern(){
        return "(-)?\d{1,19}";
    }
} 

abstract class IntValueDefinition extends IntegerInputDefinition{

    protected function maxLengthInChars()
    {
        return 9;
    }
    protected function defaultValue()
    {
        return 0;
    }
    protected function canBeNull()
    {
        return false;
    }
    
    protected function getPattern(){        
        return "\d{1,9}";
    }
    
}
abstract class IntSignedValueDefinition extends IntegerInputDefinition{

    protected function maxLengthInChars()
    {
        return 9;
    }
    protected function defaultValue()
    {
        return 0;
    }
    protected function canBeNull()
    {
        return false;
    }

    protected function getPattern(){        
        return "(-)?\d{1,8}";
    }

}

abstract class PositiveIntValueDefinition extends IntValueDefinition{
    
    protected function defaultValue()
    {
        return 1;
    }
    protected function canBeNull()
    {
        return false;
    }
    protected function getPattern(){
        return "[1-9]|[1-9]([0-9]{0,8})";
    }
}

abstract class FloatInputDefinition extends NonEnumVariable{
    protected function defaultValue()
    {
        return 0;
    }
    protected function sanitizeValue($value)
    {
        //allow for commas and spaces in values to group them
        $value = trim($value);
        $value = str_replace(",","",$value);
        $value = preg_replace("/\s+/i","",$value);
        return $value;
    }

    protected function exceededLengthMessage()
    {
        return sprintf("%s too big",$this->getDisplayName());
    }

    protected function tooShortMessage()
    {
        return sprintf("%s too small",$this->getDisplayName());
    }
    protected function canBeNegative()
    {
        return false; 
    }

    protected function getPattern()
    {        
        return "\d{1,9}([.][0-9]{1,9})?";
    }
    protected function maxLengthInChars()
    {
        return 20;
    }
}
abstract class FloatSignedInputDefinition extends FloatInputDefinition{
    protected function getPattern()
    {
        return "(-)?\d{1,9}([.][0-9]{1,9})?";
    }
}


abstract class BooleanValue extends EnumVariable{
    protected function getArrayOfAcceptableValues()
    {
        return array(0,1);
    }

    protected function defaultValue()
    {
        return 0;
    }
}


abstract class Sha1HashedInput extends TextDefinition{
    public function getValue()
    {
        return sha1(parent::getValue());
    }
}
