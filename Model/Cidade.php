<?php
/**
 * Cidade Model
 * 
 * @package		app.Model
 */
/**
 * @package		app.Model
 */
class Cidade extends AppModel {
	/**
	 * Nome do campo de exibição personalizada. 
	 *
	 * Também usado no método find('list').
	 *
	 * @var		string
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#displayfield
	 */
	public $displayField 	= 'nome';

	/**
	 * Nome da collection do banco de dados, ou nulo caso não possua tabela.
	 *
	 * @var		string
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
	 */
	public $useTable 		= 'cidades';

	/**
	 * Esquema para model Cidade
	 * 
	 * @var		array
	 */
	public $schema = array
	(
		'_id' 			=> array('type' => 'integer', 'primary' => true, 'length' => 24),
		'codigo' 			=> array
		(	'type' 		=> 'integer',
			'input'		=> array('label'=>'Código','style'=>'width: 100px;'),
			'td'		=> array('style'=>'text-align: center;')
		),
		'nome' 			=> array
		(	'type' 		=> 'string',
			'length'	=> 80,
			'input'		=> array('label'=>'Nome','style'=>'width: 300px;')
		),
		'uf' 			=> array
		(	'type' 		=> 'string',
			'length'	=> 2,
			'input'		=> array('label'=>'Uf','style'=>'width: 30px; text-align: center')
		),
		'estado' 		=> array
		(	'type' 		=> 'string',
			'length'	=> 80,
			'input'		=> array('label'=>'Estado','style'=>'width: 300px;')
		),
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
		'nome' => array
		(
			1 	=> array
			(
				'rule' 		=> 'notEmpty',
				'required' 	=> true,
				'message' 	=> 'É necessário informar o nome da cidade!',
			)
		),
		'uf' => array
		(
			1 	=> array
			(
				'rule' 		=> 'notEmpty',
				'required' 	=> true,
				'message' 	=> 'É necessário informar a Unidade de Federação!',
			)
		),
	);

	/**
	 * Cria o Cache de cidades
	 * 
	 * @return	void
	 */
	public function setCache()
	{
		if (!Cache::read($this->useTable))
		{
			$cache	= array();
			$_cache = $this->find
			('list',array
				(
					'order'=>array('Cidade.nome'=>'asc'),
					'fields'=>array('Cidade.nome','Cidade.uf')
				)
			);
			foreach($_cache as $_nome => $_uf)
			{
				$cache[$_nome.' / '.$_uf] = $_nome.' / '.$_uf;
			}
			Cache::write($this->useTable,$cache);
		}
	}
}
