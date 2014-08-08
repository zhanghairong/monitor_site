<?php
class CurveService {
    public static function GetActionData($projectid, $curveid, $timestamp) {
        $list = self::QueryAction($projectid, $curveid, $timestamp);
        $lines = array();
        $ips = array();
        foreach($list as $key => $info){
            if(empty($lines[$info['page']])){
                $lines[$info['page']] = array("num"=>0,"points"=>array());
            }
            $line = &$lines[$info['page']];
            $pointkey = $info["_id"]->getTimestamp();
            if(empty($line["points"][$pointkey])){
                $line["points"][$pointkey] = 0;
            }
            
            $line["points"][$pointkey] += $info['n'];
            if (!in_array($info['ip'], $ips)){
                $ips[] = $info['ip'];
            }
        }
        foreach($lines as $linename => &$line) {
            $line["num"] = count($line['points']);
        }
        return array("lines"=>$lines,'ips'=>$ips);
    }
    public static function QueryAction($projectid, $curveid, $timestamp) {
        return CurveDb::GetInstance()->queryAction($projectid, $timestamp, array("cid"=>new MongoId($curveid)));
    }
}