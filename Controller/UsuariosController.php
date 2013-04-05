<?php
/**
 * 
 * @package       app.Controller
 */
/**
 * @package       app.Controller
 */
class UsuariosController extends AppController {
	/**
	 * Model usuarios
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/controllers.html#components-helpers-and-uses
	 */
	public $uses = array('Usuario');

	/**
	 * Executa código antes de tudo
	 * 
	 * @link	http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
	 */
	public function beforeFilter()
	{
		if (in_array($this->action,array('editar','excluir','salvar')))
		{
			$this->viewVars['edicaoCampos'] = array('Usuario.nome','Usuario.sexo','Usuario.aniversario','Usuario.ativo','#',
			'Usuario.endereco','#',
			'Usuario.bairro','#',
			'Usuario.cidade','Usuario.cep','#',
			'Usuario.telResidencial','Usuario.telComercial','Usuario.celular','-',
			'Usuario.login','Usuario.senha','-','Usuario.perfil','-',
			'Usuario.modificado','Usuario.criado');
			$this->viewVars['focus'] = 'Usuario.nome';
			if ($this->action=='editar') unset($this->viewVars['opcMenuLista']['Editar']);
		}
		
		if ($this->action=='listar')
		{
			$this->viewVars['listaCampos']	= array('Usuario.login','Usuario.nome',
			'Usuario.telResidencial','Usuario.celular',
			'Usuario.aniversario','Usuario.perfil',
			'Usuario.modificado','Usuario.criado');
		}
		parent::beforeFilter();
	}

	/**
	 * Exibe a tela principal do sistema.\n
	 * - Aqui será exibido o menu rápido do sistema.
	 * 
	 * @return	void
	 */
	public function principal()
	{
		$this->viewVars['titulo'] = 'Acesso Rápido';
		// recuperando os 5 últimos recados
		App::uses('Mensagem','Model');
		$Mensagem = new Mensagem();
		$opc = array();
		$opc['fields'] 	= array('texto');
		$opc['limit']	= 5;
		$opc['order']['Mensagem.modificado'] = 'desc';
		$mensagens = $Mensagem->find('all',$opc);
		$this->viewVars['mensagens'] = $mensagens;
	}

	/**
	 * Exibe a tela de login
	 * 
	 * @retun	void
	 */
	public function login()
	{
		if ($this->Session->check('Usuario.login')) $this->redirect('index');
		if (isset($this->data['Usuario']['login']))
		{
			App::uses('Security', 'Utility');
			$senha = Security::hash(Configure::read('Security.salt') . $this->data['Usuario']['senha']);
			$opc = array();
			$opc['conditions']['Usuario.login'] = $this->data['Usuario']['login'];
			$opc['conditions']['Usuario.senha'] = Security::hash(Configure::read('Security.salt') . $this->data['Usuario']['senha']);
			$dataUs	= $this->Usuario->find('all',$opc);
			if (empty($dataUs))
			{
				$this->Session->setFlash('Usuário Inválido !!!','default', array('class'=>'msgErro'));
				//$this->redirect('login');
			}  else
			{
				if ($dataUs['0']['Usuario']['ativo']==false)
				{
					$this->Session->setFlash('Este Usuário está desativado !!!','default', array('class'=>'msgErro'));
					$this->redirect('login');
				} else
				{
					unset($dataUs['0']['Usuario']['senha']);
					debug($dataUs);
					$acessos = isset($dataUs['0']['Usuario']['acessos']) ? $dataUs['0']['Usuario']['acessos'] : 0;
					$novaData['Usuario'][$this->viewVars['primaryKey']] 		= $dataUs['0']['Usuario'][$this->viewVars['primaryKey']];
					$novaData['Usuario']['perfil'] 		= $dataUs['0']['Usuario']['perfil'];
					$novaData['Usuario']['nome'] 		= $dataUs['0']['Usuario']['nome'];
					$novaData['Usuario']['login'] 		= $dataUs['0']['Usuario']['login'];
					$novaData['Usuario']['acessos'] 	= $acessos+1;
					$dataUs['0']['Usuario']['ultimo'] 	= isset($dataUs['0']['Usuario']['ultimo']) ? $dataUs['0']['Usuario']['ultimo'] : '0';
					$dataUs['0']['Usuario']['ultimo_ip']= isset($dataUs['0']['Usuario']['ultimo_ip']) ? $dataUs['0']['Usuario']['ultimo_ip'] : '0';
					$novaData['Usuario']['ultimo_ip'] 	= getenv('REMOTE_ADDR');
					$novaData['Usuario']['ultimo'] 		= date('d-m-Y H:i:s');
					if (!$this->Usuario->save($novaData))
					{
						debug($dataUs);
						debug($novaData);
						echo '<pre>'.print_r($this->Usuario->validationErrors,true).'</pre>';
						die('fudeu !');
					} else
					{
						// atualizando acessos
						App::uses('Acesso','Model');
						$A = new Acesso();
						$da['login'] 		= $novaData['Usuario']['login'];
						$da['ip'] 			= getenv('REMOTE_ADDR');
						$da['data_acesso'] 	= date('d-m-Y H:i:s');
						if (!$A->save($da))
						{
							die('Erro ao tentar atualizar acesso !!!');
						} else
						{
							$dataUs['0']['Usuario']['acesso_id'] = $A->id;
						}
						
						// escrevendo dados do usuário na sessão
						$this->Session->write('Usuario',$dataUs['0']['Usuario']);
						$this->Session->setFlash('Usuário autenticado com sucesso !!!','default', array('class'=>'msgOk'));
						$this->redirect('principal');
					}
				}
			}
		}
	}

	/**
	 * Fecha a sessão do usuário 
	 *
	 * @return	void
	 */
	public function sair()
	{
		// atualizando acessos
		$this->setSaidaAcesso(false);
		$this->Session->destroy();
		$this->redirect('/');
	}

	/**
	 * Exibe a tela de Acesso negado.
	 * 
	 * @return	void
	 */
	public function acesso_negado()
	{
	}

	/**
	 * Exibe a tela de dados do usuário logado
	 * 
	 * @param	integer	$id Id do usuário logado
	 */
	public function meus_dados()
	{
		if (isset($this->data['Usuario'][$this->viewVars['primaryKey']]))
		{
			try
			{
				if ($this->Usuario->saveAll($this->data))
				{
					$this->Session->setFlash('Seu perfil foi atualizado com sucesso!!','default',array('class'=>'msgOk'));
					$this->redirect('meus_dados');
				} else
				{
					$this->Session->setFlash('Não foi possivel atualizar seu perfil !!','default',array('class'=>'msgErro'));
				}
			} catch (MongoException $e) 
			{
				$this->Session->setFlash($e->getMessage(),'default',array('class'=>'msgErro'));
				$this->redirect('listar');
			}
		}
		$this->request->data		= $this->Usuario->read(null,$this->Session->read('Usuario._id'));
		$this->viewVars['titulo']	= 'Editando o Usuário '.$this->Session->read('Usuario.nome');
	}

	/**
	 * Exibe a tela de edicação de um registro do cadastro de usuários.
	 * 
	 * @param	integer		$id	Id do registro a ser editado
	 * @return	void
	 */
	public function editar($id=0)
	{
		App::uses('Perfil','Model');
		$opc 			= array();
		$opc['fields']	= array('Perfil.nome','Perfil.nome');
		$opc['order']['Perfil.nome'] = 'asc';
		$Perfil = new Perfil();
		$this->viewVars['perfis'] = $Perfil->find('list',$opc,'listPerfil');
		parent::editar($id);
	}
}
