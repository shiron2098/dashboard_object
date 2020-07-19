<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../common/dashboard/MYSQL_t2s_bi_Collection.php';


class stopsPosCollection  extends MYSQL_t2s_bi_Collection
{
    const week = '45';
    const month = '180';
    private $interval;
    private $int;


    public function __construct()
    {
        $this->link = MYSQLConnect::ConnectToDB(host,user,password,database_t2s_bi_dashboard);
    }

    public function AUT()
    {
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
        } catch (Exception $e) {
           echo $e->getMessage();
        }
    }


    public function Week($date, $int, $offset, $count, $sort,$routekey,$groupkey)
    {
        try {
            if (!empty($date) && isset($date)) {
                $this->int = $int;
                $unixtimeMYSQL = strtotime($date);
                $timemysql = date('Ymd', $unixtimeMYSQL);
                $data = $this->select_array_stops_collection($timemysql, $offset, $count, $sort, $routekey, $groupkey);
                $dataCount = $this->select_count_POS($timemysql, $routekey, $groupkey);
                if ($data !== null) {
                    $output = array(
                        'date' => $date,
                        'threndIntervalComparer' => static::week,
                        'items' => $data,
                        'totalCount' => $dataCount
                    );
                    echo json_encode($output);
                } else {
                    $output = array(
                        'date' => $date,
                        'threndIntervalComparer' => static::week,
                        'items' => $data,
                        'totalCount' => $dataCount
                    );
                    echo json_encode($output);
                }
            }
        }catch(Exception $e){
            echo $e->getMessage();
            log::logInsert($e->getMessage(),log_file_stops_name_and_folder,ERROR);
        }
    }

    private function Months($date, $int, $offset, $count, $sort,$routekey,$groupkey)
    {
        try {
            if (!empty($date) && isset($date)) {
                $this->int = $int;
                $unixtimeMYSQL = strtotime($date);
                $timemysql = date('Ymd', $unixtimeMYSQL);
                $data = $this->select_array_stops_collection($timemysql, $offset, $count, $sort, $routekey, $groupkey);
                $dataCount = $this->select_count_POS($timemysql, $routekey, $groupkey);
                if ($data !== null) {
                    $output = array(
                        'date' => $date,
                        'threndIntervalComparer' => static::month,
                        'items' => $data,
                        'totalCount' => $dataCount
                    );
                    echo json_encode($output);
                } else {
                    $output = array(
                        'date' => $date,
                        'threndIntervalComparer' => static::month,
                        'items' => $data,
                        'totalCount' => $dataCount
                    );
                    echo json_encode($output);
                }
            }
        }catch(Exception $e){
            echo $e->getMessage();
            log::logInsert($e->getMessage(),log_file_stops_name_and_folder,ERROR);
        }
    }

    public function start($post)
    {
            if (isset($post->trendIntervalComparer) && !EMPTY($post->trendIntervalComparer) && isset($post->date) && !empty($post->date) && isset($post->offset) && isset($post->count)) {
                $this->interval = $post->trendIntervalComparer;
                switch ($this->interval) {
                    case day_45:
                        $this->Week($post->date, $post->trendIntervalComparer, $post->offset, $post->count, $post->sorting,$post->routeGlobalKeyCollection,$post->routeGroupGlobalKeyCollection);
                        break;
                    case day_180:
                        $this->Months($post->date, $post->trendIntervalComparer, $post->offset, $post->count, $post->sorting,$post->routeGlobalKeyCollection,$post->routeGroupGlobalKeyCollection);
                        break;
                }
            } else {
                log::logInsert('trendIntervalComparer null or date null check data request ',log_file_stops_name_and_folder,ERROR);
                http_response_code(404);
            }
    }
    public function __destruct()
    {
        unset($this->link);
    }
}

$start = new stopsPosCollection();
$start->AUT();