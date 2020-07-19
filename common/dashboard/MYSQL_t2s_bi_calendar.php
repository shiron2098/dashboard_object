<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/MYSQL_t2s_bi_avg.php';


class MYSQL_t2s_bi_calendar extends MYSQL_t2s_bi_avg
{
    const missed = 2;
    const stockout = 15;
    const avg = 75;
    const items = 5;
    const distribution = 5;


    protected function select_missed_stops_CALENDAR($dateStart,$routekey,$groupkey)
    {
             $class_missed_calendar = new stops_function();
             return $class_missed_calendar->daily_missed_stops_CALENDAR($this->link,$dateStart,$routekey,$groupkey,missed);
    }
    protected function select_distribution_CALENDAR($dateStart,$routekey,$groupkey)
    {
            $class_distribution_collection = new distribution_function();
            $class_distribution_collection->daily_distribution_CALENDAR($this->link,$dateStart,$routekey,$groupkey,distribution);
    }
    protected function select_avg_CALENDAR($dateStart,$routekey,$groupkey)
    {
            $class_revenue_calendar = new revenue_function();
            return $class_revenue_calendar->daily_avg_calendar($this->link,$dateStart, $routekey, $groupkey, currency_revenue,avg);
    }
    protected function select_stockouts_CALENDAR($dateStart,$routekey,$groupkey)
    {
            $class_stockouts = new stockouts_function();
            return  $class_stockouts->daily_stockouts_CALENDAR($this->link,$dateStart,$routekey,$groupkey,stockout);
    }
    protected function select_items_CALENDAR($dateStart,$routekey,$groupkey)
    {
            $class_items= new items_function();
            return $class_items->daily_items_calendar($this->link,$dateStart,$routekey,$groupkey,items);
    }
    public function DeleteArrayFile($file){
        $file[] = null;
        foreach ($file as $i => $key) {
            unset($file[$i]);
        }
        return $file;
    }


}