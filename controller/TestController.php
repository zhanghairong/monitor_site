<?php
class TestController extends CBaseController
{
    public function actionTestMongoDb()
    {
        $db = new CBaseMongoDb("mongodb://127.0.0.1:27017/", "monitor");
        $ret = $db->addArray("account",array("account"=>"zhr","passwd"=>"zhr"));
        //$ret = $db->updateArray("account",array("passwd"=>"bbbcc"),array("account"=>"zhr"));
        $ret = $db->deleteByArray("account",array("account"=>"zhr"));
        var_dump((string)$ret);
        $ret = $db->queryByArray("account",array());
        print_r($ret);
    }
    public function actionTestXmlData()
    {
        
        $params = array
        (
            'root' => array(
                 '@nodevalue' => array(
                    'workers' => array(
                        '@attributes' => array('name'=>'person','type'=>'family'),
                        '@nodevalue' => array(
                            'person' => array(
                                array(
                                    '@attributes' => array('name'=>'zhang','type'=>'coworkers','code'=>1),
                                    '@nodevalue' => 'zhangjie',
                                ),
                                array(
                                    '@attributes' => array('name'=>'zhang','type'=>'coworkers','code'=>2),
                                    '@nodevalue' => 'zhanghairong',
                                ),
                            ),
                            'company' => array(
                                '@attributes' => array('name'=>'sdo','type'=>'company','code'=>1),
                                //'@nodevalue' => 'zhangjie',
                            ),
                        ),
                    ),
                    'workers2' => array(
                        '@attributes' => array('name'=>'person','type'=>'family'),
                        '@nodevalue' => array(
                            'person' => array(
                                array(
                                    '@attributes' => array('name'=>'zhang','type'=>'coworkers','code'=>1),
                                    '@nodevalue' => 'zhangjie',
                                ),
                                array(
                                    '@attributes' => array('name'=>'zhang','type'=>'coworkers','code'=>2),
                                    '@nodevalue' => 'zhanghairong',
                                ),
                            ),
                            'company' => array(
                                '@attributes' => array('name'=>'sdo','type'=>'company','code'=>1),
                                //'@nodevalue' => 'zhangjie',
                            ),
                        ),
                    )
                 ),
            ),
        );
        
        echo XmlEncoder::Encode($params);
    }
}    