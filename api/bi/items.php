<?php
header('Content-type: application/json');
require_once __DIR__ . '/../../common/dashboard/MYSQL_t2s_bi_calendar.php';
require_once __DIR__ . '/../../config/t2s_bi_dashboard.php';


class items extends MYSQL_t2s_bi_calendar
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
                log::logInsert('access_token time*s up',log_file_items_name_and_folder,ERROR);
                http_response_code(403);
            }
        }else{
            log::logInsert('not found HTTP_AUTHORIZATION server',log_file_items_name_and_folder,ERROR);
            http_response_code(401);
        }
    }

    private function Week($date,$int,$routekey,$groupkey)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-'.$this->int . 'days');
            $unixtimeMYSQL = strtotime($date);
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $this->upordown = $this->select_items_AVG($timemysql,$timemysqlfinishavg,$routekey,$groupkey);
            $data = $this->select_items($timemysql,$routekey,$groupkey);
            $trashhold =  $this->select_items_CALENDAR($date,$routekey,$groupkey);
            if ($data !== null) {
                $output = array(
                    'numberOfProducts' => (string) $data->not_picked,
                    'percentOfProducts'=> (string) $data->total_picked,
                    'levelNumberOfProductsByThreshold' => $trashhold['value'],
                    'trend' => (string) $this->upordown['0'],
                    'date' => $timemysql,
                    'threndIntervalComparer' => static::week,
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'numberOfProducts' => (string) $data->not_picked,
                    'percentOfProducts'=> (string) $data->total_picked,
                    'levelNumberOfProductsByThreshold' => $trashhold['value'],
                    'trend' => (string) $this->upordown['0'],
                    'date' => $timemysql,
                    'threndIntervalComparer' => static::week,
                );
                echo json_encode($output);
            }
        }
    }
    public function Months($date,$int,$routekey,$groupkey)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeAVG = strtotime($date . '-'.$this->int . 'days');
            $unixtimeMYSQL = strtotime($date);
            $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $this->upordown = $this->select_items_AVG($timemysql,$timemysqlfinishavg,$routekey,$groupkey);
            $data = $this->select_items($timemysql,$routekey,$groupkey);
            $trashhold =  $this->select_items_CALENDAR($date,$routekey,$groupkey);
            if ($data !== null) {
                $output = array(
                    'numberOfProducts' => (string) $data->not_picked,
                    'percentOfProducts'=> (string) $data->total_picked,
                    'trend' => (string) $this->upordown['0'],
                    'levelNumberOfProductsByThreshold' => $trashhold['value'],
                    'date' => $trashhold['date'],
                    'threndIntervalComparer' => static::month,
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'numberOfProducts' => (string) $data->not_picked,
                    'percentOfProducts'=> (string) $data->total_picked,
                    'levelNumberOfProductsByThreshold' => $trashhold['value'],
                    'trend' => (string) $this->upordown['0'],
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
                case day_45:
                    $this->Week($post->date,$post->trendIntervalComparer,$post->routeGlobalKeyCollection,$post->routeGroupGlobalKeyCollection);
                    break;
                case day_180:
                    $this->Months($post->date,$post->trendIntervalComparer,$post->routeGlobalKeyCollection,$post->routeGroupGlobalKeyCollection);
                    break;
            }
        }else{
            log::logInsert('trendIntervalComparer null or date null check data request',log_file_items_name_and_folder,ERROR);
            http_response_code(404);
        }

    }
    public function __destruct()
    {
        unset($this->link);
    }
}
$start = new items();
$start->AUT();