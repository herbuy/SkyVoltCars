<?php
class SitemapException{
    public static function throwIfNot($condition,$message){
        if(!$condition){
            throw new Exception($message);
        }
    }
    public static function throwIfNotUrl($url){
        self::throwIfNot(is_a($url,"SitemapUrl"),"expected a sitemap url");
    }

    public static function throwIfNotUrlSet($urlset)
    {
        self::throwIfNot(is_a($urlset,"SitemapUrlSet"),"expected a sitemap url set");
    }
}
class SitemapUrl{

    private $loc = "";
    public function loc($string)
    {
        $this->loc = $string;
        return $this;
    }
    public function get_loc(){
        return $this->loc;
    }
    private $lastmod = "";
    public function lastmod($string)
    {
        $this->lastmod = $string;
        return $this;
    }
    private $priority = "";
    public function priority($string)
    {
        $this->priority = $string;
        return $this;
    }
    public function __toString()
    {
        $delimiter = chr(10).chr(13);
        return sprintf(
            "<url>%s<loc>%s</loc>%s<lastmod>%s</lastmod>%s<priority>%s</priority>%s</url>%s",
            $delimiter,htmlspecialchars($this->loc),$delimiter, htmlspecialchars($this->lastmod),
            $delimiter, htmlspecialchars($this->priority),$delimiter,$delimiter
        );
    }
}
class SitemapUrlSet{
    private $urls = [];
    public function addUrl($sitemap_url){
        SitemapException::throwIfNotUrl($sitemap_url);
        $this->urls[$sitemap_url->get_loc()] = $sitemap_url;
        return $this;
    }
    public function addUrlFromString($string){
        $this->addUrl(
            Sitemap::url()->loc($string)->lastmod("")->priority("0.50")
        );
        return $this;
    }
    public function removeUrlWithString($string){
        $this->urls = array_diff_key($this->urls,array($string=>""));
        return $this;
    }

    public function __toString()
    {
        $urls_as_string = join("",$this->urls);
        $delimiter = chr(10).chr(13);
        $urlset_as_string = sprintf(
            "<urlset %s>%s%s%s</urlset>%s",
            $this->attribute_string(),$delimiter,$urls_as_string,$delimiter,$delimiter
        );
        return $urlset_as_string;
    }

    private function attribute_string()
    {
        $attribute_string = 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"';

        return $attribute_string;
    }
}
class Sitemap{
    public static function new_instance(){
        return new self();
    }
    public static function url(){
        return new SitemapUrl();
    }

    public static function urlSet()
    {
        return new SitemapUrlSet();
    }

    private $url_sets = [];
    public function addUrlSet($urlset)
    {
        SitemapException::throwIfNotUrlSet($urlset);
        $this->url_sets[] = $urlset;
        return $this;
    }
    
    public function addUrlFromString($sitemap_url){
        $this->lastUrlSet()->addUrlFromString($sitemap_url);
        return $this;
    }
    public function removeUrlWithString($sitemap_url){
        $this->lastUrlSet()->removeUrlWithString($sitemap_url);
        return $this;
    }

    public function __toString()
    {
        return $this->header_string() . join("",$this->url_sets);
    }

    private function header_string()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'.chr(10).chr(13);
    }

    public function serialize(){
        return serialize($this);
    }
    public static function unserialize($string){
        return unserialize($string);
    }
    public function serializeToFile($fileName){
        file_put_contents($fileName,$this->serialize());
        return $this;
    }
    public static function unserializeFile($fileName){
        $obj  = self::unserialize(file_get_contents($fileName));
        return $obj;
    }

    public function countUrlSets()
    {
        return count($this->url_sets);
    }

    public function lastUrlSet()
    {
        if($this->countUrlSets() < 1){
            $this->addUrlSet(Sitemap::urlSet());
        }
        return $this->url_sets[$this->countUrlSets()-1];
    }
    
    public function build($output_file_name){
        file_put_contents($output_file_name,$this->__toString());
        return $this;
    }
}

//test
/*
$file_with_map = "sitemap.phpobject";
$saved_map = Sitemap::unserializeFile($file_with_map);
$site_map = Sitemap::new_instance();
if($saved_map){
    $site_map = $saved_map;
}
else{
    $site_map->addUrlSet(
        Sitemap::urlSet()->
        addUrl(
            Sitemap::url()->
            loc("http://www.papa101.com/menu")->
            lastmod("2017-12-19T20:12:54+00:00")->
            priority("0.80")
        )->
        addUrl(
            Sitemap::url()->
            loc("http://www.papa101.com/menu")->
            lastmod("2017-12-19T20:12:54+00:00")->
            priority("0.80")
        )
    );
    $site_map->serializeToFile($file_with_map);
}
print htmlspecialchars($site_map);*/