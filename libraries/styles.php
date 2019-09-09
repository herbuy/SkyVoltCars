<?php
require_once ("styles.php");

class InitialLeterFromString{
    private $word;
    public function __construct($word)
    {
        $word = "{$word}";//make sure it is a string
        $word = trim($word);//trim any white space on the sides
        $this->word  = $word;
    }
    public function __toString()
    {
        return substr($this->word,0,1);
    }
}

class ColorFromInitialLletterOfString{
    //this class receives the initial letter of a string and returns the color that correspond to it
    private $word;
    public function __construct($word)
    {
        $word = "{$word}";//make sure it is a string
        $word = trim($word);//trim any white space on the sides
        $this->word  = $word;
    }
    public function get(){
        $word = $this->word;
        if(strlen($word) < 1){
            return $this->defaultColor();
        }
        else{
            $initial_letter = substr($word,0,1);
            return $this->colorFor($initial_letter);
        }
    }
    private function colorFor($letter){
        $letter = strtolower($letter);
        $color_dictionary = $this->getColorDictionary();
        if(!array_key_exists($letter,$color_dictionary)){
            return $this->defaultColor();
        }
        return $color_dictionary[$letter];
    }

    private function getColorDictionary()
    {
        $color_dictionary = array(
            'a' => "acquamarine",
            'b' => "black",
            'c' => "blueviolet",
            'd' => "brown",
            'e' => "cadetblue",
            'f' => "chocolate",
            'g' => "cornflowerblue",
            'h' => "crimsom",
            'i' => "darkcyan",
            'j' => "darkmagenta",
            'k' => "darkorange",
            'l' => "darkorchid",
            'm' => "darkred",
            'n' => "darkseagreen",
            'o' => "darkslategray",
            'p' => "darkviolet",
            'q' => "deeppink",
            'r' => "deepskyblue",
            's' => "dodgerblue",
            't' => "firebrick",
            'u' => "forestgreen",
            'v' => "indianred",
            'w' => "lightseagreen",
            'x' => "mediumpurple",
            'y' => "mediumvioletred",
            'z' => "olivedrab",
            '1' => "orangered",
            '2' => "purple",
            '3' => "rebeccapurple",
            '4' => "royalblue",
            '5' => "saddlebrown",
            '6' => "seagreen",
            '7' => "sienna",
            '8' => "slateblue",
            '9' => "slategray",
            '0' => "steelblue"

            //other colors to consider: teal, 

        );
        return $color_dictionary;
    }

    private function defaultColor()
    {
        return "default color";
    }

    public function __toString()
    {
        return "".$this->get();
    }

}

