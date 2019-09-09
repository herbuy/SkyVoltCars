<?php
class HtmlSymbolFor{
    private static function from_decimal_code($decimal_code)
    {
        return sprintf("&#%s", $decimal_code);
    }
    private static function from_hex_code($hex_code)
    {
        return sprintf("&#x%s", $hex_code);
    }
    private static function from_name($name)
    {
        return sprintf("&%s;", $name);
    }
    public static function black_star(){
        return self::from_decimal_code(9733);
    }
    public static function white_star(){
        return self::from_decimal_code(9734);
    }
    public static function black_telephone(){
        return self::from_decimal_code(9742);
    }
    public static function white_telephone(){
        return self::from_decimal_code(97432);
    }
    public static function white_hearts(){
        return self::from_decimal_code(9825);
    }
    public static function black_hearts(){
        return self::from_decimal_code(9829);
    }
    public static function medium_black_circle(){
        return self::from_decimal_code(9899);
    }

    //====
    public static function black_scissors(){
        return self::from_decimal_code(9986);
    }
    public static function white_heavy_checkmark(){
        return self::from_decimal_code(9989);
    }
    public static function airplane(){
        return self::from_decimal_code(9992);
    }
    public static function envelope(){
        return self::from_decimal_code(9993);
    }
    public static function pencil(){
        return self::from_decimal_code(9999);
    }
    public static function heavy_checkmark(){
        return self::from_decimal_code(10004);
    }
    public static function heavy_multiplication(){
        return self::from_decimal_code(10006);
    }
    public static function heavy_gree_cross(){
        return self::from_decimal_code(10010);
    }
    
    //STARS
    public static function black_four_pointed_star(){
        return self::from_decimal_code(10022);
    }
    public static function circled_white_star(){
        return self::from_decimal_code(10026);
    }
    public static function outlined_black_star(){
        return self::from_decimal_code(10029);
    }
    public static function heavy_asterisk(){
        return self::from_decimal_code(10033);
    }
    public static function eight_spok_asterisk(){
        return self::from_decimal_code(10035);
    }
    public static function eight_pointed_black_star(){
        return self::from_decimal_code(10036);
    }
    public static function six_pointed_black_star(){
        return self::from_decimal_code(10038);
    }
    public static function heavy_eight_pointed_rectilinear_black_star(){
        return self::from_decimal_code(10040);
    }
    public static function twelve_pointed_black_star(){
        return self::from_decimal_code(10041);
    }
    public static function sparkle(){
        return self::from_decimal_code(10055);
    }
    
    //EXES
    public static function crossmark(){
        return self::from_decimal_code(10060);
    }
    public static function negative_squared_crossmark(){
        return self::from_decimal_code(10062);
    }
    
    
    //circles
    public static function shadowed_white_cricle(){
        return self::from_decimal_code(10061);
    }
    
    //squares
    public static function lower_right_drop_shadowed_white_square(){
        return self::from_decimal_code(10063);
    }
    public static function upper_right_drop_shadowed_white_square(){
        return self::from_decimal_code(10064);
    }
    public static function lower_right_shadowed_white_square(){
        return self::from_decimal_code(10065);
    }
    public static function upper_right_shadowed_white_square(){
        return self::from_decimal_code(10066);
    }
    
    //other
    public static function black_question_mark(){
        return self::from_decimal_code(10067);
    }
    public static function heavy_exclamation_mark(){
        return self::from_decimal_code(10071);
    }
    public static function heavy_black_heart(){
        return self::from_decimal_code(10084);
    }
    public static function heavy_left_pointing_angular_quotation_mark(){
        return self::from_decimal_code(10094);
    }
    public static function heavy_right_pointing_angular_quotation_mark(){
        return self::from_decimal_code(10095);
    }
    
    //numbers
    public static function circled_digit_1(){
        return self::from_decimal_code(10122);
    }
    public static function circled_digit_2(){
        return self::from_decimal_code(10123);
    }
    public static function circled_digit_3(){
        return self::from_decimal_code(10124);
    }
    public static function circled_digit_4(){
        return self::from_decimal_code(10125);
    }
    public static function circled_digit_5(){
        return self::from_decimal_code(10126);
    }
    public static function circled_digit_6(){
        return self::from_decimal_code(10127);
    }
    public static function circled_digit_7(){
        return self::from_decimal_code(10128);
    }
    public static function circled_digit_8(){
        return self::from_decimal_code(10129);
    }
    public static function circled_digit_9(){
        return self::from_decimal_code(10130);
    }
    public static function circled_digit_10(){
        return self::from_decimal_code(10131);
    }

    //arrows

    public static function heavy_plus_sign(){
        return self::from_decimal_code(10133);
    }
    public static function heavy_minus_sign(){
        return self::from_decimal_code(10134);
    }

    public static function southeast_arrow(){
        return self::from_decimal_code(10136);
    }
    public static function northeast_arrow(){
        return self::from_decimal_code(10138);
    }
    public static function heavy_northwest_arrow(){
        return self::from_hex_code(2196);
    }
    public static function heavy_northeast_arrow(){
        return self::from_hex_code(2197);
    }
    public static function heavy_southeast_arrow(){
        return self::from_hex_code(2198);
    }

    public static function heavy_round_tipped_rightward_arrow(){
        return self::from_decimal_code(10140);
    }
    public static function heavy_triangle_headed_rightward_arrow(){
        return self::from_decimal_code(10142);
    }
    public static function black_rightward_arrow(){
        return self::from_decimal_code(10145);
    }
    public static function black_upward_arrow(){
        return self::from_name("uarr");
    }
    public static function black_downward_arrow(){
        return self::from_name("darr");
    }
    public static function heavy_black_curved_upwards_and_rightward_arrow(){
        return self::from_decimal_code(10150);
    }
    
    public static function horizontal_elipsis(){
        return self::from_decimal_code(8230);
    }




}
