<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../dashboard/MYSQL_t2s_bi_data.php';


class MYSQL_t2s_bi_avg extends MYSQL_t2s_bi_data
{

    protected function select_stopsAVG($datetime, $datetimeavg,$routekey,$groupkey)
    {
       $class_stops = new stops_function();
      return $class_stops->daily_stopsAVG($this->link,$datetime, $datetimeavg,$routekey,$groupkey);
    }

    protected function select_stockouts_AVG($datetime, $datetimeavg,$routekey,$groupkey)
    {
        $class_stockouts = new stockouts_function();
       return $class_stockouts->daily_stockoutsAVG($this->link,$datetime, $datetimeavg,$routekey,$groupkey);
    }

    protected function select_items_AVG($datetime,$datetimeavg,$routekey,$groupkey)
    {
        $class_items = new items_function();
         return  $class_items->daily_itemsAVG($this->link,$datetime,$datetimeavg,$routekey,$groupkey);
    }

    protected function select_distribution_AVG($datetime, $datetimeavg,$routekey,$groupkey,$type)
    {
        $class_distribution = new distribution_function();
        if ($type === currency) {
            return $class_distribution->daily_collection_and_avg($this->link,$datetime, $datetimeavg,$routekey,$groupkey,currency_less_50,currency_more_50_less_75,currency_more_75_less_100,
                currency_more_100_less_150,currency_more_150);
        }
        if ($type === units) {
            return $class_distribution->daily_collection_and_avg($this->link,$datetime, $datetimeavg,$routekey,$groupkey,units_less_50,units_more_50_less_75,units_more_75_less_100,
                units_more_100_less_150,units_more_150);
        }
    }
    protected function select_revenue_AVG($datetime, $datetimeavg,$routekey,$groupkey,$type)
    {
        $class_revenue = new revenue_function();
        if ($type === currency) {
            return $class_revenue->daily_revenue_avg($this->link,$datetime, $datetimeavg, $routekey, $groupkey, currency_revenue, currency_min_params, currency_max_params);
        }
        if ($type === units) {
            return $class_revenue->daily_revenue_avg($this->link,$datetime, $datetimeavg, $routekey, $groupkey, units_revenue, units_max_params, units_max_params);
        }
    }

}