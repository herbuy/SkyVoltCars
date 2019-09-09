<?php
class ServerVariables{
    private static function at_key($key){
        return array_key_exists($key,$_SERVER) ? $_SERVER[$key] : "";
    }
    public static function http_host(){
        return self::at_key("HTTP_HOST");
    }
    public static function http_user_agent(){
        return self::at_key("HTTP_USER_AGENT");
    }
    public static function http_cookie(){
        return self::at_key("HTTP_COOKIE");
    }
    
    public static function http_referer()
    {
        return self::at_key("HTTP_REFERER");
    }
    /* requires processing of cokkies variable
    public static function php_sess_id(){
        return self::at_key("PHPSESSID");
    }*/
    public static function remote_addr(){
        return self::at_key("REMOTE_ADDR");
    }
    public static function remote_port(){
        return self::at_key("REMOTE_PORT");
    }
    public static function request_scheme(){
        return self::at_key("REQUEST_SCHEME");
    }
    public static function request_method(){
        return self::at_key("REQUEST_METHOD");
    }
    public static function request_uri(){
        return self::at_key("REQUEST_URI");
    }
    public static function request_time(){
        return self::at_key("REQUEST_TIME");
    }
    public static function request_time_float(){
        return self::at_key("REQUEST_TIME_FLOAT");
    }
    public static function server_addr(){
        return self::at_key("SERVER_ADDR");
    }
}
class TheWebsite extends ServerVariables{
    public static function is_online(){
        return !self::is_offline();
    }
    public static function is_offline(){
        return self::http_host() == "localhost";        
    }
    
}

class HttpCookieObject{
    private $assoc_arr_of_cookies = array();
    private $last_error = "";
    public function lastError(){
        return $this->last_error;
    }
    public function __construct()
    {
        $this->process_cookie(ServerVariables::http_cookie());
    }
    public static function get(){
        return new self();
    }

    private function process_cookie($http_cookie_as_string)
    {
        $assoc_arr_of_cookies = array();
        $infer_as_valid_cookie = true;

        $items = explode(";",$http_cookie_as_string);
        if(is_array($items)){
            foreach ($items as $item){
                $sub_items = explode("=",$item);
                if(is_array($sub_items)){
                   if(count($sub_items) == 2){
                       $cookie_name = trim($sub_items[0]);
                       $cookie_value = trim($sub_items[1]);
                       $assoc_arr_of_cookies[$cookie_name] = $cookie_value;
                   }
                    else{
                        $infer_as_valid_cookie = false;
                        break;
                    }
                }
                else{
                    $infer_as_valid_cookie = false;
                    break;
                }
            }
        }
        else{
            $infer_as_valid_cookie = false;
        }

        if($infer_as_valid_cookie){
            $this->assoc_arr_of_cookies = $assoc_arr_of_cookies;
        }
        else{
            $this->last_error = "invalid string for cookie";
        }

    }
    public function atKey($name){
        return array_key_exists($name,$this->assoc_arr_of_cookies) ? $this->assoc_arr_of_cookies[$name] : "";
    }

    public function asArray()
    {
        return $this->assoc_arr_of_cookies;
    }

    public function keys()
    {
        return array_keys($this->assoc_arr_of_cookies);
    }
    public function phpSessId(){
        return $this->atKey("PHPSESSID");
    }
}

//this library will be used to build some security enhancement in to the software
class SecureSession{
    public static function getId(){
        $session_id = HttpCookieObject::get()->phpSessId();
        $ip = ServerVariables::remote_addr();
        return base64_encode( $session_id."__".$ip);
    }
    public static function getIdAfterSha1(){
        $session_id = HttpCookieObject::get()->phpSessId();
        $ip = ServerVariables::remote_addr();
        return sha1($session_id."__".$ip);
    }
    
    public static function save_content($key, $content){
        $file_name = self::get_file_name($key);
        file_put_contents($file_name,$content);
    }
    public static function get_content($key){
        $file_name = self::get_file_name($key);
        $content= file_exists($file_name) ? file_get_contents($file_name) : "";
        return $content;
    }
    public static function key_exists($key){
        $file_name = self::get_file_name($key);
        return file_exists($file_name);
    }
    public static function delete_content($key){
        $file_name = self::get_file_name($key);
        if(file_exists($file_name)){
            unlink($file_name);
        }
    }

    /**
     * @param $key
     * @return string
     */
    private static function get_file_name($key)
    {
        $file_name = sprintf("%s__%s__.tmp", self::getId(), $key);
        return $file_name;
    }
}
class DuplicateRequestDetector{
    public static function is_duplicate_request($unique_key_that_repeats_on_refresh){
        return SecureSession::key_exists($unique_key_that_repeats_on_refresh);
    }

    public static function register_request($key)
    {
        SecureSession::save_content($key,"");
    }
}

class EntityIdGenerator{
    public static function newId(){
        $date = date("diYshm");
        $rand = rand(0,99999);
        $rand = str_pad($rand,5,"0");

        $final = $rand.$date;
        return $final;
    }
}

//TEST
//print SecureSession::getId();
//SecureSession::save_content("previous_page","www.smcash/products");
//print "done saving content";
//print SecureSession::get_content("previous_page");
//SecureSession::delete_content("previous_page");
//print "done";

