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

		// atualizando a view
		$this->set(compact('meses','anos','dia','mes','ano','linkA','linkP','linkH'));
	}
}
