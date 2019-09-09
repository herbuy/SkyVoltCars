<?php
class FactoryForBorders{

    public function panel()
    {
        return sprintf("1px solid %s",ui::colors()->panel_border());
    }

    public function primary_engagement()
    {
        return sprintf("1px solid %s !important",ui::colors()->primary_engagement()->mix(ui::colors()->black(),5));
    }
}