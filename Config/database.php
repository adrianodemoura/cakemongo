<?php
class DATABASE_CONFIG {

	/**
	 * Banco de dados padrão.\n
	 * Usando um dataSource do plugin mongodb.\n
	 * https://github.com/ichikaway/cakephp-mongodb\n
	 * 
	 * @var		array
	 */
	public $default = array
	(
			'datasource' 	=> 'Mongodb.MongodbSource',
			'host' 			=> 'localhost',
			'database' 		=> 'mongo_bd',
			'login' 		=> 'mongo_us',	
			'password' 		=> 'mongo_67',
			'port' 			=> 27017,
			'prefix' 		=> '',
			'persistent' 	=> 'true',
			/*
			// opcional
			'replicaset' 	=> array('host' => 'mongodb://servidor:usuario@localhost:27021,localhost:27022/controller', 
			                      'options' => array('replicaSet' => 'myRepl')
					     ),
			*/
	);
}
