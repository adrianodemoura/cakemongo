<?php
/**
 * Cadastro de Agenda
 * 
 * @package       app.Controller
 */
/**
 * @package       app.Controller
 */
class AgendaController extends AppController {
	/**
	 * Model
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/controllers.html#components-helpers-and-uses
	 */
	public $uses = array('Agenda');

	/**
	 * Executa código antes da renderização da view
	 * 
	 * @return	void
	 */
	public function beforeRender()
	{
		parent::beforeRender();	
		// re-configurando minutos
		$minutos = array();
		$a = 0;
		for($i=0; $i<12; $i++)
		{
			$a = $i*5;
			if (strlen($a)==1) $a = '0'.$a;
			$minutos[$a] = $a;
		}
		$this->viewVars['minutos'] = $minutos;
	}

	/**
	 * Exibe a página inicial do cadastro de Agenda
	 * 
	 * @return	void
	 */
	public function index($mes=0,$ano=0,$idEvento=0)
	{
		// configurando primeiro dia, mês e ano
		$dia 	= date('d');
		if (!$mes) $mes = date('m');
		if (!$ano) $ano = date('Y');

		// configurando mês e anos vizinhos
		$mesA = $mes-1;
		$anoA = $ano;
		$mesP = $mes+1;
		$anoP = $ano;
		if ($mesP>12)
		{
			$mesP = 1;
			$anoP = $ano+1;
		}
		if ($mesA<1)
		{
			$mesA = 12;
			$anoA = $ano-1;
		}
		$linkA = Router::url('/',true).'agenda/index/'.$mesA.'/'.$anoA;
		$linkH = Router::url('/',true).'agenda/index/'.date('m').'/'.date('Y');
		$linkP = Router::url('/',true).'agenda/index/'.$mesP.'/'.$anoP;
		
		// configurando o primeiro dia do mês
		$prDiaSem = date('w', strtotime("$ano/$mes/1"));
		
		// configurando os dias do quadro
		$_calendario = $this->getCalendario($mes,$ano);

		// configurando o primeiro e o último dia do mẽs corrente
		$hora_pri = mktime(0,0,0,$mes,1,$ano);
		$hora_ult = mktime(23,59,59,$mes,date('t', $hora_pri),$ano);

		// recuperando as mensagens do mês e ano
		$opc = array();
		$opc['conditions']['Agenda.data_txt'] 	= array('$gte'=>$hora_pri,'$lte'=>$hora_ult);
		$opc['order']['Agenda.data_txt'] 		= 'asc';
		$this->data = $this->Agenda->find('all',$opc);
		
		// concatenando calendário com dados lançado
		$calendario = array();
		$lc			= 0;
		foreach($_calendario as $_idS => $_arrDias)
		{
			foreach($_arrDias as $_idN => $_dia)
			{
				$calendario[$_idS][$_idN]['dia'] 	= $_dia;
				$calendario[$_idS][$_idN]['msgs'] 	= array();
				if (!empty($this->data))
				{
					$lc	= 0;
					foreach($this->data as $_l => $_arrMods)
					{
						$dia = (int) substr($_arrMods['Agenda']['data_txt'],0,2);
						if ($_dia==$dia)
						{
							$calendario[$_idS][$_idN]['msgs'][$lc]['id'] 	= $_arrMods['Agenda']['_id'];
							$calendario[$_idS][$_idN]['msgs'][$lc]['evento']= $_arrMods['Agenda']['evento'];
							$calendario[$_idS][$_idN]['msgs'][$lc]['hora'] 	= substr($_arrMods['Agenda']['data_txt'],11,5);
							$lc++;
						}
					}
				}
			}
		}

		// atualizando a view
		$this->set(compact('meses','anos','dia','mes','ano','linkA','linkP','linkH','prDiaSem','calendario','horas','minutos','idEvento'));
	}

	/**
	 * Salva o formulário postado no documento
	 * 
	 * @return	void
	 */
	public function salvar_evento()
	{
		$this->layout 	= 'ajax';
		$msg			= '';

		// se o form foi postado para salvar
		if ($this->request->isPost() && isset($this->data['evSalvar']))
		{
			unset($this->request->data['evSalvar']);
			$data = $this->data['Agenda'];
			$data['dia']= '00'.trim($data['dia']);
			$data['dia'] = substr($data['dia'],strlen($data['dia'])-2,2);

			$data['mes']= '00'.trim($data['mes']);
			$data['mes'] = substr($data['mes'],strlen($data['mes'])-2,2);

			$data['hora']= '00'.trim($data['hora']);
			$data['hora'] = substr($data['hora'],strlen($data['hora'])-2,2);

			$data['minu']= '00'.trim($data['minu']);
			$data['minu'] = substr($data['minu'],strlen($data['minu'])-2,2);

			$dataEv = $data['dia'].'/'.$data['mes'].'/'.$data['ano'].' '.$data['hora'].':'.$data['minu'].':'.date('s');
			$this->request->data = array();
			$this->request->data['0']['Agenda']['_id'] 		= isset($data['_id']) ? $data['_id'] : 0;
			$this->request->data['0']['Agenda']['evento'] 	= isset($data['evento']) ? $data['evento'] : '';
			$this->request->data['0']['Agenda']['data_txt']	= $dataEv;
			if (!$this->Agenda->saveAll($this->data))
			{
				$this->Session->setFlash('Não foi possível salvar o evento !!!','default',array('class'=>'msgErro'));
			} else
			{
				$this->Session->setFlash('O Evento foi salvo com sucesso !!!','default',array('class'=>'msgOk'));
			}
			$id = isset($this->Agenda->id) ? $this->Agenda->id : null;
			$re = 'index/'.$data['mes'].'/'.$data['ano'];
			if ($id) $re .= '/'.$id;
			$this->redirect($re);
		}

		// se o form foi postado para excluir
		if ($this->request->isPost() && isset($this->data['evExcluir']))
		{
			unset($this->request->data['evExcluir']);
			if (!$this->Agenda->delete($this->data['Agenda']['_id']))
			{
				$this->Session->setFlash('Não foi possível s excluir o evento !!!','default',array('class'=>'msgErro'));
			} else
			{
				$this->Session->setFlash('O Evento foi excluido com sucesso !!!','default',array('class'=>'msgOk'));
			}
			$this->redirect('index/'.$this->data['Agenda']['mes'].'/'.$this->data['Agenda']['ano']);
		}

		$this->set(compact('msg'));
	}

	/**
	 * 
	 */
	public function listar()
	{
		parent::listar();
		$this->viewVars['botoes']['Index']['url'] = Router::url('/',true).'agenda/index';
		$this->viewVars['botoes']['Index']['img'] = Router::url('/',true).'img/logo_agendas.png';
		$this->viewVars['botoes']['Index']['title'] = 'Clique aqui para acessar a agenda completa ...';
	}

	/**
	 * Exibe a tela de edição do registro de agenda
	 * 
	 * @param	integer	$id
	 * @return	void
	 */
	public function editar($id=0)
	{
		$this->viewVars['edicaoCampos'] = array('Agenda.evento','#','Agenda.data_txt');
		$this->viewVars['focus'] = 'Agenda.evento';
		parent::editar($id);
		if ($id==0)
		{
			$this->request->data['Agenda']['data_txt'] = date('d/m/Y H:i');
		}
	}
}
