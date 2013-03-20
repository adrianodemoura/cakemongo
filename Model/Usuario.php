<?php
/**
 * Usuario Model
 * 
 * @package		app.Model
 */
/**
 * @package		app.Model
 */
class Usuario extends AppModel {
	/**
	 * Nome do campo de exibição personalizada. 
	 *
	 * Também usado no método find('list').
	 *
	 * @var		string
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#displayfield
	 */
	public $displayField 		= 'nome';

	/**
	 * Nome da collection do banco de dados, ou nulo caso não possua tabela.
	 *
	 * @var		string
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
	 */
	public $useTable 			= 'usuarios';

	/**
	 * Campos a serem salvos do jeito que foram digitados.\n
	 *
	 * @var 	array
	 */
	public $ignorarMaiusculo 	= array('senha','email','login');

	/**
	 * Esquema do model usuários
	 * 
	 * @var		array
	 */
	public $schema = array
	(
		'_id' 			=> array('type' => 'integer', 'primary' => true, 'length' => 24),
		'senha' 		=> array
		(	'type' 		=> 'string',
			'null'		=> false,
			'length'	=> 12,
			'input'		=> array('label'=>'Senha','type'=>'password','style'=>'width: 120px; text-align: center;')
		),
		'login' 		=> array
		(	'type' 		=> 'string',
			'length'	=> 15,
			'null'		=> false,
			'input'		=> array('label'=>'Login','style'=>'width: 120px; text-transform: lowercase; text-align: center;')
		),
		'nome' 			=> array
		(	'type' 		=> 'string',
			'length'	=> 80,
			'null'		=> false,
			'input'		=> array('label'=>'Nome','style'=>'width: 300px;')
		),
		'ativo' 		=> array
		(	'type' 		=> 'string',
			'length'	=> 1,
			'null'		=> false,
			'input'		=> array('label'=>'Ativo','options'=>array(true=>'Sim',false=>'Não'))
		),
		'endereco'		=> array
		(	'type' 		=> 'string',
			'length'	=> 80,
			'input'		=> array('label'=>'Endereço','style'=>'width: 300px;')
		),
		'bairro'		=> array
		(	'type' 		=> 'string',
			'length'	=> 50,
			'input'		=> array('label'=>'Bairro','style'=>'width: 300px;')
		),
		'sexo' 			=> array
		(	'type' 		=> 'string',
			'length'	=> 1,
			'input'		=> array('default'=>'M','label'=>'Sexo','options'=>array('F'=>'F','M'=>'M'))
		),
		'aniversario' 	=> array
		(	'type' 		=> 'string',
			'length'	=> 4,
			'td'		=> array('style'=>'text-align: center;'),
			'input'		=> array('label'=>'Aniversário','style'=>'width: 40px; text-align: center;'),
			'mascara'	=> '99/99'
		),
		'cidade' 			=> array
		(	'type' 		=> 'string',
			'length'	=> 50,
			'input'		=> array('default'=>'BELO HORIZONTE / MG','label'=>'Cidade','empty'=>'--','options'=>'cidades','style'=>'width: 300px;')
		),
		'cep' 			=> array
		(	'type' 		=> 'string',
			'length'	=> 8,
			'input'		=> array('label'=>'Cep','style'=>'width: 80px; text-align: center;'),
			'mascara'	=> '99.999-999'
		),
		'telResidencial'=> array
		(	'type' 		=> 'string',
			'length'	=> 10,
			'input'		=> array('label'=>'Tel. Residêncial','style'=>'width: 98px; text-align: center;'),
			'mascara'	=> '(99)9999-9999'
		),
		'telComercial'=> array
		(	'type' 		=> 'string',
			'length'	=> 10,
			'input'		=> array('label'=>'Tel. Comercial','style'=>'width: 98px; text-align: center;'),
			'mascara'	=> '(99)9999-9999'
		),
		'celular'=> array
		(	'type' 		=> 'string',
			'length'	=> 10,
			'input'		=> array('label'=>'Celular','style'=>'width: 98px; text-align: center;'),
			'mascara'	=> '(99)9999-9999'
		),
		'perfil'		=> array
		(
			'type'		=> 'string',
			'length'	=> 30,
			'input'		=> array('label'=>'Perfil','options'=>'perfis'),
		),
		'acessos'		=> array
		(
			'type'		=> 'integer',
			'input'		=> array('label'=>'Acessos'),
		),
		'ultimo_ip'		=> array
		(
			'type'		=> 'string',
			'length'	=> 15,
			'input'		=> array('label'=>'Acessos'),
		),
		'ultimo'		=> array
		(
			'type'		=> 'integer',
			'tipo'		=> 'datatempo',
			'input'		=> array('label'=>'Último Acesso'),
		),
		'modificado'	=> array
		(	'type' 		=> 'integer',
			'tipo'		=> 'datatempo',
			'input'		=> array('label'=>'Modificado')
		),
		'criado'=> array
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
		'nome' => array
		(
			1 	=> array
			(
				'rule' 		=> 'notEmpty',
				'required' 	=> true,
				'message' 	=> 'É necessário informar o nome do Usuário!',
			),
			2 	=> array
			(
				'rule' 		=> 'isUnique',
				'message' 	=> 'Este nome já foi cadastrado!',
				'on'		=> 'create'
			),
		),
		'login' => array
		(
			1 	=> array
			(
				'rule' 		=> 'notEmpty',
				'message' 	=> 'É necessário informar o login do Usuário!',
			),
			2 	=> array
			(
				'rule' 		=> 'isUnique',
				'message' 	=> 'Este login já foi cadastrado!',
			),
		),
		'senha'	=> array
		(
			1	=> array
			(
				'rule'		=> 'notEmpty',
				'required'	=> true,
				'message'	=> 'A senha é obrigatória no momento da inclusão !',
				'on'		=> 'create'
			),
			2	=> array
			(
				'rule'		=> array('minLength', '6'),
				'msg'		=> 'A senha deve ter no mínimo 6 caracteres',
				'on'		=> 'create'
			),
		)
	);

	/**
	 * Executa código antes da operação salvar, depois da validação.\n
	 * - Encripta a senha do usuário caso ela seja postada.\n
	 * - O Usuário administrador sempre DEVE estar ativo.\n
	 * - O Usuário administrador sempre DEVE estar no Perfil Administrador.\n
	 * - O Campo criado deve ser configurado na inclusão.\n
	 *
	 * @param	array	$options
	 * @return	boolean	Verdadeiro se a operação deve continuar, false se deve abordar.
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#beforesave
	 */
	public function beforeSave($options = array()) 
	{
		// encriptando a senha
		if (isset($this->data['Usuario']['senha']))
		{
			if (!empty($this->data['Usuario']['senha']))
			{
				App::uses('Security', 'Utility');
				$senha = Security::hash(Configure::read('Security.salt') . $this->data['Usuario']['senha']);
				$this->data['Usuario']['senha'] = $senha;
			} else
			{
				unset($this->data['Usuario']['senha']);
			}
		}
		// usuário administrador, sempre administrador.
		if (isset($this->data['Usuario']['login']) && $this->data['Usuario']['login']=='admin')
		{
			$this->data['Usuario']['login'] = 'admin';
			$this->data['Usuario']['perfil']= 'ADMINISTRADOR';
			$this->data['Usuario']['ativo'] = true;
		}
		// campo criado
		if (empty($this->data['Usuario'][$this->primaryKey]))
		{
			$this->data['Usuario']['criado'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
		}
		return parent::beforeSave($options);
	}

	/**
	 * Chamada depois de cada operação find. Pode ser usada para modificar os resultado retornado pelo método find().
	 * - Retorna os valores modificados.
	 * - Limpa alguns campos como senha
	 *
	 * @param	mixed	$results	Os resultados da operação find
	 * @param	boolean	$primary	Se o modeulo está sendo consultado pelo primarKey
	 * @return	mixed	Resultado da operação find
	 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#afterfind
	 */
	public function afterFind($results, $primary = false)
	{
		$results = parent::afterFind($results, $primary);
		if (!empty($results) && !isset($results['0'][$this->name]['count']))
		{
			foreach($results as $_l => $_arrMods)
			{
				if (isset($_arrMods['Usuario']['senha']))
				{
					$results[$_l]['Usuario']['senha'] = '';
				}
			}
		}
		return $results;
	}

	/**
	 * Chamada antes de cada operação de exclusão.
	 *
	 * @param	boolean	$cascade	Se verdadeiro, o registros dependentes também serão excluídos.
	 * @return	boolean	Retorna verdadeiro se a operação deve continuar, false se deve abortar.
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#beforedelete
	 */
	public function beforeDelete($cascade = true) 
	{
		$opc['conditions']['Usuario._id'] = $this->id;
		$data = $this->find('all',$opc);
		if ($data['0']['Usuario']['login']=='admin')
		{
			$this->erro = 'O Usuário Administrador não pode ser excluído !!!';
			return false;
		}
		return true;
	}

	/**
	 * Chamada para cada método de validação, antes da validação.
	 * - Todos os campos serão salvos em maiúsculo, salvo se foi configurado no atributo ignorarMaisculo.
	 *
	 * @param	array	$options Options passed from model::save(), see $options of model::save().
	 * @return 	boolean True if validate operation should continue, false to abort
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
	 */
	public function beforeValidate($options = array()) 
	{
		// login sempre em minúsculo
		if (isset($this->data['Usuario']['login']))
		{
			$this->data['Usuario']['login'] = strtolower($this->data['Usuario']['login']);
		}
		return parent::beforeValidate($options);
	}
}
