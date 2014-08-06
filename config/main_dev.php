<?php
return array(
	'params' => array(
        'sessiontype' => 'file',
        'database' => array(
            'monitor_config' => array(
                'host' => 'mongodb://127.0.0.1:27017/',
                'database' => 'monitor_config',
            ),
            'monitor_action' => array(
                'host' => 'mongodb://127.0.0.1:27017/',
                'database' => 'monitor_action',
            ),
        ),
        'pagesize' => 10,
        
        'tableconfig' => array(
            'account' => array('privilege'=>array(0X001=>'用户管理')),
            'curve_group' => array('type'=>array(0=>'action',1=>'page')),
        ),
        
        
		'logconfigs' => Array(
			'all' => Array(
				'dir' => ROOT . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . "monitor",
				'filename' => 'all.log',
				'levels' => Array('error' => 100, 'warning' => 100, 'info' => 100, 'debug' => 100),
				'timeLevel' => 1,
			),
			'interface' => Array(
				'dir' => ROOT . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . "monitor",
				'filename' => 'interface.log',
				'levels' => Array('error' => 100, 'warning' => 100, 'info' => 100, 'debug' => 100),
				'timeLevel' => 0,
			),
			'dbinfo' => Array(
				'dir' => ROOT . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . "monitor",
				'filename' => 'dbinfo.log',
				'levels' => Array('error' => 100, 'warning' => 100, 'info' => 100, 'debug' => 100),
				'timeLevel' => 0,
			),
		),
        
        
        'memcache_addrs' => array(
            1 => array(
                'ip' => '10.241.37.35',
                'port' => 11211,
                ),
                /*
            2 => array(
                'ip' => '10.152.21.11',
                'port' => 11215,
            ),*/
        ),
	),
);


