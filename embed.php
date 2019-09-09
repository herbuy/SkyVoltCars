<?php
print "embed image in markup and upload image";
class MotokaTranslatorForLink{
    public function translate($subject)
    {
        $pattern = $this->pattern();
        $replacement = $this->replacement();

        $output = preg_replace(
            $pattern,
            $replacement,
            $subject
        );
        return $output;
    }

    protected function replacement()
    {
        return "<a href='$1'>$2</a>";
    }

    protected function pattern()
    {
        return "/==link:(.+),text:(.+)==/i";
    }

}
class MotokaTextTranslators{
    public static function link(){
        return new MotokaTranslatorForLink();
    }
}


$subject = "hello this is goood ==link:www.google.com/path/to/file,text:great stuff==";
/*$pattern = "/==link:(.+),text:(.+)==/i";
$replacement = "<a href='$1'>$2</a>";

$output = preg_replace(
    $pattern,
    $replacement,
    $subject
);*/
print "<hr/>";
$output = MotokaTextTranslators::link()->translate($subject); // (new MotokaTranslatorForLink())->process($subject);
//print $output;exit;
print htmlspecialchars($output);


