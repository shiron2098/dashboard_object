<?php
include_once __DIR__ . '/../../common/dashboard/MYSQL_t2s_bi_calendar.php';
require_once __DIR__ . '/../../config/t2s_bi_dashboard.php';



class calendar extends MYSQL_t2s_bi_calendar
{

    private $datefrom;
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
                http_response_code(403);
            }
        } else {
            http_response_code(401);
        }
    }
    public function Week($array,$int,$routekey,$groupkey)
    {
            if (!empty($array) && isset($array)) {
                foreach ($array as $date) {
                    $arraydate = [];
                    $this->DeleteArrayFile($arraydate);
                    $this->int = $int;
                    $this->datefrom = $date;
                    $arraydate[] = $this->select_missed_stops_CALENDAR($this->datefrom, $routekey, $groupkey);
                    $arraydate[] = $this->select_items_CALENDAR($this->datefrom, $routekey, $groupkey);
                    $arraydate[] = $this->select_avg_CALENDAR($this->datefrom, $routekey, $groupkey);
                    $arraydate[] = $this->select_stockouts_CALENDAR($this->datefrom, $routekey, $groupkey);
                    $arraydate[] = $this->select_distribution_CALENDAR($this->datefrom, $routekey, $groupkey);
                    foreach ($arraydate as $dateOKANDALLERT) {
                        if (!empty($arraydate['0']) && isset($arraydate)) {
                            if ($dateOKANDALLERT['value'] === 'ok') {
                                $finishdate = array(
                                    'date' => $dateOKANDALLERT['date'],
                                    'levelDateByThreshold' => $dateOKANDALLERT['value'],
                                );
                            } elseif ($dateOKANDALLERT['value'] === 'alert') {
                                $finishdate = array(
                                    'date' => $dateOKANDALLERT['date'],
                                    'levelDateByThreshold' => $dateOKANDALLERT['value'],
                                );
                                break;
                            }
                        } else {
                            $finishdate = array(
                                'date' => $date,
                                'levelDateByThreshold' => 'nodata',
                            );
                            break;
                        }
                    }
                    $arrayjson[] = $finishdate;
                }
                $date = array(
                    'threndIntervalComparer' => '45',
                    'dateCollection' => $arrayjson,
                );
                echo json_encode($date);
            }else{
                log::logInsert('ERROR array data is null check request',log_file_calendar_name_and_folder,ERROR);
            }
    }
    public function Months($array,$int,$routekey,$groupkey)
    {
        if (!empty($array) && isset($array)) {

            if (!empty($array) && isset($array)) {

                foreach ($array as $date) {
                    $arraydate= [];
                    $this->DeleteArrayFile($arraydate);
                    $this->int = $int;
                    $this->datefrom = $date;
                    $arraydate[] = $this->select_missed_stops_CALENDAR($this->datefrom,$routekey,$groupkey);
                    $arraydate[] = $this->select_items_CALENDAR($this->datefrom,$routekey,$groupkey);
                    $arraydate[] = $this->select_avg_CALENDAR($this->datefrom,$routekey,$groupkey);
                    $arraydate[] = $this->select_stockouts_CALENDAR($this->datefrom,$routekey,$groupkey);
                    $arraydate[] = $this->select_distribution_CALENDAR($this->datefrom,$routekey,$groupkey);
                    foreach($arraydate as $dateOKANDALLERT) {
                        if (!empty($arraydate['0'])&&isset($arraydate)) {
                            if($dateOKANDALLERT['value'] === 'ok'){
                                $finishdate = array(
                                    'date' => $dateOKANDALLERT['date'],
                                    'levelDateByThreshold' => $dateOKANDALLERT['value'],
                                );
                            }elseif($dateOKANDALLERT['value'] === 'alert'){
                                $finishdate = array(
                                    'date' => $dateOKANDALLERT['date'],
                                    'levelDateByThreshold' => $dateOKANDALLERT['value'],
                                );
                                break;
                            }
                        }else{
                            $finishdate = array(
                                'date' => $date,
                                'levelDateByThreshold' => 'nodata',
                            );
                            break;
                        }
                    }
                    $arrayjson[]=$finishdate;
                }
                $date = array(
                    'threndIntervalComparer' => '180',
                    'dateCollection' => $arrayjson,
                );
                echo json_encode($date);
            }
        }else {
            log::logInsert('ERROR array data is null check request',log_file_calendar_name_and_folder,ERROR);
        }
    }
    public function start($post)
    {
        if (isset($post->trendIntervalComparer) && !EMPTY($post->trendIntervalComparer) && isset($post->dateCollection) && !empty($post->dateCollection)) {
            $this->interval = $post->trendIntervalComparer;
            switch ($this->interval) {
                case day_45:
                    $this->Week($post->dateCollection,$post->trendIntervalComparer,$post->routeGlobalKeyCollection,$post->routeGroupGlobalKeyCollection);
                    break;
                case day_180:
                    $this->Months($post->dateCollection,$post->trendIntervalComparer,$post->routeGlobalKeyCollection,$post->routeGroupGlobalKeyCollection);
                    break;
            }
        }else {
            log::logInsert('trendIntervalComparer null or date null check data request ',log_file_calendar_name_and_folder,ERROR);
            http_response_code(404);
        }
    }
    public function __destruct()
    {
        unset($this->link);
    }

}
$start = new calendar();
$start->AUT();
