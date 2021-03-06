<?php
/**
 * Agenda Model
 * 
 * @package		app.Model
 */
App::uses('AppModel', 'Model');
/**
 * @package		app.Model
 */
class Agenda extends AppModel {
	/**
	 * Nome do campo de exibição personalizada. 
	 *
	 * Também usado no método find('list').
	 *
	 * @var		string
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#displayfield
	 */
	public $displayField 	= 'evento';

	/**
	 * Nome da collection do banco de dados, ou nulo caso não possua tabela.
	 *
	 * @var		string
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
	 */
	public $useTable 		= 'agendas';

	/**
	 * Campos a serem salvos do jeito que foram digitados.\n
	 *
	 * @var 	array
	 */
	public $ignorarMaiusculo = array('evento');

	/**
	 * Esquema
	 * 
	 * @var		array
	 */
	public $schema = array
	(
		'_id' 			=> array('type' => 'integer', 'primary' => true, 'length'=>24),
		'evento' 		=> array
		(	'type' 		=> 'string',
			'index'		=> true,
			'length'	=> 250,
			'input'		=> array('label'=>'Evento','style'=>array('width: 600px; text-transform: none;'))
		),
		'data_txt'	=> array
		(	'type' 		=> 'integer',
			'index'		=> true,
			'tipo'		=> 'datatempo',
			'input'		=> array('label'=>'Data/Hora')
		),
		'criado'		=> array
		(	'type' 		=> 'integer',
			'tipo'		=> 'datatempo',
			'input'		=> array('label'=>'Criado')
		)
	);

	/**
	 * Regras de validação para cada campo do módulo
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#validate
	 * @link	http://book.cakephp.org/2.0/en/models/data-validation.html
	 */
	public $validate = array
	(
		'evento' => array
		(
			1 	=> array
			(
				'rule' 		=> 'notEmpty',
				'required' 	=> true,
				'message' 	=> 'É necessário informar o Evento!',
			)
		),
		'data_txt' => array
		(
			1 	=> array
			(
				'rule' 		=> 'notEmpty',
				'required' 	=> true,
				'message' 	=> 'É necessário informar a data e horário!',
			)
		),
	);
}
