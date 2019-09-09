<?php


class TokenizerForMotokaPost{
    private $tokens = array();

    public function __construct($input_text)
    {
        $input_text .= "";
        $this->tokens = $this->get_token_array($input_text);
    }

    private function get_token_array($input_text)
    {
        $tokens = array();

        $length = strlen($input_text);
        if($length < 1){
            return array();
        }
        else{
            $position = 0;
            $previous_command = '';
            while($position < $length){
                if(
                    $input_text[$position] == '#' ||
                    $input_text[$position] == '{' ||
                    $input_text[$position] == '}'
                ){
                    //record delimiter
                    $tokens[] = $input_text[$position];
                    $previous_command = $input_text[$position];
                    $position += 1;
                }
                else{
                    //read normal text
                    $text = '';
                    while(
                        $position < $length &&
                        $input_text[$position] != '#' &&
                        $input_text[$position] != '{' &&
                        $input_text[$position] != '}'
                    ){
                        $text .= $input_text[$position];
                        $position += 1;

                    }
                    $tokens[] = $text;
                }
            }
        }
        return $tokens;
    }

    private function readChar($input_text, $position)
    {
        return $input_text[$position];
    }

    private function commandStartChar()
    {
        return "#";
    }

    private function nextPosition($position)
    {
        return $position + 1;
    }

    private function charMeansNormalTextFollows($char)
    {
        return strcmp($char,$this->commandStartChar()) != 0;
    }

    public function output_array(){
        return $this->tokens;
    }
}

//========
$tokenizer = new TokenizerForMotokaPost(
    file_get_contents("sample_input_for_translator.txt")
);

print "<pre>";
print json_encode($tokenizer->output_array());
print "</pre>";

//=====================
exit;

/*
 *
 * //record command start char
                    $tokens[] = $input_text[$position];
                    $position += 1;
                    //read the keyword
                    if($position < $length){
                        if($input_text[$position] == ' '){
                            //skip space
                            while($position < $length && $input_text[$position] == ' '){
                                $position += 1;
                            }
                        }
                        else{
                            $keyword = '';
                            while($position < $length && $input_text[$position] != ' '){
                                //read $command
                                $keyword .= $input_text[$position];
                                $position += 1;
                            }
                            $tokens[] = $keyword;
                        }
                    }
 *
 */