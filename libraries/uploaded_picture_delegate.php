<?php
class DelegateForUploadedPictureFileToSave{
    private $tmp_name;
    private $short_file_name;
    private $extension;

    public function __construct($key_in_files_array_as_string)
    {
        if(!array_key_exists($key_in_files_array_as_string,$_FILES) || "" == $_FILES[$key_in_files_array_as_string] ){
            throw new Exception("no file to upload");
        }
        $file_info = $_FILES[$key_in_files_array_as_string];
        $this->tmp_name = $this->get_tmp_name($file_info);
        $this->short_file_name = $file_info['name'];
        
        $this->extension = $this->determine_file_extension();
    }

    private function temp_name(){
        return $this->tmp_name;
    }

    private function file_extension_to_lowercase(){
        return strtolower($this->extension);
    }
    private function determine_file_extension(){
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
        return $target_folder . "/" . $this->short_file_name;
    }
}


class TmpFile{
    private $tmp_name;
    private $short_file_name;
    private $extension;

    public function __construct($key_in_files_array_as_string)
    {
        if(!array_key_exists($key_in_files_array_as_string,$_FILES) || "" == $_FILES[$key_in_files_array_as_string] ){
            throw new Exception("no file to upload");
        }
        $file_info = $_FILES[$key_in_files_array_as_string];
        $this->tmp_name = $this->get_tmp_name($file_info);
        $this->short_file_name = $file_info['name'];

        $this->extension = $this->determine_file_extension();
    }

    protected function temp_name(){
        return $this->tmp_name;
    }

    private function file_extension_to_lowercase(){
        return strtolower($this->extension);
    }
    private function determine_file_extension(){
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
}
class TmpFileAfterSave extends TmpFile{
    private $dest_file_path;
    public function __construct($key_in_files_array,$dest_file_name,$is_uploaded_file = true)
    {
        parent::__construct($key_in_files_array);
        
        $src_file_name = $this->temp_name();

        if($is_uploaded_file){
            if(!move_uploaded_file($src_file_name,$dest_file_name)){
                throw new Exception("could not move file");
            }
        }
        $this->dest_file_path = $dest_file_name;
    }
    public function dest_file_path(){
        return $this->dest_file_path;
    }
    public function extension_to_lowercase(){
        return strtolower($this->extension());
    }
    public function extension(){
        $subparts = explode(".",$this->dest_file_path);
        return $subparts[count($subparts)-1];
    }
    public function delete(){
        try{
            unlink($this->dest_file_path);
        }
        catch(Exception $ex){

        }
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
                return new JPEGPictureThumbnail($saved_file->dest_file_path(), $width, $height,$retain_aspect_ratio);
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
}