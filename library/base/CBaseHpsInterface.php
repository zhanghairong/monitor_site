<?php
class CBaseHpsInterface {
    
     private $hpsdomain;
     private $hps_osap_userName;
     private $hps_signature_password;
     private $appId;
     private $areaId;
     
     private $defaultreqparams;
     
     public function __construct($hpsdomain, $osapusername, $sigkey, $appid, $areaid)
     {
         $this->hpsdomain = $hpsdomain;
         $this->hps_osap_userName = $osapusername;
         $this->hps_signature_password = $sigkey;
         $this->appId = $appid;
         $this->areaId = $areaid;         
     }

     public function callHttpRequestWithLog($urlPath,$arrParas,$timeout=3)
     {
         $urlPath = $this->hpsdomain.$urlPath;
         
         $this->defaultreqparams = array(
             //'appId' => $this->appId,
             'merchant_name' =>  $this->hps_osap_userName,
             'signature_method' => 'MD5',
             'timestamp' => (int)microtime(true),
         );
         
         $arrParas = array_merge($arrParas, $this->defaultreqparams);
         
         $signCode = $this->calculateSignature($arrParas);
         $arrParas['signature'] = $signCode;
         $url = CHttp::constructGetUrl($urlPath,$arrParas);

         $tBegin = Log::getLogTime();
         $data = CHttp::GetRequest($url,'http',$timeout);
         $tEnd = Log::getLogTime();
         Log::writeLog('CBaseHpsInterface ret[' . $data . ']  url[' . $url . ']','debug', $tEnd-$tBegin);
         if(!$data)
         {
             Log::writeLog('CBaseHpsInterface Call Error.  url[' . $url . ']', 'error');
             return false;
         }

         return $data;
     }

     private function calculateSignature($paraArray){
         ksort($paraArray);
         $paraSign="";
         foreach ($paraArray as $key => $val) {
             $paraSign.="$key=$val";
         }
         $paraSign.=$this->hps_signature_password;
         return md5($paraSign);
     }
     
}