<?php
header('Content-type: application/json');
require_once __DIR__ . '/../../common/log/log.php';
require_once __DIR__ . '/../../common/dashboard/MYSQL_t2s_bi_calendar.php';
require_once __DIR__ . '/../../config/t2s_bi_dashboard.php';


class stops extends MYSQL_t2s_bi_calendar
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
        try {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
            $this->selectkey($authHeader);
            if (!empty($authHeader)) {
                if ($_SESSION['AUT'] === true) {
                    $json_str = file_get_contents('php://input');
                    $json_obj = json_decode($json_str);
                    $this->start($json_obj);
                } else {
                    log::logInsert('access_token time*s up', log_file_stops_name_and_folder, ERROR);
                    http_response_code(403);
                }
            } else {
                log::logInsert('not found HTTP_AUTHORIZATION server', log_file_stops_name_and_folder, ERROR);
                http_response_code(401);
            }
        }catch (Exception $e){
            log::logInsert($e->getMessage(),log_file_stops_name_and_folder,ERROR );
            echo $e->getMessage();
        }
    }

    public function Week($date,$int,$routekey,$groupkey)
    {

            if (!empty($date) && isset($date)) {
                $this->int = $int;
                $unixtimeAVG = strtotime($date . '-' . $this->int . 'days');
                $unixtimeMYSQL = strtotime($date);
                $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
                $timemysql = date('Ymd', $unixtimeMYSQL);
                $this->upordown = $this->select_stopsAVG($timemysql, $timemysqlfinishavg, $routekey, $groupkey);
                $data = $this->select_missed_stops($timemysql, $routekey, $groupkey);
                $trashhold = $this->select_missed_stops_CALENDAR($timemysql, $routekey, $groupkey);
                if ($data !== null) {
                    $output = array(
                        'totalScheduledStopsNumber' => (string)$data->scheduled_stops,
                        'missedStopsNumber' => (string)$data->missed_stops,
                        'missedStopsTrend' => (string)$this->upordown['0'],
                        'outOfScheduleStopsNumber' => (string)$data->out_of_schedule_stops,
                        'outOfScheduleStopsTrend' => (string)$this->upordown['1'],
                        'levelMissedStopsNumberByThreshold' => $trashhold['value'],
                        'date' => $timemysql,
                        'threndIntervalComparer' => static::week,
                    );
                    echo json_encode($output);
                } else {
                    $output = array(
                        'totalScheduledStopsNumber' => (string)$data->scheduled_stops,
                        'missedStopsNumber' => (string)$data->missed_stops,
                        'missedStopsTrend' => (string)$this->upordown['0'],
                        'outOfScheduleStopsNumber' => (string)$data->out_of_schedule_stops,
                        'outOfScheduleStopsTrend' => (string)$this->upordown['1'],
                        'levelMissedStopsNumberByThreshold' => $trashhold['value'],
                        'date' => $timemysql,
                        'threndIntervalComparer' => static::week,
                    );
                    echo json_encode($output);
                }
            }
    }
    private function Months($date,$int,$routekey,$groupkey)
        {
                if (!empty($date) && isset($date)) {
                    $this->int = $int;
                    $unixtime = strtotime($date);
                    $unixtimeAVG = strtotime($date . '-' . $this->int . 'days');
                    $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
                    $timemysql = date('Ymd', $unixtime);
                    $this->upordown = $this->select_stopsAVG($timemysql, $timemysqlfinishavg, $routekey, $groupkey);
                    $data = $this->select_missed_stops($timemysql, $routekey, $groupkey);
                    $trashhold = $this->select_missed_stops_CALENDAR($timemysql, $routekey, $groupkey);
                    if ($data !== null) {
                        $output = array(
                            'totalScheduledStopsNumber' => (string)$data->scheduled_stops,
                            'missedStopsNumber' => (string)$data->missed_stops,
                            'missedStopsTrend' => (string)$this->upordown['0'],
                            'outOfScheduleStopsNumber' => (string)$data->out_of_schedule_stops,
                            'outOfScheduleStopsTrend' => (string)$this->upordown['1'],
                            'levelMissedStopsNumberByThreshold' => $trashhold['value'],
                            'date' => $trashhold['date'],
                            'threndIntervalComparer' => static::month,
                        );
                        echo json_encode($output);
                    } else {
                        $output = array(
                            'totalScheduledStopsNumber' => (string)$data->scheduled_stops,
                            'missedStopsNumber' => (string)$data->missed_stops,
                            'missedStopsTrend' => (string)$this->upordown['0'],
                            'outOfScheduleStopsNumber' => (string)$data->out_of_schedule_stops,
                            'outOfScheduleStopsTrend' => (string)$this->upordown['1'],
                            'levelMissedStopsNumberByThreshold' => $trashhold['value'],
                            'date' => $trashhold['date'],
                            'threndIntervalComparer' => static::month,
                        );
                        echo json_encode($output);
                    }
                }
        }
    public function start($post)
    {
            if (isset($post->trendIntervalComparer) && !EMPTY($post->trendIntervalComparer) && isset($post->date) && !empty($post->date)) {
                $this->interval = $post->trendIntervalComparer;
                switch ($this->interval) {
                    case  day_45:
                        $this->Week($post->date, $post->trendIntervalComparer, $post->routeGlobalKeyCollection, $post->routeGroupGlobalKeyCollection);
                        break;
                    case day_180:
                        $this->Months($post->date, $post->trendIntervalComparer, $post->routeGlobalKeyCollection, $post->routeGroupGlobalKeyCollection);
                        break;
                }
            } else {
                log::logInsert('trendIntervalComparer null or date null check data', log_file_stops_name_and_folder, ERROR);
                http_response_code(404);
            }
    }
    public function __destruct()
    {
        unset($this->link);
    }

}
$start = new stops();
$start->AUT();