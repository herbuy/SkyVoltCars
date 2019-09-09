<?php
class SectionForExtendedPostTokens extends SmartDiv{
    /** @param \ReaderForValuesStoredInArray $reader_for_extended_post_tokens */
    public function __construct($reader_for_extended_post_tokens)
    {
        ui::exception()->throwIfNotReader($reader_for_extended_post_tokens);
        parent::__construct();
        $this->font_variant("initial");
        

        $count = $reader_for_extended_post_tokens->count();
                
        for ($i = 0; $i < $count;$i++){
            $item_reader = $reader_for_extended_post_tokens->get_reader_for_item_at($i);
            $content_type_identifier_string = $item_reader->content_type();
            
            $contentType = (new ContentTypeIdentifier($content_type_identifier_string))->
            toContentType();            
            $this->add_child($contentType->toHtml($item_reader));
        }
    }
}

class ContentTypeIdentifier{
    private $contentType;

    public function __construct($content_type_identifier)
    {
        switch ($content_type_identifier){
            case app::content_type_id()->span():
                $this->contentType = new ContentTypeForSpan();
                break;
            case app::content_type_id()->img():
                $this->contentType = new ContentTypeForImage();
                break;
            case app::content_type_id()->youtube_video():
                $this->contentType = new ContentTypeForYoutubeVideo();
                break;
            case app::content_type_id()->link():
                $this->contentType = new ContentTypeForLink();
                break;
            case app::content_type_id()->br():
                $this->contentType = new ContentTypeForBreak();
                break;
            case app::content_type_id()->space():
                $this->contentType = new ContentTypeForSpace();
                break;
            case app::content_type_id()->subhead():
                $this->contentType = new ContentTypeForSubhead();
                break;
            case app::content_type_id()->tintscreen():
                $this->contentType = new ContentTypeForTintScreen();
                break;
            case app::content_type_id()->columnrule():
                $this->contentType = new ContentTypeForColumnRule();
                break;
            case app::content_type_id()->quote():
                $this->contentType = new ContentTypeForQuote();
                break;
            case app::content_type_id()->bold():
                $this->contentType = new ContentTypeForBold();
                break;
            case app::content_type_id()->italics():
                $this->contentType = new ContentTypeForItalics();
                break;

            case app::content_type_id()->rating():
                $this->contentType = new ContentTypeForRating();
                break;

            case app::content_type_id()->at_symbol():
                $this->contentType = new ContentTypeForAtSymbol();
                break;

            default:
                $this->contentType = new ContentTypeForUnknown();
                break;            
        }
    }

    public function toContentType()
    {
        return $this->contentType;
    }

}
abstract class ContentTypeForIdentifier{
    /** @param \ReaderForValuesStoredInArray $item_reader */
    abstract public function toHtml($item_reader);
}

class ContentTypeForSpan extends ContentTypeForIdentifier{
    public function toHtml($item_reader)
    {
        $tag = new SmartSpan();
        $tag->add_child($item_reader->content());
        $tag->width_auto()->display_inline();
        return $tag;
    }
}
class ContentTypeForBold extends ContentTypeForSpan{
    public function toHtml($item_reader)
    {
        return parent::toHtml($item_reader)->font_weight_bold();
    }
}


class ContentTypeForItalics extends ContentTypeForSpan{
    public function toHtml($item_reader)
    {
        return parent::toHtml($item_reader)->font_style("italics");
    }
}


class ContentTypeForAtSymbol extends ContentTypeForSpan{
}

class ContentTypeForTintScreen extends ContentTypeForIdentifier{
    public function toHtml($item_reader)
    {
        $tag = new SmartSpan();
        $tag->add_child($item_reader->content());
        $tag->width("33%")->min_width("160px")->padding("1.0em")->margin_right("1.0em")->background_color("#eee")->float_left();
        return $tag;
    }
}


class ContentTypeForSubhead extends ContentTypeForIdentifier{
    public function toHtml($item_reader)
    {
        $tag = new SmartHeading3();
        $tag->add_child($item_reader->content());

        $tag->border_left("8px solid tomato")->margin_left("1.0em")->padding_left("1.0em");
        return $tag;
    }
}

class ContentTypeForRating extends ContentTypeForIdentifier{
    public function toHtml($item_reader)
    {
        $title = new SmartBold();
        $title->add_child(
            sprintf("%s:- %s<br/>%s<br/>",
                SmartUtils::capitalize_first_letter(trim($item_reader->title())),
                ui::html()->span()->add_child(
                    SmartUtils::capitalize_first_letter($item_reader->rating())
                )->width_auto()->color(ui::colors()->header_bg()),
                $this->starsFromRating($item_reader)->vertical_align_middle()
            )
        );

        $title->border_left("8px solid tomato")/*->margin_left("1.0em")*/->padding_left("1.0em");

        $container = new SmartDiv();
        $container->add_child($title);
        $container->add_child(
            SmartUtils::capitalize_first_letter(
                trim($item_reader->content())
            )
        );
        $container->background_color("#f5f5f5")->border("1px dashed #ccc")->width_auto()->padding("0.5em 1.0em");
        return $container;
    }

    private function starsFromRating($item_reader)
    {
        $star_on = ui::urls()->asset("star-on.png")->toImage()->width_auto();
        $star_off = ui::urls()->asset("star-off.png")->toImage()->width_auto();

        $total_stars_on = 0;
        switch ($item_reader->rating()){
            case app::possible_ratings()->excellent():
                $total_stars_on = 5;
                break;
            case app::possible_ratings()->above_average():
                $total_stars_on = 4;
                break;
            case app::possible_ratings()->average():
                $total_stars_on = 3;
                break;
            case app::possible_ratings()->below_average():
                $total_stars_on = 2;
                break;
            case app::possible_ratings()->poor():
                $total_stars_on = 1;
                break;
            default:
                $total_stars_on = 0;
                break;
        }

        $span = new SmartSpan();
        $span->width_auto();
        for($i = 1; $i <= 5; $i++){
            $star_to_add = $star_off;
            if($i <= $total_stars_on){
                $star_to_add = $star_on;
            }
            $span->add_child($star_to_add);
        }
        return $span;

        //return ui::urls()->asset("star-on.png")->toImage()->width_auto() . $item_reader;
    }
}

class ContentTypeForColumnRule extends ContentTypeForIdentifier{
    public function toHtml($item_reader)
    {
        $tag = new SmartSpan();
        $tag->add_child($item_reader->content());

        $tag->width_auto()->font_style("italic")->border_left("8px solid #888")->margin_left("1.0em")->padding_left("1.0em");
        return $tag;
    }
}
class ContentTypeForQuote extends ContentTypeForIdentifier{
    public function toHtml($item_reader)
    {
        $tag = new SmartDiv();
        $tag->add_child($item_reader->content());

        $tag->font_style("italics")->border_left("8px solid black")->margin_left("1.0em")->padding_left("1.0em");
        return $tag;
    }
}

class ContentTypeForUnknown extends ContentTypeForSpan{
}
class ContentTypeForImage extends ContentTypeForIdentifier{
    public function toHtml($item_reader)
    {
        $tag = new SmartImage($item_reader->src());
        $tag->set_alt($item_reader->alt());

        $caption = new SmartCustomTag("figcaption");
        $caption->add_child($item_reader->alt())->font_style("italic")->margin_top("0.5em")->margin_bottom("1.0em")->opacity(0.8);

        $final_tag = new SmartCustomTag("figure");
        return $final_tag->add_child($tag)->add_child($caption)->margin_top("0.5em");

    }
}

class ContentTypeForYoutubeVideo extends ContentTypeForIdentifier{
    public function toHtml($item_reader)
    {
        $tag = new LinkToYoutubeVideoUsingIFrame($item_reader->src());
        $tag->width("100%");
        $tag->height("100%");
        $tag->border_width("0px");


        $container = new SmartSpan();
        $container->display_inline_block();

        if($item_reader->width() != "auto"){
            $container->width($item_reader->width());
        }
        if($item_reader->height() != "auto"){
            $container->height($item_reader->height());
        }
        $container->add_child($tag);
        $container->background_color("#000");
        return $container;
    }
}

class ContentTypeForLink extends ContentTypeForIdentifier{
    public function toHtml($item_reader)
    {
        $tag = new SmartLink();
        $tag->set_href($item_reader->href());
        $tag->add_child($item_reader->content());
        return $tag->color("orange");
    }
}
class ContentTypeForBreak extends ContentTypeForIdentifier{
    public function toHtml($item_reader)
    {
        $tag = new SmartLineBreak();
        return $tag;
    }
}
class ContentTypeForSpace extends ContentTypeForIdentifier{
    public function toHtml($item_reader)
    {
        return "&nbsp;";
    }
}