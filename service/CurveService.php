<?php
class CurveService {
    public static function GetActionData($projectid, $curveid, $timestamp, $ips, $groupbyip, $page = "") {
        $list = self::QueryAction($projectid, $curveid, $timestamp, $ips, $page);
        $lines = array();
        foreach($list as $key => $info){
            $linename = $groupbyip==1 ? $info['page'] : $info['page'].'_'.$info['ip'];
            if(empty($lines[$linename])){
                $lines[$linename] = array("num"=>0,"points"=>array());
            }
            $line = &$lines[$linename];
            $pointkey = $info["_id"]->getTimestamp();
            if(empty($line["points"][$pointkey])){
                $line["points"][$pointkey] = 0;
            }
            $line["points"][$pointkey] += $info['n'];
        }
        foreach($lines as $linename => &$line) {
            $line["num"] = count($line['points']);
        }
        return array("lines"=>$lines);
    }
    public static function QueryDailyData($projectid, $curveid, $timestamp, $ips, $groupbyip) {
        $list = self::QueryAction($projectid, $curveid, $timestamp, $ips);
        $lines = array();
        foreach($list as $key => $info){
            $linename = $groupbyip==1 ? $info['page'] : $info['page'].'_'.$info['ip'];
            if(empty($lines[$linename])){
                $lines[$linename] = 0;
            }
            $lines[$linename] += $info['n'];
        }
        return $lines;
    }
    public static function QueryAction($projectid, $curveid, $timestamp, $ips, $page=null) {
        $query = array("cid"=>new MongoId($curveid),'ip'=>array('$in'=>$ips));
        if (!empty($page)){
            $query['page']=$page;
        }
        return CurveDb::GetInstance()->queryAction($projectid, $timestamp, $query);
    }
    public static function QueryDistinctIp($projectid, $curveid, $timestamp) {
        return CurveDb::GetInstance()->queryDistinctKey($projectid, $timestamp, "ip", array("cid"=>new MongoId($curveid)));
    }
    public static function QueryDistinctPage($projectid, $curveid, $timestamp) {
        return CurveDb::GetInstance()->queryDistinctKey($projectid, $timestamp, "page", array("cid"=>new MongoId($curveid)));
    }
}