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
	 * Exibe a página inicial do cadastro de Agenda
	 * 
	 * @return	void
	 */
	public function index($mes=0,$ano=0)
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

		// configurando meses e anos
		$meses 	= array();
		$anos	= array();
		for($i=1; $i<13; $i++) $meses[$i] = $i;
		for($i=date('Y')-20; $i<date('Y')+30; $i++) $anos[$i] = $i;
		
		// configurando o primeiro dia do mês
		$prDiaSem = date('w', strtotime("$ano/$mes/1"));
		
		// configurando os dias do quadro
		$calendario = $this->getCalendario($mes,$ano);

		// configurando o primeiro e o último dia do mẽs corrente
		$hora_pri = mktime(0,0,0,$mes,1,$ano);
		$hora_ult = mktime(0,0,0,$mes,date('t', $hora_pri),$ano);

		// recuperando as mensagens do mês e ano
		$opc = array();
		$opc['conditions']['Agenda.data_txt'] 	= array('$gte'=>$hora_pri,'$lte'=>$hora_ult);
		$opc['order']['Agenda.data_txt'] 		= 'asc';
		$this->data = $this->Agenda->find('all',$opc);

		// atualizando a view
		$this->set(compact('meses','anos','dia','mes','ano','linkA','linkP','linkH','prDiaSem','calendario'));
	}

	/**
	 * Exibe a tela de edição do registro de agenda
	 * 
	 * @param	integer	$id
	 * @return	void
	 */
	public function editar($id=0)
	{
		$this->viewVars['edicaoCampos'] = array('Agenda.texto','#','Agenda.data_txt');
		$this->viewVars['focus'] = 'Agenda.texto';
		parent::editar($id);
		if ($id==0)
		{
			$this->request->data['Agenda']['data_txt'] = date('d/m/Y H:i');
		}
	}
}
