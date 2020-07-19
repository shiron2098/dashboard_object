<?php
require_once __DIR__ . '/route_and_group_and_sort_function.php';

class revenue_function extends  route_and_group_and_sort_function
{

    public function __construct()
    {
        $this->link = MYSQLConnect::ConnectToDB(host,user,password,database_t2s_bi_dashboard);
    }

    public function revenue($link,$datenum, $route, $group,$average_params,$min_params,$max_params)
    {
       $route = $this->CheckRouteAndGroup($link,$datenum, $route, $group,table_daily_revenue_avg);
        $route ='"' . $route . '"';
        $result=PDORealization::Realization($link, "CALL chart_revenue_collection('$datenum','$average_params','$min_params','$max_params',$route)",null,object);
        if (!empty($result)&& $result !==false) {
            foreach ($result as $date) {
                return $date;
            }
        } else {
            log::logInsert('result null in revenue_min_max_avg date #' . $datenum,log_file_revenue_name_and_folder,ERROR);
        }
    }
    public function revenue_per_collection($link,$datenum,$route,$group,$columnsorting,$sort,$offset,$count,$selectcolumn){
        $route = $this->CheckRouteAndGroup($link,$datenum, $route, $group,table_daily_revenue_avg);
        $route ='"' . $route . '"';
        $result=PDORealization::Realization($link,"CALL chart_revenue_collection_per_collection('$datenum','$columnsorting','$sort','$offset','$count','$selectcolumn',$route)",null,array_data);
        if (!empty($result)) {
            foreach ($result as $data) {
                $stringAdress = $data['zip'] . ',' . $data['state'] . ',' . $data['city'] . ',' . $data['address_1'] . ',' . $data['address_2'];
                if (empty($data['zip']) && empty($data['state']) && empty($data['city']) && empty($data['address_1']) && empty($data['address_2'])) {
                    $stringAdress = null;
                }
                $dataPOS = array(
                    'routeCode' => $data['codeRoute'],
                    'routeDescription' => $data['routeDescription'],
                    'posCode' => $data['posCode'],
                    'posGlobalKey' => $data['posGlobalKey'],
                    'posDescription' => $data['posDescription'],
                    'customerCode' => $data['customerCode'],
                    'customerDescription' => $data['customerDescription'],
                    'collectionsValue' => $data['collection'],
                    'address' => $stringAdress,
                );
                $array[] = $dataPOS;
            }
            return $array;
        } else {
            log::logInsert('result null in revenue_per_collection date #' . $datenum,log_file_revenue_name_and_folder,ERROR);
        }
    }
    public function daily_array_revenue($link,$datetime,$routekey,$groupkey,$revenue_params_select){
        $route = $this->CheckRouteAndGroup($link,$datetime, $routekey, $groupkey,table_daily_revenue_avg);
        $route ='"' . $route . '"';
        $result=PDORealization::Realization($link, "CALL chart_revenue_collection_array('$datetime','$revenue_params_select',$route)",null,object);
        if (!empty($result)&& $result !== false) {
            foreach ($result as $date) {
                $date->date = (string) $date->date;
                $array[] = (array) $date;
            }
            return $array;
        } else {
            log::logInsert('result null in daily_array_revenue date #' . $datetime,log_file_revenue_name_and_folder,ERROR);
        }
    }
    public function daily_revenue_avg($link,$datetime, $datetimeavg,$routekey,$groupkey,$revenue_params_select,$min_params,$max_params){
        $route = $this->CheckRouteAndGroup($link,$datetime, $routekey, $groupkey,table_daily_revenue_avg);
        $route ='"' . $route . '"';
        $result=PDORealization::Realization($link, "CALL chart_revenue_collection_avg_week_and_month('$datetimeavg','$datetime','$revenue_params_select','$min_params','$max_params',$route)",null,object);
        if ($result !== false) {
            if (empty($result)) {
                return null;
            }
            foreach($result as $row);
            $result2=PDORealization::Realization($link, "CALL chart_revenue_collection_avg_day('$datetime','$revenue_params_select','$min_params','$max_params',$route)",null,object);
            if (empty($result2)) {
                return null;
            }
            foreach($result2 as $row2)
            if ($result !== false && $result2 !== false && !empty($result)&&!empty($result2)) {
                foreach ($result2 as $date) {
                    foreach ($date as $column => $value) {
                        switch ($column) {
                            case 'average_collect':
                                if ($row->averagecollect >= $row2->average_collect) {
                                    $upandown[] = 'up';
                                    break;
                                } else {
                                    $upandown[] = 'down';
                                    break;
                                }
                            case 'min_collect':
                                if ($row->mincollect >= $row2->min_collect) {
                                    $upandown[] = 'up';
                                    break;
                                } else {
                                    $upandown[] = 'down';
                                    break;
                                }
                            case 'max_collect':
                                if ($row->maxcollect >= $row2->max_collect) {
                                    $upandown[] = 'up';
                                    break;
                                } else {
                                    $upandown[] = 'down';
                                    break;
                                }
                        }
                    }
                }
            }
            return $upandown;
        }else{
            log::logInsert( 'result null in daily_revenue_avg date #' . $datetime,log_file_revenue_name_and_folder,ERROR);
        }
    }
    public function daily_avg_calendar($link,$datetime,$routekey,$groupkey,$revenue_params_select,$avg){
        $route = $this->CheckRouteAndGroup($link,$datetime, $routekey, $groupkey,table_daily_revenue_avg);
        $route ='"' . $route . '"';
        $result=PDORealization::Realization($link, "CALL chart_revenue_collection_calendar('$datetime','$revenue_params_select',$route)",null,object);
        if (!empty($result) && $result !== false) {
            foreach ($result as $date) {
                if (is_null($date->average_collect)) {
                    return null;
                }
                if ($date->average_collect >= $avg) {
                    $value['date'] = $datetime;
                    $value['value'] = 'ok';
                    return $value;
                } else {
                    $value['date'] = $datetime;
                    $value['value'] = 'alert';
                    return $value;
                }
            }
        } else {
            log::logInsert('result null in daily_avg_calendar date #' . $datetime,log_file_revenue_name_and_folder,ERROR);
        }
    }
    public function daily_count_revenue($link,$date,$routekey,$groupkey){
        $route = $this->CheckRouteAndGroup($link,$date, $routekey, $groupkey,table_daily_revenue_avg);
        $route ='"' . $route . '"';
        $result=PDORealization::Realization($link,"CALL chart_revenue_collection_per_collection_count('$date',$route)",null,array_data);
        if (!empty($result)&& $result !== false) {
            foreach($result as $date) {
                $count = $date['count'];
            }
            return $count;
        } else {
            log::logInsert('result null in daily_count_revenue date #' . $date,log_file_revenue_name_and_folder,ERROR);
        }
    }
}
