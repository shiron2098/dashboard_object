<?php
header('Content-type: application/json');
require_once __DIR__ . '/../../common/dashboard/MYSQL_t2s_bi_calendar.php';
require_once __DIR__ . '/../../config/t2s_bi_dashboard.php';


class stockouts extends MYSQL_t2s_bi_calendar
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
                log::logInsert('access_token time*s up',log_file_stockouts_name_and_folder,ERROR);
                http_response_code(403);
            }
        }else{
            log::logInsert('not found HTTP_AUTHORIZATION server',log_file_stockouts_name_and_folder,ERROR);
            http_response_code(401);
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
                $after = $this->select_after_stockouts($timemysql, $routekey, $groupkey);
                $before = $this->select_before_stockouts($timemysql, $routekey, $groupkey);
                $this->upordown = $this->select_stockouts_AVG($timemysql, $timemysqlfinishavg, $routekey, $groupkey);
                $trashhold = $this->select_stockouts_CALENDAR($timemysql, $routekey, $groupkey);
                if ($after !== null && $before !== null) {
                    $output = array(
                        'beforeVisitNumberOfProducts' => (string)$before->count,
                        'beforeVisitPercentOfProducts' => (string)$before->before_percentage,
                        'beforeVisitTrend' => (string)$this->upordown['0'],
                        'afterVisitNumberOfProducts' => (string)$after->pro_empty_after,
                        'afterVisitPercentOfProducts' => (string)$after->after_percentage,
                        'levelBeforeVisitNumberOfProductsByThreshold' => $trashhold['value'],
                        'date' => $timemysql,
                        'threndIntervalComparer' => static::week,
                    );
                    echo json_encode($output);
                } else {
                    $output = array(
                        'beforeVisitNumberOfProducts' => (string)$before->count,
                        'beforeVisitPercentOfProducts' => (string)$before->before_percentage,
                        'beforeVisitTrend' => (string)$this->upordown['0'],
                        'afterVisitNumberOfProducts' => (string)$after->pro_empty_after,
                        'afterVisitPercentOfProducts' => (string)$after->after_percentage,
                        'levelBeforeVisitNumberOfProductsByThreshold' => $trashhold['value'],
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
                $unixtimeAVG = strtotime($date . '-' . $this->int . 'days');
                $unixtimeMYSQL = strtotime($date);
                $timemysqlfinishavg = date('Ymd', $unixtimeAVG);
                $timemysql = date('Ymd', $unixtimeMYSQL);
                $after = $this->select_after_stockouts($timemysql, $routekey, $groupkey);
                $before = $this->select_before_stockouts($timemysql, $routekey, $groupkey);
                $this->upordown = $this->select_stockouts_AVG($timemysql, $timemysqlfinishavg, $routekey, $groupkey);
                $trashhold = $this->select_stockouts_CALENDAR($timemysql, $routekey, $groupkey);
                if ($after !== null && $before !== null) {
                    $output = array(
                        'beforeVisitNumberOfProducts' => (string)$before->count,
                        'beforeVisitPercentOfProducts' => (string)$before->before_percentage,
                        'beforeVisitTrend' => (string)$this->upordown['0'],
                        'afterVisitNumberOfProducts' => (string)$after->pro_empty_after,
                        'afterVisitPercentOfProducts' => (string)$after->after_percentage,
                        'levelBeforeVisitNumberOfProductsByThreshold' => $trashhold['value'],
                        'date' => $timemysql,
                        'threndIntervalComparer' => static::month,
                    );
                    echo json_encode($output);
                } else {
                    $output = array(
                        'beforeVisitNumberOfProducts' => (string)$before->count,
                        'beforeVisitPercentOfProducts' => (string)$before->before_percentage,
                        'beforeVisitTrend' => (string)$this->upordown['0'],
                        'afterVisitNumberOfProducts' => (string)$after->pro_empty_after,
                        'afterVisitPercentOfProducts' => (string)$after->after_percentage,
                        'levelBeforeVisitNumberOfProductsByThreshold' => $trashhold['value'],
                        'date' => $timemysql,
                        'threndIntervalComparer' => static::month,
                    );
                    echo json_encode($output);
                }
            }
    }

    public function start($post)
    {
        if (isset($post->trendIntervalComparer) && !EMPTY($post->trendIntervalComparer) && isset($post->date) && !empty($post->date) && !empty($post->date)) {
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
            log::logInsert('trendIntervalComparer null or date null check data request',log_file_stockouts_name_and_folder,ERROR);
            http_response_code(404);
        }
    }
    public function __destruct()
    {
        unset($this->link);
    }
}
$start = new stockouts();
$start->AUT();
