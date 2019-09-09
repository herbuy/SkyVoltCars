<?php
class ReaderForValuesStoredInArray extends BaseClassOfReaderForDataStoredInArray{
    
    /*public function get_reader_for_item_at($entity_index){
        $item = $this->at($entity_index);
        $item = is_array($item) ? $item : [];
        return app::reader($item);
    }
    public function get_reader_for_item_1(){
        return $this->get_reader_for_item_at(0);
    }
    public function get_reader_for_item_2(){
        return $this->get_reader_for_item_at(1);
    }
    public function get_reader_for_item_3(){
        return $this->get_reader_for_item_at(2);
    }
    public function get_reader_for_item_4(){
        return $this->get_reader_for_item_at(3);
    }
    public function get_reader_for_item_5(){
        return $this->get_reader_for_item_at(4);
    }
    public function get_reader_for_item_6(){
        return $this->get_reader_for_item_at(5);
    }
    public function get_reader_for_item_7(){
        return $this->get_reader_for_item_at(6);
    }
    public function get_reader_for_item_8(){
        return $this->get_reader_for_item_at(7);
    }

    public function get_reader_for_item_9(){
        return $this->get_reader_for_item_at(8);
    }

    public function get_reader_for_item_10(){
        return $this->get_reader_for_item_at(9);
    }*/


    protected function getNewInstance($array){
        return new self($array);
    }
    //==============================
    
    public function error(){
        return $this->read_key(app::values()->error());
    }
    public function content(){
        return $this->read_key(app::values()->content());
    }
    public function extended_post_content(){
        return $this->read_key(app::values()->extended_post_content());
    }
    public function keywords(){
        return $this->read_key(app::values()->keywords());
    }
    public function title(){
        return $this->read_key(app::values()->title());
    }
    public function rating(){
        return $this->read_key(app::values()->rating());
    }
    public function timestamp(){//rowid, cat, file, enti
        return $this->read_key(app::values()->timestamp());
    }
    public function row_id(){
        return $this->read_key(app::values()->row_id());
    }
    public function category(){
        return $this->read_key(app::values()->category());
    }
    public function file_name(){
        return $this->read_key(app::values()->file_name());
    }
    public function picture_file_name(){
        return $this->read_key(app::values()->picture_file_name());
    }
    public function entity_id(){
        return $this->read_key(app::values()->entity_id());
    }
    public function section_id(){
        return $this->read_key(app::values()->section_id());
    }
    public function total_posts(){
        return $this->read_key(app::values()->total_posts());
    }

    public function content_type(){
        return $this->read_key(app::values()->content_type());
    }
    public function src(){
        return $this->read_key(app::values()->src());
    }
    public function youtube_video_id(){
        return $this->read_key(app::values()->youtube_video_id());
    }

    public function width(){
        return $this->read_key(app::values()->width());
    }
    public function height(){
        return $this->read_key(app::values()->height());
    }
    public function href(){
        return $this->read_key(app::values()->href());
    }
    public function alt(){
        return $this->read_key(app::values()->alt());
    }

    public function year_number(){
        return $this->read_key(app::values()->year_number());
    }
    public function month_description(){
        return $this->read_key(app::values()->month_description());
    }
    public function week_of_the_year_description(){
        return $this->read_key(app::values()->week_of_the_year_description());
    }
    public function day_of_the_year_description(){
        return $this->read_key(app::values()->day_of_the_year_description());
    }

    public function email_address(){
        return $this->read_key(app::values()->email_address());
    }
    public function views(){
        return $this->read_key(app::values()->views());
    }
    public function likes(){
        return $this->read_key(app::values()->likes());
    }
    public function comments(){
        return $this->read_key(app::values()->comments());
    }
}