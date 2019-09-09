<?php
abstract class UrlToRedirectTo{
    private $value;
    private $id_of_element_to_seek_to = "";
    private $parameters_as_assoc_array = array();

    public function seeksToElementId($id){
        $this->id_of_element_to_seek_to = $id;
        return $this;
    }

    protected function __construct($url,$id_of_element_to_seek_to = '',$remove_query_string=false)
    {
        if($remove_query_string){
            $url = $this->urlAfterRemoveQueryString($url);
        }
        $this->value = $url;
        $this->id_of_element_to_seek_to = $id_of_element_to_seek_to;
    }

    public function gotoAddress(){
        $header = new HeaderForLocation($this."");
        $header->sendToBrowser();
    }

    public function gotoAddressIfSubmittedForm(){
        if(ContentTypeSentToServer::get()->is_multi_part_form_data()){
            $this->gotoAddress();
        }
    }
    
    public function add_parameter($key,$value){
        $this->parameters_as_assoc_array[] = join("=",array($key,$value));
        return $this;
    }
    public function __toString()
    {
        $url = $this->value;      
        $query_string = $this->query_string();

        $url = strlen($query_string) > 0 ? join("?",array($url,$query_string)) : $url;
        $url = $this->id_of_element_to_seek_to ? join("#",array($url,$this->id_of_element_to_seek_to)): $url;        
        return $url;
    }

    private function query_string()
    {
        return join("&",$this->parameters_as_assoc_array);
    }

    private function urlAfterRemoveQueryString($url)
    {
        $parts = explode("?",$url);
        if(!is_array($parts)){
            return $url;
        }
        return $parts[0];        
    }
    protected function read_key($array,$key){
        return array_key_exists($key,$array) ? $array[$key] : "";
    }
}

class UrlOfRefererPage extends UrlToRedirectTo{
    private $value;
    
    protected function __construct()
    {
        parent::__construct($this->read_key($_SERVER,"HTTP_REFERER"));
    }
    public static function get(){
        return new self();
    }

}

class UrlThatWasRedirected extends UrlToRedirectTo{
    private $value;

    protected function __construct()
    {
        parent::__construct($this->read_key($_SERVER,"REDIRECT_URL"));
    }
    public static function get(){
        return new self();
    }

}

class UrlOfCurrentRequest extends UrlToRedirectTo{    
    protected function __construct()
    {
        parent::__construct($this->read_key($_SERVER,"REQUEST_URI"));
    }
    public static function get(){
        return new self();
    }    
}
class UrlOfCurrentRequestAfterRemoveQueryString extends UrlToRedirectTo{
    protected function __construct()
    {
        parent::__construct($this->read_key($_SERVER,"REQUEST_URI"),"",true);
    }
    public static function get(){
        return new self();
    }
}
class ContentTypeSentToServer{
    private $value;

    protected function __construct()
    {
        $this->value = array_key_exists("CONTENT_TYPE",$_SERVER) ?  $_SERVER["CONTENT_TYPE"] : "";
    }
    public static function get(){
        return new self();
    }
    public function is_multi_part_form_data(){
        return preg_match("/multipart\/form-data/i",$this->value);
    }
}


//======================= response headers =========================
abstract class ResponseHeader{
    private $content;
    public function __construct($content)
    {
        $this->content = $content;
    }

    public function sendToBrowser()
    {
        ob_clean();
        header($this->content);
        exit;
    }
}
class HeaderForLocation extends ResponseHeader{
    public function __construct($url)
    {
        parent::__construct(sprintf("location: %s",$url));
    }
}

