<?php
require_once __DIR__ . '/route_and_group_and_sort_function.php';
require_once __DIR__ . '/../../common/log/log.php';

class stops_function extends  route_and_group_and_sort_function
{

    public function daily_missed_stops($link,$datenum,$routekey,$groupkey)
    {
        $route = $this->CheckRouteAndGroup($link,$datenum, $routekey, $groupkey,table_daily_stops);
        $route = '"' . $route . '"';
        $result = PDORealization::Realization($link,"CALL chart_stops('$datenum',$route)",null,object);
        if (!empty($result)) {
            foreach ($result as $date)
                return $date;
        } else {
            return null;
        }
    }
    public function daily_stopsAVG($link,$datetime, $datetimeavg,$routekey,$groupkey){
        $route = $this->CheckRouteAndGroup($link,$datetime, $routekey, $groupkey,table_daily_stops);
        $route ='"' . $route . '"';
        $result=PDORealization::Realization($link, "CALL chart_stops_avg_week_and_month('$datetimeavg','$datetime',$route)",null,object);;
        if ($result !== false) {
            foreach($result as $row);
            $result2=PDORealization::Realization($link, "CALL chart_stops_avg_day('$datetime',$route)",null,object);
            foreach($result2 as $row2);
        }
        if ($result !== false && $result2 !== false) {
            foreach ($row2 as $column => $value) {
                switch ($column) {
                    case 'missed_stops':
                        if ($row->missedstops <= $row2->missed_stops) {
                            $upandown[] = 'down';
                            break;
                        } else {
                            $upandown[] = 'up';
                            break;
                        }
                    case 'out_of_schedule_stops':
                        if ($row->outstops <= $row2->out_of_schedule_stops) {
                            $upandown[] = 'down';
                            break;
                        } else {
                            $upandown[] = 'up';
                            break;
                        }
                }
            }
            return $upandown;
        }else{
            log::logInsert('result null in daily_stopsAVG date #' . $datetime,log_file_stops_name_and_folder,ERROR);
        }
    }
    public function daily_missed_stops_CALENDAR($link,$datetime,$routekey,$groupkey,$missed){
        $route = $this->CheckRouteAndGroup($link,$datetime, $routekey, $groupkey,table_daily_stops);
        $route ='"' . $route . '"';
        $result=PDORealization::Realization($link, "CALL chart_stops_avg_calendar('$datetime',$route)",null,object);
        if (!empty($result)) {
            foreach ($result as $date) {
                if (is_null($date->missed_stops)) {
                    return null;
                }
                if ($date->missed_stops <= $missed) {
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
            log::logInsert('result null in daily_missed_stops_CALENDAR date #' . $datetime,log_file_stops_name_and_folder,ERROR);
        }
    }
    public function daily_array_stops_collection($link,$date,$offset,$count,$routekey, $groupkey, $columnsorting,$sort){
        $route = $this->CheckRouteAndGroup($link,$date, $routekey, $groupkey,table_daily_stops);
        $route ='"' . $route . '"';
        $result=PDORealization::Realization($link, "CALL chart_stops_per_collection('$date','$columnsorting','$sort',$offset,$count,$route)",null,array_data);
        if (!empty($result)) {
            foreach ($result as $data) {
                $stringAdress = $data['zip'] . ',' . $data['state'] . ',' . $data['city'] . ',' . $data['address_1'] . ',' . $data['address_2'];
                if(empty($data['zip'])&&empty($data['state'])&&empty($data['city'])&&empty($data['address_1'])&&empty($data['address_2'])){
                    $stringAdress = null;
                }
                $dataPOS = array(
                    'routeCode' => $data['codeRoute'],
                    'routeDescription' => $data['routeDescription'],
                    'posGlobalKey' => $data['posGlobalKey'],
                    'customerCode' => $data['customerCode'],
                    'customerDescription' => $data['customerDescription'],
                    'posCode' => $data['posCode'],
                    'posDescription' => $data['posDescription'],
                    'address' => $stringAdress,
                );
                $array[] = $dataPOS;
            }
            return $array;
        } else {
            log::logInsert('result null in daily_array_stops_per_collection date #' . $date,log_file_stops_name_and_folder,ERROR);
        }
    }
    public function daily_pos_count($link,$date,$routekey,$groupkey){
        $route = $this->CheckRouteAndGroup($link,$date, $routekey, $groupkey,table_daily_stops);
        $route ='"' . $route . '"';
        $result=PDORealization::Realization($link, "CALL chart_stops_per_collection_count('$date',$route)",null,object);
        if (!empty($result)) {
            foreach ($result as $date) {
                foreach ($date as $item) {
                    $array = $item;
                }
            }
            return $array;
        } else {
            log::logInsert('result null in daily_pos_count date #' . $date,log_file_stops_name_and_folder,ERROR);
        }
    }
}