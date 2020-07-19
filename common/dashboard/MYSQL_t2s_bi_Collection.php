<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/MYSQL_t2s_bi_calendar.php';


class MYSQL_t2s_bi_Collection extends MYSQL_t2s_bi_calendar
{

    protected function select_array_stops_collection($date, $offset, $count, $arraysorting,$routekey,$groupkey)
    {

        $class_stops_collection = new stops_function();
        foreach ($arraysorting as $datasorting) {
            $columnsorting = $class_stops_collection->columnsort($arraysorting);
            if ($datasorting->rule == 'ascending') {
                return $class_stops_collection->daily_array_stops_collection($this->link,$date,$offset,$count,$routekey, $groupkey, $columnsorting, ASC);
            } else if ($datasorting->rule == 'descending') {
                return $array = $class_stops_collection->daily_array_stops_collection($this->link,$date,$offset,$count,$routekey, $groupkey, $columnsorting, DESC);
            } else {
                return null;
            }
        }
    }

    public function select_array_items_Collection($date, $offset, $count, $arraysorting,$routekey,$groupkey)
    {
        $class_items_collection = new items_function();
        foreach ($arraysorting as $datasorting) {
            $columnsorting = $class_items_collection->columnsort($arraysorting);
            if ($datasorting->rule == 'ascending') {
                return $class_items_collection->daily_items_per_collection($this->link,$date, $offset, $count, $routekey, $groupkey, $columnsorting, ASC);
            } else if ($datasorting->rule == 'descending') {
                return $class_items_collection->daily_items_per_collection($this->link,$date, $offset, $count, $routekey, $groupkey, $columnsorting, DESC);
            } else {
                return null;
            }
        }
    }

    public function select_array_stockouts_collection($date, $offset, $count, $arraysorting,$routekey,$groupkey){
        $class_stockouts_collection = new stockouts_function();
        foreach ($arraysorting as $datasorting) {
            $columnsorting = $class_stockouts_collection->columnsort($arraysorting);
            if ($datasorting->rule == 'ascending') {
                return $class_stockouts_collection->daily_stockouts_per_collection($this->link,$date, $offset, $count, $routekey, $groupkey, $columnsorting, ASC);
            } else if ($datasorting->rule == 'descending') {
                return $class_stockouts_collection->daily_stockouts_per_collection($this->link,$date, $offset, $count, $routekey, $groupkey, $columnsorting, DESC);
            } else {
                return null;
            }
        }
}

    public function select_array_revenue_collection($date, $offset, $count, $arraysorting,$routekey,$groupkey,$type)
    {
        $class_revenue_per_collection = new revenue_function();
        if($type === currency) {
            foreach ($arraysorting as $datasorting) {
               $columnsorting = $class_revenue_per_collection->columnsort($arraysorting);
                if ($datasorting->rule == 'ascending') {
                    return $class_revenue_per_collection->revenue_per_collection($this->link,$date, $routekey, $groupkey, $columnsorting, ASC, $offset, $count,currency_revenue_per_collection_select);
                } else if ($datasorting->rule == 'descending') {
                   return  $class_revenue_per_collection->revenue_per_collection($this->link,$date, $routekey, $groupkey, $columnsorting, DESC, $offset, $count,currency_revenue_per_collection_select);
                } else {
                    return null;
                }
            }
        }
        if($type === units) {
            foreach ($arraysorting as $datasorting) {
                $columnsorting = $class_revenue_per_collection->columnsort($arraysorting);
                if ($datasorting->rule == 'ascending') {
                    $array = $class_revenue_per_collection->revenue_per_collection($this->link,$date, $routekey, $groupkey, $columnsorting, ASC, $offset, $count, units_revenue_per_collection_select);
                    return $array;
                } else if ($datasorting->rule == 'descending') {
                    $array = $class_revenue_per_collection->revenue_per_collection($this->link,$date, $routekey, $groupkey, $columnsorting, DESC, $offset, $count, units_revenue_per_collection_select);
                    return $array;
                } else {
                    return null;
                }
            }
        }
    }
    public function select_array_distribution_collection($date, $offset, $count, $arraysorting,$minsales,$maxsales,$routekey,$groupkey,$type)
    {
        $class_distribution = new distribution_function();
        if($type === currency) {
            foreach ($arraysorting as $datasorting) {
                $columnsorting = $class_distribution->columnsort($arraysorting);
                if ($datasorting->rule == 'ascending') {
                    return $class_distribution->daily_distribution_per_collection($this->link,$date, $offset, $count, $columnsorting, $minsales, $maxsales, $routekey, $groupkey
                        ,currency_collection_value, currency_per_collection_50, currency_per_collection_more_150,ASC);
                } else if ($datasorting->rule == 'descending') {
                    return  $class_distribution->daily_distribution_per_collection($this->link,$date, $offset, $count, $columnsorting, $minsales, $maxsales, $routekey, $groupkey
                        ,currency_collection_value, currency_per_collection_50, currency_per_collection_more_150,DESC);
                } else {
                    return null;
                }
            }
        }
        if($type === units) {
            foreach ($arraysorting as $datasorting) {
                $columnsorting = $class_distribution->columnsort($arraysorting);
                if ($datasorting->rule == 'ascending') {
                    return  $class_distribution->daily_distribution_per_collection($this->link,$date, $offset, $count, $columnsorting, $minsales, $maxsales, $routekey, $groupkey,units_collection_value
                        , units_per_collection_50, units_per_collection_more_150,ASC);
                } else if ($datasorting->rule == 'descending') {
                    return  $class_distribution->daily_distribution_per_collection($this->link,$date, $offset, $count, $columnsorting, $minsales, $maxsales, $routekey, $groupkey,units_collection_value
                        ,units_per_collection_50, units_per_collection_more_150,DESC);
                } else {
                    return null;
                }
            }
        }
    }
    public function routeAll(){
        $result=PDORealization::Realization($this->link,"select distinct rte_id as routeGlobalKey,rte_description as routeDescription
                      from routes",null,array_data);
        if(!empty($result)) {
            foreach($result as $row){
                $array [] = $row;
            }
            echo json_encode($array);
        }else {
            log::logInsert('result null route_all in table for route',log_file_route_name_and_folder,ERROR);
            http_response_code(501);
        }
    }
    public function routeFilter($route){
        $route = $this->route($route);
        $result=PDORealization::Realization($this->link,"select distinct rte_id as routeGlobalKey,rte_description as routeDescription
                      from routes
                      where rte_id IN ($route)",null,array_data);
        if(!empty($result)) {
            foreach($result as $row){
                $array [] = $row;
            }
            echo json_encode($array);
        }else {
            log::logInsert('result null route_filter in table for route',log_file_route_name_and_folder,ERROR);
            http_response_code(501);
        }
    }

    public function groupAll()
    {

        $result=PDORealization::Realization($this->link,"select distinct rte_grp_id as routeGroupGlobalKey,rte_grp_description as routeGroupDescription
                      from `groups`",null,array_data);
        if(!empty($result)) {
            foreach($result as $row){
                $array [] = $row;
            }
            echo json_encode($array);
        }else {
            log::logInsert('result null group_all in table for group',log_file_group_name_and_folder,ERROR);
            http_response_code(501);
        }

    }
    public function groupFilter($group){
        $group = $this->route($group);
        $result=PDORealization::Realization($this->link,"select distinct rte_grp_id as routeGroupGlobalKey,rte_grp_description as routeGroupDescription
                      from `groups`
                      where rte_grp_id IN ($group)",null,array_data);
        if(!empty($result)) {
            foreach($result as $row){
                $array [] = $row;
            }
            echo json_encode($array);
        }else {
            log::logInsert('result null group_filter in table for group #' . $group,log_file_group_name_and_folder,ERROR);
            http_response_code(501);
        }
    }

}