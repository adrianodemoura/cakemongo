<?php
/**
 * Url Model
 * 
 * Neste model são configuradas as regras de acesso para cada perfil de usuário.
 * 
 * 
 * @package		app.Model
 */
/**
 * @package		app.Model
 */
class Url extends AppModel {
	/**
	 * Nome do campo principal do model.
	 *
	 * Também usado no método find('list').
	 *
	 * @var		string
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#displayfield
	 */
	public $displayField 	= 'link';

	/**
	 * Nome da collection do banco de dados, ou nulo caso não possua tabela.
	 *
	 * @var		string
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
	 */
	public $useTable 		= 'urls';

	/**
	 * Campos a serem salvos do jeito que foram digitados.\n
	 *
	 * @var 	array
	 */
	public $ignorarMaiusculo = array('link');

	/**
	 * Esquema
	 * 
	 * @var		array
	 */
	public $schema = array
	(
		'_id' 			=> array('type' => 'integer', 'primary' => true, 'length' => 40),
		'link' 			=> array
		(	'type' 		=> 'string',
			'index'		=> true,
			'length'	=> 60,
			'null'		=> false,
			'th'		=> array('style'=>'width: 300px;'),
			'td'		=> array('style'=>'align: left;'),
			'input'		=> array('label'=>'Url','style'=>'width: 600px; text-transform: none;')
		),
		'perfis' 		=> array
		(	'type' 		=> 'array',
			'null'		=> false,
			'tipo'		=> 'multiplo',
			'input'		=> array('label'=>'Perfis','options'=>'perfis')
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
		'link' => array
		(
			1 	=> array
			(
				'rule' 		=> 'notEmpty',
				'required' 	=> true,
				'message' 	=> 'É necessário informar o link!',
			),
			2 	=> array
			(
				'rule' 		=> 'isUnique',
				'message' 	=> 'Esta link já foi cadastrada!'
			),
		)
	);

	/**
	 * Executa código antes da operação salvar, depois da validação.\n
	 * - Retorna verdadeiro/falso se deve salvar.
	 * - Todos os campos devem ser transformados para o maiúscolo.
	 * - Todos as máscaras deverão ser limpas
	 *
	 * @param	array	$options
	 * @return	boolean	Verdadeiro se a operação deve continuar, false se deve abordar.
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#beforesave
	 */
	public function beforeSave($options = array()) 
	{
		if (!empty($this->data))
		{
			foreach($this->data as $_mod => $_arrCmps)
			{
				foreach($_arrCmps as $_cmp => $_vlr)
				{
					// tudo minúsculo
					if (!in_array($_cmp,array('perfis')))
					{
						$this->data[$_mod][$_cmp] = mb_strtolower($_vlr,'UTF8');
					}
				}
			}
		}
		return true;
	}

	/**
	 * Chamada depois de cada método "save".
	 *
	 * @param	boolean	$created	Verdadeiro se foi criado um novo registro, um inclusão.
	 * @return	void
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#aftersave
	 */
	public function afterSave($created) 
	{
		parent::afterSave($created);
		if (isset($this->data['Url']['perfis']) && is_array(($this->data['Url']['perfis'])))
		{
			foreach($this->data['Url']['perfis'] as $_l => $_perfil)
			{
				Cache::delete('urls'.$_perfil);
			}
		}
	}

	/**
	 * Chamada depois da execução do método delete.
	 *
	 * @return	void
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#afterdelete
	 */
	public function afterDelete() 
	{
		parent::afterDelete();
		foreach($this->data['Url']['perfis'] as $_l => $_perfil)
		{
			Cache::delete('urls'.$_perfil);
		}
	}
}
