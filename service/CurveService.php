<?php
class CurveService {
    public static function GetActionData($projectid, $curveid, $timestamp) {
        $list = self::QueryAction($projectid, $curveid, $timestamp);
        $lines = array();
        $ips = array();
        foreach($list as $key => $info){
            if(empty($lines[$info['page']])){
                $lines[$info['page']] = array();
            }
            if(empty($lines[$info['page']][$info["_id"]->getTimestamp()])){
                $lines[$info['page']][$info["_id"]->getTimestamp()] = 0;
            }
            $lines[$info['page']][$info["_id"]->getTimestamp()] += $info['n'];
            if (!in_array($info['ip'], $ips)){
                $ips[] = $info['ip'];
            }
        }
        return array("lines"=>$lines,'ips'=>$ips);
    }
    public static function QueryAction($projectid, $curveid, $timestamp) {
        return CurveDb::GetInstance()->queryAction($projectid, $timestamp, array("cid"=>new MongoId($curveid)));
    }
}