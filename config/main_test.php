<?php
return array(
	'params' => array(
        'database' => array(
            'test' => array(
                'host' => 'localhost',
                'user' => 'root',
                'password' => 'root',
                'database' => 'test',
            ),
        ),
        
        'tableconfig' => array(
            'tb_userinfo' => array('gender'=>array('0'=>'男','1'=>'女')),
        ),
        
		'logconfigs' => Array(
			'all' => Array(
				'dir' => ROOT . DIRECTORY_SEPARATOR . 'log',
				'filename' => 'all.log',
				'levels' => Array('error' => 100, 'warning' => 100, 'info' => 100, 'debug' => 100),
				'timeLevel' => 1,
			),
			'interface' => Array(
				'dir' => ROOT . DIRECTORY_SEPARATOR . 'log',
				'filename' => 'interface.log',
				'levels' => Array('error' => 100, 'warning' => 100, 'info' => 100, 'debug' => 100),
				'timeLevel' => 0,
			),
			'dbinfo' => Array(
				'dir' => ROOT . DIRECTORY_SEPARATOR . 'log',
				'filename' => 'dbinfo.log',
				'levels' => Array('error' => 100, 'warning' => 100, 'info' => 100, 'debug' => 100),
				'timeLevel' => 0,
			),
		),
	),
);


