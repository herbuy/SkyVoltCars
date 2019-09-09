<?php
class SectionFactory{
    public function reviews_and_comments(){
        return new SectionForReviewsAndComments();
    }

    public function exporter_reviews($reader)
    {
        return new SectionForExporterReviews($reader);
    }

    public function tips_and_advice()
    {
        return new SectionForTipsAndAdvice();
    }

    public function ongoingDiscussions()
    {
        return new SectionForOnGoingDiscusssions();
    }

    public function preNav()
    {
        return new SectionForPreNav();
    }

    public function mostRecentReview($reader)
    {
        return new SectionForMostRecentReview($reader);
    }

    public function carOfTheWeek()
    {
        return new SectionForCarOfTheWeek();
    }

    public function searchForCars()
    {
        return new SectionForSearchForCars();
    }

    public function postOrUpdateArticles()
    {
        return new SectionForPostArticles();
    }

    public function quickFacts($reader_for_most_recent_post_per_category)
    {
        return new SectionForMostRecentPostsPerCategory($reader_for_most_recent_post_per_category);
    }

    public function footer($reader_for_current_user)
    {
        return new SectionForTheFooter($reader_for_current_user);
    }

    public function topNav()
    {
        return new SectionForTopNav();
    }

    public function admin_navigation_box()
    {
        return new SectionForAdminNavigationBox();
    }

    public function total_content()
    {
        return new SectionForTotalContent();
    }

    public function total_discussions()
    {
        return new SectionForTotalDiscussions();
    }

    public function article_details($reader)
    {
        return new SectionForArticleDetails($reader);        
    }
    public function article_details_in_preview_mode($reader)
    {
        return new SectionForArticleDetailsInPreviewMode($reader);
    }
    public function extended_post_tokens($reader_for_extended_post_tokens)
    {
        return new SectionForExtendedPostTokens($reader_for_extended_post_tokens);
    }

    public function contact_info()
    {
        $info = new LayoutForNRows();
        $info->addNewRow()->add_child("Tel: +256 772 966 397");
        $info->addNewRow()->add_child("Email: cars.skyvolt@gmail.com");

        
        return $info;
    }

    public function actions_for_edit_post($reader_for_post)
    {
        return new ListOfActionsForEditPost($reader_for_post);
    }

    public function edit_post($reader_for_post,$reader_for_extended_post_tokens)
    {
        return new SectionForEditPost($reader_for_post,$reader_for_extended_post_tokens);
    }

    public function header()
    {
        return new PageHeaderForHome();
    }

    public function contact_us()
    {
    }


}