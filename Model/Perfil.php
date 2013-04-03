<?php
/**
 * Perfil Model
 * 
 * @package		app.Model
 */
/**
 * @package		app.Model
 */
class Perfil extends AppModel {
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
	public $useTable 		= 'perfis';

	/**
	 * Esquema
	 * 
	 * @var		array
	 */
	public $schema = array
	(
		'_id' 			=> array('type' => 'integer', 'primary' => true, 'length' => 40),
		'nome' 			=> array
		(	'type' 		=> 'string',
			'index'		=> true,
			'length'	=> 20,
			'input'		=> array('label'=>'Nome','style'=>'width: 300px;')
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
				'message' 	=> 'É necessário informar o nome do perfil!',
			),
			2 	=> array
			(
				'rule' 		=> 'isUnique',
				'message' 	=> 'Este perfil já foi cadastrado!',
			),
		),
	);

	/**
	 * Chamada antes do método de exclusão.
	 * - Perfil ADMINISTRADOR não pode ser excluído.
	 *
	 * @param	boolean	$cascade	Se verdadeiro, os documentos dependentes também serão excluídos.
	 * @return	boolean				Verdadeiro se deve continuar, false se deve abortar.
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#beforedelete
	 */
	public function beforeDelete($cascade = true) 
	{
		$opc['conditions']['Perfil._id'] = $this->id;
		$data = $this->find('list',$opc);
		foreach($data as $_id => $_perfil)
		{
			$this->nome= $_perfil;
			if ($_perfil=='ADMINISTRADOR')
			{
				$this->erro = 'Perfil ADMINISTRADOR não pode ser excluído !!!';
				return false;
			}
		}
		return true;
	}

	/**
	 * Chamada depois da execução do método delete.\n
	 * - Exclui o cache de permissão do perfil excluído..\n
	 * - Remove o perfil excluído das permissões.\n
	 * - Remove o perfil dos usuários.\n
	 *
	 * @return	void
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#afterdelete
	 */
	public function afterDelete() 
	{
		// excluido todos os caches
		Cache::delete('urls'.$this->nome);

		// remove o perfil das permissões
		App::uses('Url','Model');
		$Url  = new Url();
		$opc['conditions']['Url.perfis']['$in'] = array($this->nome);
		$data = $Url->find('all',$opc);
		$dataN= array();
		foreach($data as $_l => $_arrMods)
		{
			$dataN[$_l]['Url']['_id']		= $_arrMods['Url']['_id'];
			$dataN[$_l]['Url']['link'] 		= $_arrMods['Url']['link'];
			$_perfis = $_arrMods['Url']['perfis'];
			$perfis  = array();
			foreach($_perfis as $_l2 => $_perfil) if ($_perfil!=$this->nome) $perfis[$_l2] = $_perfil;
			$dataN[$_l]['Url']['perfis'] = $perfis;
		}
		if (count($dataN) && !$Url->saveAll($dataN)) die('fudeu na hora de atualizar Permissões !!!');

		// removendo cache list
		Cache::delete('listPerfil');

		// removendo perfil de usuários
		/*App::uses('Usuario','Model');
		$Usuario = new Usuario();
		if (!$Usuario->updateAll(array('perfil'=>''),array('Usuario.perfil'=>$this->nome)))
		{
			die('Fudeu na hora de atualizar Usuários !!!');
		}*/

		parent::afterDelete();
	}

	/**
	 * Chamada depois de cada método "save".
	 * - Quando na inclusão de um novo registro, cria atomaticamente, uma permissão para as seguintes telas
	 * 	usuarios/meus_dados
	 *  ajuda/sobreMim
	 *  acessos/listar
	 *
	 * @param	boolean	$criou	Verdadeiro se foi criado um novo registro, um inclusão.
	 * @return	void
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#aftersave
	 */
	public function afterSave($criou) 
	{
		$opc['conditions']['Perfil.nome'] = 'asc';
		$lista = $this->find('list',$opc);
		foreach($lista as $_id => $_perfil) Cache::delete('urls'.$_perfil);

		// removendo cache list
		Cache::delete('listPerfil');

		parent::afterSave($criou);
		/*
		 * está gerando duplicidade, tem que buscar as urls aonde o link já existe.
		 */
		if ($criou)
		{
			$links['0'] = '/ajuda/sobre_mim';
			$links['1'] = '/acessos/listar';
			$links['2'] = '/usuarios/meus_dados';

			// recuperando as permissões já configuradas, pq deve sofrer update e não insert
			$perfil = $this->data['Perfil']['nome'];
			App::uses('Url','Model');
			$Url 	= new Url();
			$opc	= array();
			$opc['order']['Url.link'] = 'asc';
			$opc['conditions']['Url.link']['$in'] = $links;
			$data = $Url->find('all',$opc);
			//debug($data);
			
			$novaData 	= array();
			foreach($links as $_l => $_link)
			{
				$novaData[$_l]['Url']['_id'] 	= 0;
				$novaData[$_l]['Url']['link'] 	= $_link;
				$novaData[$_l]['Url']['perfis'] = array($perfil);
				foreach($data as $_l2 => $_arrMods)
				{
					if ($_arrMods['Url']['link']==$_link)
					{
						$novaData[$_l]['Url']['_id'] = $_arrMods['Url']['_id'];
						$novaData[$_l]['Url']['perfis'] = $_arrMods['Url']['perfis'];
						if (is_array($_arrMods['Url']['perfis']))
						{
							if (!in_array($perfil,$novaData[$_l]['Url']['perfis']))
							{
								array_push($novaData[$_l]['Url']['perfis'],$perfil);
							}
						}
					}
				}
			}
			//debug($novaData);

			$res = $Url->saveAll($novaData);
			if (!$res)
			{
				echo '<pre>'.print_r($Url->validationErros,true).'</pre>';
				die('Erro ao incluir permiss&otilde;es automaticamente !!!');
			}
		}
	}
}
