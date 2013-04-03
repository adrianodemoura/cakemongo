<?php
/**
 *  Controller Pai de todos
 * 
 * - Joga na sessão o perfil do usuário logado.\n
 * - Joga no cache as Permissões de Acesso do perfil do usuário.\n
 * - Por padrão utiliza CRUD customizado, não é o scaffolds do core.
 * 
 * @package		app.Controller
 */
App::uses('Controller', 'Controller');
/**
 * @package		app.Controller
 */
class AppController extends Controller {
	/**
	 * Usar a view Scaffolds ?
	 * 
	 * @var		boolean
	 */
	public $usarScaffolds	= true;

	/**
	 * Filtro para paginação
	 * 
	 * @var		array
	 */
	public $filtro			= array();

	/**
	 * Executa código antes de tudo
	 * 
	 * @return	void
	 */
	public function beforeFilter()
	{
		$modelClass 					= $this->modelClass;
		$this->viewVars['modelClass']	= $modelClass;
		$this->viewVars['primaryKey']	= isset($this->$modelClass->primaryKey) ? $this->$modelClass->primaryKey : '';

		// verifica sessão
		$this->setSessao();

		// se é busca ajax
		if (!in_array($this->action,array('busca_ajax')))
		{
			// variáveis globais para todas as views
			$this->viewVars['schema']		= $this->$modelClass->schema();
			$this->viewVars['onRead']		= '';
			$this->viewVars['sexos']['F'] 	= 'Feminino';
			$this->viewVars['sexos']['M'] 	= 'Masculino';
			$this->viewVars['sexos']['S'] 	= 'Sim';
			$this->viewVars['sexos']['N'] 	= 'Não';
			$this->viewVars['sexos']['1'] 	= 'Ativo';
			$this->viewVars['sexos']['0'] 	= 'Inativo';

			// menuLista Padrão
			if ($this->getLink('/'.strtolower($this->name).'/editar'))
			{
				if (!in_array($this->action,array('editar')))
				{
					$this->viewVars['opcMenuLista']['Editar'] = $this->base.'/'.strtolower($this->name).'/editar/{'.$this->viewVars['primaryKey'].'}';
				}
			}
			if ($this->getLink('/'.strtolower($this->name).'/excluir') && !isset($this->viewVars['soLeitura']))
			{
				$this->viewVars['opcMenuLista']['Excluir']= $this->base.'/'.strtolower($this->name).'/excluir/{'.$this->viewVars['primaryKey'].'}';
			}

			$this->setMenu();
		}
	}

	/**
	 * Atualiza a cada 5 minutos, no banco de dados, a data de saída do usuário logado.\n
	 * 
	 * @param	boolean		Recupear cronômetro da sessão ?
	 * @return	void
	 */
	public function setSaidaAcesso($sessao=true)
	{
		$cro = ($this->Session->check('cronometro')) ? $this->Session->read('cronometro') : strtotime('now');
		if (!$this->Session->check('cronometro')) $this->Session->write('cronometro',$cro);
		$dif = date('i',(strtotime('now') - $cro));

		if ($dif>=5 || $sessao==false)
		{
			$this->Session->write('cronometro',strtotime('now'));
			$id	= $this->Session->check('Usuario.acesso_id') ? $this->Session->read('Usuario.acesso_id') : 0;
			if ($id)
			{
				App::uses('Acesso','Model');
				$A = new Acesso();
				$da[$A->primaryKey]			= $id;
				$da['data_saida'] 	= date('d-m-Y H:i:s');
				if (!$A->saveAll($da)) die('fudeu !!!');
			}
		}
	}

	/**
	 * Recupera no banco de dados, as opções de menu ao qual o perfil do usuário logado possui acesso.
	 * 
	 * - Aproveita a xepa, e já verifica se o usuário logado está acessando algo não permitido.
	 * - Aproveita a xepa, e atualiza saída de acesso.
	 * 
	 * @return	void
	 */
	public function setMenu()
	{
		// recuperando as urls do perfil logado
		$perfil = $this->Session->read('Usuario.perfil');
		if ($perfil)
		{
			// atualizando acessos
			$login0800 = Configure::read('login0800');
			if (!$login0800) $this->setSaidaAcesso();

//			Cache::delete('urls'.$perfil);
			$urls	= Cache::read('urls'.$perfil);
			if (!$urls)
			{
				App::uses('Url','Model');
				$Url 	= new Url();
				$opc	= array();
				$opc['conditions']['perfis']['$in'] = array($perfil);
				$_urls	= $Url->find('all',$opc);
				$urls 	= array();
				foreach($_urls as $_l => $_arrMods)
				{
					$urls[$_arrMods['Url']['link']] = 1;
				}
				if (isset($_urls['0']))
				{
					Cache::write('urls'.$perfil,$urls);
				}
			}

			// verificando se o perfil logado pode acessar aqui, se não manda pra fora.
			$minhaUrl = '';
			if (!empty($this->plugin)) 	$minhaUrl .= '/'.strtolower($this->plugin);
			if (!empty($this->name))	$minhaUrl .= '/'.strtolower($this->name);
			if (!empty($this->action))	$minhaUrl .= '/'.strtolower($this->action);
			if (	!isset($urls[$minhaUrl]) &&
					!in_array($this->action,array('acesso_negado','sair','principal')) &&
					!in_array($perfil,array('ADMINISTRADOR'))
				)
			{
				$this->Session->setFlash('Seu Perfil de Usuário não tem permissão para acessar '.$minhaUrl.' !!!','default',array('class'=>'msgErro'));
				$this->redirect(array('controller'=>'usuarios','action'=>'acesso_negado'));
			}
		}
	}

	/**
	 * Teste se o usuário logado, possui acesso a determinada url.\n
	 * - perfil administrador, não passa pelo teste.
	 * 
	 * @param	string	$url	Url a ser testada
	 * @return	boolean			Retorna Verdadeiro se a url é válida, falso se não.
	 */
	public function getLink($url='')
	{
		$perfil = $this->Session->read('Usuario.perfil');
		if ($perfil=='ADMINISTRADOR') return true;
		if ($perfil)
		{
			$urls = Cache::read('urls'.$perfil);
			if (!isset($urls[$url])) return false;
		}
		return true;
	}

	/**
	 * Executa código antes da renderização da view.\n
	 * 
	 * - Se está no CRUD e não foi definido os campos, define automaticamente lendo o schema do model.
	 * 
	 * @return	void
	 * @link	http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
	 */
	public function beforeRender()
	{
		$modelClass = $this->viewVars['modelClass'];

		// criando automaticamente os campos de manutenção
		if (in_array($this->action,array('editar','excluir','salvar')))
		{
			if (!isset($this->viewVars['edicaoCampos']))
			{
				$edicaoCampos = array();
				foreach($this->$modelClass->schema as $_cmp => $_arrProp)
				{
					if (!in_array($_cmp,array($this->viewVars['primaryKey'],'id'))) array_push($edicaoCampos,$modelClass.'.'.$_cmp);
				}
				$this->viewVars['edicaoCampos'] = $edicaoCampos;
			}
		}
		
		// criando automaticamente os campos da lista
		if ($this->action=='listar')
		{
			if (!isset($this->viewVars['listaCampos']))
			{
				$listaCampos = array();
				foreach($this->$modelClass->schema as $_cmp => $_arrProp)
				{
					if (!in_array($_cmp,array($this->viewVars['primaryKey'],'id'))) array_push($listaCampos,$modelClass.'.'.$_cmp);
				}
				$this->viewVars['listaCampos'] = $listaCampos;
			}
		}
		
		// título da página
		if (!isset($this->viewVars['titulo'])) $this->viewVars['titulo'] = $this->name;
	}

	/**
	 * Exibe a tela principal do cadastro de alunos
	 * 
	 * @return	void
	 */
	public function index()
	{
		$this->redirect('listar');
	}

	/**
	 * Exibe a tela de paginação do cadastro de alunos
	 * 
	 * @return	void
	 */
	public function listar()
	{
		$chave					= $this->plugin.'.'.$this->name;
		$modelClass 			= $this->viewVars['modelClass'];

		// direção
		$pag['direction'] 		= isset($this->passedArgs['dire']) 		? $this->passedArgs['dire'] 	: 'asc';
		$pag['page']			= isset($this->passedArgs['pag']) 		? $this->passedArgs['pag'] 		: 1;
		$pag['sort']			= isset($this->passedArgs['ordem']) 	? $this->passedArgs['ordem'] 	: 'nome';

		$filtro 				= isset($this->filtro) ? $this->filtro : array();

		$this->params['named'] 	= $pag;
		$this->data				= $this->paginate(null,$filtro);

		$this->viewVars['titulo']	= 'Listando '.$this->name;
		if ($this->usarScaffolds) $this->viewPath = 'Scaffolds';
		
		if (!isset($this->viewVars['listaCampos']))
		{
			$listaCampos = array();
			foreach($this->$modelClass->schema as $_cmp => $_arrProp)
			{
				if (!in_array($_cmp,array($this->viewVars['primaryKey'],'id'))) array_push($listaCampos,$modelClass.'.'.$_cmp);
			}
			$this->viewVars['listaCampos'] = $listaCampos;
		}
		
	}

	/**
	 * Exclui o documento no banco de dados.
	 * 
	 * @return	void
	 */
	public function excluir($id=0)
	{
		$modelClass = $this->viewVars['modelClass'];
		if (isset($this->viewVars['soLeitura']))
		{
			$this->Session->setFlash('Este documento só permite leitura !!!','default',array('class'=>'msgErro'));
			$this->redirect('editar/'.$id);
		}

		if (!$id)
		{
			$this->Session->setFlash('Id Inválido !!!','default',array('class'=>'msgErro'));
			$this->redirect('listar');
		} elseif($this->$modelClass->delete($id))
		{
			$this->Session->setFlash('O Registro foi excluído com sucesso !!!','default',array('class'=>'msgOk'));
			$this->redirect('listar');
		} else
		{
			$msg = isset($this->$modelClass->erro) ? $this->$modelClass->erro : 'O Registro não pode ser excluído !!!';
			$this->Session->setFlash($msg,'default',array('class'=>'msgErro'));
			$this->redirect('listar');
		}
		if ($this->usarScaffolds) $this->viewPath = 'Scaffolds';
	}

	/**
	 * Exibe a tela de edição.\n
	 * 
	 * @param	integer	$id 	Id do registro a ser editado.
	 * @retu	void
	 */
	public function editar($id=0)
	{
		$modelClass = $this->viewVars['modelClass'];

		$opc				= array();
		$opc['conditions'][$modelClass.'.'.$this->viewVars['primaryKey']] 	= $id;
		$this->data = $this->$modelClass->read(null,$id);
		$this->viewVars['titulo']	= 'Editando '.$this->name;

		if ($this->usarScaffolds) $this->viewPath = 'Scaffolds';
	}

	/**
	 * Salva o formulário, enviado via POST, no banco de dados.
	 * 
	 * @return	void
	 */
	public function salvar()
	{
		$modelClass = $this->viewVars['modelClass'];

		if (isset($this->viewVars['soLeitura']))
		{
			$id = $this->data[$modelClass][$this->viewVars['primaryKey']];
			$this->Session->setFlash('Este documento só permite leitura !!!','default',array('class'=>'msgErro'));
			$this->redirect('editar/'.$id);
		}

		if ($this->usarScaffolds) $this->viewPath = 'Scaffolds';
		$this->view = 'editar';

		if (isset($this->data['btSalvar'])) unset($this->request->data['btSalvar']);
		try
		{
			if ($this->$modelClass->saveAll($this->data))
			{
				$this->Session->setFlash('O Registro foi salvo com sucesso !!!','default',array('class'=>'msgOk'));
				$this->redirect('editar/'.$this->$modelClass->id);
			}
		} catch (MongoException $e) 
		{
			$this->Session->setFlash($e->getMessage(),'default',array('class'=>'msgOk'));
			$this->redirect('listar');
		}
	}

	/**
	 * Verifica se a sessão do usuário foi iniciada, caso contrário será redirecinado para a tela de login.
	 * - Salvo se o acesso a configuração do core, "login0800" igual a true. Este configuração foi implmementada para
	 * que possa fazer testes de stress sem precisar de login. Quando "login0800" igual a true, corresponde ao Administrador.
	 * 
	 * @return	void
	 */
	public function setSessao()
	{
		$login0800 = Configure::read('login0800');
		if ($login0800)
		{
			$data['Usuario']['id'] 			= 1;
			$data['Usuario']['login'] 		= 'admin';
			$data['Usuario']['perfil'] 		= 'ADMINISTRADOR';
			$data['Usuario']['nome'] 		= 'Administrador 0800';
			$data['Usuario']['ultimo_ip'] 	= getenv('REMOTE_ADDR');
			$data['Usuario']['ultimo'] 		= date('d/m/Y H:i');
			$data['Usuario']['acessos'] 	= 1;
			$this->Session->write('Usuario',$data['Usuario']);
		}

		// sem login, nem a pau, juvenal !!!
		if (	!$this->Session->check('Usuario.login') &&
				(	!in_array($this->name,array('Usuarios')) &&
					!in_array($this->action,array('login','sair'))
				)
			)
		{

			$this->Session->setFlash('É preciso estar logado para este acesso!!!','default',array('class'=>'msgOk'));
			$this->redirect(array('controller'=>'usuarios','action'=>'login'));
		}
	}

	/**
	 * Executa uma busca para resposta ajax
	 * 
	 * @return	void
	 */
	public function busca_ajax($texto='')
	{
		$modelClass 	= $this->modelClass;
		$campo			= isset($this->$modelClass->displayField) ? $this->$modelClass->displayField : 'nome';
		$this->layout	= 'ajax';
		if ($this->usarScaffolds) $this->viewPath = 'Scaffolds';
		$opc	= array();
		$opc['order'][$modelClass.'.'.$campo] = 'asc';
		$opc['fields'] = array($modelClass.'.'.$campo,$modelClass.'.'.$campo);
		$opc['conditions'][$modelClass.'.'.$campo] = array("\$regex" => ".*".mb_strtoupper($texto).".*");
		//$this->Course->find("all", array("conditions" => array("Course.professor" => array("\$regex" => ".*Odinga.*"))));
		$opc['limit'] = 10000;
		$this->data = $this->$modelClass->find('list',$opc);
	}

	/**
	 * Exibe o relatório de lista total
	 * 
	 * @model	string	$model	Nome do model
	 * @opc		array	$opc	Opções para o método group do mongoDB
	 * @return	array	$data	Lista formatada
	 */
	public function listaGrupo($model,$opc)
	{
		App::uses($model,'Model');
		$Model 			= new $model();
		$chave			= $opc['key'];
		$opc['key']		= isset($opc['key'])		? array($opc['key']=>true) 	: array($Model->displayField=>true);
		$opc['initial']	= isset($opc['initial']) 	? $opc['initial'] 			: array('total'=>0);
		$opc['reduce']	= isset($opc['reduce'])  	? $opc['reduce']  			: 'function(obj,prev) { prev.total +=1; }';
		$mongo 			= $Model->getDataSource();
		$_data 			= $mongo->group($Model,$opc);
		$data			= array();
		foreach($_data['retval'] as $_l => $_arrCmps) $data[$_arrCmps[$chave]] = $_arrCmps['total'];
		arsort($data);
		return $data;
	}

	/**
	 * Executa uma pesquisa no banco de dados
	 * 
	 * @param	string	$campo	Campo a ser pesquisado.
	 * @param	string	$texto	Texto a ser pesquisado.
	 * @return	void
	 */
	public function pesquisar($campo='',$texto='')
	{
		$this->viewPath = 'Scaffolds';
		$this->layout 	= 'ajax';
		$url 			= Router::url('/',true).strtolower($this->name).'/editar';
		$modelClass		= $this->viewVars['modelClass'];
		if (!empty($this->request->params['plugin'])) $url .= $this->request->params['plugin'].'/';

		$opc = array();
		$opc['conditions'][$modelClass.'.'.$campo] = array("\$regex" => ".*$texto.*");
		$opc['order'][$modelClass.'.'.$campo] = 'asc';
		$opc['limit'] = 100;
		$pesquisa = $this->$modelClass->find('list',$opc);
		$this->set(compact('url','pesquisa'));
	}
}
