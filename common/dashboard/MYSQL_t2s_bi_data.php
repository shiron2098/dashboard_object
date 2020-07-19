<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../api/authentication/protectedaut.php';
require_once __DIR__ . '/../../config/params_t2s_dashboard.php';
require_once __DIR__ . '/revenue_function.php';
require_once __DIR__ . '/stops_function.php';
require_once __DIR__ . '/items_function.php';
require_once __DIR__ . '/stockouts_function.php';
require_once __DIR__ . '/distribution_function.php';
require_once __DIR__ . '/../../config/t2s_bi_dashboard.php';

class MYSQL_t2s_bi_data extends protectedaut
{

    protected function select_missed_stops($datenum,$routekey,$groupkey)
    {
        $class_stops = new stops_function();
        return $class_stops->daily_missed_stops($this->link,$datenum,$routekey,$groupkey);
    }

    protected function select_after_stockouts($datenum,$routekey,$groupkey)
    {
       $class_stockouts = new stockouts_function();
       return $class_stockouts->daily_after_stockouts($this->link,$datenum,$routekey,$groupkey);
    }
    protected function select_before_stockouts($datenum,$routekey,$groupkey){
      $class_stockouts = new stockouts_function();
      return $class_stockouts->daily_before_stockouts($this->link,$datenum,$routekey,$groupkey);
    }
    protected function select_items($datenum,$routekey,$groupkey){
       $class_items = new items_function();
      return $class_items->daily_items($this->link,$datenum,$routekey,$groupkey);
    }

    protected function select_revenue_per_collection($datenum,$routekey,$groupkey,$type)
    {
        $class_revenue = new revenue_function();
        if($type === currency) {
            return $class_revenue->revenue($this->link,$datenum,$routekey,$groupkey,currency_revenue,currency_min_params,currency_max_params);
        }
        if($type === units) {
            return $class_revenue->revenue($this->link,$datenum,$routekey,$groupkey,units_revenue,units_min_params,units_max_params);
        }
    }

    protected function select_revenue_array($datetime,$routekey,$groupkey,$type)
    {
        $class_revenue = new revenue_function();
        if($type === currency) {
            return $class_revenue->daily_array_revenue($this->link,$datetime,$routekey,$groupkey,currency_revenue);
        }
        if($type === units) {
            return $class_revenue->daily_array_revenue($this->link,$datetime,$routekey,$groupkey,units_revenue);
        }
    }

    protected function select_count_POS($date,$routekey,$groupkey)
    {
        $class_stops = new stops_function();
        return $class_stops->daily_pos_count($this->link,$date,$routekey,$groupkey);

    }

    protected function select_count_items($date,$routekey,$groupkey)
    {
        $class_items = new items_function();
        return $class_items->daily_count_items($this->link,$date,$routekey,$groupkey);
    }


    protected function select_count_stockouts($date,$routekey,$groupkey)
    {
        $class_stockouts = new stockouts_function();
        return $class_stockouts->daily_stockouts_COUNT($this->link,$date,$routekey,$groupkey);
    }

    protected function select_count_revenue($date,$routekey,$groupkey)
    {
        $class_revenue = new revenue_function();
        return  $class_revenue->daily_count_revenue($this->link,$date,$routekey,$groupkey);
    }

    protected function select_count_destribution($date, $minsales, $maxsales,$routekey,$groupkey,$type)
    {
        $class_distribution = new distribution_function();
        if ($type === currency) {
            return $class_distribution->daily_distribution_count($this->link,$date, $minsales, $maxsales, $routekey, $groupkey
                , currency_per_collection_50, currency_per_collection_more_150);
        }else if ($type === units) {
            return $class_distribution->daily_distribution_count($this->link,$date, $minsales, $maxsales, $routekey, $groupkey
                , units_per_collection_50, units_per_collection_more_150);
        }
    }
    protected function route($routekey){
        $stringkey = "'" . implode("','", $routekey) . "'";
        return $stringkey;
    }
}