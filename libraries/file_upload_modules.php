<?php

class UploadedFileToSave{
    private $tmp_name;
    private $short_file_name;
    public function __construct($key_in_files_array_as_string)
    {
        if(!array_key_exists($key_in_files_array_as_string,$_FILES) || "" == $_FILES[$key_in_files_array_as_string] ){
            throw new Exception("no file to upload");
        }
        $file_info = $_FILES[$key_in_files_array_as_string];
        $this->tmp_name = $this->get_tmp_name($file_info);
        $this->short_file_name = $file_info['name'];
    }
    protected function temp_name(){
        return $this->tmp_name;
    }

    protected function file_extension_to_lowercase(){
        return strtolower($this->file_extension());
    }
    protected function file_extension(){
        $parts = explode(".",$this->short_file_name);
        return !is_array($parts) || count($parts) < 1 ? "": $parts[count($parts) - 1];
    }
    private $message_if_no_file_to_upload = 'File to upload not specified';

    private function get_tmp_name($file_info)
    {
        $tmp_name = $file_info['tmp_name'];
        if(trim($tmp_name) == ""){
            throw new Exception($this->message_if_no_file_to_upload);
        }
        return $tmp_name;
    }

    /** @return SavedFile */
    public function saveToFolder($target_folder){
        
        return new SavedFile($this->temp_name(), $this->create_file_path($target_folder));
    }

    /** @return SavedPictureFile */
    public function saveAsPictureToFolder($target_folder){
        return new SavedPictureFile($this->temp_name(), $this->create_file_path($target_folder));
    }

    /**
     * @param $target_folder
     * @return string
     */
    private function create_file_path($target_folder)
    {
        return $target_folder . "/" . $this->unique_id_to_avoid_overwriting()."-". $this->short_file_name;
    }
    private function unique_id_to_avoid_overwriting(){
        return PictureIdGenerator::newId();
    }

}
class PictureIdGenerator{
    public static function newId(){
        return str_pad( rand(0,99999), 5) . date("diYshm");
    }
}

class UploadedPictureFileToSave extends UploadedFileToSave{
    private $image_size;
    public function __construct($key_in_files_array_as_string)
    {
        parent::__construct($key_in_files_array_as_string);

        $this->image_size = getimagesize($this->temp_name());
        if(!$this->image_size){
            throw new Exception("invalid picture file");
        }

        /*if(
            $this->file_extension_to_lowercase() != "jpg" &&
            $this->file_extension_to_lowercase() != "jpeg"){
            throw new Exception("only jpeg images can be uploaded for now");
        }*/
    }
    protected function image_size(){
        return $this->image_size;
    }

    /** @return SavedAndVersionedPictureFile */
    public function saveAndVersionUploadedPicture($dest_folder_path)
    {        
        $saved_picture_file = $this->saveAsPictureToFolder($dest_folder_path);
        $saved_and_versioned_picture_file = $saved_picture_file->create_versions();
        return $saved_and_versioned_picture_file;
    }
}
class SavedFile{
    private $file_path;
    public function __construct($src_file_name,$dest_file_name,$is_uploaded_file = true)
    {
        
        if($is_uploaded_file){
            if(!move_uploaded_file($src_file_name,$dest_file_name)){
                throw new Exception("could not move file");
            }
        }
        else if(!copy($src_file_name,$dest_file_name)){
            throw new Exception("could not copy file");
        }
        $this->file_path = $dest_file_name;
    }
    public function file_path(){
        return $this->file_path;
    }
    public function extension_to_lowercase(){
        return strtolower($this->extension());
    }
    public function extension(){
        $subparts = explode(".",$this->file_path);
        return $subparts[count($subparts)-1];
    }
    public function delete(){
        try{
            unlink($this->file_path);
        }
        catch(Exception $ex){
            
        }
    }

}
class SavedPictureFile extends SavedFile{
    
    /** @return SavedAndVersionedPictureFile  */
    public function create_versions($retain_aspect_ratio = true)
    {
        $picture_id = PictureIdGenerator::newId();
        
        //create versions of the image

        $desired_image_dimensions = array(
            array(50, 50, "$picture_id" . "_icon"),
            array(100, 100, "$picture_id" . "_small"),
            array(200, 200, "$picture_id" . "_medium"),
            array(800, 600, "$picture_id" . "_large")
        );
        for ($i = 0; $i < count($desired_image_dimensions); $i++) {
            $dimensions = $desired_image_dimensions[$i];
            $width = $dimensions[0];
            $height = $dimensions[1];
            $pictureThumbnail = $this->cloneImage($width, $height,$retain_aspect_ratio);
            $pictureThumbnail->renameFileTo($dimensions[2]);

        }
        return new SavedAndVersionedPictureFile($picture_id);
    }

    /**
     * @param SavedFile $saved_file
     * @param $width
     * @param $height
     * @return JPEGPictureThumbnail
     */
    private function cloneImage($width, $height,$retain_aspect_ratio=true)
    {
        $saved_file = $this;

        switch($saved_file->extension_to_lowercase()){
            case "jpg":
                return new JPEGPictureThumbnail($saved_file->file_path(), $width, $height,$retain_aspect_ratio);
                break;
            case "jpeg":
                return new JPEGPictureThumbnail($saved_file->file_path(), $width, $height,$retain_aspect_ratio);
                break;
            case "png":
                return new PngPictureThumbnail($saved_file->file_path(), $width, $height,$retain_aspect_ratio);
                break;
            case "gif":
                return new GifPictureThumbnail($saved_file->file_path(), $width, $height,$retain_aspect_ratio);
                break;
            default:                
                throw new Exception("unsupported picture type");
                break;
        }

    }
    public function getThumbnail($width, $height,$retain_aspect_ratio=true){
        return $this->cloneImage($width,$height,$retain_aspect_ratio);
    }
}

class SavedAndVersionedPictureFile{
    private $picture_id;
    public function picture_id(){
        return $this->picture_id;
    }
    public function __construct($picture_id)
    {
        $this->picture_id = $picture_id;
    }
}


//=============== picture slicing classes ======

abstract class BasePictureFileToSlice{
    private $path_to_picture_file;
    private $destination_file_name = "";

    public function __construct($path_to_picture_file,$nx,$ny,$retain_aspect_ratio = false)
    {
        if(!file_exists($path_to_picture_file)){
            throw new Exception("file to slice does not exist");
        }
        $image_size = getimagesize($path_to_picture_file);
        if(!$image_size){
            throw new Exception("invalid picture file to slice");
        }
        $this->path_to_picture_file = $path_to_picture_file;
        $this->destination_file_name = dirname($this->path_to_picture_file) . "/".base64_encode(rand()).".". $this->getImageExtension();
        $this->slice($nx,$ny,$retain_aspect_ratio);

    }

    private function slice($nx,$ny,$retain_aspect_ratio = false){
        list($nx, $ny) = $this->determine_final_dimensions($nx, $ny, $retain_aspect_ratio);
        $nm = $this->createTrueColorImage($nx, $ny);
        ini_set("memory_limit","128M");
        $im = @$this->loadImageFromFile($this->path_to_picture_file);
        if(!$im){
            $im = @$this->tryLoadingImageByBruteForce($this->path_to_picture_file);
            if(!$im){
                throw new Exception("Could not read file content");
            }
        }
        imagecopyresampled($nm, $im, 0, 0, 0, 0, $nx, $ny, imagesx($im), imagesy($im));
        $this->renderImageOutput($nm, $this->destination_file_name);
    }


    function maximizeWidthAndHeight($width_to_maximize,$height_to_maximize,$width_not_to_exceed,$height_not_to_exceed){

        //two states: a) width smaller than height. B. height smaller than width
        $computed_width = $width_to_maximize;
        $computed_height = $height_to_maximize;
        //-------
        $aspect_ratio = $width_to_maximize / $height_to_maximize;
        if($width_to_maximize != $width_not_to_exceed){
            $computed_width = $width_not_to_exceed;
            $computed_height = $computed_width / $aspect_ratio;
        }

        if($computed_height > $height_not_to_exceed){
            $computed_height = $height_not_to_exceed;
            $computed_width = $computed_height * $aspect_ratio;
        }
        if($computed_width <= $width_not_to_exceed && $computed_height <= $height_not_to_exceed){
            return array($computed_width,$computed_height);
        }
        else{
            throw new Exception("bug in computation");
        }
    }

    private function createTrueColorImage($nx, $ny)
    {
        $nm = imagecreatetruecolor($nx, $ny);
        imageantialias($nm, true);
        imagealphablending($nm, false);
        imagesavealpha($nm, true);
        $bgcolor = imagecolorallocatealpha($nm, 255, 255, 255, 0);
        imagecolortransparent($nm, $bgcolor);
        return $nm;
    }

    public function temporary_path()
    {
        return $this->destination_file_name;
    }

    public function renameFileTo($new_name){
        $new_short_file_name = $new_name.".".$this->getImageExtension();
        
        $new_file_path = dirname($this->destination_file_name)."/".$new_short_file_name;
                
        try{
            rename($this->destination_file_name,$new_file_path);
            $this->destination_file_name = $new_file_path;
        }
        catch(Exception $ex){
            throw new Exception("invalid file name: ".$new_file_path);
        }
        return $new_short_file_name;

    }

    abstract protected function loadImageFromFile($file_path);
    abstract protected function renderImageOutput($nm,$destinationFilename = null,$quality = null);
    abstract public function getImageExtension();

    /**
     * @param $nx
     * @param $ny
     * @param $retain_aspect_ratio
     * @return array
     * @throws Exception
     */
    private function determine_final_dimensions($nx, $ny, $retain_aspect_ratio)
    {
        if(!$retain_aspect_ratio){
            return array($nx, $ny);
        }
        $image_size_info = getimagesize($this->path_to_picture_file);
        $image_width = $image_size_info[0];
        $image_height = $image_size_info[1];
        $computed_dimensions = $this->maximizeWidthAndHeight($image_width, $image_height, $nx, $ny);
        $nx = $computed_dimensions[0];
        $ny = $computed_dimensions[1];
        return array($nx, $ny);
    }

    private function tryLoadingImageByBruteForce($file_path)
    {
        $img = @imagecreatefromjpeg($file_path);
        if(!$img){
            $img = @imagecreatefrompng($file_path);
            if(!$img){
                $img = @imagecreatefromgif($file_path);
                if(!$img){
                    $img = @imagecreatefromgd($file_path);
                    if(!$img){
                        $img = @imagecreatefromgd2($file_path);
                        if(!$img){
                            $img = @imagecreatefromwbmp($file_path);
                            if(!$img){
                                $img = @imagecreatefromxbm($file_path);
                                if(!$img){
                                    $img = @imagecreatefromxpm($file_path);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $img;
    }
}

class JPEGPictureThumbnail extends BasePictureFileToSlice{
    public function getImageExtension()
    {
        return "jpg";
    }
    protected function loadImageFromFile($file_path)
    {

        return imagecreatefromjpeg($file_path);
    }

    protected function renderImageOutput($nm,$destinationFilename = null,$quality = 99)
    {
        return imagejpeg($nm, $destinationFilename,$quality);
    }
}

class PngPictureThumbnail extends BasePictureFileToSlice{
    public function getImageExtension()
    {
        return "png";
    }
    protected function loadImageFromFile($file_path)
    {
        return imagecreatefrompng($file_path);
    }

    protected function renderImageOutput($nm,$destinationFilename = null,$quality = 9)
    {
        return imagepng($nm, $destinationFilename,$quality);
    }
}
class GifPictureThumbnail extends BasePictureFileToSlice{
    public function getImageExtension()
    {
        return "gif";
    }
    protected function loadImageFromFile($file_path)
    {
        return imagecreatefromgif($file_path);
    }

    protected function renderImageOutput($nm,$destinationFilename = null,$quality = 99)
    {
        return imagegif($nm, $destinationFilename);
    }
}

//============

//picture processing classes
class PictureToDisplay{
    private $path;
    public function __construct($pictureIdToDisplay, $sizeToDisplay,$source_folder_path)
    {

        $this->path = join("", array("$source_folder_path/",$pictureIdToDisplay, "_", $sizeToDisplay, ".jpg"));
        if(!file_exists($this->path)){            
            throw new Exception("picture file does not exist");
        }
    }
    public function display(){

        $img = imagecreatefromjpeg($this->path);
        ob_clean();
        header("Content-Type:image/jpeg");
        imagejpeg($img,null,99);
        exit;
    }
}
