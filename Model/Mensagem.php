<?php
/**
 * Mensagem Model
 * 
 * @package		app.Model
 */
App::uses('AppModel', 'Model');
/**
 * @package		app.Model
 */
class Mensagem extends AppModel {
	/**
	 * Nome do campo de exibição personalizada. 
	 *
	 * Também usado no método find('list').
	 *
	 * @var		string
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#displayfield
	 */
	public $displayField 	= 'texto';

	/**
	 * Nome da collection do banco de dados, ou nulo caso não possua tabela.
	 *
	 * @var		string
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
	 */
	public $useTable 		= 'mensagens';

	/**
	 * Campos a serem salvos do jeito que foram digitados.\n
	 *
	 * @var 	array
	 */
	public $ignorarMaiusculo = array('texto');

	/**
	 * Esquema
	 * 
	 * @var		array
	 */
	public $schema = array
	(
		'_id' 			=> array('type' => 'integer', 'primary' => true, 'length'=>24),
		'texto' 		=> array
		(	'type' 		=> 'string',
			'index'		=> true,
			'length'	=> 250,
			'input'		=> array('label'=>'Texto','style'=>array('width: 600px; text-transform: none;'))
		),
		'modificado'	=> array
		(	'type' 		=> 'integer',
			'index'		=> true,
			'tipo'		=> 'datatempo',
			'input'		=> array('label'=>'Modificado')
		),
		'criado'		=> array
		(	'type' 		=> 'integer',
			'tipo'		=> 'datatempo',
			'input'		=> array('label'=>'Criado')
		)
	);
}
