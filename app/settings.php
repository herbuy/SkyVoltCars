<?php
class WebsiteSettings{
    public function db_host(){
        return TheWebsite::is_offline() ? "localhost": "motokaviews.com.mysql";
    }
    public function db_username(){
        return TheWebsite::is_offline() ? "root": "motokaviews_com";
    }
    public function db_password(){
        return TheWebsite::is_offline() ? "usbw": "4hRePjnNhERU9ZTiAVXv2Tmy";
    }
    public function db_name(){
        return TheWebsite::is_offline() ? "motokaviews": "motokaviews_com";
    }

    public function build_web_address($web_address){
        if(TheWebsite::is_online()){
            $web_address->add_domain_component("www");
            $web_address->add_domain_component("skyvolt");
            $web_address->add_domain_component("com");
        }
        else{
            $web_address->add_domain_component("localhost");
            $web_address->add_path_part("motokaviews");
        }
        return $web_address;
    }
    
    public function host_file_for_php_sitemap(){
        return TheWebsite::is_offline() ? "sitemap.phpobject.localhost": "sitemap.phpobject";        
    }

    public function host_file_for_xml_sitemap(){
        return TheWebsite::is_offline() ? "sitemap.localhost.xml": "sitemap.xml";
    }

}