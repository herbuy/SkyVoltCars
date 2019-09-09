<?php
//================
class RichTextString{
    private $string;
    private $length;

    public function __construct($string)
    {
        $this->string = $string;
        $this->length = strlen($string);
    }

    private $position = 0;
    public function hasNext()
    {
        return $this->position < $this->length;
    }

    private function hasPrevious()
    {
        return $this->position > 0 && $this->length > 0;
    }

    public function peek(){
        if($this->hasNext()){
            return $this->string[$this->position];
        }
        else{
            return null;
        }
    }
    public function peekPrevious(){
        if($this->hasPrevious()){
            return $this->string[$this->position - 1];
        }
        return null;
    }
    public function getNext(){
        $output = $this->peek();
        if(!is_null($output)){
            $this->position++;
        }
        return $output;
    }

    private function throwException($string)
    {
        throw new Exception($string);
    }

    public function nextIsNewLineChar()
    {
        return $this->matchesAny($this->peek(),array("\r","\n"));
    }
    public function getNewLine(){
        $output = "";
        if($this->nextIsNewLineChar()){
            $char = $this->getNext();
            if(
                $char == "\r" && $this->peek() == "\n" ||
                $char == "\n" && $this->peek() == "\r"
            ){
                $this->getNext();//skip again
            }

            $output = "\r";
        }
        return $output;
    }

    public function nextIsDoubleQuote()
    {
        return $this->peek() == '"';
    }
    public function nextIsSingleQuote()
    {
        return $this->peek() == "'";
    }
    public function nextIsDigit()
    {
        return preg_match("/[0-9]/", $this->peek());
    }

    private function nextIsDot()
    {
        return $this->peek() == ".";
    }

    public function nextIsWordChar()
    {
        return $this->hasNext() && $this->noneTrue(array(
            $this->nextIsNewLineChar(), $this->nextIsAtSign()
        ));
    }

    public function nextIsAtSign(){
        return $this->peek() == "@";
    }

    public function getAllTextInDoubleQuote()
    {
        $output = "";
        if($this->nextIsDoubleQuote()){
            $output .= $this->getNext();
            while($this->hasNext() && !$this->nextIsDoubleQuote() || $this->previousIsBackslash()){
                $output .= $this->getNext();
            }
            if($this->nextIsDoubleQuote()){
                $output .= $this->getNext();
            }
            else{
                $this->throwException("expected closing double-quote");
            }
        }
        return $output;
    }

    public function getAllTextInSingleQuote()
    {
        $output = "";
        if($this->nextIsSingleQuote()){
            $output .= $this->getNext();
            while($this->hasNext() && !$this->nextIsSingleQuote() || $this->previousIsBackslash()){
                $output .= $this->getNext();
            }
            if($this->nextIsSingleQuote()){
                $output .= $this->getNext();
            }
            else{
                $this->throwException("expected closing single-quote");
            }
        }
        return $output;
    }

    private function previousIsBackslash()
    {
        return $this->peekPrevious() == "\\";
    }

    public function getNumber()
    {
        $output = "";
        if($this->nextIsDigit()){
            $output .= $this->getNext();
            while($this->nextIsDigit()){
                $output .= $this->getNext();
            }
        }        
        return $this->nextIsDot() ? $this->getFloatingNumber($output) : $output;
    }

    private function getFloatingNumber($output)
    {
        if($this->nextIsDot()){
            $output .= $this->getNext();
            if($this->nextIsDigit()){
                while($this->nextIsDigit()){
                    $output .= $this->getNext();
                }
            }
            else{
                $this->throwException("expected atleast one digit after the dot - number can not end with a dot");
            }
            return $output;
        }
    }

    public function getWord()
    {
        $output = "";
        if($this->nextIsWordChar()){
            while($this->nextIsWordChar()){
                $output .= $this->getNext();
            }
        }
        return $output;
    }

    private function matchesAny($string, $patterns_to_match)
    {
        $count = count($patterns_to_match);
        for($i = 0; $i < $count; $i++){
            $pattern = $patterns_to_match[$i];
            if($string == $pattern){
                return true;
            }
        }
        return false;
    }

    private function noneTrue($arr_conditions){
        foreach ($arr_conditions as $condition){
            if($condition){
                return false;
            }
        }
        return true;
    }
}
class MotokaviewsTokens{
    public function __construct($input_string)
    {
        $input_string = new CharacterString($input_string);
        while($input_string->hasNext()){
            //TODO: precedence is given to the quotes, so we can override everything else
            if($input_string->nextIsDoubleQuote()){
                $this->arr_output[] = $input_string->getAllTextInDoubleQuote();
            }
            else if($input_string->nextIsSingleQuote()){
                $this->arr_output[] = $input_string->getAllTextInSingleQuote();
            }
            else if($input_string->nextIsDigit()){
                $this->arr_output[] = $input_string->getNumber();
            }
            else if($input_string->nextIsAtSign()){
                $this->arr_output[] = $input_string->getNext();
            }
            else if($input_string->nextIsNewLineChar()){
                $this->arr_output[] = $input_string->getNewLine();
            }
            else if($input_string->nextIsWordChar()){
                $this->arr_output[] = $input_string->getWord();
            }
            else{
                $input_string->getNext();
            }

        }
    }

    private $arr_output = [];
    public function get_array(){
        return $this->arr_output;
    }
    public function toString(){
        $output = "";
        ob_clean();
        print "<pre>";
        htmlspecialchars( print_r($this->arr_output) );
        print "</pre>";
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}

//class 


//test


/*$actual_text = file_get_contents("input_string.motokaviews_tokenizer.txt");
print (new MotokaviewsTokens($actual_text))->toString();
exit;*/
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

