<?php
require_once __DIR__ . '/route_and_group_and_sort_function.php';

class stockouts_function extends  route_and_group_and_sort_function
{
    public function __construct()
    {
        $this->link = MYSQLConnect::ConnectToDB(host,user,password,database_t2s_bi_dashboard);
    }

    public function daily_after_stockouts($link,$datenum,$routekey,$groupkey)
    {
        $route = $this->CheckRouteAndGroup($link,$datenum, $routekey, $groupkey,table_items_and_stockouts);
        if(!empty($route)) {
            $route = '"' . $route . '"';
            $result = PDORealization::Realization($link, "CALL chart_stockouts_after('$datenum',$route)", null, object);
            if (!empty($result) && $result !== false) {
                foreach ($result as $date)
                    return $date;
            } else {
                log::logInsert('result null in daily_after_stockouts date #' . $datenum, log_file_stockouts_name_and_folder, ERROR);
                return null;
            }
        }else {
            log::logInsert('route null in daily_after_stockouts date #' . $datenum, log_file_stockouts_name_and_folder, WARNING);
            return null;
        }
    }

    public function daily_before_stockouts($link,$datenum,$routekey,$groupkey)
    {
        $route = $this->CheckRouteAndGroup($link,$datenum, $routekey, $groupkey,table_items_and_stockouts);
        if(!empty($route)) {
            $route = '"' . $route . '"';
            $result = PDORealization::Realization($link, "CALL chart_stockouts_before('$datenum',$route)", null, object);
            if (!empty($result)) {
                foreach ($result as $date)
                    return $date;
            } else {
                log::logInsert('result null in daily_before_stockouts date #' . $datenum, log_file_stockouts_name_and_folder, ERROR);
                return null;
            }
        }else {
            log::logInsert('route null in daily_before_stockouts date #' . $datenum, log_file_stockouts_name_and_folder, WARNING);
            return null;
        }
    }

    public function daily_stockoutsAVG($link,$datetime, $datetimeavg,$routekey,$groupkey)
    {
        $route = $this->CheckRouteAndGroup($link,$datetime, $routekey, $groupkey,table_items_and_stockouts);
        if(!empty($route)) {
            $route = '"' . $route . '"';
            $result = PDORealization::Realization($link, "CALL chart_stockouts_avg_week_and_month('$datetimeavg','$datetime',$route)", null, object);
            if ($result !== false) {
                foreach ($result as $row) ;
                $result2 = PDORealization::Realization($link, "CALL chart_stockouts_avg_day('$datetime',$route)", null, object);
                foreach ($result2 as $row2) ;
            }
            if ($result !== false && $result2 !== false && !empty($result) && !empty($result2)) {
                foreach ($row2 as $column => $value) {
                    switch ($column) {
                        case 'before_stockouts':
                            if ($row->beforestockouts <= $row2->before_stockouts) {
                                $upandown[] = 'down';
                                break;
                            } else {
                                $upandown[] = 'up';
                                break;
                            }
                    }
                }
                return $upandown;
            } else {
                log::logInsert('result null in daily_stockoutsAVG date' . $datetime, log_file_stockouts_name_and_folder, ERROR);
                return null;
            }
        }else {
            log::logInsert('route null in daily_stockoutsAVG date' . $datetime, log_file_stockouts_name_and_folder, ERROR);
            return null;
        }
    }

    public function daily_stockouts_CALENDAR($link,$dateStart,$routekey,$groupkey,$stockouts)
    {
        $route = $this->CheckRouteAndGroup($link,$dateStart, $routekey, $groupkey,table_items_and_stockouts);
        if(!empty($route)) {
            $route = '"' . $route . '"';
            $result = PDORealization::Realization($link, "CALL chart_stockouts_calendar('$dateStart',$route)", null, object);
            if (!empty($result)) {
                foreach ($result as $date)
                    if ($date->before_percentage <= $stockouts) {
                        $value['date'] = $dateStart;
                        $value['value'] = 'ok';
                        return $value;
                    } else {
                        $value['date'] = $dateStart;
                        $value['value'] = 'alert';
                        return $value;
                    }
            } else {
                log::logInsert('result null in daily_stockouts_CALENDAR date #' . $dateStart, log_file_stockouts_name_and_folder, ERROR);
                return null;
            }
        }else{
            log::logInsert('route null in daily_stockouts_CALENDAR date #' . $dateStart, log_file_stockouts_name_and_folder, WARNING);
            return null;
        }
    }

    public function daily_stockouts_per_collection($link,$date, $offset, $count, $routekey, $groupkey, $columnsorting,$sort)
    {
        $route = $this->CheckRouteAndGroup($link,$date, $routekey, $groupkey,table_items_and_stockouts);
        if(!empty($route)) {
            $route = '"' . $route . '"';
            $result = PDORealization::Realization($link, "CALL chart_stockouts_per_collection('$date','$columnsorting','$sort',$offset,$count,$route)", null, array_data);
            if (!empty($result) && $result !== false) {
                foreach ($result as $data) {
                    $dataPOS = array(
                        'routeCode' => $data['codeRoute'],
                        'routeDescription' => $data['routeDescription'],
                        'productCode' => $data['productCode'],
                        'productDescription' => $data['productDescription'],
                        'posCode' => $data['posCode'],
                        'posDescription' => $data['posDescription'],
                        'customerCode' => $data['customerCode'],
                        'customerDescription' => $data['customerDescription'],
                    );
                    $array[] = $dataPOS;
                }
                return $array;
            } else {
                log::logInsert('result null in daily_stockouts_per_collection date #' . $date, log_file_stockouts_name_and_folder, ERROR);
                return null;
            }
        }else{
            log::logInsert('route null in daily_stockouts_per_collection date #' . $date, log_file_stockouts_name_and_folder, WARNING);
            return null;
        }
    }

    public function daily_stockouts_COUNT($link,$date,$routekey,$groupkey)
    {
        $route = $this->CheckRouteAndGroup($link, $date, $routekey, $groupkey, table_items_and_stockouts);
        if (!empty($route)) {
            $route = '"' . $route . '"';
            $result = PDORealization::Realization($link, "CALL chart_stockouts_per_collection_count('$date',$route)", null, array_data);
            if (!empty($result)) {
                foreach ($result as $date) {
                    $count = $date['countPosid'];
                }
                return $count;
            } else {
                log::logInsert('result null in daily_stockouts_count date #' . $date, log_file_stockouts_name_and_folder, ERROR);
                return null;
            }
        } else {
            log::logInsert('route null in daily_stockouts_count date #' . $date, log_file_stockouts_name_and_folder, WARNING);
            return null;
        }
    }
}