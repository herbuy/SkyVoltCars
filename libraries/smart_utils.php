<?php
class SmartUtils{
    public static function createCommaSeparatedNumber($text){
        if(!is_numeric($text)){
            return $text;
        }
        //format and return new text object
        $number = strrev("". intval($text));
        $str_result = "";
        $last_index = strlen($number) - 1;
        $first_index = 0;
        for($current_index = $first_index; $current_index <= $last_index ;$current_index++){
            if($current_index % 3 == 0 && $current_index > $first_index){
                $str_result .= ",";
            }
            $str_result .= $number[$current_index];
        }
        //reverse the array
        $comma_separated_value = strrev($str_result);
        return $comma_separated_value;
    }
    public static function format_as_currency($text){
        $text = $text ? $text : 0;
        return "Ushs.&nbsp;".self::createCommaSeparatedNumber($text);
    }

    public static function format_as_proper_noun($text)
    {
        //replace extra spaces with single space
        $text = preg_replace("/\s+/i"," ",$text);

        //now split using space
        $parts = explode(" ",$text);
        $parts = is_array($parts) ? $parts : array();
        //capitalize each part and make the rest small case
        $resulting_words = array();
        foreach($parts as $part){
            $first_letter = substr($part,0,1);
            $other_letters = substr($part,1);

            $final_word = strtoupper($first_letter).strtolower($other_letters);
            $resulting_words[] = $final_word;
        }
        return join(" ",$resulting_words);
    }
    public static function capitalize_first_letter($text)
    {
        $first_letter = strtoupper(substr($text,0,1));
        $other_letters = substr($text,1);
        return $first_letter.$other_letters;
    }
    public static function limit_text_to_length($text,$length){
        return strlen($text) > $length ? substr($text,0,$length - 3)."...": $text;
    }
    public static function add_label_to_total($total,$label_if_one,$label_otherwise){
        $total = "".$total;
        $output =  sprintf("%s %s",
            $total,
            $total == "1" ? $label_if_one : $label_otherwise
        );
        return $output;
    }
}


