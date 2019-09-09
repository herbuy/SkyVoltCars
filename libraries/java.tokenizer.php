<?php
//================
class InputString{
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

    public function nextIsSpace()
    {
        return preg_match("/\s/i",$this->peek());
    }
    public function nextIsNewLineChar()
    {
        return $this->matchesAny($this->peek(),array("\r","\n"));
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

    public function nextIsWordStartChar()
    {
        return preg_match("/[A-Za-z_]/", $this->peek());
    }
    public function nextIsWordChar()
    {
        return $this->nextIsWordStartChar() || $this->nextIsDigit();
    }

    public function nextIsAsterisk(){
        return $this->peek() == "*";
    }

    public function nextIsSpecialChar(){
        return $this->nextIsBlockDelimiter() ||
        $this->nextIsArithmeticChar() ||
        $this->nextIsBitwiseChar() ||
        $this->nextIsComparisonChar() ||
        $this->nextIsOtherSpecialChar();
    }

    private function nextIsBlockDelimiter(){
        return $this->matchesAny($this->peek(),array("{","}","[","]","(",")"));
    }
    private function nextIsArithmeticChar(){
        return $this->matchesAny($this->peek(),array("+","-","*","/"));
    }
    private function nextIsBitwiseChar(){
        return $this->matchesAny($this->peek(),array("&","|"));
    }
    private function nextIsComparisonChar(){
        return $this->matchesAny($this->peek(),array(">","<","="));
    }
    private function nextIsOtherSpecialChar(){
        return $this->matchesAny($this->peek(),array(";",","));
    }

    public function getSpecialChar()
    {
        $output = "";
        if($this->nextIsSpecialChar()){
            $output = $this->getNext();
            //some chars are combined with another to form new meaning
            // e.g. math: +=, -=, *=, /=, logic: &&, ||, comparision: >>, <<, >=, <=, ==, !=
            if($this->nextIsSpecialChar()){
                $new_pattern = $output. $this->peek();
                $patterns_to_match = array("+=","-=","*=","/=","&&","||",">>","<<",">=","<=","==","!=","//");
                if($this->matchesAny($new_pattern,$patterns_to_match)){
                    $output = $new_pattern;
                    $this->getNext();
                    //incase it is // it, means we have a comment, so we read everything till new line char
                    if($output == "//"){
                        while($this->hasNext() && !$this->nextIsNewLineChar()){
                            $output .= $this->getNext();
                        }
                    }
                }
            }
        }
        return $output;
    }

    public function getAllSpace()
    {
        /*$space = "";
        if($this->nextIsSpace()){
            while($this->nextIsSpace()){
                $space .= $this->getNext();
            }
        }
        return $space;*/
        $space = " ";
        if($this->nextIsSpace()){
            while($this->nextIsSpace()){
                $this->getNext();
            }
        }
        return $space;
    }
    public function getAllNonSpace()
    {
        $non_space = "";
        while(!$this->nextIsSpace()){
            $non_space .= $this->getNext();
        }
        return $non_space;
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
        if($this->nextIsWordStartChar()){
            $output .= $this->getNext();
            while($this->nextIsWordChar()){
                $output .= $this->getNext();
            }
        }
        return $this->nextIsDot() ? $this->getPath($output) : $output;
    }

    private function getPath($output)
    {
        if($this->nextIsDot()){
            $output .= $this->getNext();
            if($this->nextIsWordStartChar()){
                $output .= $this->getWord();
            }
            else if($this->nextIsAsterisk()){
                $output .= $this->getNext();
            }
            else{
                $this->throwException("expected atleast a word or * - but found ".$this->peek());
            }
            return $output;
        }
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
}
class JavaTokens{
    public function __construct($input_string)
    {
        $input_string = new InputString($input_string);
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
            else if($input_string->nextIsWordStartChar()){
                $this->arr_output[] = $input_string->getWord();
            }
            else if($input_string->nextIsSpecialChar()){
                $this->arr_output[] = $input_string->getSpecialChar();
            }
            /*else if($input_string->nextIsSpace()){
                $this->arr_output[] = $input_string->getAllSpace();
            }*/
            else{
                $input_string->getNext();
            }

        }
    }

    private $arr_output = [];
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


//test
$actual_text = file_get_contents("inputs_string.java_tokenizer.txt");
print (new JavaTokens($actual_text))->toString();
exit;
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@


