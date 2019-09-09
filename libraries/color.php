<?php

//namespace Mexitek\PHPColors;

//use \Exception;

abstract class AbstractColor{

    /**
     * Auto darkens/lightens by 10% for sexily-subtle gradients.
     * Set this to FALSE to adjust automatic shade to be between given color
     * and black (for darken) or white (for lighten)
     */
    const DEFAULT_ADJUST = 10;

    //interface methods
    abstract public function getClone();
    abstract public function darken($amount);
    abstract public function lighten($amount);
    abstract public function mix($rgb2, $amount);
    abstract public function complementary();
    abstract public function __toString();
    abstract public function isLight($lighterThan = 130);
    abstract public function isDark($darkerThan);
    abstract public function ToHex();
    abstract public function ToHsl();
    abstract public function ToRgb();

    /**
     * Creates an array with two shades that can be used to make a gradient
     * @param int $amount Optional percentage amount you want your contrast color
     * @return ColorGradient
     */
    private function makeGradient( $amount = self::DEFAULT_ADJUST ) {
        // Decide which color needs to be made
        if( $this->isLight() ) {
            $lightColor = $this->getClone();
            $darkColor = $this->darken($amount);
        } else {
            $lightColor = $this->lighten($amount);
            $darkColor = $this->getClone();
        }

        // Return our gradient array
        return new ColorGradient($lightColor,$darkColor);
    }

    public function getCssGradient( $amount = self::DEFAULT_ADJUST, $vintageBrowsers = FALSE, $suffix = "" , $prefix = "" ) {

        // Get the recommended gradient
        $g = $this->makeGradient($amount)->to_array();

        $css = "";
        /* fallback/image non-cover color */
        $css .= "{$prefix}background-color: ".$this.";{$suffix}";

        /* IE Browsers */
        $css .= "{$prefix}filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='".$g['light']."', endColorstr='".$g['dark']."');{$suffix}";

        /* Safari 4+, Chrome 1-9 */
        if ( $vintageBrowsers ) {
            $css .= "{$prefix}background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(".$g['light']."), to(".$g['dark']."));{$suffix}";
        }

        /* Safari 5.1+, Mobile Safari, Chrome 10+ */
        $css .= "{$prefix}background-image: -webkit-linear-gradient(top, ".$g['light'].", ".$g['dark'].");{$suffix}";

        /* Firefox 3.6+ */
        if ( $vintageBrowsers ) {
            $css .= "{$prefix}background-image: -moz-linear-gradient(top, ".$g['light'].", ".$g['dark'].");{$suffix}";
        }

        /* Opera 11.10+ */
        if ( $vintageBrowsers ) {
            $css .= "{$prefix}background-image: -o-linear-gradient(top, ".$g['light'].", ".$g['dark'].");{$suffix}";
        }

        /* Unprefixed version (standards): FF 16+, IE10+, Chrome 26+, Safari 7+, Opera 12.1+ */
        $css .= "{$prefix}background-image: linear-gradient(to bottom, ".$g['light'].", ".$g['dark'].");{$suffix}";

        // Return our CSS
        return $css;
    }


}

class HSLColor extends AbstractColor{

    private $h;
    private $s;
    private $l;
    
    public function __construct($h, $s, $l)
    {
        $this->H($h);
        $this->S($s);
        $this->L($l);
    }
    
    public function H($int){
        $int = intval($int);
        if($int < 0){
            $absolute = abs($int);
            $int = $absolute % 360;
            $int = 360 - $int;
        }
        else{
            $int = $int % 360;
        }
        $this->h = $int;
        return $this;
    }
    public function HPlus($int){
        $int = intval($int);
        $this->H($this->getH() + $int);
        return $this;
    }

    public function S($int){
        $this->s = floatval($int);
        return $this;
    }
    public function L($int){
        $this->l = floatval($int);
        return $this;
    }
    public function getH(){
        return $this->h;
    }
    public function getS(){
        return $this->s;
    }
    public function getL(){
        return $this->l;
    }
    public function Stimes($float,$max = 0.9){
        return $this->getClone()->S(min($max, $this->getS()*$float));
    }
    public function LTimes($float)
    {
        return $this->getClone()->L(min(0.999, $this->getL()*$float));
    }
    //===================
   
    /**
     *  Given a HSL associative array returns the equivalent HEX string
     * @param array $hsl
     * @return HexColor
     * @throws Exception "Bad HSL Array"
     */
    public function ToHex(){
        $hsl = $this;
        list($H,$S,$L) = array( $hsl->getH()/360,$hsl->getS(),$hsl->getL());

        if( $S == 0 ) {
            $r = $L * 255;
            $g = $L * 255;
            $b = $L * 255;
        } else {

            if($L<0.5) {
                $var_2 = $L*(1+$S);
            } else {
                $var_2 = ($L+$S) - ($S*$L);
            }

            $var_1 = 2 * $L - $var_2;

            $r = round(255 * $this->_huetorgb( $var_1, $var_2, $H + (1/3) ));
            $g = round(255 * $this->_huetorgb( $var_1, $var_2, $H ));
            $b = round(255 * $this->_huetorgb( $var_1, $var_2, $H - (1/3) ));

        }

        // Convert to hex
        $r = dechex($r);
        $g = dechex($g);
        $b = dechex($b);

        // Make sure we get 2 digits for decimals
        $r = (strlen("".$r)===1) ? "0".$r:$r;
        $g = (strlen("".$g)===1) ? "0".$g:$g;
        $b = (strlen("".$b)===1) ? "0".$b:$b;

        return new HexColor(
            $r.$g.$b
        );
    }

    private function _huetorgb( $v1,$v2,$vH ) {
        if( $vH < 0 ) {
            $vH += 1;
        }

        if( $vH > 1 ) {
            $vH -= 1;
        }

        if( (6*$vH) < 1 ) {
            return ($v1 + ($v2 - $v1) * 6 * $vH);
        }

        if( (2*$vH) < 1 ) {
            return $v2;
        }

        if( (3*$vH) < 2 ) {
            return ($v1 + ($v2-$v1) * ( (2/3)-$vH ) * 6);
        }

        return $v1;

    }

    /**
     * Darkens a given HSL array
     * @param array $hsl
     * @param int $amount
     * @return HSLColor
     */
    public function darken($amount = self::DEFAULT_ADJUST){  
        $amount = intval($amount);
        
        $hsl = $this->getClone();
        if($amount == 0){
           return $hsl; 
        }

        // Check if we were provided a number
        if( $amount ) {
            $hsl->L(
                ($hsl->getL() * 100) - $amount
            );
            $hsl->L(
                ($hsl->getL() < 0) ? 0 : $hsl->getL()/100
            );
        } else {
            // We need to find out how much to darken
            $hsl->L(
                $hsl->getL()/2
            );
        }

        return $hsl;
    }

    /**
     * Lightens a given HSL array
     * @param array $hsl
     * @param int $amount
     * @return HSLColor
     */
    public function lighten($amount = self::DEFAULT_ADJUST){
        $hsl = $this->getClone();
        // Check if we were provided a number
        if( $amount ) {
            $hsl->L(
                ($hsl->getL() * 100) + $amount
            );
            $hsl->L(
                ($hsl->getL() > 100) ? 1 : $hsl->getL() / 100
            );
        } else {
            // We need to find out how much to lighten
            $hsl->L(
                $this->getL() + (1 - $hsl->getL()) / 2
            );
        }

        return $hsl;
    }

    public function getClone(){
        return new HSLColor($this->getH(),$this->getS(),$this->getL());
    }

    /**
     * Returns the complimentary color
     * @return HSLColor Complementary color
     *
     */
    public function complementary() {
        // Get our HSL
        $hsl = $this->getClone();
        // Adjust Hue 180 degrees
        $hsl->H(
            $hsl->getH() +  ($hsl->getH() > 180) ? -180 : 180
        );
        return $hsl;
    }

    public function __toString() {
        return sprintf(
            "HSL(%s,%s,%s)",
            $this->getH(),$this->getS(),$this->getL()
        );
    }

    public function mix($rgb2, $amount = 50){
        return $this->ToHex()->mix($rgb2,$amount)->ToHsl();
    }
    public function isLight($lighterThan=130){
        return $this->ToHex()->isLight($lighterThan);
    }
    public function isDark($darkerThan){
        return $this->ToHex()->isDark($darkerThan);
    }
    public function ToHsl(){
        return $this->getClone();
    }

    public function ToRgb(){
        return $this->ToHex()->ToRgb();
    }
    
}

class RGBColor extends AbstractColor{

    private $r;
    private $g;
    private $b;
    
    public function __construct($r, $g, $b)
    {
        $this->R($r);
        $this->G($g);
        $this->B($b);
    }

    private function to_array()
    {
        return array("R"=>$this->r,"G"=>$this->g,"B"=>$this->b);
    }
    public function R($int){
        $this->r = $this->val_after_limiting($int);
        return $this;
    }
    public function G($int){
        $this->g = $this->val_after_limiting($int);
        return $this;
    }
    public function B($int){
        $this->b = $this->val_after_limiting($int);
        return $this;
    }
    public function getR(){
        return $this->r;
    }
    public function getG(){
        return $this->g;
    }
    public function getB(){
        return $this->b;
    }
    //===================
    
    /**
     *  Given an RGB associative array returns the equivalent HEX string
     * @param array $rgb
     * @return HexColor
     * @throws Exception "Bad RGB Array"
     */
    public function ToHex(){
        $rgb = $this->to_array();
        // Make sure it's RGB
        if(empty($rgb) || !isset($rgb["R"]) || !isset($rgb["G"]) || !isset($rgb["B"]) ) {
            throw new Exception("Param was not an RGB array");
        }

        // https://github.com/mexitek/phpColors/issues/25#issuecomment-88354815
        // Convert RGB to HEX
        $hex[0] = str_pad(dechex($rgb['R']), 2, '0', STR_PAD_LEFT);
        $hex[1] = str_pad(dechex($rgb['G']), 2, '0', STR_PAD_LEFT);
        $hex[2] = str_pad(dechex($rgb['B']), 2, '0', STR_PAD_LEFT);

        return new HexColor( 
            implode( '', $hex ) 
        );
    }

    /**
     * Mix 2 rgb colors and return an rgb color
     * @param array $rgb1
     * @param RGBColor $rgb2
     * @param int $amount ranged 0..100
     * @return RGBColor $rgb
     *
     * 	ported from http://phpxref.pagelines.com/nav.html?includes/class.colors.php.source.html
     */
    public function mix($rgb2, $amount = 50) {
        $amount = intval($amount);
        $amount = max(0,$amount);
        $amount = min(100,$amount);

        $r2 = $amount / 100;
        $r1 = 1 - $r2;

        $rgb1 = $this;
        $rmix = (($rgb1->getR() * $r1) + ($rgb2->getR() * $r2));
        $gmix = (($rgb1->getG() * $r1) + ($rgb2->getG() * $r2));
        $bmix = (($rgb1->getB() * $r1) + ($rgb2->getB() * $r2));

        return new RGBColor($rmix, $gmix, $bmix);
    }

    /**
     * Returns whether or not given color is considered "light"
     * @param int $lighterThan
     * @return boolean
     */
    public function isLight($lighterThan = 130 ){
        // Get our color
        $lighterThan = intval($lighterThan);
        return (( $this->getR()*299 + $this->getG()*587 + $this->getB()*114 )/1000 > $lighterThan);
    }

    /**
     * Returns whether or not a given color is considered "dark"
     * @param int $darkerThan
     * @return boolean
     */
    public function isDark($darkerThan = 130 ){
        // Get our color
        $darkerThan = intval($darkerThan);
        return (( $this->getR()*299 + $this->getG()*587 + $this->getB()*114 )/1000 <= $darkerThan);
    }

    public function __toString() {
        return sprintf(
            "RGB(%s,%s,%s)",
            $this->getR(),$this->getG(),$this->getB()
        );
    }

    //==================
    public function getClone(){
        return new RGBColor($this->getR(),$this->getG(),$this->getB());
    }
    public function darken($amount){
        return $this->ToHex()->darken($amount)->ToRgb();
    }
    public function lighten($amount){
        return $this->ToHex()->lighten($amount)->ToRgb();
    }

    public function complementary(){
        return $this->ToHex()->complementary()->ToRgb();
    }
    public function ToHsl(){
        return $this->ToHex()->ToHsl();
    }
    public function ToRgb(){
        return $this->getClone();
    }

    private function val_after_limiting($int)
    {
        return max(min(intval($int), 255), 0);
    }

}

class HexColor extends AbstractColor{

    private $_hex;
    function __construct($hex ) {
        // Strip # sign is present
        $color = $this->removeHashTagIfPresent($hex);

        // Make sure it's 6 digits
        if( strlen($color) === 3 ) {
            $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
        }
        else if( strlen($color) != 6 ) {
            throw new Exception(sprintf("HEX color needs to be 6 or 3 digits long, found %s",$color));
        }
        $this->_hex = $color;

    }

    private function removeHashTagIfPresent($hex)
    {
        return str_replace("#", "", $hex."");
    }

    /**
     * You need to check if you were given a good hex string
     * @param string $hex
     * @return string Color
     * @throws Exception "Bad color format"
     */
    private function _checkHex($hex ) {

        // Strip # sign is present
        $color = $this->removeHashTagIfPresent($hex);

        // Make sure it's 6 digits
        if( strlen($color) == 3 ) {
            $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
        } else if( strlen($color) != 6 ) {
            throw new Exception("HEX color needs to be 6 or 3 digits long");
        }

        return $color;
    }


    //===================
    /** 
     * @return HSLColor
     */
    public function ToHsl(){
        // Convert HEX to DEC
        $rgb = $this->ToRgb();
        $var_R = ($rgb->getR() / 255);
        $var_G = ($rgb->getG() / 255);
        $var_B = ($rgb->getB() / 255);

        $var_Min = min($var_R, $var_G, $var_B);
        $var_Max = max($var_R, $var_G, $var_B);
        $del_Max = $var_Max - $var_Min;

        $H = 0;
        $S = 0;
        $L = ($var_Max + $var_Min)/2;

        if ($del_Max == 0)
        {
            $H = 0;
            $S = 0;
        }
        else
        {
            if ( $L < 0.5 ) $S = $del_Max / ( $var_Max + $var_Min );
            else            $S = $del_Max / ( 2 - $var_Max - $var_Min );

            $del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
            $del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
            $del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

            if      ($var_R == $var_Max) $H = $del_B - $del_G;
            else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
            else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;

            if ($H<0) $H++;
            if ($H>1) $H--;
        }

        return new HSLColor(
            $H*360, $S, $L 
        );        
    }

    /**
     * Given a HEX string returns a RGB array equivalent.
     * @param string $color
     * @return RGBColor
     */
    public function ToRgb(){

        // Sanity check
        $color = $this->_checkHex($this->_hex);

        // Convert HEX to DEC
        $R = hexdec($color[0].$color[1]);
        $G = hexdec($color[2].$color[3]);
        $B = hexdec($color[4].$color[5]);

        return new RGBColor(
            $R, $G, $B
        );        
    }

    /**
     * Given a HEX value, returns a darker color. If no desired amount provided, then the color halfway between
     * given HEX and black will be returned.
     * @param int $amount
     * @return HexColor
     */
    public function darken( $amount = self::DEFAULT_ADJUST ){
        return $this->ToHsl()->darken($amount)->ToHex();
    }

    /**
     * Given a HEX value, returns a lighter color. If no desired amount provided, then the color halfway between
     * given HEX and white will be returned.
     * @param int $amount
     * @return HexColor
     */
    public function lighten($amount = self::DEFAULT_ADJUST ){
        return $this->ToHsl()->lighten($amount)->ToHex();
    }

    /**
     * Given a HEX value, returns a mixed color. If no desired amount provided, then the color mixed by this ratio
     * @param HexColor $hex2 Secondary HEX value to mix with
     * @param int $amount = 0..100
     * @return HexColor mixed HEX value
     */
    public function mix($hex2, $amount = 50){
        return $this->ToRgb()->mix($hex2->ToRgb(),$amount)->ToHex();
    }
    public function mix_white($amount = 50){
        return $this->mix(new HexColor("#ffffff"),$amount);
    }
    public function mix_black($amount = 50){
        return $this->mix(new HexColor("#000000"),$amount);
    }
    

    /**
     * Returns the complimentary color
     * @return HexColor Complementary color
     *
     */
    public function complementary() {
        return $this->ToHsl()->complementary()->ToHex();
    }

    /**
     * Returns whether or not given color is considered "light"
     * @param string|Boolean $color
     * @param int $lighterThan
     * @return boolean
     */
    public function isLight($lighterThan = 130 ){
        return $this->ToRgb()->isLight($lighterThan);
    }
    /**
     * Returns whether or not a given color is considered "dark"
     * @param string|Boolean $color
     * @param int $darkerThan
     * @return boolean
     */
    public function isDark($darkerThan = 130 ){
        return $this->ToRgb()->isDark($darkerThan);
    }

    public function getClone(){
        return new HexColor($this->_hex);
    }

    public function __toString() {
        return "#".$this->_hex;
    }

    public function ToHex(){
        return $this->getClone();
    }

    public function H($int)
    {
        return $this->ToHsl()->H($int)->ToHex();
    }

    public function S($int)
    {
        return $this->ToHsl()->S($int)->ToHex();
    }
    public function L($int)
    {
        return $this->ToHsl()->L($int)->ToHex();
    }
    public function STimes($float,$max=0.9)
    {
        return $this->ToHsl()->Stimes($float,$max)->ToHex();
    }
    public function LTimes($float)
    {
        return $this->ToHsl()->LTimes($float)->ToHex();
    }

    public function HPlus($int)
    {
        return $this->ToHsl()->HPlus($int)->ToHex();
    }


}

class ColorGradient{
    private $color1;
    private $color2;

    public function __construct($color1, $color2)
    {
        $this->color1 = $color1;
        $this->color2 = $color2;
    }
    public function getColor1(){
        return $this->color1;
    }

    public function getColor2(){
        return $this->color2;
    }

    public function to_array(){
        return array( "light" => $this->getColor1(), "dark" => $this->getColor2() );
    }

}

