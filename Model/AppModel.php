<?php
/**
 * Model cakeMongo
 *
 * @package		app.Model
 * 
 */
App::uses('Model', 'Model');
/**
 * @package       app.Model
 */
class AppModel extends Model {
	/**
	 * Nome da primaryKey do model.
	 *
	 * @var		string
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#primaryKey
	 */
	public $primaryKey = '_id';

	/**
	 * Erro
	 * 
	 * @var		string
	 */
	public $erro		= '';

	/**
	 * Esquema do model usuários
	 */
	public $schema 	= array();

	/**
	 * Por padrão todos os campos são salvos em maiúsculos.\n
	 * Se quiser ignorar algum campo, coloque-os aqui.
	 *
	 * @var 	array
	 */
	public $ignorarMaiusculo = array();

	/**
	 * Retorna o schema do model.\n
	 * Aproveitei a xepa pra incrementar outras propriedades, porque eu não vou criar uma view pra cada controller.
	 * 
	 * @param	string	$registro	Nome do registro a ser retornado
	 * @return	array
	 */
	function schema($registro='') 
	{
		$this->_schema = $this->schema;
		
		// implementando schema
		foreach($this->schema as $_cmp => $_arrProp)
		{
			// descobrindo o tipo do campo
			$tipo = isset($this->_schema[$_cmp]['type']) ? $this->_schema[$_cmp]['type'] : 'string';
			$tipo = isset($this->_schema[$_cmp]['tipo']) ? $this->_schema[$_cmp]['tipo'] : $tipo;

			// algumas propriedades para o input
			$this->schema[$_cmp]['input']['maxlength'] = (isset($this->schema[$_cmp]['length'])) ? $this->schema[$_cmp]['length'] : '';
			$this->schema[$_cmp]['input']['maxlength'] = (isset($this->schema[$_cmp]['mascara'])) ? strlen($this->schema[$_cmp]['mascara']) : $this->schema[$_cmp]['input']['maxlength'];

			// sem auto-complete
			$this->schema[$_cmp]['input']['autocomplete'] = "off";

			// tratando alguns tipos
			switch($tipo)
			{
				case 'datatempo':
					if (in_array($_cmp,array('criado','modificado')))		$this->schema[$_cmp]['input']['readonly'] = 'readonly';
					if (empty($this->schema[$_cmp]['input']['maxlength'])) $this->schema[$_cmp]['input']['maxlength'] = 20;
					if (empty($this->schema[$_cmp]['mascara'])) 			$this->schema[$_cmp]['mascara'] = '99/99/9999 99:99';
					if (empty($this->schema[$_cmp]['length'])) 			$this->schema[$_cmp]['length'] = 19;
					break;
			}
		}

		if (!empty($registro)) return $this->schema[$registro];
		$this->_schema = $this->schema;

        return $this->_schema;
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
		if (!empty($this->data))
		{
			foreach($this->data as $_mod => $_arrCmps)
			{
				foreach($_arrCmps as $_cmp => $_vlr)
				{
					// tudo maiúsculo
					if (!in_array($_cmp, $this->ignorarMaiusculo))
					{
						if (is_string($_vlr)) $this->data[$_mod][$_cmp] = mb_strtoupper($_vlr,'UTF8');
					}
				}
			}
		}
		return true;
	}

	/**
	 * Executa código antes da operação salvar, depois da validação.\n
	 * - Retorna verdadeiro/falso se deve salvar.
	 * - Todo atributo deve ser convertido para o maiúsculo.
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
					// descobrindo o tipo do campo
					$tipo = isset($this->schema[$_cmp]['type']) ? $this->schema[$_cmp]['type'] : 'string';
					$tipo = isset($this->schema[$_cmp]['tipo']) ? $this->schema[$_cmp]['tipo'] : $tipo;

					// limpando a máscara
					if (isset($this->schema[$_cmp]['mascara']))
					{
						$m = str_replace('9','',$this->schema[$_cmp]['mascara']);
						for($i=0; $i<strlen($m); $i++)
						{
							$_vlr = str_replace(substr($m,$i,1),'',$_vlr);
						}
						$this->data[$_mod][$_cmp] = $_vlr;
					}
					
					// tratando alguns tipos
					switch($tipo)
					{
						case 'datatempo':
						case 'data':
							if ($_vlr)
							{
								// transformando o campo data em segundos, porque vou salvar em segundos.
								$_vlr	= str_replace('-','',$_vlr);
								$ano	= substr($_vlr,4,4);
								$mes	= substr($_vlr,2,2);
								$dia	= substr($_vlr,0,2);
								$hora	= substr($_vlr,8,2);
								$minu	= substr($_vlr,10,2);
								$segu	= date('s');
								$this->data[$_mod][$_cmp] = mktime($hora,$minu,$segu,$mes,$dia,$ano);
								//debug($_vlr); debug($this->schema[$_cmp]);
							}
							if ($_cmp=='modificado')
							{
								$this->data[$_mod][$_cmp] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
							}
							if ($_cmp=='criado' && (empty($this->data[$_mod][$this->primaryKey])))
							{
								$this->data[$_mod][$_cmp] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
							}
							if ($_cmp=='criado' && (!empty($this->data[$_mod][$this->primaryKey])))
							{
								unset($this->data[$_mod][$_cmp]);
							}
							break;
					}
				}
			}
			if (isset($this->data[$this->name][$this->primaryKey]) && empty($this->data[$this->name][$this->primaryKey]))
			{
				if (isset($this->schema['criado']))
				{
					$this->data[$this->name]['criado'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
				}
			}
		}
		return true;
	}

	/**
	 * Executa uma busca no banco de dados.
	 * 
	 * @param	string	$type	Type of find operation (all / first / count / neighbors / list / threaded)
	 * @param	array	$query	Option fields (conditions / fields / joins / limit / offset / order / page / group / callbacks)
	 * @param	string	$cache	Nome do cache
	 * @return	array	Array of records, or Null on failure.
	 * @link http://book.cakephp.org/2.0/en/models/deleting-data.html#deleteall
	 */
	public function find($type = 'first', $query = array(), $cache='')
	{
		if (!empty($cache))
		{
			$res = Cache::read($cache);
			if (!$res)
			{
				$res = parent::find($type,$query);
				Cache::write($cache,$res);
				return $res;
			} else
			{
				return $res;
			}
		}
		return parent::find($type,$query);
	}

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
				foreach($_arrMods as $_mod => $_arrCmps)
				{
					foreach($_arrCmps as $_cmp => $_vlr)
					{

						// descobrindo o tipo do campo
						$tipo = isset($this->schema[$_cmp]['type']) ? $this->schema[$_cmp]['type'] : 'string';
						$tipo = isset($this->schema[$_cmp]['tipo']) ? $this->schema[$_cmp]['tipo'] : $tipo;

						// tratando alguns tipos
						switch($tipo)
						{
							case 'datatempo':
								if ($_vlr<>null && !empty($_vlr) && is_numeric($_vlr))
								{
									$results[$_l][$_mod][$_cmp] = date('d/m/Y H:i:s', $_vlr);
									$results[$_l][$_mod][$_cmp] = substr($results[$_l][$_mod][$_cmp],0,16);
								}
								break;
						}
					}
				}
			}
		}
		return $results;
	}

	/**
	 * Chamada depois de cada método "save".
	 * - Deleta a listaModel, que possa estar no CACHE.
	 *
	 * @param	boolean	$created	Verdadeiro se foi criado um novo registro, um inclusão.
	 * @return	void
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#aftersave
	 */
	public function afterSave($created) 
	{
		Cache::delete('lista'.$this->name);
	}

	/**
	 * Chamada depois da execução do método delete.
	 *
	 * @return	void
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#afterdelete
	 */
	public function afterDelete() 
	{
		Cache::delete('lista'.$this->name);
	}

	/**
	 * Correção para falha do método isUnique
	 * https://github.com/ichikaway/cakephp-mongodb
	 * 
	 * @param	array	$campos	[campo][valor] a ser testado.
	 * @return	boolean	Verdadeiro se NÃO tem repetição, Falso se tem.
	 */
   function isUnique($campos=array()) 
   {
		foreach($campos as $_cmp => $_vlr) 
		{
			$opc = array();
			$opc['conditions'][$_cmp]	= $_vlr;
			if (!empty($this->id)) $opc['conditions'][$this->primaryKey.' <>']	= $this->id;
			$data	= $this->find('first',$opc);
			if (!empty($data)) return false;
		}
		return true;
  }
}
