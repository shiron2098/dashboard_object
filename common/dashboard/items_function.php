<?php
require_once __DIR__ . '/route_and_group_and_sort_function.php';

class items_function extends  route_and_group_and_sort_function
{


    public function daily_items($link,$datenum,$routekey,$groupkey){
        $route = $this->CheckRouteAndGroup($link,$datenum, $routekey, $groupkey,table_items_and_stockouts);
        if(!empty($route)) {
            $route = '"' . $route . '"';
            $result = PDORealization::Realization($link, "CALL chart_items('$datenum',$route)", null, object);
            if (!empty($result) && $result !== false) {
                foreach ($result as $date) {
                    return $date;
                }
            } else {
                log::logInsert('result null in daily_items date #' . $datenum, log_file_items_name_and_folder, ERROR);
                return null;
            }
        }else{
            log::logInsert('route null in daily_items date #' . $datenum, log_file_items_name_and_folder, WARNING);
            return null;
        }
    }
    public function daily_itemsAVG($link,$datetime,$datetimeavg,$routekey,$groupkey){
        $route = $this->CheckRouteAndGroup($link,$datetime, $routekey, $groupkey,table_items_and_stockouts);
        if(!empty($route)) {
            $route = '"' . $route . '"';
            $result = PDORealization::Realization($link, "CALL chart_items_avg_week_and_month('$datetimeavg','$datetime',$route)", null, object);
            if ($result !== false && !empty($result)) {
                if (empty($result)) {
                    return null;
                }
                foreach ($result as $row)
                    $result2 = PDORealization::Realization($link, "CALL chart_items_avg_day('$datetime',$route)", null, object);
                foreach ($result2 as $row2) ;
            }
            if ($result !== false && $result2 !== false && !empty($result) && !empty($result2)) {
                foreach ($row2 as $column => $value) {
                    switch ($column) {
                        case 'not_picked':
                            if ($row->notpicked <= $row2->not_picked) {
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
                log::logInsert('result null in daily_before_stockouts date #' . $datetime, log_file_items_name_and_folder, ERROR);
                return null;
            }
        }else{
            log::logInsert('route null in daily_before_stockouts date #' . $datetime, log_file_items_name_and_folder, WARNING);
            return null;
        }
    }
    public function daily_items_calendar($link,$dateStart,$routekey,$groupkey,$item){
        $route = $this->CheckRouteAndGroup($link,$dateStart, $routekey, $groupkey,table_items_and_stockouts);
        if(!empty($route)) {
            $route = '"' . $route . '"';
            $result = PDORealization::Realization($link, "CALL chart_items_calendar('$dateStart',$route)", null, object);
            if (!empty($result)) {
                foreach ($result as $date) {
                    if (is_null($date->total_picked)) {
                        return null;
                    }
                    if ($date->total_picked <= $item) {
                        $value['date'] = $dateStart;
                        $value['value'] = 'ok';
                        return $value;
                    } else {
                        $value['date'] = $dateStart;
                        $value['value'] = 'alert';
                        return $value;
                    }
                }
            } else {
                log::logInsert('result null in daily_items_calendar date #' . $dateStart, log_file_items_name_and_folder, ERROR);
                return null;
            }
        }else{
            log::logInsert('route null in daily_items_calendar date #' . $dateStart, log_file_items_name_and_folder, WARNING);
            return null;
        }
    }
    public function daily_items_per_collection($link,$date, $offset, $count, $routekey, $groupkey, $columnsorting, $sort){
        $route = $this->CheckRouteAndGroup($link,$date, $routekey, $groupkey,table_items_and_stockouts);
        if(!empty($route)) {
            $route = '"' . $route . '"';
            $result = PDORealization::Realization($link, "CALL chart_items_per_collection('$date','$columnsorting','$sort',$offset,$count,$route)", null, array_data);
            if (!empty($result)) {
                foreach ($result as $data) {
                    $dataPOS = array(
                        'routeCode' => $data['codeRoute'],
                        'routeDescription' => $data['routeDescription'],
                        'posCode' => $data['posCode'],
                        'posDescription' => $data['posDescription'],
                        'productGlobalKey' => $data['productGlobalKey'],
                        'productCode' => $data['productCode'],
                        'productDescription' => $data['productDescription'],
                        'quantity' => $data['quantity'],
                    );
                    $array[] = $dataPOS;
                }
                return $array;
            } else {
                log::logInsert('result null in daily_items_per_collection date #' . $date, log_file_items_name_and_folder, ERROR);
                return null;
            }
        }else{
            log::logInsert('route null in daily_items_per_collection date #' . $date, log_file_items_name_and_folder, WARNING);
            return null;
        }
    }
    public function daily_count_items($link,$date,$routekey,$groupkey){
        $route = $this->CheckRouteAndGroup($link,$date, $routekey, $groupkey,table_items_and_stockouts);
        if($route) {
            $route = '"' . $route . '"';
            $result = PDORealization::Realization($link, "CALL chart_items_per_collection_count('$date',$route)", null, array_data);
            if (!empty($result && $result !== false)) {
                foreach ($result as $date) {
                    $count = $date['count'];
                }
                return $count;
            } else {
                log::logInsert('result null in daily_count_items date #' . $date, log_file_items_name_and_folder, ERROR);
                return null;
            }
        }else{
            log::logInsert('route null in daily_count_items date #' . $date, log_file_items_name_and_folder, WARNING);
            return null;
        }
    }

}