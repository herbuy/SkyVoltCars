<?php

class Kicker extends SmartDiv{

}
class UnderlinedKicker extends Kicker{
    public function __construct()
    {
        parent::__construct();
        $this->border_bottom("2px solid tomato");
    }
}

class KickerForExporterReviews extends UnderlinedKicker{
    public function __construct()
    {
        parent::__construct();
        $this->add_child("EXPORTER REVIEWS");
    }
}

class KickerForMostPurhased extends UnderlinedKicker{
    public function __construct()
    {
        parent::__construct();
        $this->add_child("most PURCHASED");
    }
}

class KickerForNews extends UnderlinedKicker{
    public function __construct()
    {
        parent::__construct();
        $this->add_child("NEWS");
    }
}
class KickerForCareers extends UnderlinedKicker{
    public function __construct()
    {
        parent::__construct();
        $this->add_child("CAREERS");
    }
}

class KickerForCarMaintenance extends UnderlinedKicker{
    public function __construct()
    {
        parent::__construct();
        $this->add_child("CAR MAINTENANCE");
    }
}

class KickerForCustomContent extends UnderlinedKicker{
    public function __construct($content)
    {
        parent::__construct();
        $this->add_child($content);
    }
}

