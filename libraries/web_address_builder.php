<?php

//print "web address builder";

abstract class WebAddress{

    private $protocol;
    private $domain_parts_array = array();
    private $directory_array = array();
    private $filename = "";
    private $query_paramaters_array = array();
    private $fragment = "";

    protected function set_protocol_to($string)
    {
        $this->protocol = urlencode($string);
    }

    public function add_domain_component($string)
    {
        $this->domain_parts_array[] = urlencode($string);
    }

    public function add_path_part($string)
    {
        $this->directory_array[] = urlencode($string);
    }
    public function set_file_name($string)
    {
        $this->filename = urlencode($string);
    }
    public function set_query_parameter($key, $value)
    {
        $this->query_paramaters_array[urlencode($key)] = urlencode($value);
    }
    public function set_fragment($string)
    {
        $this->fragment = urlencode($string);
    }
    public function __toString()
    {
        $protocol_string = $this->create_protocol_string($this->protocol);
        $domain_string = join(".",$this->domain_parts_array);
        $directory_string = $this->create_directory_string($this->directory_array);
        $file_string = $this->create_file_string($this->filename);
        $query_string = $this->create_query_string($this->query_paramaters_array);
        $fragment_string = $this->create_fragment_string($this->fragment);

        $web_address_string = join("",array(
            $protocol_string,
            $domain_string,$directory_string,$file_string,$query_string,$fragment_string
        ));
        return $web_address_string;
    }

    private function create_query_string($query_paramaters_array)
    {
        $query_string = "";
        $separator = "";
        foreach ($query_paramaters_array as $key=>$value){
            $query_string .= $separator.$key."=".$value;
            $separator = "&";
        }

        $query_string = $query_string ? "?$query_string":"";
        return $query_string;
    }

    private function create_directory_string($directory_array)
    {
        return count($directory_array) > 0 ? "/" . join("/", $directory_array) : "";
    }

    private function create_file_string($filename)
    {
        return strlen(trim($filename)) > 0 ? "/" . trim($filename) : "";
    }

    private function create_fragment_string($fragment)
    {
        return strlen(trim($fragment)) > 0 ? "#" . $fragment : "";
    }

    private function create_protocol_string($protocol)
    {
        return $protocol . "://";
    }


}

class HttpWebAddress extends WebAddress{
    public function __construct()
    {
        $this->set_protocol_to("http");
    }

}
class HttpsWebAddress extends WebAddress{
    public function __construct()
    {
        $this->set_protocol_to("https");
    }
}
class FtpWebAddress extends WebAddress{
    public function __construct()
    {
        $this->set_protocol_to("ftp");
    }
}
class FtpsWebAddress extends WebAddress{
    public function __construct()
    {
        $this->set_protocol_to("ftps");
    }
}
class FileAddress extends WebAddress{
    public function __construct()
    {
        $this->set_protocol_to("file");
    }
}
class MailtoAddress extends WebAddress{
    public function __construct()
    {
        $this->set_protocol_to("mailto");
    }
}


?>