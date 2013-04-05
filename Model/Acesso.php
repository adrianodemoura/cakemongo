<?php
/**
 * Acesso Model
 * 
 * @package		app.Model
 */
App::uses('AppModel', 'Model');
/**
 * @package		app.Model
 */
class Acesso extends AppModel {
	/**
	 * Nome do campo de exibição personalizada. 
	 *
	 * Também usado no método find('list').
	 *
	 * @var		string
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#displayfield
	 */
	public $displayField 	= 'login';

	/**
	 * Nome da collection do banco de dados, ou nulo caso não possua tabela.
	 *
	 * @var		string
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
	 */
	public $useTable 		= 'acessos';

	/**
	 * Campos a serem salvos do jeito que foram digitados.\n
	 *
	 * @var 	array
	 */
	public $ignorarMaiusculo = array('login');

	/**
	 * Esquema
	 * 
	 * @var		array
	 */
	public $schema = array
	(
		'_id' 			=> array('type' => 'integer', 'primary' => true, 'length' => 40),
		'login' 		=> array
		(	'type' 		=> 'string',
			'length'	=> 20,
			'input'		=> array('label'=>'Login do Usuário')
		),
		'ip' 			=> array
		(	'type' 		=> 'string',
			'length'	=> 20,
			'input'		=> array('label'=>'Ip')
		),
		'data_acesso'	=> array
		(	'type' 		=> 'integer',
			'tipo'		=> 'datatempo',
			'input'		=> array('label'=>'Data de Acesso')
		),
		'data_saida'	=> array
		(	'type' 		=> 'integer',
			'tipo'		=> 'datatempo',
			'input'		=> array('label'=>'Último Click')
		),
	);

	/**
	 * Chamada depois de cada operação find. Pode ser usada para modificar os resultado retornado pelo método find().
	 * Retorno os valores modificados.
	 *
	 * @param	mixed	$results	Os resultados da operação find
	 * @param	boolean	$primary	Se o modeulo está sendo consultado pelo primarKey
	 * @return	mixed	Resultado da operação find
	 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#afterfind
	 */
	public function afterFind($results, $primary = false)
	{
		if (!empty($results) && !isset($results['0'][$this->name]['count']))
		{
			foreach($results as $_l => $_arrMods)
			{
				$dif = 0;
				if (isset($_arrMods['Acesso']['data_acesso']) && isset($_arrMods['Acesso']['data_saida']))
				{
					$results[$_l]['Acesso']['diferenca'] = difHora($_arrMods['Acesso']['data_acesso'],$_arrMods['Acesso']['data_saida']);
				}
			}
		}
		return parent::afterFind($results,$primary);
	}
}
