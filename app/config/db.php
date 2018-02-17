<?php
return [
    'class' 	=> 'yii\db\Connection', 
    'dsn' 	=> "pgsql:host=127.0.0.1;dbname=ce_dev", 
    'username'  => 'ce', 
    'password'  => 'ececec', 
    'schemaMap' => [ 'pgsql' => 'tigrov\pgsql\Schema' ]
];
