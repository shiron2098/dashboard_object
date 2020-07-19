<?php
header('Content-type: application/json');
require_once __DIR__ . '/../../common/dashboard/MYSQL_t2s_bi_Collection.php';


class distributionPosCollection  extends MYSQL_t2s_bi_Collection
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


    public function Week($date, $int, $offset, $count,$sort,$minsales,$maxsales,$routekey,$groupkey,$type)
    {
        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtimeMYSQL = strtotime($date);
            $timemysql = date('Ymd', $unixtimeMYSQL);
            $data = $this->select_array_distribution_collection($timemysql,$offset,$count,$sort,$minsales,$maxsales,$routekey,$groupkey,$type);
            $dataCount= $this->select_count_destribution($timemysql,$minsales,$maxsales,$routekey,$groupkey,$type);
            if ($data !== null) {
                $output = array(
                    'date' => $date,
                    'minTresholdSales' => $minsales,
                    'maxTresholdSales' => $maxsales,
                    'threndIntervalComparer' => static::week,
                    'items' => $data,
                    'totalCount' => $dataCount
                );
                echo json_encode($output);
            } else {
                $output = array(
                    'date' => $date,
                    'minTresholdSales' => $minsales,
                    'maxTresholdSales' => $maxsales,
                    'threndIntervalComparer' => static::week,
                    'items' => $data,
                    'totalCount' => $dataCount
                );
                echo json_encode($output);
            }
        }
    }

    private function Months($date, $int, $offset, $count,$sort,$minsales,$maxsales,$routekey,$groupkey,$type)
    {

        if (!empty($date) && isset($date)) {
            $this->int = $int;
            $unixtime = strtotime($date);
            $timemysql = date('Ymd', $unixtime);
            $data = $this->select_array_distribution_collection($timemysql,$offset,$count,$sort,$minsales,$maxsales,$routekey,$groupkey,$type);
            $dataCount= $this->select_count_destribution($timemysql,$minsales,$maxsales,$routekey,$groupkey,$type);
                if ($data !== null) {
                    $output = array(
                        'date' => $date,
                        'minTresholdSales' => $minsales,
                        'maxTresholdSales' => $maxsales,
                        'threndIntervalComparer' => static::month,
                        'items' => $data,
                        'totalCount' => $dataCount
                    );
                    echo json_encode($output);
                } else {
                    $output = array(
                        'date' => $date,
                        'minTresholdSales' => $minsales,
                        'maxTresholdSales' => $maxsales,
                        'threndIntervalComparer' => static::month,
                        'items' => $data,
                        'totalCount' => $dataCount
                    );
                    echo json_encode($output);
                }
        }
    }

    public function start($post)
    {
            if (isset($post->trendIntervalComparer) && !EMPTY($post->trendIntervalComparer) && isset($post->date) && !empty($post->date) && isset($post->offset) && isset($post->count)) {
                $this->interval = $post->trendIntervalComparer;
                switch ($this->interval) {
                    case day_45:
                        $this->Week($post->date, $post->trendIntervalComparer, $post->offset, $post->count, $post->sorting
                            ,$post->minTresholdSales,$post->maxTresholdSales,$post->routeGlobalKeyCollection,$post->routeGroupGlobalKeyCollection,$post->salesDistributionType);
                        break;
                    case day_180:
                        $this->Months($post->date, $post->trendIntervalComparer, $post->offset, $post->count, $post->sorting
                            ,$post->minTresholdSales,$post->maxTresholdSales,$post->routeGlobalKeyCollection,$post->routeGroupGlobalKeyCollection,$post->salesDistributionType);
                        break;
                }
            } else {
                log::logInsert('trendIntervalComparer null or date null check data request',log_file_distribution_name_and_folder,ERROR);
                http_response_code(404);
            }
    }
    public function __destruct()
    {
        unset($this->link);
    }
}

$start = new distributionPosCollection();
$start->AUT();