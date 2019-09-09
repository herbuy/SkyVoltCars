<?php
class KickerFactory{
    public function exporter_reviews(){
        return new KickerForExporterReviews();
    }

    public function news()
    {
        return new KickerForNews();
    }

    public function careers()
    {
        return new KickerForCareers();
    }

    public function car_exporters()
    {
        return new KickerForMostPurhased();
    }

    public function car_maintenance()
    {
        return new KickerForCarMaintenance();
    }

    public function custom($content)
    {
        return new KickerForCustomContent($content);
    }
}