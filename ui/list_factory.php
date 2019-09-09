<?php
class ListFactory{
    
    public function most_recent_post_per_category($reader)
    {
        return new ListOfMostRecentPostPerCategory($reader);
    }

    public function reviews($reader)
    {
        return new ListOfPostsUnderReviews($reader);
    }
    public function exporter_reviews($reader){
        return new ListOfPostsUnderExporterReviews($reader);
    }
    public function car_videos($reader){
        return new ListOfPostsUnderCarVideos($reader);
    }
    public function car_exporters($reader)
    {
        return new ListOfPostsUnderCarExporters($reader);
    }
    public function car_pictures($reader)
    {
        return new ListOfPostsUnderCarPictures($reader);
    }
    public function layout1($reader)
    {
        return new ListOfPostsLayout1($reader);
    }
    public function news($reader)
    {
        return new ListOfPostsLayout1($reader);
    }
    public function careers($reader)
    {
        return new ListOfPostsUnderCareers($reader);
    }
    public function car_maintenance($reader)
    {
        return new ListOfPostsUnderCarMaintenance($reader);
    }
    public function admin_draft_posts($reader)
    {
        return new LayoutForDraftPosts1($reader);
    }

    public function admin_published_posts($reader)
    {
        return new ListOfPublishedPosts($reader);
    }

    public function stats_per_section($reader)
    {
        return new ListOfStatsPerSection($reader,ui::text_with_contrast_colors("SECTION","STATISTICS"));
    }

    public function stats_per_year($reader)
    {
        return new ListOfStatsPerYear($reader,ui::text_with_contrast_colors("YEAR ","STATISTICS"));
    }
    public function stats_per_month($reader)
    {
        return new ListOfStatsPerMonth($reader,ui::text_with_contrast_colors("MONTHLY ","STATISTICS"));
    }
    public function stats_per_week($reader)
    {
        return new ListOfStatsPerWeek($reader,ui::text_with_contrast_colors("WEEKLY ","STATISTICS"));
    }
    public function stats_per_day($reader)
    {
        return new ListOfStatsPerDay($reader,ui::text_with_contrast_colors("DAILY ","STATISTICS"));
    }
}