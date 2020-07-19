<?php
header('Content-type: application/json');
require_once __DIR__ . '/../../common/dashboard/MYSQL_t2s_bi_calendar.php';
require_once __DIR__ . '/../../config/t2s_bi_dashboard.php';


class distribution extends MYSQL_t2s_bi_calendar
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


    public function AUT()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $this->selectkey($authHeader);
        if (!empty($authHeader)) {
            if ($_SESSION['AUT'] === true) {
                $json_str = file_get_contents('php://input');
                $json_obj = json_decode($json_str);
                $this->start($json_obj);
            } else {
                log::logInsert('access_token time*s up',log_file_distribution_name_and_folder,ERROR);
                http_response_code(403);
            }
        } else {
            log::logInsert('not found HTTP_AUTHORIZATION server',log_file_distribution_name_and_folder,ERROR);
            http_response_code(401);
        }
    }

    public function Week($date,$int,$routekey,$groupkey,$type)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-' . $this->int . 'days');
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $this->upordown = $this->select_distribution_AVG($timemysql, $timemysqlfinishavg,$routekey,$groupkey,$type);
            $this->select_distribution_CALENDAR($timemysql,$routekey,$groupkey);
            if ($this->upordown !== null) {
                $output = array(
                    'date' => $date,
                    'threndIntervalComparer' => static::week,
                    'salesDistributionCollection' => $this->upordown
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'date' => $date,
                    'threndIntervalComparer' => static::week,
                    'salesDistributionCollection' => array()
                );
                echo json_encode($output);
            }
        }
    }
    private function Months($date,$int,$routekey,$groupkey,$type)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-' . $this->int . 'days');
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $this->upordown = $this->select_distribution_AVG($timemysql,$timemysqlfinishavg,$routekey,$groupkey,$type);
            $this->select_distribution_CALENDAR($timemysql,$routekey,$groupkey);
            if ($this->upordown !== null) {
                $output = array(
                    'date' => $date,
                    'threndIntervalComparer' => static::month,
                    'salesDistributionCollection' => $this->upordown
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'date' => $date,
                    'threndIntervalComparer' => static::month,
                    'salesDistributionCollection' => array()
                );
                echo json_encode($output);
            }
        }
    }
    public function start($post)
    {
        if (isset($post->trendIntervalComparer) && !EMPTY($post->trendIntervalComparer) && isset($post->date) && !empty($post->date)&& isset($post->salesDistributionType)) {
            $this->interval = $post->trendIntervalComparer;
            switch ($this->interval) {
                case day_45:
                    $this->Week($post->date,$post->trendIntervalComparer,$post->routeGlobalKeyCollection,$post->routeGroupGlobalKeyCollection,$post->salesDistributionType);
                    break;
                case day_180:
                    $this->Months($post->date,$post->trendIntervalComparer,$post->routeGlobalKeyCollection,$post->routeGroupGlobalKeyCollection,$post->salesDistributionType);
                    break;
            }
        }else{
            log::logInsert('trendIntervalComparer null or date null check data request',log_file_distribution_name_and_folder,ERROR);
            http_response_code(404);
        }
    }
    public function __destruct()
    {
        unset($this->link);
    }
}
$start = new distribution();
$start->AUT();