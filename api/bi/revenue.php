<?php
header('Content-type: application/json');
require_once __DIR__ . '/../../common/dashboard/MYSQL_t2s_bi_calendar.php';
require_once __DIR__ . '/../../config/t2s_bi_dashboard.php';


class revenue extends MYSQL_t2s_bi_calendar
{
    const week = '45';
    const month = '180';

    private $upordown;
    private $interval;
    private $int;


    public function __construct()
    {
        $this->link = MYSQLConnect::ConnectToDB(host,user,password,database_t2s_bi_dashboard);
    }

    public function AUT(){
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $this->selectkey($authHeader);
        if(!empty($authHeader)) {
            if ($_SESSION['AUT'] === true) {
                $json_str = file_get_contents('php://input');
                $json_obj = json_decode($json_str);
                $this->start($json_obj);
            } else {
                log::logInsert('access_token time*s up',log_file_revenue_name_and_folder,ERROR);
                http_response_code(403);
            }
        }else{
            log::logInsert('not found HTTP_AUTHORIZATION server',log_file_revenue_name_and_folder,ERROR);
            http_response_code(401);
        }
    }

    public function Week($date,$int,$routekey,$groupkey,$type)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-'. $this->int . 'days');
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->select_revenue_per_collection($timemysql,$routekey,$groupkey,$type);
            $datarevenue = $this->select_revenue_array($timemysql,$routekey,$groupkey,$type);
            $this->upordown = $this->select_revenue_AVG($timemysql, $timemysqlfinishavg,$routekey,$groupkey,$type);
            $trashhold = $this->select_avg_CALENDAR($timemysql,$routekey,$groupkey);
            if (!empty($data->average_collect)||!empty($data->min_collect)||!empty($data->max_collect)) {
                $output = array(
                    'date' => (string)$timemysql,
                    'averageRevenue' => (string)$data->average_collect,
                    'averageRevenueTrend' => (string)$this->upordown['0'],
                    'minRevenue' => (string)$data->min_collect,
                    'minAverageRevenueTrend' => (string)$this->upordown['1'],
                    'maxRevenue' => (string)$data->max_collect,
                    'maxAverageRevenueTrend' => (string)$this->upordown['2'],
                    'levelAverageRevenueByThreshold' => $trashhold['value'],
                    'averageRevenueCollection' => (array) $datarevenue,
                    'threndIntervalComparer' => static::week,
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'date' => (string)$timemysql,
                    'averageRevenue' => (string)$data['average_collect'],
                    'averageRevenueTrend' => (string)$this->upordown['0'],
                    'minRevenue' => (string)$data['min_collect'],
                    'minAverageRevenueTrend' => (string)$this->upordown['1'],
                    'maxRevenue' => (string)$data['max_collect'],
                    'maxAverageRevenueTrend' => (string)$this->upordown['2'],
                    'averageRevenueCollection' => array(),
                    'levelAverageRevenueByThreshold' => $trashhold['value'],
                    'threndIntervalComparer' => static::week,
                );
                echo json_encode($output);
            }
        }
    }

    private function Months($date,$int,$routekey,$groupkey,$type)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-'. $this->int . 'days');
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->select_revenue_per_collection($timemysql,$routekey,$groupkey,$type);
            $datarevenue = $this->select_revenue_array($timemysql,$routekey,$groupkey,$type);
            $this->upordown = $this->select_revenue_AVG($timemysql, $timemysqlfinishavg,$routekey,$groupkey,$type);
            $trashhold = $this->select_avg_CALENDAR($timemysql,$routekey,$groupkey);
            if (!empty($data->average_collect)||!empty($data->min_collect)||!empty($data->max_collect)) {
                $output = array(
                    'date' => (string)$timemysql,
                    'averageRevenue' => (string)$data->average_collect,
                    'averageRevenueTrend' => (string)$this->upordown['0'],
                    'minRevenue' => (string)$data->min_collect,
                    'minAverageRevenueTrend' => (string)$this->upordown['1'],
                    'maxRevenue' => (string)$data->max_collect,
                    'maxAverageRevenueTrend' => (string)$this->upordown['2'],
                    'levelAverageRevenueByThreshold' => $trashhold['value'],
                    'averageRevenueCollection' => (array) $datarevenue,
                    'threndIntervalComparer' => static::month,
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'date' => (string)$timemysql,
                    'averageRevenue' => (string)$data->average_collect,
                    'averageRevenueTrend' => (string)$this->upordown['0'],
                    'minRevenue' => (string)$data->min_collect,
                    'minAverageRevenueTrend' => (string)$this->upordown['1'],
                    'maxRevenue' => (string)$data->max_collect,
                    'maxAverageRevenueTrend' => (string)$this->upordown['2'],
                    'averageRevenueCollection' => array(),
                    'levelAverageRevenueByThreshold' => $trashhold['value'],
                    'threndIntervalComparer' => static::month,
                );
                echo json_encode($output);
            }
        }
    }
    public function start($post)
    {
        if (isset($post->trendIntervalComparer) && !EMPTY($post->trendIntervalComparer) && isset($post->date) && !empty($post->date) && isset($post->averageRevenueType)) {
            $this->interval = $post->trendIntervalComparer;
            switch ($this->interval) {
                case day_45:
                    $this->Week($post->date,$post->trendIntervalComparer,$post->routeGlobalKeyCollection,$post->routeGroupGlobalKeyCollection,$post->averageRevenueType);
                    break;
                case day_180:
                    $this->Months($post->date,$post->trendIntervalComparer,$post->routeGlobalKeyCollection,$post->routeGroupGlobalKeyCollection,$post->averageRevenueType);
                    break;
            }
        }else{
            log::logInsert('trendIntervalComparer null or date null check data request',log_file_revenue_name_and_folder,ERROR);
            http_response_code(404);
        }

    }
    public function __destruct()
    {
        unset($this->link);
    }
}
$start = new revenue();
$start->AUT();